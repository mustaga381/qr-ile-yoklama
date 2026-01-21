@extends('layouts.app')

@section('baslik', 'Öğrenci Kaydı')

@section('icerik')
    <div class="mx-auto max-w-md">
        <div class="rounded-3xl bg-white/75 backdrop-blur-xl shadow-sm p-7 md:p-9">
            <div class="text-center">
                <div
                    class="inline-flex items-center gap-2 rounded-full border bg-white/70 px-3 py-1 text-xs font-semibold text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    Öğrenci Kaydı
                </div>

                <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Kayıt Ol</h1>
                <p class="mt-1 text-sm text-slate-600">Hesabını oluştur, derse kayıt ol ve yoklamaya katıl.</p>
            </div>

            @if ($errors->any())
                <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <div class="font-semibold mb-1">Bir şeyler ters gitti</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('kayit.kaydet') }}" class="mt-6 space-y-4">
                @csrf

                {{-- Güvenlik: rol sabit öğrenci --}}
                <input type="hidden" name="rol" value="ogrenci">

                <div>
                    <label class="text-sm font-semibold text-slate-800">Ad Soyad</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <x-heroicon-o-user class="h-5 w-5 text-slate-400" />

                        </span>

                        <input name="ad_soyad" value="{{ old('ad_soyad') }}"
                            class="w-full rounded-2xl border bg-white/85 pl-10 pr-3 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                            placeholder="Adın Soyadın" required>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">E-posta</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <x-heroicon-o-envelope class="h-5 w-5 text-slate-400" />

                        </span>

                        <input type="email" name="eposta" value="{{ old('eposta') }}" autocomplete="email"
                            inputmode="email"
                            class="w-full rounded-2xl border bg-white/85 pl-10 pr-3 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                            placeholder="ornek@okul.edu.tr" required>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Öğrenci Numarası</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <x-heroicon-o-identification class="h-5 w-5 text-slate-400" />

                        </span>

                        <input name="ogrenci_no" value="{{ old('ogrenci_no') }}"
                            class="w-full rounded-2xl border bg-white/85 pl-10 pr-3 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                            placeholder="Örn: 213255067" required>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Okul numaranı doğru gir — ders kayıtlarında kullanacağız.</p>
                </div>
                <div>
                    <label class="text-sm font-medium">Bölüm</label>
                    <select name="bolum_id" class="mt-1 w-full rounded-xl border px-3 py-2" required>
                        <option value="">Seçiniz</option>
                        @foreach ($bolumler as $b)
                            <option value="{{ $b->id }}" @selected(old('bolum_id') == $b->id)>{{ $b->bolum_adi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-800">Şifre</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <!-- lock -->
                            <x-heroicon-o-lock-closed class="h-5 w-5 text-slate-400" />
                        </span>

                        <input id="sifre" type="password" name="sifre" minlength="8"
                            class="w-full rounded-2xl border bg-white/85 pl-10 pr-16 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                            placeholder="En az 8 karakter" required>

                        <button type="button"
                            onclick="const i=document.getElementById('sifre'); i.type = (i.type==='password'?'text':'password');"
                            class="absolute inset-y-0 right-2 my-auto h-10 px-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100">
                            Göster
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Şifre Tekrar</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <!-- lock -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-7-2a2 2 0 0 1 4 0v2h-4V6z" />
                            </svg>
                        </span>

                        <input type="password" name="sifre_confirmation" minlength="8"
                            class="w-full rounded-2xl border bg-white/85 pl-10 pr-3 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                            placeholder="Tekrar gir" required>
                    </div>
                </div>

                <button
                    class="w-full rounded-2xl py-3 font-semibold text-white shadow-sm hover:opacity-95 active:scale-[0.99]
                           bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600">
                    Kayıt Ol
                </button>

                <p class="text-sm text-slate-600 text-center">
                    Zaten hesabın var mı?
                    <a class="font-semibold text-slate-900 underline underline-offset-4 hover:opacity-80"
                        href="{{ route('giris.form') }}">
                        Giriş yap
                    </a>
                </p>
            </form>
        </div>
    </div>
@endsection
