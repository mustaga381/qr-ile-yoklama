@extends('layouts.app')

@section('baslik', 'Giriş')

@section('icerik')
    <div class="mx-auto max-w-md">
        <div class="rounded-3xl border bg-white/75 backdrop-blur-xl shadow-sm p-7 md:p-9">
            <div class="text-center">
                <div
                    class="inline-flex items-center gap-2 rounded-full border bg-white/70 px-3 py-1 text-xs font-semibold text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    QR Yoklama
                </div>
                <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Giriş Yap</h1>
                <p class="mt-1 text-sm text-slate-600">Devam etmek için bilgilerini gir.</p>
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

            <form method="POST" action="{{ route('giris.yap') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-semibold text-slate-800">E-posta</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
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
                    <label class="text-sm font-semibold text-slate-800">Şifre</label>
                    <div class="mt-1 relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <x-heroicon-o-lock-closed class="h-5 w-5 text-slate-400" />
                        </span>


                        <input id="sifre" type="password" name="sifre" autocomplete="current-password"
                            class="w-full rounded-2xl border bg-white/85 pl-10 pr-16 py-3 text-slate-900 placeholder:text-slate-400 shadow-sm
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                            placeholder="••••••••" required>

                        <button type="button"
                            onclick="const i=document.getElementById('sifre'); i.type = (i.type==='password'?'text':'password');"
                            class="absolute inset-y-0 right-2 my-auto h-10 px-3 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100">
                            Göster
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <label class="flex items-center gap-2 text-sm text-slate-700 select-none">
                        <input type="checkbox" name="beni_hatirla"
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-200">
                        Beni hatırla
                    </label>

                    {{-- İstersen sonra ekleriz --}}
                    <span class="text-xs text-slate-500">Şifremi unuttum (yakında)</span>
                </div>

                <button
                    class="w-full rounded-2xl py-3 font-semibold text-white shadow-sm hover:opacity-95 active:scale-[0.99]
                           bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600">
                    Giriş
                </button>

                <p class="text-sm text-slate-600 text-center">
                    Hesabın yok mu?
                    <a class="font-semibold text-slate-900 underline underline-offset-4 hover:opacity-80"
                        href="{{ route('kayit.form') }}">
                        Kayıt ol
                    </a>
                </p>
            </form>
        </div>
    </div>
@endsection
