@extends('layouts.app')

@section('baslik', 'Ders Daveti')

@section('icerik')
    @php
        $u = auth()->user();

        $acilim = $davet->dersAcilim ?? null;
        $ders = $acilim?->ders ?? null;

        $donemText = match ($acilim?->donem) {
            'guz' => 'Güz',
            'bahar' => 'Bahar',
            'yaz' => 'Yaz',
            default => $acilim?->donem ? ucfirst($acilim->donem) : '—',
        };

        $sureDoldu = $davet->son_gecerlilik_tarihi ? now()->gt($davet->son_gecerlilik_tarihi) : false;
        $kullanimDoldu = ((int) $davet->kullanim_sayisi) >= ((int) $davet->max_kullanim);

        $hedefOk = true;
        if ($davet->hedef_ogrenci_id && (int) $davet->hedef_ogrenci_id !== (int) $u->id) {
            $hedefOk = false;
        }
        if ($davet->hedef_eposta && mb_strtolower($davet->hedef_eposta) !== mb_strtolower($u->eposta)) {
            $hedefOk = false;
        }

        $kabulEdilebilir = (bool) $davet->aktif && !$sureDoldu && !$kullanimDoldu && $hedefOk;

        $durumText = !$davet->aktif
            ? 'Pasif'
            : ($sureDoldu
                ? 'Süresi Doldu'
                : ($kullanimDoldu
                    ? 'Kullanım Doldu'
                    : ($hedefOk
                        ? 'Aktif'
                        : 'Bu davet sana ait değil')));

        $durumClass = $kabulEdilebilir
            ? 'bg-emerald-50 text-emerald-800 ring-emerald-200'
            : 'bg-slate-50 text-slate-700 ring-slate-200';
    @endphp

    <div class="space-y-6">

        @if (session('ok'))
            <div class="ui-card border-emerald-200 bg-emerald-50/80 p-4 text-sm text-emerald-800">
                {{ session('ok') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="ui-card border-red-200 bg-red-50/80 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="ui-card p-7 relative overflow-hidden">
            <div
                class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 opacity-80">
            </div>

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Ders Daveti</div>

                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">
                        {{ $ders?->ders_kodu ?? '—' }} — {{ $ders?->ders_adi ?? 'Ders' }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-600">
                        Bu daveti kabul ederek derse kayıt olacaksın.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">
                            Akademik Yıl: <b class="text-slate-900">{{ $acilim?->akademik_yil ?? '—' }}</b>
                        </span>
                        <span class="ui-badge bg-white/70">
                            Dönem: <b class="text-slate-900">{{ $donemText }}</b>
                        </span>
                        <span class="ui-badge bg-white/70">
                            Şube: <b class="text-slate-900">{{ $acilim?->sube ?? '—' }}</b>
                        </span>

                        <span class="ui-badge bg-white/70">
                            Kullanım: <b class="text-slate-900">{{ $davet->kullanim_sayisi }}/{{ $davet->max_kullanim }}</b>
                        </span>

                        @if ($davet->son_gecerlilik_tarihi)
                            <span class="ui-badge bg-white/70">
                                Son: <b class="text-slate-900">{{ $davet->son_gecerlilik_tarihi->format('d.m.Y H:i') }}</b>
                            </span>
                        @endif

                        <span class="text-xs rounded-full px-2 py-1 ring-1 {{ $durumClass }}">
                            {{ $durumText }}
                        </span>
                    </div>
                </div>

                <div class="shrink-0">
                    <div
                        class="h-11 w-11 rounded-2xl grid place-items-center text-white shadow-sm
                            bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                        <x-heroicon-o-link class="h-6 w-6" />
                    </div>
                </div>
            </div>

            {{-- Hedef kısıtları --}}
            @if ($davet->hedef_ogrenci_id || $davet->hedef_eposta)
                <div class="mt-5 rounded-2xl bg-white/70 p-4 shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                    <div class="text-sm font-semibold text-slate-900">Bu davet kısıtlı</div>
                    <div class="mt-1 text-sm text-slate-600">
                        @if ($davet->hedef_ogrenci_id)
                            • Sadece belirli bir öğrenci hesabı kullanabilir.
                        @endif
                        @if ($davet->hedef_eposta)
                            • Sadece şu e-posta ile: <span class="font-semibold">{{ $davet->hedef_eposta }}</span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Aksiyon --}}
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                @if ($kabulEdilebilir)
                    <form method="POST" action="{{ route('davet.kabul', $davet->token) }}" class="w-full sm:w-auto">
                        @csrf
                        <button class="ui-btn-primary w-full sm:w-auto">
                            <x-heroicon-o-check class="h-5 w-5" />
                            Daveti Kabul Et
                        </button>
                    </form>
                @else
                    <div class="w-full rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200 text-sm text-slate-700">
                        Bu davet şu an kullanılamıyor: <b>{{ $durumText }}</b>
                    </div>
                @endif

                <a href="{{ route('ogrenci.panel') }}" class="ui-btn w-full sm:w-auto">
                    <x-heroicon-o-squares-2x2 class="h-5 w-5 text-slate-700" />
                    Panele Dön
                </a>
            </div>
        </div>

    </div>
@endsection
