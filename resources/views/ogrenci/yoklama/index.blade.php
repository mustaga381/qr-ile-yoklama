@extends('layouts.app')
@section('baslik', 'Yoklamalarım')

@section('icerik')
    <div class="mx-auto space-y-6">

        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Yoklamalarım</h1>
                    <p class="text-slate-600 mt-2">Katıldığın yoklama kayıtları burada listelenir.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('ogrenci.derslerim.index') }}" class="ui-btn">Derslerim</a>
                </div>
            </div>

            {{-- Filtre --}}
            <form class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3" method="GET"
                action="{{ route('ogrenci.yoklamalarim') }}">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Arama (Ders / Oturum)</label>
                    <input type="text" name="q" value="{{ $q }}" class="ui-input mt-1"
                        placeholder="Örn: Matematik / Vize">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Durum</label>
                    <select name="durum" class="ui-select mt-1">
                        <option value="">Tümü</option>
                        <option value="katildi" @selected($durum === 'katildi')>Katıldı</option>
                        <option value="gec_kaldi" @selected($durum === 'gec_kaldi')>Geç Kaldı</option>
                        <option value="manuel" @selected($durum === 'manuel')>Manuel</option>
                        <option value="supheli" @selected($durum === 'supheli')>Şüpheli</option>
                        <option value="reddedildi" @selected($durum === 'reddedildi')>Reddedildi</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button class="ui-btn-primary w-full" type="submit">Filtrele</button>
                    <a href="{{ route('ogrenci.yoklamalarim') }}" class="ui-btn">Sıfırla</a>
                </div>
            </form>
        </div>

        <div class="ui-card p-6 overflow-auto">
            <div class="flex items-center justify-between gap-3">
                <div class="font-extrabold text-slate-900 text-lg">Kayıtlar</div>
                <span class="ui-badge">Toplam: {{ $items->total() }}</span>
            </div>

            <table class="w-full text-sm mt-4">
                <thead class="text-left text-slate-600">
                    <tr class="border-b border-slate-900/10">
                        <th class="py-3 pr-4">Ders</th>
                        <th class="py-3 pr-4">Oturum</th>
                        <th class="py-3 pr-4">Durum</th>
                        <th class="py-3 pr-4">İşaretlenme</th>
                        <th class="py-3 pr-4">IP</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($items as $y)
                        <tr class="border-b border-slate-900/5">
                            <td class="py-3 pr-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $y->oturum->dersAcilim->tam_ad ?? 'Ders Açılım #' . $y->ders_acilim_id }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    Hoca: {{ $y->oturum->dersAcilim->hoca->ad_soyad ?? '-' }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="font-semibold text-slate-900">
                                    {{ $y->oturum_baslik ?? 'Oturum #' . $y->ders_oturum_id }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ \Carbon\Carbon::parse($y->oturum_tarihi)->format('d.m.Y') }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                <span class="ui-badge ring-1 {{ yoklamaBadgeClass($y->durum) }}">
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
                                Henüz yoklama kaydın yok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>

    </div>
@endsection
