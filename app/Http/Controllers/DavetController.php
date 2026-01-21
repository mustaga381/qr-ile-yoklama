<?php

namespace App\Http\Controllers;

use App\Models\DersDavet;
use App\Models\DersKayit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DavetController extends Controller
{
    public function goster(Request $request, string $token)
    {
        $ogrenci = $request->user();
        abort_unless($ogrenci && $ogrenci->rol === 'ogrenci', 403);

        $davet = DersDavet::with(['dersAcilim.ders'])
            ->where('token', $token)
            ->firstOrFail();

        [$kabulEdilebilirMi, $mesaj] = $this->davetKontrol($davet, $ogrenci);

        return view('ogrenci.davet.goster', [
            'davet' => $davet,
            'kabulEdilebilirMi' => $kabulEdilebilirMi,
            'mesaj' => $mesaj,
        ]);
    }

    public function kabul(Request $request, string $token)
    {
        $ogrenci = $request->user();
        abort_unless($ogrenci && $ogrenci->rol === 'ogrenci', 403);

        return DB::transaction(function () use ($token, $ogrenci) {

            // yarış durumlarını engellemek için lock
            $davet = DersDavet::with(['dersAcilim'])
                ->where('token', $token)
                ->lockForUpdate()
                ->firstOrFail();

            [$kabulEdilebilirMi, $mesaj] = $this->davetKontrol($davet, $ogrenci);
            if (!$kabulEdilebilirMi) {
                return back()->withErrors(['genel' => $mesaj]);
            }

            // zaten kayıtlı mı?
            $mevcut = DersKayit::where('ders_acilim_id', $davet->ders_acilim_id)
                ->where('ogrenci_id', $ogrenci->id)
                ->first();

            if ($mevcut && in_array($mevcut->durum, ['aktif', 'birakti'], true)) {
                return redirect()->route('ogrenci.panel')->with('ok', 'Zaten bu derse kayıtlısın.');
            }

            // kayıt oluştur/güncelle
            DersKayit::updateOrCreate(
                [
                    'ders_acilim_id' => $davet->ders_acilim_id,
                    'ogrenci_id'     => $ogrenci->id,
                ],
                [
                    'durum'         => 'aktif',
                    'kayit_sekli'   => 'davet',
                    'ders_davet_id' => $davet->id,
                ]
            );


            // kullanım sayısını artır
            $davet->increment('kullanim_sayisi');

            return redirect()->route('ogrenci.panel')->with('ok', 'Davet kabul edildi, derse kaydoldun.');
        });
    }

    private function davetKontrol(DersDavet $davet, $ogrenci): array
    {
        if (!$davet->aktif) {
            return [false, 'Bu davet pasif.'];
        }

        if ($davet->son_gecerlilik_tarihi && now()->gt($davet->son_gecerlilik_tarihi)) {
            return [false, 'Bu davetin süresi dolmuş.'];
        }

        if ($davet->kullanim_sayisi >= $davet->max_kullanim) {
            return [false, 'Bu davetin kullanım hakkı dolmuş.'];
        }

        if (!$davet->dersAcilim || !$davet->dersAcilim->aktif_mi) {
            return [false, 'Bu ders açılışı pasif veya bulunamadı.'];
        }

        // hedef öğrenci kısıtı
        if ($davet->hedef_ogrenci_id && (int)$davet->hedef_ogrenci_id !== (int)$ogrenci->id) {
            return [false, 'Bu davet sana özel değil.'];
        }

        // hedef e-posta kısıtı
        if ($davet->hedef_eposta && mb_strtolower(trim($davet->hedef_eposta)) !== mb_strtolower(trim($ogrenci->eposta))) {
            return [false, 'Bu davet bu e-posta için geçerli değil.'];
        }

        return [true, ''];
    }
}
