<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use App\Models\DersAcilim;
use App\Models\DersKayit;
use Illuminate\Http\Request;

class OgrenciKayitController extends Controller
{
    public function kayit(Request $request, DersAcilim $acilim)
    {
        $ogrenci = $request->user();

        abort_unless($ogrenci && $ogrenci->rol === 'ogrenci', 403);

        if (!$acilim->aktif_mi) {
            return back()->withErrors(['genel' => 'Bu ders açılışı pasif.']);
        }

        // davet modu ise öğrencinin “normal” kayıt butonu kapalı olmalı
        if ($acilim->kayit_modu === 'davet') {
            return back()->withErrors(['genel' => 'Bu ders açılışı davet ile kayıt alıyor. Davet linki/kodu gerekli.']);
        }

        $durum = $acilim->kayit_modu === 'onayli' ? 'beklemede' : 'onayli'; // onayli=>beklemede, acik=>onayli

        // daha önce kayıt var mı?
        $kayit = DersKayit::where('ders_acilim_id', $acilim->id)
            ->where('ogrenci_id', $ogrenci->id)
            ->first();

        if ($kayit) {
            // zaten onaylı/beklemede ise tekrar oluşturma
            if (in_array($kayit->durum, ['onayli', 'beklemede'], true)) {
                return back()->with('ok', 'Zaten bu açılışa kayıtlısın.');
            }

            // reddedildi/iptal gibi durumlarda yeniden başvuruya izin ver
            $kayit->update([
                'durum'  => $durum,
                'kaynak' => 'ogrenci',
                'ders_davet_id' => null,
            ]);

            return back()->with('ok', 'Kayıt güncellendi.');
        }

        // ilk kez kayıt
        // create/update kısmında
        DersKayit::updateOrCreate(
            [
                'ders_acilim_id' => $acilim->id,
                'ogrenci_id'     => $ogrenci->id,
            ],
            [
                'durum'         => 'onayli',
                'kayit_sekli'   => 'ogrenci',
                'ders_davet_id' => null,
            ]
        );

        return back()->with('ok', $durum === 'beklemede' ? 'Kayıt isteği gönderildi.' : 'Kayıt olundu.');
    }
}
