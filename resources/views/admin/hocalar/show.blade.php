@extends('layouts.app')

@section('baslik', 'Hoca Detay')

@section('icerik')
    <div class="space-y-6">

        {{-- Üst Bilgi --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Hoca</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $hoca->ad_soyad }}
                    </h1>

                    <div class="mt-3 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">E-posta: <b>{{ $hoca->eposta }}</b></span>

                        @if ($hoca->personel_no)
                            <span class="ui-badge bg-white/70">Personel No: <b>{{ $hoca->personel_no }}</b></span>
                        @endif

                        @if (isset($hoca->bolum) && $hoca->bolum?->ad)
                            <span class="ui-badge bg-white/70">Bölüm: <b>{{ $hoca->bolum->ad }}</b></span>
                        @endif

                        <span class="ui-badge bg-white/70">Toplam Açılış:
                            <b>{{ $toplamAcilimTum ?? $acilimlar->total() }}</b></span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.hocalar.index') }}" class="ui-btn">
                        <x-heroicon-o-arrow-left class="h-5 w-5 text-slate-700" />
                        Hocalar
                    </a>

                    <a href="{{ route('admin.hocalar.edit', $hoca->id) }}" class="ui-btn">
                        <x-heroicon-o-pencil-square class="h-5 w-5 text-slate-700" />
                        Düzenle
                    </a>
                </div>
            </div>
        </div>

        {{-- Ders Açılışları --}}
        <div class="ui-card p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="text-lg font-extrabold tracking-tight text-slate-900">Dersler</div>
                    <div class="text-sm text-slate-600">Hocanın açtığı ders açılışları ve kayıtlı öğrenci sayıları.</div>
                </div>
                <span class="ui-badge bg-white/70">{{ $acilimlar->total() }}</span>
            </div>

            <div class="mt-5 overflow-hidden rounded-2xl bg-white/70 shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                <div class="grid grid-cols-12 px-4 py-3 text-xs font-semibold text-slate-600 bg-white/60">
                    <div class="col-span-5">Ders</div>
                    <div class="col-span-3">Dönem</div>
                    <div class="col-span-2">Şube</div>
                    <div class="col-span-2 text-right">Öğrenci</div>
                </div>

                <div class="divide-y divide-slate-200/60">
                    @forelse ($acilimlar as $a)
                        @php
                            $donemText = match ($a->donem) {
                                'guz' => 'Güz',
                                'bahar' => 'Bahar',
                                'yaz' => 'Yaz',
                                default => ucfirst($a->donem),
                            };
                        @endphp

                        <div class="grid grid-cols-12 px-4 py-3 items-center">
                            <div class="col-span-5 min-w-0">
                                <div class="text-xs text-slate-500">
                                    {{ $a->ders?->ders_kodu }}
                                </div>
                                <div class="text-sm font-semibold text-slate-900 truncate">
                                    {{ $a->ders?->ders_adi }}
                                </div>
                            </div>

                            <div class="col-span-3 text-sm text-slate-700">
                                {{ $a->akademik_yil }} / {{ $donemText }}
                            </div>

                            <div class="col-span-2 text-sm text-slate-700">
                                {{ $a->sube }}
                            </div>

                            <div class="col-span-2 text-right">
                                <span
                                    class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold
                                    bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200">
                                    {{ (int) ($a->ogrenci_sayisi ?? 0) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-sm text-slate-600">
                            Bu hocanın açtığı ders açılışı yok.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4">
                {{ $acilimlar->links() }}
            </div>
        </div>

    </div>
@endsection
