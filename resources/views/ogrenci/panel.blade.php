@extends('layouts.app')
@section('baslik', 'Öğrenci Paneli')

@section('icerik')
    <div class="mx-auto space-y-6">

        {{-- Üst Özet --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Öğrenci Paneli</h1>
                    <p class="text-slate-600 mt-2">
                        Derslerini yönet, yoklama geçmişini gör. Yoklamaya katılmak için hocanın açtığı QR linkini kullan.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('ogrenci.derslerim.index') }}" class="ui-btn-primary">Derslerim</a>
                </div>
            </div>

            {{-- Mini istatistik --}}
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Kayıtlı Ders</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['ders_sayisi'] ?? '-' }}</div>
                </div>

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Toplam Yoklama</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['yoklama_toplam'] ?? '-' }}</div>
                </div>

                <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                    <div class="text-xs font-semibold text-slate-500">Şüpheli</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['supheli'] ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Hızlı İşlemler --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="ui-card p-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold text-slate-900 text-lg">Hızlı Erişim</div>
                    <span class="ui-badge">Önerilen</span>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <a href="{{ route('ogrenci.derslerim.index') }}"
                        class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-5 hover:bg-white transition block">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs font-semibold text-slate-500">Ders Yönetimi</div>
                                <div class="text-lg font-extrabold text-slate-900 mt-1">Derslerim</div>
                                <div class="text-sm text-slate-600 mt-2">
                                    Kayıtlı olduğun dersleri gör, ders detayına gir.
                                </div>
                            </div>
                            <span class="ui-badge">→</span>
                        </div>
                    </a>

                    <a href="{{ route('ogrenci.yoklamalarim') }}"
                        class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-5 hover:bg-white transition block">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs font-semibold text-slate-500">Katılım Geçmişi</div>
                                <div class="text-lg font-extrabold text-slate-900 mt-1">Yoklamalarım</div>
                                <div class="text-sm text-slate-600 mt-2">
                                    Katılım durumlarını (katıldı/şüpheli/reddedildi) takip et.
                                </div>
                            </div>
                            <span class="ui-badge">→</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Nasıl çalışır --}}
            <div class="ui-card p-6">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold text-slate-900 text-lg">Yoklama Akışı</div>
                    <span class="ui-badge">3 adım</span>
                </div>

                <ol class="mt-4 space-y-3 text-sm text-slate-700">
                    <li class="flex gap-3">
                        <span class="ui-badge">1</span>
                        <div><span class="font-semibold text-slate-900">Hoca</span> yoklama penceresini açar.</div>
                    </li>
                    <li class="flex gap-3">
                        <span class="ui-badge">2</span>
                        <div>QR’dan çıkan <span class="font-semibold text-slate-900">linke</span> girersin.</div>
                    </li>
                    <li class="flex gap-3">
                        <span class="ui-badge">3</span>
                        <div>Katılım otomatik alınır ve kaydın <span
                                class="font-semibold text-slate-900">Yoklamalarım</span>’da görünür.</div>
                    </li>
                </ol>

                <div class="mt-5 rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4 text-xs text-slate-600">
                    Not: Yoklama kapalıysa link açılır ama katılım alınmaz.
                </div>
            </div>

        </div>

    </div>
@endsection
