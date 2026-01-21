@extends('layouts.app')

@section('baslik', 'Dersler')

@section('icerik')
    <div class="space-y-6">

        {{-- Üst başlık --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Hoca Paneli</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Dersler</h1>
                    <p class="mt-1 text-sm text-slate-600">Ders tanımlarını buradan yönet, açılışlarını oluştur.</p>
                </div>

                <a href="{{ route('hoca.dersler.create') }}" class="ui-btn-primary">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Yeni Ders
                </a>
            </div>
        </div>

        {{-- Arama --}}
        <div class="ui-card p-4">
            <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-center">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600">Ara</label>
                    <input name="q" value="{{ $q }}" class="ui-input mt-1"
                        placeholder="Ders adı veya ders kodu ara...">
                </div>

                <div class="flex gap-2 md:self-end">
                    <button class="ui-btn">
                        <x-heroicon-o-magnifying-glass class="h-5 w-5 text-slate-700" />
                        Ara
                    </button>
                    <a href="{{ route('hoca.dersler.index') }}" class="ui-btn">
                        <x-heroicon-o-arrow-path class="h-5 w-5 text-slate-700" />
                        Sıfırla
                    </a>
                </div>
            </form>
        </div>

        {{-- Liste --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse ($dersler as $d)
                <div class="group ui-card p-5 relative overflow-hidden">
                    {{-- Accent --}}
                    <div
                        class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 opacity-80">
                    </div>
                    <div
                        class="absolute -right-10 -top-10 h-28 w-28 rounded-full blur-2xl opacity-0 group-hover:opacity-30 transition
                            bg-gradient-to-br from-fuchsia-500 via-indigo-500 to-cyan-400">
                    </div>

                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="inline-flex items-center gap-2">
                                <span class="ui-badge bg-white/70 text-slate-600 shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                                    {{ $d->ders_kodu }}
                                </span>
                                <span class="text-xs text-slate-500">ID: {{ $d->id }}</span>
                            </div>

                            <div class="mt-2 text-lg font-extrabold tracking-tight text-slate-900 leading-snug truncate">
                                {{ $d->ders_adi }}
                            </div>
                        </div>

                        <div
                            class="h-10 w-10 rounded-2xl grid place-items-center text-white shadow-sm
                                bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                            <x-heroicon-o-book-open class="h-5 w-5" />
                        </div>
                    </div>

                    {{-- Açıklama --}}
                    <div class="mt-3">
                        @if ($d->aciklama)
                            {{-- line-clamp yoksa fallback: --}}
                            <p class="text-sm text-slate-600 leading-relaxed md:hidden"
                                style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $d->aciklama }}
                            </p>
                        @else
                            <p class="text-sm text-slate-400">Açıklama yok.</p>
                        @endif
                    </div>

                    {{-- Aksiyonlar --}}
                    <div class="mt-5 grid grid-cols-3 gap-2">
                        <a href="{{ route('hoca.dersler.edit', $d->id) }}" class="ui-btn !rounded-2xl w-full">
                            <x-heroicon-o-pencil-square class="h-5 w-5 text-slate-700" />
                            <span class="text-sm">Düzenle</span>
                        </a>

                        <a href="{{ route('hoca.ders_acilimlari.create', ['ders_id' => $d->id]) }}"
                            class="ui-btn !rounded-2xl w-full">
                            <x-heroicon-o-academic-cap class="h-5 w-5 text-slate-700" />
                            <span class="text-sm">Açılış</span>
                        </a>

                        <form method="POST" action="{{ route('hoca.dersler.destroy', $d->id) }}"
                            onsubmit="return confirm('Bu dersi silmek istiyor musun?')">
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
                    Kayıt bulunamadı.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $dersler->links() }}
        </div>

    </div>
@endsection
