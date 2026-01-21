@extends('layouts.app')
@section('baslik', 'Hocalar')

@section('icerik')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Kullanıcı Yönetimi</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Hocalar</h1>
                    <p class="mt-1 text-sm text-slate-600">Hoca ekle, düzenle, bölümünü yönet.</p>
                </div>

                <a href="{{ route('admin.hocalar.create') }}" class="ui-btn-primary">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Yeni Hoca
                </a>
            </div>
        </div>
        {{-- Search --}}
        <div class="ui-card p-4">
            <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-end">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600">Ara</label>
                    <input name="q" value="{{ $q }}" class="ui-input mt-1"
                        placeholder="Ad soyad / e-posta / personel no...">
                </div>

                <div class="flex gap-2">
                    <button class="ui-btn">
                        <x-heroicon-o-magnifying-glass class="h-5 w-5 text-slate-700" />
                        Ara
                    </button>
                    <a href="{{ route('admin.hocalar.index') }}" class="ui-btn">
                        <x-heroicon-o-arrow-path class="h-5 w-5 text-slate-700" />
                        Sıfırla
                    </a>
                </div>
            </form>
        </div>

        {{-- List --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse ($hocalar as $h)
                <div class="group ui-card p-5 relative overflow-hidden">
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
                                {{ $h->ad_soyad }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500 break-all">
                                {{ $h->eposta }}
                            </div>
                        </div>

                        <div
                            class="h-10 w-10 rounded-2xl grid place-items-center text-white shadow-sm
                                bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                            <x-heroicon-o-user class="h-5 w-5" />
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <span class="ui-badge bg-white/70">Personel: <b>{{ $h->personel_no }}</b></span>
                        <span class="ui-badge bg-white/70">
                            Bölüm: <b>{{ $h->bolum?->bolum_adi ?? '—' }}</b>
                        </span>
                        <span class="ui-badge bg-white/70">Ders: <b>{{ (int) ($h->ders_sayisi ?? 0) }}</b></span>

                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.hocalar.show', $h->id) }}" class="ui-btn">
                            <x-heroicon-o-eye class="h-5 w-5 text-slate-700" />
                            Detay
                        </a>

                        <a href="{{ route('admin.hocalar.edit', $h->id) }}" class="ui-btn w-full">
                            <x-heroicon-o-pencil-square class="h-5 w-5 text-slate-700" />
                            Düzenle
                        </a>

                        <form method="POST" action="{{ route('admin.hocalar.destroy', $h->id) }}"
                            onsubmit="return confirm('Bu hocayı silmek istiyor musun?')">
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
                <div class="col-span-full ui-card p-6 text-sm text-slate-600">
                    Kayıt yok.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $hocalar->links() }}
        </div>
    </div>
@endsection
