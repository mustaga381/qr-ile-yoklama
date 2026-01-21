<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\DersOturum;
use App\Models\YoklamaPencere;
use App\Models\Yoklama;
use App\Models\YoklamaSuphe;
use App\Models\Cihaz;

class OgrenciYoklamaController extends Controller
{
    /**
     * QR linki ile gelen öğrenci:
     * /ogrenci/yoklama?data=...
     * GET gelir, içeride direkt katılım mantığı çalışır. POST route gerekmez.
     */
    public function auto(Request $request)
    {
        $payloadStr = (string) $request->query('data', '');

        if (trim($payloadStr) === '') {
            return view('ogrenci.yoklama.result', [
                'durum' => 'error',
                'mesaj' => 'Geçersiz yoklama linki.',
            ]);
        }

        $cihazUuid = $this->deviceUuid($request); // şimdilik server-side
        $platform  = 'web';

        [$durum, $mesaj] = $this->katilCore(
            payloadStr: $payloadStr,
            cihazUuid: $cihazUuid,
            platform: $platform,
            request: $request
        );

        return view('ogrenci.yoklama.result', [
            'durum' => $durum,
            'mesaj' => $mesaj,
        ]);
    }

    /**
     * İstersen ileride API için POST endpoint bağlarsın diye bıraktım (opsiyonel).
     * Şu an route vermek zorunda değilsin.
     */
    public function katil(Request $request)
    {
        $data = $request->validate([
            'data' => ['required', 'string', 'min:10'],
            'cihaz_uuid' => ['required', 'string', 'max:64'],
            'platform' => ['nullable', 'string', 'max:50'],
        ]);

        [$durum, $mesaj] = $this->katilCore(
            payloadStr: $data['data'],
            cihazUuid: $data['cihaz_uuid'],
            platform: $data['platform'] ?? 'web',
            request: $request
        );

        // web/api ortak cevap
        return response()->json([
            'durum' => $durum,
            'message' => $mesaj,
        ], $durum === 'error' ? 422 : 200);
    }

