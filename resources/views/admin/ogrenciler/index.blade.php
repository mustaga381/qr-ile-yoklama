@extends('layouts.app')
@section('baslik', 'Öğrenciler')

@section('icerik')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Kullanıcı Yönetimi</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Öğrenciler</h1>
                    <p class="mt-1 text-sm text-slate-600">Öğrenci listesi, detaylar, yoklama geçmişi.</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="ui-card p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-6">
                    <label class="text-xs font-semibold text-slate-600">Ara</label>
                    <input name="q" value="{{ $q }}" class="ui-input mt-1"
                        placeholder="Ad soyad / e-posta / öğrenci no...">
                </div>

                <div class="md:col-span-4">
                    <label class="text-xs font-semibold text-slate-600">Bölüm</label>
                    <select name="bolum_id" class="ui-input mt-1">
                        <option value="">Tümü</option>
                        @foreach ($bolumler as $b)
                            <option value="{{ $b->id }}" @selected((string) $bolumId === (string) $b->id)>
                                {{ $b->bolum_adi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-2">
                        <button class="ui-btn w-full">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-slate-700" />
                            Ara
                        </button>

                        <a href="{{ route('admin.ogrenciler.index') }}" class="ui-btn w-full">
                            <x-heroicon-o-arrow-path class="h-5 w-5 text-slate-700" />
                            Sıfırla
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($ogrenciler as $o)
                <a href="{{ route('admin.ogrenciler.show', $o->id) }}"
                    class="group ui-card p-5 relative overflow-hidden hover:bg-white transition">
                    <div
                        class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 opacity-80">
                    </div>
                    <div
                        class="absolute -right-10 -top-10 h-28 w-28 rounded-full blur-2xl opacity-0 group-hover:opacity-30 transition
                            bg-gradient-to-br from-fuchsia-500 via-indigo-500 to-cyan-400">
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-lg font-extrabold tracking-tight text-slate-900 truncate">
                                {{ $o->ad_soyad }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500 break-all">{{ $o->eposta }}</div>
                        </div>

                        <div
                            class="h-10 w-10 rounded-2xl grid place-items-center text-white shadow-sm
                                bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                            <x-heroicon-o-academic-cap class="h-5 w-5" />
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">Öğrenci No: <b>{{ $o->ogrenci_no }}</b></span>
                        <span class="ui-badge bg-white/70">Bölüm: <b>{{ $o->bolum?->bolum_adi ?? '—' }}</b></span>
                    </div>

                    <div class="mt-4 text-xs text-slate-500">
                        Detaylara git →
                    </div>
                </a>
            @empty
                <div class="col-span-full ui-card p-6 text-sm text-slate-600">
                    Kayıt yok.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $ogrenciler->links() }}
        </div>

    </div>
@endsection
