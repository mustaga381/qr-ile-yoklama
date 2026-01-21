<?php

namespace App\Http\Controllers\Hoca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use App\Models\DersAcilim;       // sende isim farklıysa değiştir
use App\Models\DersOturum;
use App\Models\YoklamaPencere;
use App\Models\Yoklama;

class HocaYoklamaController extends Controller
{
    // 1) Start: hoca ders açılımlarını görür
    public function start(Request $request)
    {
        $hocaId = auth()->id();

        $dersAcilimlari = DersAcilim::query()
            ->where('hoca_id', $hocaId)
            ->with('ders:id,ders_adi')
            ->orderByDesc('id')
            ->get();
        // dd($dersAcilimlari->toArray());
        // isterse bugün en son oturumları da göstermek için:
        // $bugunOturumlar = DersOturum::query()
        //     ->where('hoca_id', $hocaId)
        //     ->with('dersAcilim')
        //     ->whereDate('oturum_tarihi', now()->toDateString())
        //     ->orderByDesc('id')
        //     ->get();
        $bugun = now()->toDateString();

        $bugunOturumlar = DersOturum::query()
            ->where('hoca_id', $hocaId)
            ->whereDate('oturum_tarihi', $bugun)
            ->with('dersAcilim')
            ->orderByDesc('id')
            ->get();

        $oncekiOturumlar = DersOturum::query()
            ->where('hoca_id', $hocaId)
            ->whereDate('oturum_tarihi', '<', $bugun)
            ->with('dersAcilim')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('hoca.yoklama.start', compact(
            'dersAcilimlari',
            'bugunOturumlar',
            'oncekiOturumlar'
        ));
    }

    // 2) Oturum başlat: ders_acilim seçilir, yeni ders_oturumu oluşur
    public function oturumBaslat(Request $request)
    {
        $data = $request->validate([
            'ders_acilim_id' => ['required', 'integer'],
            'baslik' => ['nullable', 'string', 'max:255'],
        ]);

        $hocaId = auth()->id();

        $dersAcilim = DersAcilim::where('id', $data['ders_acilim_id'])
            ->where('hoca_id', $hocaId)
            ->firstOrFail();

        $oturum = DersOturum::create([
            'ders_acilim_id' => $dersAcilim->id,
            'oturum_tarihi' => now()->toDateString(),
            'baslangic_zamani' => now(),
            'bitis_zamani' => null,
            'baslik' => $data['baslik'] ?? null,
            'hoca_id' => $hocaId,
        ]);

        return redirect()->route('hoca.yoklama.show', $oturum->id)
            ->with('ok', 'Oturum başlatıldı.');
    }

    // 3) Oturum ekranı: aktif pencere + ilk liste
    public function show(DersOturum $oturum)
    {
        $this->assertOwner($oturum);

        $aktifPencere = YoklamaPencere::where('ders_oturum_id', $oturum->id)
            ->where('durum', 'acik')
            ->orderByDesc('id')
            ->first();

        $sonKatilimlar = Yoklama::where('ders_oturum_id', $oturum->id)
            ->with(['ogrenci', 'cihaz'])
            ->orderByDesc('isaretlenme_zamani')
            ->limit(50)
            ->get();

        $katilimItems = $sonKatilimlar->map(function ($y) {
            return [
                'id' => $y->id,
                'ogrenci' => [
                    'id' => $y->ogrenci?->id,
                    'ad' => trim(($y->ogrenci?->ad ?? '') . ' ' . ($y->ogrenci?->soyad ?? '')),
                    'email' => $y->ogrenci?->email,
                ],
                'durum' => $y->durum,
                'zaman' => optional($y->isaretlenme_zamani)->format('Y-m-d H:i:s'),
                'ip' => $y->ip_adresi,
                'cihaz' => [
                    'uuid' => $y->cihaz?->cihaz_uuid,
                    'platform' => $y->cihaz?->platform,
                    'guvenilir' => (bool) ($y->cihaz?->guvenilir_mi),
                ],
                'qr_adim_no' => $y->qr_adim_no,
            ];
        })->values()->toArray();

        return view('hoca.yoklama.show', compact(
            'oturum',
            'aktifPencere',
            'katilimItems'
        ));
    }

