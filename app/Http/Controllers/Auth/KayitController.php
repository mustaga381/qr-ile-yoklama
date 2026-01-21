<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kullanici;
use Illuminate\Http\Request;

use App\Models\Bolum;

class KayitController extends Controller
{
    public function form()
    {
        $bolumler = Bolum::where('aktif_mi', true)->orderBy('bolum_adi')->get();
        return view('auth.kayit', compact('bolumler'));
    }

    public function kaydet(Request $request)
    {
        // Güvenlik: bu ekrandan sadece öğrenci kaydı
        if ($request->input('rol') && $request->input('rol') !== 'ogrenci') {
            abort(403, 'Bu ekrandan sadece öğrenci kaydı yapılabilir.');
        }

        $data = $request->validate([
            'ad_soyad'   => ['required', 'string', 'max:255'],
            'eposta'     => ['required', 'email', 'max:255', 'unique:kullanicilar,eposta'],
            'ogrenci_no' => ['required', 'string', 'max:32', 'unique:kullanicilar,ogrenci_no'],
            'bolum_id' => ['required', 'integer', 'exists:bolumler,id'],
            'sifre'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $kullanici = Kullanici::create([
            'ad_soyad'   => $data['ad_soyad'],
            'eposta'     => $data['eposta'],
            'rol'        => 'ogrenci',
            'ogrenci_no' => $data['ogrenci_no'],
            'bolum_id' => $data['bolum_id'],
            'personel_no' => null,
            'sifre'      => $data['sifre'],
            'aktif_mi'   => true,
        ]);

        auth()->login($kullanici);

        return redirect()->route('ogrenci.panel');
    }
}