    /**
     * -------------------------
     * Asıl katılım mantığı (tek kaynak)
     * -------------------------
     */
    private function katilCore(string $payloadStr, string $cihazUuid, string $platform, Request $request): array
    {
        $ogrenciId = auth()->id();

        // 1) parse
        $parsed = $this->parsePayload($payloadStr);
        if (!$parsed) {
            return ['error', 'Geçersiz QR verisi.'];
        }

        $pencereId = (int) $parsed['p'];
        $oturumId  = (int) $parsed['o'];
        $adimNo    = (int) $parsed['a'];
        $sig       = (string) $parsed['s'];

        // 2) pencere doğrula
        $pencere = YoklamaPencere::where('id', $pencereId)
            ->where('ders_oturum_id', $oturumId)
            ->first();

        if (!$pencere) {
            return ['error', 'Yoklama penceresi bulunamadı.'];
        }

        if ($pencere->durum === 'acik' && now()->greaterThan(Carbon::parse($pencere->bitis_zamani))) {
            $pencere->update(['durum' => 'sure_doldu']);
        }

        if ($pencere->durum !== 'acik') {
            return ['error', 'Yoklama kapalı.'];
        }

        $oturum = DersOturum::find($oturumId);
        if (!$oturum) {
            return ['error', 'Oturum bulunamadı.'];
        }

        // 3) HMAC doğrula
        $raw = "{$pencere->id}|{$oturumId}|{$adimNo}";
        $expected = hash_hmac('sha256', $raw, $pencere->gizli_anahtar);
        if (!hash_equals($expected, $sig)) {
            return ['error', 'QR doğrulanamadı.'];
        }

        // 4) adım kontrol
        $serverAdim = $this->currentAdimNo($pencere);
        $isOldStep = $adimNo < $serverAdim;

        // 5) cihaz kayıt/güncelle
        $cihaz = Cihaz::firstOrCreate(
            ['cihaz_uuid' => $cihazUuid],
            [
                'kullanici_id' => $ogrenciId,
                'platform' => $platform,
                'user_agent' => $request->userAgent(),
                'ilk_gorulme' => now(),
                'son_gorulme' => now(),
            ]
        );

        if ((int) $cihaz->kullanici_id !== (int) $ogrenciId) {
            return ['error', 'Cihaz doğrulanamadı.'];
        }

        $cihaz->update([
            'platform' => $platform ?: $cihaz->platform,
            'user_agent' => $request->userAgent(),
            'son_gorulme' => now(),
        ]);

        // 6) yoklama oluştur/güncelle
        $now = now();
        $ip = $request->ip();

        $existing = Yoklama::where('ders_oturum_id', $oturumId)
            ->where('ogrenci_id', $ogrenciId)
            ->first();

        if (!$existing) {
            $yoklama = Yoklama::create([
                'ders_oturum_id' => $oturumId,
                'ogrenci_id' => $ogrenciId,
                'yoklama_pencere_id' => $pencere->id,
                'cihaz_id' => $cihaz->id,
                'ip_adresi' => $ip,
                'user_agent' => $request->userAgent(),
                'durum' => $isOldStep ? 'supheli' : 'katildi',
                'isaretlenme_zamani' => $now,
                'qr_adim_no' => $adimNo,
            ]);

            if (!$cihaz->guvenilir_mi) {
                $this->logSuphe($yoklama->id, 'yeni_cihaz', [
                    'cihaz_id' => $cihaz->id,
                    'uuid' => $cihaz->cihaz_uuid,
                    'guvenilir_mi' => (bool) $cihaz->guvenilir_mi,
                ]);
            }

            if ($isOldStep) {
                $this->logSuphe($yoklama->id, 'qr_tekrar', [
                    'client_adim' => $adimNo,
                    'server_adim' => $serverAdim,
                ]);
            }

            return [$yoklama->durum, $isOldStep ? 'Yoklama alındı (şüpheli).' : 'Yoklama alındı.'];
        }

        $supheliMi = false;

        if ($existing->cihaz_id && (int)$existing->cihaz_id !== (int)$cihaz->id) {
            $supheliMi = true;
            $this->logSuphe($existing->id, 'ayni_oturum_coklu_cihaz', [
                'onceki_cihaz_id' => $existing->cihaz_id,
                'yeni_cihaz_id' => $cihaz->id,
            ]);
        }

        if ($existing->ip_adresi && $ip && $existing->ip_adresi !== $ip) {
            $supheliMi = true;
            $this->logSuphe($existing->id, 'ip_uyusmazligi', [
                'onceki_ip' => $existing->ip_adresi,
                'yeni_ip' => $ip,
            ]);
        }

        if ($existing->qr_adim_no !== null && $adimNo <= (int)$existing->qr_adim_no) {
            $supheliMi = true;
            $this->logSuphe($existing->id, 'qr_tekrar', [
                'onceki_adim' => (int)$existing->qr_adim_no,
                'yeni_adim' => $adimNo,
                'server_adim' => $serverAdim,
            ]);
        }

        $existing->update([
            'yoklama_pencere_id' => $pencere->id,
            'cihaz_id' => $cihaz->id,
            'ip_adresi' => $ip,
            'user_agent' => $request->userAgent(),
            'isaretlenme_zamani' => $now,
            'qr_adim_no' => $adimNo,
            'durum' => $supheliMi ? 'supheli' : $existing->durum,
        ]);

        $existing->refresh();

        return [$existing->durum, $supheliMi ? 'Zaten işaretlisin (şüpheli loglandı).' : 'Zaten işaretlisin.'];
    }

    // --------------------
    // Helpers
    // --------------------

    private function parsePayload(string $payload): ?array
    {
        $parts = explode('|', $payload);
        $out = [];

        foreach ($parts as $part) {
            $kv = explode(':', $part, 2);
            if (count($kv) !== 2) continue;
            $out[trim($kv[0])] = trim($kv[1]);
        }

        if (!isset($out['p'], $out['o'], $out['a'], $out['s'])) return null;
        if (!is_numeric($out['p']) || !is_numeric($out['o']) || !is_numeric($out['a'])) return null;

        return $out;
    }

    private function currentAdimNo(YoklamaPencere $pencere): int
    {
        $bas = Carbon::parse($pencere->baslangic_zamani);
        $diffSec = $bas->diffInSeconds(now());
        $step = max(1, (int) $pencere->yenileme_saniye);
        return intdiv($diffSec, $step);
    }

    private function deviceUuid(Request $request): string
    {
        // IP dahil ETME: wifi değişince cihaz değişmiş gibi olmasın
        $ua = $request->userAgent() ?? 'ua';
        return substr(sha1($ua), 0, 64);
    }

    private function logSuphe(int $yoklamaId, string $tur, array $meta = []): void
    {
        $allowed = ['yeni_cihaz', 'ayni_oturum_coklu_cihaz', 'ip_uyusmazligi', 'qr_tekrar'];
        if (!in_array($tur, $allowed, true)) return;

        YoklamaSuphe::create([
            'yoklama_id' => $yoklamaId,
            'tur' => $tur,
            'meta' => $meta ?: null, // json column -> array bas
        ]);
    }
}
