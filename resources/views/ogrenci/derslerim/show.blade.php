@extends('layouts.app')
@section('baslik', 'Ders Detayı')

@section('icerik')
    <div class="mx-auto space-y-6">

        {{-- Üst bar --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 truncate">
                            {{ $dersKayit->acilim->tam_ad ?? ($dersKayit->acilim->ders->ad ?? 'Ders Detayı') }}
                        </h1>

                        <div class="flex items-center gap-2">
                            <span class="ui-badge">Kayıtlı</span>
                            <span class="ui-badge">Kayıt #{{ $dersKayit->id }}</span>
                        </div>
                    </div>

                    <p class="text-slate-600 mt-2">
                        Ders açılım bilgileri, hoca bilgisi ve yoklama akışı için hızlı erişim.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('ogrenci.derslerim.index') }}" class="ui-btn">← Geri</a>
                </div>
            </div>

            {{-- mini özet --}}
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="flex items-start gap-3">
                        <div
                            class="h-10 w-10 rounded-2xl bg-white ring-1 ring-slate-900/10 flex items-center justify-center text-slate-700">
                            @svg('heroicon-o-academic-cap', 'h-5 w-5')
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-slate-500">Ders</div>
                            <div class="mt-1 text-lg font-extrabold text-slate-900 truncate">
                                {{ $dersKayit->acilim->ders->ad ?? ($dersKayit->acilim->tam_ad ?? '-') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="flex items-start gap-3">
                        <div
                            class="h-10 w-10 rounded-2xl bg-white ring-1 ring-slate-900/10 flex items-center justify-center text-slate-700">
                            @svg('heroicon-o-user', 'h-5 w-5')
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-slate-500">Hoca</div>
                            <div class="mt-1 text-lg font-extrabold text-slate-900 truncate">
                                {{ $dersKayit->acilim->hoca->ad_soyad ?? '-' }}
                            </div>
                            <div class="mt-1 text-sm text-slate-600 truncate">
                                {{ $dersKayit->acilim->hoca->eposta ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="ui-card p-6">
            <div class="flex items-center justify-between gap-3">
                <div class="font-extrabold text-slate-900 text-lg">Bu Dersteki Yoklamalarım</div>
                <span class="ui-badge">Toplam: {{ $yoklamalar->total() }}</span>
            </div>

            <div class="mt-4 overflow-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-slate-600">
                        <tr class="border-b border-slate-900/10">
                            <th class="py-3 pr-4">Oturum</th>
                            <th class="py-3 pr-4">Tarih</th>
                            <th class="py-3 pr-4">Durum</th>
                            <th class="py-3 pr-4">İşaretlenme</th>
                            <th class="py-3 pr-4">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($yoklamalar as $y)
                            <tr class="border-b border-slate-900/5">
                                <td class="py-3 pr-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $y?->oturum?->baslik ?? 'Oturum #' . $y->ders_oturum_id }}
                                    </div>
                                    <div class="text-xs text-slate-500">Oturum #{{ $y->ders_oturum_id }}</div>
                                </td>

                                <td class="py-3 pr-4 text-slate-700">
                                    {{ \Carbon\Carbon::parse($y->oturum_tarihi)->format('d.m.Y') }}
                                </td>

                                <td class="py-3 pr-4">
                                    <span class="ui-badge">
                                        {{ $y->durum }}
                                    </span>
                                </td>

                                <td class="py-3 pr-4 text-slate-700">
                                    {{ \Carbon\Carbon::parse($y->isaretlenme_zamani)->format('d.m.Y H:i') }}
                                </td>

                                <td class="py-3 pr-4 text-slate-700">
                                    {{ $y->ip_adresi ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-600">
                                    Bu derste henüz yoklama kaydın yok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $yoklamalar->links() }}
            </div>
        </div>

    </div>
@endsection
