@extends('layouts.app')
@section('baslik', 'Öğrenci Detay')

@section('icerik')
    @php
        $bolum = $ogrenci->bolum?->bolum_adi ?? '—';
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Öğrenci</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 truncate">
                        {{ $ogrenci->ad_soyad }}
                    </h1>
                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">Öğrenci No: <b>{{ $ogrenci->ogrenci_no }}</b></span>
                        <span class="ui-badge bg-white/70">Bölüm: <b>{{ $bolum }}</b></span>
                        <span class="ui-badge bg-white/70">E-posta: <b>{{ $ogrenci->eposta }}</b></span>
                    </div>
                </div>

                <a href="{{ route('admin.ogrenciler.index') }}" class="ui-btn">
                    <x-heroicon-o-arrow-left class="h-5 w-5 text-slate-700" />
                    Listeye Dön
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            {{-- Ders Kayıtları --}}
            <div class="lg:col-span-7 ui-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-extrabold tracking-tight text-slate-900">Ders Kayıtları</div>
                        <div class="text-sm text-slate-600">Öğrencinin kayıtlı olduğu ders açılışları.</div>
                    </div>
                    <span class="ui-badge bg-white/70">{{ $dersKayitlari->count() }}</span>
                </div>

                <div class="mt-4 divide-y divide-slate-200/60">
                    @forelse($dersKayitlari as $k)
                        @php
                            $a = $k->acilim;
                            $d = $a?->ders;
                        @endphp
                        <div class="py-3">
                            <div class="font-semibold text-slate-900">
                                {{ $d?->ders_kodu }} — {{ $d?->ders_adi }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                Açılış: {{ $a?->akademik_yil }} / {{ $a?->donem }} / Şube {{ $a?->sube }}
                                • Durum: <b>{{ $k->durum ?? '—' }}</b>
                            </div>
                            @if (!empty($k->kayit_sekli))
                                <div class="mt-1 text-xs text-slate-500">Kayıt Şekli: <b>{{ $k->kayit_sekli }}</b></div>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-sm text-slate-600">Kayıtlı ders yok.</div>
                    @endforelse
                </div>
            </div>

            {{-- Cihazlar --}}
            <div class="lg:col-span-5 ui-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-extrabold tracking-tight text-slate-900">Cihazlar</div>
                        <div class="text-sm text-slate-600">Yoklamaya katıldığı cihazlar.</div>
                    </div>
                    <span class="ui-badge bg-white/70">{{ $cihazlar->count() }}</span>
                </div>

                <div class="mt-4 divide-y divide-slate-200/60">
                    @forelse($cihazlar as $c)
                        <div class="py-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-sm font-semibold text-slate-900 truncate">
                                    {{ $c->etiket ?? ($c->platform ?? 'Cihaz') }}
                                </div>

                                @if (!empty($c->guvenilir_mi))
                                    <span
                                        class="text-xs rounded-full px-2 py-1 bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200">
                                        Güvenilir
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-500 break-all mt-1">UUID: {{ $c->cihaz_uuid }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                Son görülme: {{ $c->son_gorulme ? $c->son_gorulme->format('d.m.Y H:i') : '—' }}
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-sm text-slate-600">Cihaz kaydı yok.</div>
                    @endforelse
                </div>
            </div>

            {{-- Yoklama geçmişi --}}
            <div class="lg:col-span-12 ui-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-extrabold tracking-tight text-slate-900">Yoklama Geçmişi</div>
                        <div class="text-sm text-slate-600">Son 30 yoklama katılımı.</div>
                    </div>
                    <span class="ui-badge bg-white/70">{{ $yoklamalar->count() }}</span>
                </div>

                <div class="mt-4 overflow-hidden rounded-2xl bg-white/70 shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                    <div class="grid grid-cols-12 px-4 py-3 text-xs font-semibold text-slate-600 bg-white/60">
                        <div class="col-span-3">Tarih</div>
                        <div class="col-span-5">Ders</div>
                        <div class="col-span-2">IP</div>
                        <div class="col-span-2">Cihaz</div>
                    </div>

                    <div class="divide-y divide-slate-200/60">
                        @forelse($yoklamalar as $y)
                            @php
                                $o = $y->oturum;
                                $a = $o?->dersAcilim;
                                $d = $a?->ders;
                            @endphp
                            <div class="grid grid-cols-12 px-4 py-3 text-sm">
                                <div class="col-span-3 text-slate-700">
                                    {{ $y->katilim_zamani?->format('d.m.Y H:i:s') ?? '—' }}
                                    <div class="text-xs text-slate-500">Durum: <b>{{ $y->durum ?? '—' }}</b></div>
                                </div>

                                <div class="col-span-5">
                                    <div class="font-semibold text-slate-900">
                                        {{ $d?->ders_kodu }} — {{ $d?->ders_adi }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $a?->akademik_yil }} / {{ $a?->donem }} / Şube {{ $a?->sube }}
                                    </div>
                                </div>

                                <div class="col-span-2 text-slate-700">
                                    <div class="text-xs break-all">{{ $y->ip_adresi ?? '—' }}</div>
                                </div>

                                <div class="col-span-2 text-slate-700">
                                    <div class="text-xs break-all">{{ $y->cihaz_uuid ?? '—' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-sm text-slate-600">
                                Yoklama kaydı yok.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
