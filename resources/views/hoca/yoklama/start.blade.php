@extends('layouts.app')
@section('baslik', 'Yoklama')

@section('icerik')
    <div class="mx-auto px-4 py-6 space-y-6">

        @if (session('toast'))
            <div class="ui-card p-4">
                <div class="font-semibold text-slate-900">{{ session('toast') }}</div>
            </div>
        @endif

        <div class="ui-card p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">Yoklama</h1>
                    <p class="text-slate-600 mt-1">Önce ders açılımını seç, oturumu başlat, sonra yoklamayı aç.</p>
                </div>
                <span class="ui-badge">Oturum</span>
            </div>

            <form class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3" method="POST"
                action="{{ route('hoca.yoklama.oturumBaslat') }}">
                @csrf

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Ders Açılımı</label>
                    <select name="ders_acilim_id" class="ui-select mt-1" required>
                        <option value="">Seçiniz…</option>
                        @foreach ($dersAcilimlari as $da)
                            <option value="{{ $da->id }}">
                                #{{ $da->id }} — {{ $da->tam_ad ?? 'Ders Açılım ' . $da->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Başlık (opsiyonel)</label>
                    <input name="baslik" class="ui-input mt-1" placeholder="Örn: Vize Öncesi Tekrar" />
                </div>

                <div class="md:col-span-3 flex flex-col sm:flex-row gap-2 mt-2">
                    <button class="ui-btn-primary w-full sm:w-auto" type="submit">Oturum Başlat</button>
                    <a class="ui-btn w-full sm:w-auto" href="{{ url()->current() }}">Yenile</a>
                </div>
            </form>
        </div>

        <div class="ui-card p-6">
            <div class="flex items-center justify-between">
                <div class="font-extrabold text-slate-900">
                    Bugünün Oturumları
                    <span class="ui-badge">{{ $bugunOturumlar->count() }}</span>
                </div>
                <span class="ui-badge">{{ now()->format('d.m.Y') }}</span>
            </div>

            <div class="mt-4 divide-y divide-slate-900/5">
                @forelse($bugunOturumlar as $o)
                    <a href="{{ route('hoca.yoklama.show', $o->id) }}" class="block py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900 truncate">
                                    {{ $o->baslik ?? 'Oturum' }} — {{ $o->dersAcilim->tam_ad }}
                                </div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Başlangıç: {{ optional($o->baslangic_zamani)->format('H:i') ?? '-' }}
                                </div>
                            </div>
                            <span class="ui-badge">Devam Et</span>
                        </div>
                    </a>
                @empty
                    <div class="py-6 text-slate-600">Bugün başlatılmış oturum yok.</div>
                @endforelse
            </div>
        </div>
        {{-- Önceki Oturumlar --}}
        <div class="ui-card p-6">
            <div class="flex items-center justify-between">
                <div class="font-extrabold text-slate-900">Önceki Oturumlar</div>
                <span class="ui-badge">{{ $oncekiOturumlar->total() }}</span>
            </div>

            <div class="mt-4 divide-y divide-slate-900/5">
                @forelse($oncekiOturumlar as $o)
                    <a href="{{ route('hoca.yoklama.show', $o->id) }}" class="block py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900 truncate">
                                    {{ $o->baslik ?? 'Oturum' }} — {{ $o->dersAcilim->tam_ad }}
                                </div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Tarih: {{ optional($o->baslangic_zamani)->format('d.m.Y H:i') ?? '-' }}
                                    @if ($o->bitis_zamani)
                                        <span class="text-slate-400">•</span>
                                        Bitiş: {{ optional($o->bitis_zamani)->format('H:i') }}
                                    @endif
                                </div>
                            </div>

                            <span class="ui-badge bg-white/70">Detay</span>
                        </div>
                    </a>
                @empty
                    <div class="py-6 text-slate-600">Önceki oturum bulunamadı.</div>
                @endforelse
            </div>

            <div class="pt-4">
                {{ $oncekiOturumlar->links() }}
            </div>
        </div>

    </div>
@endsection
