@extends('layouts.app')

@section('baslik', 'Açılış Detayı')

@section('icerik')
    @php
        $donemText = match ($acilim->donem) {
            'guz' => 'Güz',
            'bahar' => 'Bahar',
            'yaz' => 'Yaz',
            default => ucfirst($acilim->donem),
        };

        $sekilMap = ['ogrenci' => 'Öğrenci', 'hoca' => 'Hoca', 'davet' => 'Davet'];
        $durumMap = [
            'onayli' => 'Onaylı',
            'beklemede' => 'Beklemede',
            'reddedildi' => 'Reddedildi',
            'iptal' => 'İptal',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Üst Bilgi --}}
        <div class="ui-card p-6 relative overflow-hidden">
            <div
                class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 opacity-80">
            </div>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Açılış Detayı</div>

                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $acilim->ders->ders_kodu }} — {{ $acilim->ders->ders_adi }}
                    </h1>

                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">{{ $acilim->akademik_yil }}</span>
                        <span class="ui-badge bg-white/70">Dönem: {{ $donemText }}</span>
                        <span class="ui-badge bg-white/70">Şube: {{ $acilim->sube }}</span>
                        <span class="ui-badge bg-white/70">Açılış ID: {{ $acilim->id }}</span>

                        <span class="ui-badge bg-white/70">
                            Kayıt: <b class="text-slate-900">{{ $kayitlar->count() }}</b>
                        </span>

                        @if ($acilim->aktif_mi)
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
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('hoca.ders_acilimlari.index') }}" class="ui-btn">
                        <x-heroicon-o-arrow-left class="h-5 w-5 text-slate-700" />
                        Geri
                    </a>

                    <a href="{{ route('hoca.ders_acilimlari.edit', $acilim->id) }}" class="ui-btn">
                        <x-heroicon-o-pencil-square class="h-5 w-5 text-slate-700" />
                        Düzenle
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="ui-card border-red-200 bg-red-50/80 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            {{-- Sol: Öğrenci ekle + Davet --}}
            <div class="lg:col-span-5 space-y-4">

                {{-- Öğrenci Ekle --}}
                <div class="ui-card p-6">
                    <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Öğrenci Ekle</h2>
                    <p class="text-sm text-slate-600 mt-1">Öğrenci numarası ile direkt derse ekle.</p>

                    <form method="POST" action="{{ route('hoca.ders_acilimlari.ogrenci_ekle', $acilim->id) }}"
                        class="mt-4 space-y-3">
                        @csrf
                        <div>
                            <label class="text-sm font-semibold text-slate-800">Öğrenci No</label>
                            <input name="ogrenci_no" class="ui-input mt-1" placeholder="örn: 202312345" required>
                        </div>

                        <button class="ui-btn-primary w-full">
                            <x-heroicon-o-plus class="h-5 w-5" />
                            Ekle
                        </button>
                    </form>
                </div>

                {{-- Davet Oluştur --}}
                <div class="ui-card p-6">
                    <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Davet Linki Oluştur</h2>
                    <p class="text-sm text-slate-600 mt-1">Link üret, öğrenci linkle kaydolabilsin.</p>

                    <form method="POST" action="{{ route('hoca.ders_acilimlari.davet_olustur', $acilim->id) }}"
                        class="mt-4 space-y-3">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-slate-800">Max Kullanım</label>
                                <input type="number" name="max_kullanim" class="ui-input mt-1" value="1"
                                    min="1" max="200" required>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-800">Geçerlilik (dk)</label>
                                <input type="number" name="gecerlilik_dakika" class="ui-input mt-1" placeholder="örn: 60">
                                <div class="text-xs text-slate-500 mt-1">Boş bırak: süresiz</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-slate-800">Hedef Öğrenci No (ops.)</label>
                                <input name="hedef_ogrenci_no" class="ui-input mt-1"
                                    placeholder="sadece o öğrenci kullanır">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-800">Hedef E-posta (ops.)</label>
                                <input type="email" name="hedef_eposta" class="ui-input mt-1"
                                    placeholder="sadece bu mail">
                            </div>
                        </div>

                        <button class="ui-btn w-full">
                            <x-heroicon-o-link class="h-5 w-5 text-slate-700" />
                            Davet Üret
                        </button>
                    </form>

                    {{-- Davetler --}}
                    @if ($davetler->count())
                        <div class="mt-5 space-y-2">
                            <div class="text-sm font-extrabold tracking-tight text-slate-900">Davetler</div>

                            @foreach ($davetler->sortByDesc('id') as $d)
                                @php
                                    $link = route('davet.goster', $d->token);

                                    $durum = !$d->aktif
                                        ? 'Pasif'
                                        : ($d->son_gecerlilik_tarihi && now()->gt($d->son_gecerlilik_tarihi)
                                            ? 'Süresi doldu'
                                            : 'Aktif');

                                    $durumClass =
                                        $durum === 'Aktif'
                                            ? 'bg-emerald-50 text-emerald-800 ring-emerald-200'
                                            : 'bg-slate-50 text-slate-700 ring-slate-200';
                                @endphp

                                <div class="rounded-2xl bg-white/70 p-3 shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <div class="text-xs text-slate-600">
                                                <span class="font-semibold">Link:</span>
                                                <span class="select-all break-all">{{ $link }}</span>
                                            </div>

                                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                                <span
                                                    class="ui-badge bg-white/70">{{ $d->kullanim_sayisi }}/{{ $d->max_kullanim }}</span>

                                                <span class="text-xs rounded-full px-2 py-1 ring-1 {{ $durumClass }}">
                                                    {{ $durum }}
                                                </span>

                                                @if ($d->son_gecerlilik_tarihi)
                                                    <span class="ui-badge bg-white/70">
                                                        Son: {{ $d->son_gecerlilik_tarihi->format('d.m.Y H:i') }}
                                                    </span>
                                                @endif

                                                @if ($d->hedef_ogrenci_id)
                                                    <span class="ui-badge bg-white/70">Hedef: Öğrenci</span>
                                                @endif

                                                @if ($d->hedef_eposta)
                                                    <span class="ui-badge bg-white/70 truncate">Mail:
                                                        {{ $d->hedef_eposta }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($d->aktif)
                                            <form method="POST"
                                                action="{{ route('hoca.ders_acilimlari.davet_pasif', [$acilim->id, $d->id]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-xs font-semibold text-red-700 hover:underline">
                                                    Pasife Al
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sağ: Kayıtlı öğrenciler --}}
            <div class="lg:col-span-7">
                <div class="ui-card p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Kayıtlı Öğrenciler</h2>
                        <span class="ui-badge bg-white/70">{{ $kayitlar->count() }}</span>
                    </div>

                    <div class="mt-4 divide-y divide-slate-200/60">
                        @forelse($kayitlar as $k)
                            @php
                                $sekil = $sekilMap[$k->kayit_sekli] ?? ($k->kayit_sekli ?? '—');
                                $durum = $durumMap[$k->durum] ?? ($k->durum ?? '—');
                            @endphp

                            <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-900 truncate">
                                        {{ $k->ogrenci->ad_soyad ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        No: {{ $k->ogrenci->ogrenci_no ?? '—' }}
                                        • Durum: {{ $durum }}
                                        • Kayıt: {{ $sekil }}
                                    </div>
                                </div>

                                <form method="POST"
                                    action="{{ route('hoca.ders_acilimlari.ogrenci_sil', [$acilim->id, $k->ogrenci_id]) }}"
                                    onsubmit="return confirm('Öğrenciyi bu açılıştan çıkarmak istiyor musun?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm font-semibold text-red-700 hover:underline">
                                        Çıkar
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="py-6 text-sm text-slate-600">
                                Henüz kayıtlı öğrenci yok.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