    // 4) Yoklama penceresi aç (QR başlar)
    public function pencereAc(Request $request, DersOturum $oturum)
    {
        $this->assertOwner($oturum);

        $data = $request->validate([
            'sure_dakika' => ['nullable', 'integer', 'min:1', 'max:60'],
            'yenileme_saniye' => ['nullable', 'integer', 'in:5,10,15,20'],
        ]);

        $sure = (int) ($data['sure_dakika'] ?? 5);
        $yenileme = (int) ($data['yenileme_saniye'] ?? 10);
        // açık pencereyi kapat
        YoklamaPencere::where('ders_oturum_id', $oturum->id)
            ->where('durum', 'acik')
            ->update(['durum' => 'kapali']);

        $pencere = YoklamaPencere::create([
            'ders_oturum_id' => $oturum->id,
            'acani_hoca_id' => auth()->id(),
            'baslangic_zamani' => now(),
            'bitis_zamani' => now()->addMinutes($sure),
            'yenileme_saniye' => $yenileme,
            'gizli_anahtar' => Str::random(64),
            'durum' => 'acik',
        ]);

        return redirect()->route('hoca.yoklama.show', $oturum->id)
            ->with('ok', 'Yoklama penceresi açıldı.');
    }

    public function pencereKapat(YoklamaPencere $pencere)
    {
        $oturum = DersOturum::findOrFail($pencere->ders_oturum_id);
        $this->assertOwner($oturum);

        if ($pencere->durum === 'acik') {
            $pencere->update(['durum' => 'kapali']);
        }

        return back()->with('ok', 'Yoklama kapatıldı.');
    }

    // 5) Canlı QR payload (p:o:a:s)
    public function qr(YoklamaPencere $pencere)
    {
        $oturum = DersOturum::findOrFail($pencere->ders_oturum_id);
        $this->assertOwner($oturum);

        if ($pencere->durum === 'acik' && now()->greaterThan(Carbon::parse($pencere->bitis_zamani))) {
            $pencere->update(['durum' => 'sure_doldu']);
        }
        if ($pencere->durum !== 'acik') {
            return response()->json(['durum' => $pencere->durum], 409);
        }

        $bas = Carbon::parse($pencere->baslangic_zamani);
        $diffSec = $bas->diffInSeconds(now());
        $stepLen = (int) $pencere->yenileme_saniye;
        $adim = intdiv($diffSec, max(1, $stepLen));
        $kalan = $stepLen - ($diffSec % $stepLen);

        $raw = "{$pencere->id}|{$oturum->id}|{$adim}";
        $sig = hash_hmac('sha256', $raw, $pencere->gizli_anahtar);
        $payload = "p:{$pencere->id}|o:{$oturum->id}|a:{$adim}|s:{$sig}";


        $ogrenciUrl = url('/ogrenci/yoklama') . '?data=' . urlencode($payload);

        return response()->json([
            'durum' => 'acik',
            'payload' => $payload,
            'adim_no' => $adim,
            'ogrenci_url' => $ogrenciUrl,
            'yenileme_saniye' => $stepLen,
            'step_kalan_saniye' => $kalan,
            'bitis_zamani' => Carbon::parse($pencere->bitis_zamani)->toIso8601String(),
        ]);
    }

    // 6) Canlı katılımlar JSON
    public function katilimlar(DersOturum $oturum)
    {
        $this->assertOwner($oturum);

        $items = Yoklama::where('ders_oturum_id', $oturum->id)
            ->with(['ogrenci', 'cihaz'])
            ->orderByDesc('isaretlenme_zamani')
            ->limit(150)
            ->get()
            ->map(fn($y) => [
                'id' => $y->id,
                'ogrenci' => [
                    'id' => $y->ogrenci?->id,
                    'ad' => trim(($y->ogrenci?->ad_soyad ?? '')),
                    'email' => $y->ogrenci?->eposta,
                ],
                'durum' => $y->durum,
                'zaman' => optional($y->isaretlenme_zamani)->translatedFormat('d M y H:i'),
                'ip' => $y->ip_adresi,
                'cihaz' => [
                    'uuid' => $y->cihaz?->cihaz_uuid,
                    'platform' => $y->cihaz?->platform,
                    'guvenilir' => (bool)($y->cihaz?->guvenilir_mi),
                ],
                'qr_adim_no' => $y->qr_adim_no,
            ]);

        return response()->json(['items' => $items]);
    }

    private function assertOwner(DersOturum $oturum): void
    {
        abort_unless((int)$oturum->hoca_id === (int)auth()->id(), 403);
    }
}
