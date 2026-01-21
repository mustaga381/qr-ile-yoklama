<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use App\Models\DersKayit;
use App\Models\Yoklama;
use Illuminate\Http\Request;

class OgrenciDersController extends Controller
{
    public function derslerim(Request $request)
    {
        $ogrenciId = auth()->id();

        // ✅ Buradaki pivot tabloyu kendi sistemine göre düzelt:
        // örn: ders_kayitlari / ders_ogrenci / ogrenci_ders_acilim vs.
        $dersler = DersKayit::query()
            ->where('ogrenci_id', $ogrenciId)
            ->with('acilim.hoca')
            ->get();

        // dd($dersler->toArray());
        return view('ogrenci.derslerim.index', compact('dersler'));
    }
    public function show(DersKayit $dersKayit)
    {
        $ogrenciId = auth()->id();
        if ($ogrenciId != $dersKayit->ogrenci_id) return route('ogrenci.panel');

        $dersKayit->load(['acilim.ders', 'acilim.hoca']);

        $yoklamalar = Yoklama::query()
            ->where('ogrenci_id', $ogrenciId)
            ->with('oturum')
            ->orderBy('isaretlenme_zamani', 'desc')
            ->paginate(15);

        // dd($yoklamalar->toArray());
        return view('ogrenci.derslerim.show', compact('dersKayit', 'yoklamalar'));
    }
}
