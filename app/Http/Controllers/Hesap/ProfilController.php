<?php

namespace App\Http\Controllers\Hesap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function goster(Request $request)
    {
        $u = $request->user();

        $view = match ($u->rol) {
            'hoca'    => 'hoca.profil',
            'ogrenci' => 'ogrenci.profil',
            'admin'   => 'admin.profil',
            default   => 'login', // Herhangi bir rol eşleşmezse hata almamak için
        };
        return view($view, [
            'kullanici' => $u,
            'cihazlar'  => $u->cihazlar()->latest('son_gorulme')->get(), // ilişki adın neyse
        ]);
    }

    public function guncelle(Request $request)
    {
        $kullanici = $request->user();

        $data = $request->validate([
            'eposta' => [
                'required',
                'email',
                'max:255',
                Rule::unique('kullanicilar', 'eposta')->ignore($kullanici->id),
            ],
        ]);

        $kullanici->update([
            'eposta' => trim($data['eposta']),
        ]);

        return back()->with('ok', 'E-posta güncellendi.');
    }

    public function sifreGuncelle(Request $request)
    {
        $kullanici = $request->user();

        $data = $request->validate([
            'mevcut_sifre' => ['required', 'string'],
            'yeni_sifre'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($data['mevcut_sifre'], $kullanici->sifre)) {
            return back()->withErrors(['mevcut_sifre' => 'Mevcut şifre yanlış.']);
        }

        $kullanici->update([
            'sifre' => $data['yeni_sifre'], // model mutator hashler
        ]);

        return back()->with('ok', 'Şifre güncellendi.');
    }
}
