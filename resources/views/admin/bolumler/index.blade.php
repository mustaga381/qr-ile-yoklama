@extends('layouts.app')
@section('baslik', 'Bölümler')

@section('icerik')
    <div class="space-y-6">

        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Yönetim</div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Bölümler</h1>
                    <p class="mt-1 text-sm text-slate-600">Bilgisayar Mühendisliği, Makine, vb.</p>
                </div>

                <a href="{{ route('admin.bolumler.create') }}" class="ui-btn-primary">
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Yeni Bölüm
                </a>
            </div>
        </div>

        @if (session('ok'))
            <div class="ui-card p-4 text-sm text-emerald-800 bg-emerald-50 border-emerald-200">
                {{ session('ok') }}
            </div>
        @endif

        <div class="ui-card p-4">
            <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-end">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600">Ara</label>
                    <input name="q" value="{{ $q }}" class="ui-input mt-1" placeholder="Bölüm adı ara...">
                </div>

                <div class="flex gap-2">
                    <button class="ui-btn">
                        <x-heroicon-o-magnifying-glass class="h-5 w-5 text-slate-700" />
                        Ara
                    </button>
                    <a href="{{ route('admin.bolumler.index') }}" class="ui-btn">
                        <x-heroicon-o-arrow-path class="h-5 w-5 text-slate-700" />
                        Sıfırla
                    </a>
                </div>
            </form>
        </div>

        <div class="ui-card p-2 overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 text-xs font-semibold text-slate-600 bg-white/60">
                <div class="col-span-7">Bölüm</div>
                <div class="col-span-2">Durum</div>
                <div class="col-span-3 text-right">İşlemler</div>
            </div>

            <div class="divide-y divide-slate-200/60">
                @forelse($bolumler as $b)
                    <div class="grid grid-cols-12 px-4 py-3 items-center">
                        <div class="col-span-7 min-w-0">
                            <div class="text-sm font-semibold text-slate-900 truncate">{{ $b->bolum_adi }}</div>
                            <div class="text-xs text-slate-500">ID: {{ $b->id }}</div>
                        </div>

                        <div class="col-span-2">
                            @if ($b->aktif_mi)
                                <span
                                    class="text-xs rounded-full px-2 py-1 bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200">Aktif</span>
                            @else
                                <span
                                    class="text-xs rounded-full px-2 py-1 bg-slate-50 text-slate-700 ring-1 ring-slate-200">Pasif</span>
                            @endif
                        </div>

                        <div class="col-span-3 flex justify-end gap-2">
                            <a href="{{ route('admin.bolumler.edit', $b->id) }}" class="ui-btn">
                                <x-heroicon-o-pencil-square class="h-5 w-5 text-slate-700" />
                                Düzenle
                            </a>

                            <form method="POST" action="{{ route('admin.bolumler.destroy', $b->id) }}"
                                onsubmit="return confirm('Bu bölümü silmek istiyor musun?')">
                                @csrf
                                @method('DELETE')
                                <button class="ui-btn">
                                    <x-heroicon-o-trash class="h-5 w-5 text-red-600" />
                                    Sil
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-sm text-slate-600">Henüz bölüm yok.</div>
                @endforelse
            </div>
        </div>

        <div class="pt-2">
            {{ $bolumler->links() }}
        </div>
    </div>
@endsection
