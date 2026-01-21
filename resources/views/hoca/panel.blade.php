@extends('layouts.app')
@section('baslik', 'Hoca Paneli')

@section('icerik')
    <div class="mx-auto space-y-6">

        {{-- Üst Özet --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Hoca Paneli</h1>
                    <p class="text-slate-600 mt-2">
                        Yoklama başlat, aktif yoklamaları izle, katılımları ve şüpheli kayıtları kontrol et.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('hoca.yoklama.start') }}" class="ui-btn-primary">Yoklama Yönetimi</a>
                    <a href="{{ route('hoca.yoklama.start') }}" class="ui-btn">Oturumlar</a>
                </div>
            </div>

            {{-- Mini istatistik --}}
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Ders Açılımı</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['ders_sayisi'] ?? '-' }}</div>
                </div>

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Toplam Oturum</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['oturum_sayisi'] ?? '-' }}</div>
                </div>

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Aktif Yoklama</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['aktif_pencere'] ?? '-' }}</div>
                </div>

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Şüpheli</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['supheli_sayi'] ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Sol: Aktif yoklamalar --}}
            <div class="ui-card p-6 lg:col-span-2">
                <div class="flex items-center justify-between gap-3">
                    <div class="font-extrabold text-slate-900 text-lg">Aktif Yoklamalar</div>
                    <a href="{{ route('hoca.yoklama.start') }}" class="ui-btn">Tümünü Gör</a>
                </div>

                <div class="mt-4 divide-y divide-slate-900/5">
                    @forelse($aktifPencereler as $p)
                        <div class="py-4 flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900 truncate">
                                    {{ $p->oturum->dersAcilim->tam_ad ?? 'Oturum #' . $p->ders_oturum_id }}
                                </div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Pencere: <span class="font-semibold text-slate-800">#{{ $p->id }}</span> ·
                                    Bitiş: <span
                                        class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($p->bitis_zamani)->format('H:i') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="ui-badge">Açık</span>
                                <a href="{{ route('hoca.yoklama.show', $p->ders_oturum_id) }}" class="ui-btn">İzle</a>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-600">
                            Şu an aktif yoklama yok. Yoklama yönetiminden bir oturum başlatabilirsin.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Sağ: Hızlı aksiyon --}}
            <div class="ui-card p-6">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold text-slate-900 text-lg">Hızlı İşlem</div>
                    <span class="ui-badge">Önerilen</span>
                </div>

                <div class="mt-4 space-y-3">
                    <a href="{{ route('hoca.yoklama.start') }}"
                        class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-5 hover:bg-white transition block">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs font-semibold text-slate-500">Yoklama</div>
                                <div class="text-lg font-extrabold text-slate-900 mt-1">Yoklama Başlat</div>
                                <div class="text-sm text-slate-600 mt-2">
                                    Ders açılımını seç, oturum oluştur ve pencereyi aç.
                                </div>
                            </div>
                            <span class="ui-badge">→</span>
                        </div>
                    </a>

                    <a href="{{ route('hoca.yoklama.start') }}"
                        class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-5 hover:bg-white transition block">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs font-semibold text-slate-500">Kontrol</div>
                                <div class="text-lg font-extrabold text-slate-900 mt-1">Şüpheli Kayıtlar</div>
                                <div class="text-sm text-slate-600 mt-2">
                                    Şüpheli yoklamaları incele ve gerekirse müdahale et.
                                </div>
                            </div>
                            <span class="ui-badge">→</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        {{-- Son katılımlar --}}
        <div class="ui-card p-6 overflow-auto">
            <div class="flex items-center justify-between gap-3">
                <div class="font-extrabold text-slate-900 text-lg">Son Katılımlar</div>
                <span class="ui-badge">Son {{ $sonYoklamalar->count() }}</span>
            </div>

            <table class="w-full text-sm mt-4">
                <thead class="text-left text-slate-600">
                    <tr class="border-b border-slate-900/10">
                        <th class="py-3 pr-4">Öğrenci</th>
                        <th class="py-3 pr-4">Ders</th>
                        <th class="py-3 pr-4">Durum</th>
                        <th class="py-3 pr-4">Zaman</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sonYoklamalar as $y)
                        <tr class="border-b border-slate-900/5">
                            <td class="py-3 pr-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $y->ogrenci->ad_soyad ?? 'Öğrenci #' . $y->ogrenci_id }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $y->ogrenci->eposta ?? '-' }}</div>
                            </td>
                            <td class="py-3 pr-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $y->oturum->dersAcilim->tam_ad ?? 'Oturum #' . $y->ders_oturum_id }}
                                </div>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="ui-badge">{{ $y->durum }}</span>
                            </td>
                            <td class="py-3 pr-4 text-slate-700">
                                {{ \Carbon\Carbon::parse($y->isaretlenme_zamani)->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-slate-600">Henüz katılım yok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
