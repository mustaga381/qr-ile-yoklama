<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GirisController extends Controller
{
    public function form()
    {
        return view('auth.giris');
    }

    public function giris(Request $request)
    {
        $data = $request->validate([
            'eposta' => ['required', 'email'],
            'sifre'  => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('beni_hatirla');

        // Not: Auth::attempt içinde 'password' anahtarı kullanılır,
        // model getAuthPassword() ile sifre alanını okur.
        if (!Auth::attempt(['eposta' => $data['eposta'], 'password' => $data['sifre']], $remember)) {
            return back()->withErrors(['eposta' => 'E-posta veya şifre hatalı.'])->withInput();
        }

        $request->session()->regenerate();

        $kullanici = $request->user();
        return match ($kullanici->rol) {
            'hoca' => redirect()->route('hoca.panel'),
            'admin'      => redirect()->route('admin.panel'),
            'ogrenci'      => redirect()->route('ogrenci.panel'),
            default        => abort(403),
        };
    }
}
