@extends('layouts.app')

@section('baslik', 'Profil')

@section('icerik')
    <div class="space-y-6">

        @if (session('ok'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">
                {{ session('ok') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Kişisel Bilgiler -->
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-xl font-semibold">Profil</h1>
                    <p class="text-sm text-slate-600 mt-1">Kişisel bilgilerini güncelle.</p>
                </div>
                <span class="text-xs rounded-full border px-2 py-1 text-slate-600">
                    Rol: {{ $kullanici->rol }}
                </span>
            </div>

            <form method="POST" action="{{ route('profil.guncelle') }}" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="text-sm font-medium">Ad Soyad</label>
                    <input name="ad_soyad" value="{{ old('ad_soyad', $kullanici->ad_soyad) }}"
                        class="mt-1 w-full rounded-xl border px-3 py-2" required>
                </div>

                <div>
                    <label class="text-sm font-medium">E-posta</label>
                    <input type="email" name="eposta" value="{{ old('eposta', $kullanici->eposta) }}"
                        class="mt-1 w-full rounded-xl border px-3 py-2" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">Öğrenci No</label>
                        <input name="ogrenci_no" value="{{ old('ogrenci_no', $kullanici->ogrenci_no) }}"
                            class="mt-1 w-full rounded-xl border px-3 py-2" @disabled($kullanici->rol !== 'ogrenci')>
                        @if ($kullanici->rol !== 'ogrenci')
                            <p class="text-xs text-slate-500 mt-1">Sadece öğrenci rolünde düzenlenir.</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium">Personel No</label>
                        <input name="personel_no" value="{{ old('personel_no', $kullanici->personel_no) }}"
                            class="mt-1 w-full rounded-xl border px-3 py-2" @disabled($kullanici->rol !== 'hoca')>
                        @if ($kullanici->rol !== 'hoca')
                            <p class="text-xs text-slate-500 mt-1">Sadece hoca rolünde düzenlenir.</p>
                        @endif
                    </div>
                </div>

                <button class="rounded-xl bg-slate-900 text-white px-4 py-2.5 font-medium hover:opacity-90">
                    Bilgileri Kaydet
                </button>
            </form>
        </div>

        <!-- Şifre Değiştir -->
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Şifre Değiştir</h2>

            <form method="POST" action="{{ route('profil.sifre') }}" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="text-sm font-medium">Mevcut Şifre</label>
                    <input type="password" name="mevcut_sifre" class="mt-1 w-full rounded-xl border px-3 py-2" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">Yeni Şifre</label>
                        <input type="password" name="yeni_sifre" class="mt-1 w-full rounded-xl border px-3 py-2" required
                            minlength="8">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Yeni Şifre Tekrar</label>
                        <input type="password" name="yeni_sifre_confirmation"
                            class="mt-1 w-full rounded-xl border px-3 py-2" required minlength="8">
                    </div>
                </div>

                <button class="rounded-xl border bg-white px-4 py-2.5 font-medium hover:bg-slate-50">
                    Şifreyi Güncelle
                </button>
            </form>
        </div>

        <!-- Cihazlar -->
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Cihazlar</h2>
            <p class="text-sm text-slate-600 mt-1">Yoklamalara hangi cihazlarla katıldığın burada görünecek.</p>

            <div class="mt-4 divide-y">
                @forelse ($cihazlar as $c)
                    <div class="py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <div class="text-sm font-medium">
                                {{ $c->etiket ?? ($c->platform ?? 'Cihaz') }}
                                @if ($c->guvenilir_mi)
                                    <span
                                        class="ml-2 text-xs rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5">Güvenilir</span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-500 break-all">
                                UUID: {{ $c->cihaz_uuid }}
                            </div>
                        </div>
                        <div class="text-xs text-slate-500">
                            Son görülme: {{ $c->son_gorulme ? $c->son_gorulme->format('d.m.Y H:i') : '—' }}
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-sm text-slate-600">
                        Henüz cihaz kaydı yok.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
