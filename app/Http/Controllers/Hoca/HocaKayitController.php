<?php

namespace App\Http\Controllers\Hoca;

use App\Http\Controllers\Controller;
use App\Models\DersAcilim;
use App\Models\DersKayit;
use App\Models\Kullanici;
use Illuminate\Http\Request;

class HocaKayitController extends Controller
{
    /**
     * Hoca, ders açılışına öğrenci ekler (öğrenci no ile).
     */
    public function ogrenciEkle(Request $request, DersAcilim $acilim)
    {

        // $hoca = $request->user();

        $data = $request->validate([
            'ogrenci_no' => ['required', 'string', 'max:32'],
        ]);

        $ogrenci = Kullanici::where('rol', 'ogrenci')
            ->where('ogrenci_no', $data['ogrenci_no'])
            ->first();

        if (!$ogrenci) {
            return back()->withErrors(['ogrenci_no' => 'Bu öğrenci numarasıyla öğrenci bulunamadı.']);
        }

        // aynı öğrenciyi aynı açılışa 1 kez kaydet
        $mevcut = DersKayit::where('ders_acilim_id', $acilim->id)
            ->where('ogrenci_id', $ogrenci->id)
            ->first();

        if ($mevcut && in_array($mevcut->durum, ['aktif', 'birakti'], true)) {
            return back()->with('ok', 'Öğrenci zaten bu derse kayıtlı.');
        }

        DersKayit::updateOrCreate(
            [
                'ders_acilim_id' => $acilim->id,
                'ogrenci_id'     => $ogrenci->id,
            ],
            [
                'durum'       => 'aktif',
                'kayit_sekli' => 'hoca',
                'ders_davet_id' => null,
            ]
        );

        return back()->with('ok', 'Öğrenci derse eklendi.');
    }

    /**
     * (Opsiyonel) Hoca, ders açılışından öğrenciyi çıkarır.
     */
    public function kayitSil(Request $request, DersAcilim $acilim, Kullanici $ogrenci)
    {
        // $hoca = $request->user();

        // (İstersen) sadece kendi açılışından silebilsin:
        // abort_unless($acilim->hoca_id === $hoca->id || $hoca->rol === 'admin', 403);

        DersKayit::where('ders_acilim_id', $acilim->id)
            ->where('ogrenci_id', $ogrenci->id)
            ->delete();

        return back()->with('ok', 'Öğrenci kaydı kaldırıldı.');
    }
}
