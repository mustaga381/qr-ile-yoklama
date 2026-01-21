@extends('layouts.app')
@section('baslik', 'Derslerim')

@section('icerik')
    <div class="mx-auto space-y-6">

        {{-- Header --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Derslerim</h1>
                        <span class="ui-badge">Toplam: {{ count($dersler) }}</span>
                    </div>
                    <p class="text-slate-600 mt-2">
                        Kayıtlı olduğun dersleri buradan görebilir, hoca bilgilerine hızlıca ulaşabilirsin.
                    </p>
                </div>
            </div>

            {{-- mini özet --}}
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Kayıtlı Ders</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ count($dersler) }}</div>
                </div>
                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Durum</div>
                    <div class="text-lg font-extrabold text-slate-900">Aktif</div>
                    <div class="text-xs text-slate-500 mt-1">Derslerin listeleniyor</div>
                </div>
                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">İpucu</div>
                    <div class="text-sm text-slate-700 mt-1">
                        Yoklamaya girmek için hocanın açtığı QR linkini kullan.
                    </div>
                </div>
            </div>
        </div>

        {{-- Ders kartları --}}
        <div class="ui-card p-6">
            <div class="flex items-center justify-between gap-3">
                <div class="font-extrabold text-slate-900 text-lg">Kayıtlı Dersler</div>
                <span class="ui-badge">Güncel Liste</span>
            </div>

            @if (count($dersler) === 0)
                <div class="py-12 text-center">
                    <div class="text-xl font-extrabold text-slate-900">Kayıtlı ders bulunamadı</div>
                    <div class="text-slate-600 mt-2">Ders kaydın yapıldığında burada görünecek.</div>
                </div>
            @else
                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach ($dersler as $d)
                        <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-5 hover:bg-white transition">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm text-slate-500">Ders</div>
                                    <div class="font-extrabold text-slate-900 text-lg leading-snug truncate">
                                        {{ $d->acilim->tam_ad ?? 'Ders Açılım #' . $d->id }}
                                    </div>
                                </div>
                                <span class="ui-badge">Kayıtlı</span>
                            </div>

                            <div class="mt-4 flex items-start gap-3">
                                <div
                                    class="h-10 w-10 rounded-2xl bg-white ring-1 ring-slate-900/10 flex items-center justify-center">
                                    {{-- basit ikon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 2a6 6 0 100 12 6 6 0 000-12zM2 18a8 8 0 0116 0H2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-slate-500">Hoca</div>
                                    <div class="font-semibold text-slate-900 truncate">
                                        {{ $d->acilim->hoca->ad_soyad ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        Açılım ID: <span
                                            class="font-semibold text-slate-700">#{{ $d->acilim_id ?? $d->id }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="ui-badge">QR ile yoklama</span>
                                <span class="ui-badge">Canlı katılım</span>
                                <span class="ui-badge">Şüphe kontrolü</span>
                            </div>

                            <div class="mt-5 flex gap-2">
                                {{-- ileride ders detay istersen burayı bağlarız --}}
                                <a href="{{ route('ogrenci.derslerim.show', ['ders_kayit' => $d->id]) }}"
                                    class="ui-btn w-full" title="Ders detayı yakında">
                                    Detay
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@endsection
