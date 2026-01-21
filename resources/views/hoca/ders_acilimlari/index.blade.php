@extends('layouts.app')

@section('baslik', 'Ders Açılışları')

@section('icerik')
    @php

        $toplam = $istatistik['toplam'];
        $sayfadaAktif = $istatistik['aktif'];
        $sayfadaPasif = $istatistik['pasif'];
        $qVar = (string) request('q') !== '';
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="ui-card p-6 relative overflow-hidden">
            <div
                class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 opacity-80">
            </div>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Ders Yönetimi</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Ders Açılışları</h1>
                    <p class="mt-1 text-sm text-slate-600">Akademik yıl / dönem / şube bazında ders aç.</p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">Toplam: <b class="text-slate-900">{{ $toplam }}</b></span>
                        <span class="ui-badge bg-white/70">Bu sayfa aktif: <b
                                class="text-emerald-700">{{ $sayfadaAktif }}</b></span>
                        <span class="ui-badge bg-white/70">Bu sayfa pasif: <b
                                class="text-slate-700">{{ $sayfadaPasif }}</b></span>
                    </div>
                </div>

                <a href="{{ route('hoca.ders_acilimlari.create') }}" class="ui-btn-primary">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Yeni Açılış
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="ui-card p-4">
            <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-end">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600">Ara</label>
                    <div class="mt-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                        </span>
                        <input name="q" value="{{ $q }}" class="ui-input pl-10"
                            placeholder="Ders adı / kod / akademik yıl ara...">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="ui-btn">
                        Ara
                    </button>

                    @if ($qVar)
                        <a href="{{ route('hoca.ders_acilimlari.index') }}" class="ui-btn">
                            Sıfırla
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($acilimlar as $a)
                @php
                    $donemText = match ($a->donem) {
                        'guz' => 'Güz',
                        'bahar' => 'Bahar',
                        'yaz' => 'Yaz',
                        default => ucfirst($a->donem),
                    };

                    $kayitSay = $a->kayitlar_count ?? null;
                @endphp

                <div class="group ui-card p-5 relative overflow-hidden hover:shadow-md transition">
                    <div
                        class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 opacity-70">
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-xs text-slate-500">
                                {{ $a->ders->ders_kodu }} • {{ $a->akademik_yil }}
                            </div>
                            <div class="mt-1 text-lg font-extrabold tracking-tight text-slate-900 truncate">
                                {{ $a->ders->ders_adi }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                Açılış ID: {{ $a->id }}
                            </div>
                        </div>

                        <div
                            class="shrink-0 h-10 w-10 rounded-2xl grid place-items-center text-white shadow-sm
                                    bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                            <x-heroicon-o-academic-cap class="h-5 w-5" />
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">Dönem: {{ $donemText }}</span>
                        <span class="ui-badge bg-white/70">Şube: {{ $a->sube }}</span>

                        @if (!is_null($kayitSay))
                            <span class="ui-badge bg-white/70">Kayıt: <b
                                    class="text-slate-900">{{ $kayitSay }}</b></span>
                        @endif

                        @if ($a->aktif_mi)
                            <span
                                class="text-xs rounded-full px-2 py-1 bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200">
                                Aktif
                            </span>
                        @else
                            <span class="text-xs rounded-full px-2 py-1 bg-slate-50 text-slate-700 ring-1 ring-slate-200">
                                Pasif
                            </span>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-3 gap-2">
                        {{-- ✅ Detay --}}
                        <a href="{{ route('hoca.ders_acilimlari.show', $a->id) }}" class="ui-btn w-full">
                            <x-heroicon-o-eye class="h-5 w-5 text-slate-700" />
                            Detay
                        </a>

                        <a href="{{ route('hoca.ders_acilimlari.edit', $a->id) }}" class="ui-btn w-full">
                            <x-heroicon-o-pencil-square class="h-5 w-5 text-slate-700" />
                            Düzenle
                        </a>

                        <form method="POST" action="{{ route('hoca.ders_acilimlari.destroy', $a->id) }}"
                            onsubmit="return confirm('Bu açılışı silmek istiyor musun?')">
                            @csrf
                            @method('DELETE')
                            <button
                                class="w-full rounded-2xl px-3 py-2.5 text-sm font-semibold text-red-700
                                       bg-red-50 hover:bg-red-100 transition
                                       shadow-[0_0_0_1px_rgba(239,68,68,0.25)] inline-flex items-center justify-center gap-2">
                                <x-heroicon-o-trash class="h-5 w-5" />
                                Sil
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full ui-card p-8">
                    <div class="text-sm text-slate-700 font-semibold">Kayıt bulunamadı.</div>
                    <div class="text-sm text-slate-500 mt-1">
                        Arama yaptıysan sıfırlayıp tekrar dene ya da yeni açılış oluştur.
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('hoca.ders_acilimlari.create') }}" class="ui-btn-primary">
                            <x-heroicon-o-plus class="h-5 w-5" />
                            Yeni Açılış
                        </a>
                        <a href="{{ route('hoca.ders_acilimlari.index') }}" class="ui-btn">
                            Listeyi Sıfırla
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $acilimlar->links() }}
        </div>

    </div>
@endsection
