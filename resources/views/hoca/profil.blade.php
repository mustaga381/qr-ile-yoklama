@extends('layouts.app')

@section('baslik', 'Profil')

@section('icerik')
    @php $u = $kullanici; @endphp

    <div class="space-y-6">

        @if (session('ok'))
            <div class="ui-card border-emerald-200 bg-emerald-50/80 p-4 text-sm text-emerald-800">
                <div class="font-semibold">Başarılı</div>
                <div class="mt-1">{{ session('ok') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="ui-card border-red-200 bg-red-50/80 p-4 text-sm text-red-700">
                <div class="font-semibold mb-1">Bir şeyler ters gitti</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="ui-card p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Hoca</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Profil</h1>
                    <p class="mt-1 text-sm text-slate-600">E-postanı güncelle, derslerine hızlı eriş.</p>
                </div>
                <span class="ui-badge">Rol: HOCA</span>
            </div>
        </div>
        <div class="ui-card p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold tracking-tight text-slate-900">Bölüm</h3>
            </div>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="text-sm text-slate-600">{{ $kullanici->bolum?->bolum_adi ?? '—' }}</div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            <div class="lg:col-span-7 space-y-4">

                <div class="ui-card p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Kişisel Bilgiler</h2>
                        <x-heroicon-o-user-circle class="h-6 w-6 text-slate-600" />
                    </div>

                    <form method="POST" action="{{ route('profil.guncelle') }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="text-sm font-semibold text-slate-800">Ad Soyad</label>
                            <input value="{{ $u->ad_soyad }}" readonly tabindex="-1"
                                class="ui-input mt-1 bg-slate-50/70 text-slate-700 cursor-not-allowed">
                            <input type="hidden" name="ad_soyad" value="{{ $u->ad_soyad }}">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-800">Personel No</label>
                            <input value="{{ $u->personel_no }}" readonly tabindex="-1"
                                class="ui-input mt-1 bg-slate-50/70 text-slate-700 cursor-not-allowed">
                            <input type="hidden" name="personel_no" value="{{ $u->personel_no }}">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-800">E-posta</label>
                            <input type="email" name="eposta" value="{{ old('eposta', $u->eposta) }}"
                                class="ui-input mt-1" required>
                        </div>

                        <button class="ui-btn-primary w-full sm:w-auto">
                            <x-heroicon-o-check class="h-5 w-5" />
                            Bilgileri Kaydet
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-4">

                <div class="ui-card p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Şifre Değiştir</h2>
                        <x-heroicon-o-key class="h-6 w-6 text-slate-600" />
                    </div>

                    <form method="POST" action="{{ route('profil.sifre') }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="text-sm font-semibold text-slate-800">Mevcut Şifre</label>
                            <input type="password" name="mevcut_sifre" class="ui-input mt-1" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-slate-800">Yeni Şifre</label>
                                <input type="password" name="yeni_sifre" class="ui-input mt-1" required minlength="8">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-800">Yeni Şifre Tekrar</label>
                                <input type="password" name="yeni_sifre_confirmation" class="ui-input mt-1" required
                                    minlength="8">
                            </div>
                        </div>

                        <button class="ui-btn w-full sm:w-auto">
                            <x-heroicon-o-arrow-path class="h-5 w-5 text-slate-700" />
                            Şifreyi Güncelle
                        </button>
                    </form>
                </div>

                <div class="ui-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Cihazlar</h2>
                            <p class="text-sm text-slate-600 mt-1">Yoklamalara hangi cihazlarla katıldığın.</p>
                        </div>
                        <x-heroicon-o-device-phone-mobile class="h-6 w-6 text-slate-600" />
                    </div>

                    <div class="mt-4 divide-y divide-slate-200/60">
                        @forelse ($cihazlar as $c)
                            <div class="py-3">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ $c->etiket ?? ($c->platform ?? 'Cihaz') }}
                                        @if ($c->guvenilir_mi)
                                            <span
                                                class="ml-2 text-xs rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 px-2 py-0.5">
                                                Güvenilir
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $c->son_gorulme ? $c->son_gorulme->format('d.m.Y H:i') : '—' }}
                                    </div>
                                </div>
                                <div class="text-xs text-slate-500 break-all">UUID: {{ $c->cihaz_uuid }}</div>
                            </div>
                        @empty
                            <div class="py-6 text-sm text-slate-600">Henüz cihaz kaydı yok.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
