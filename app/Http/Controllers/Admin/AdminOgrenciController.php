<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bolum;
use App\Models\Kullanici;
use App\Models\DersKayit;
use App\Models\Yoklama;
use App\Models\YoklamaKatilim;
use App\Models\YoklamaPencere;
use Illuminate\Http\Request;

class AdminOgrenciController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $bolumId = $request->get('bolum_id');

        $bolumler = Bolum::where('aktif_mi', true)->orderBy('bolum_adi')->get();

        $ogrenciler = Kullanici::where('rol', 'ogrenci')
            ->when($q, fn($s) => $s->where(function ($w) use ($q) {
                $w->where('ad_soyad', 'like', "%$q%")
                    ->orWhere('eposta', 'like', "%$q%")
                    ->orWhere('ogrenci_no', 'like', "%$q%");
            }))
            ->when($bolumId, fn($s) => $s->where('bolum_id', $bolumId))
            ->with('bolum')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.ogrenciler.index', compact('ogrenciler', 'q', 'bolumler', 'bolumId'));
    }

    public function show(Kullanici $ogrenci)
    {
        abort_unless($ogrenci->rol === 'ogrenci', 404);

        $ogrenci->load('bolum');

        // Ders kayıtları (onaylı vb.)
        $dersKayitlari = DersKayit::where('ogrenci_id', $ogrenci->id)
            ->with(['acilim.ders'])
            ->latest('id')
            ->get();
        // Yoklama geçmişi (son 30)
        $yoklamalar = Yoklama::where('ogrenci_id', $ogrenci->id)
            ->with(['oturum.dersAcilim.ders'])
            ->latest('isaretlenme_zamani')
            ->limit(30)
            ->get();

        // Cihazlar tablon varsa burayı kendi modelinle değiştir.
        // Yoksa boş gönderiyoruz (view patlamasın)
        $cihazlar = method_exists($ogrenci, 'cihazlar')
            ? $ogrenci->cihazlar()->latest('son_gorulme')->get()
            : collect();

        return view('admin.ogrenciler.show', compact('ogrenci', 'dersKayitlari', 'yoklamalar', 'cihazlar'));
    }
}
