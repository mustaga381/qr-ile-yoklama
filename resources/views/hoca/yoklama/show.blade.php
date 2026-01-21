@extends('layouts.app')
@section('baslik', 'Oturum • Yoklama')

@section('icerik')
    <div class="mx-auto px-4 py-6 space-y-6" x-data="hocaYoklamaPage()" x-init="init()">

        @if (session('toast'))
            <div class="ui-card p-4">
                <div class="font-semibold text-slate-900">{{ session('toast') }}</div>
            </div>
        @endif

        {{-- Header --}}
        <div class="ui-card p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-2xl font-extrabold text-slate-900">
                            {{ $oturum->baslik ?? 'Ders Oturumu' }}
                        </h1>
                        <span class="ui-badge">Oturum #{{ $oturum->id }}</span>
                        <span class="ui-badge">Ders Açılım #{{ $oturum->ders_acilim_id }}</span>
                    </div>

                    <div class="mt-2 text-slate-600">
                        Tarih: <span
                            class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($oturum->oturum_tarihi)->format('d.m.Y') }}</span>
                        <span class="mx-2">•</span>
                        Başlangıç: <span
                            class="font-semibold text-slate-800">{{ optional($oturum->baslangic_zamani)->format('H:i') ?? '-' }}</span>
                        <span class="mx-2">•</span>
                        Bitiş: <span
                            class="font-semibold text-slate-800">{{ optional($oturum->bitis_zamani)->format('H:i') ?? '-' }}</span>
                    </div>

                    <div class="mt-1 text-sm text-slate-500">
                        Bu ekranda yoklama penceresi açıp QR’ı gösterebilirsin. Katılımlar otomatik akar.
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('hoca.yoklama.start') }}" class="ui-btn">← Ders Seç</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Sol: Yoklama penceresi yönetimi + QR --}}
            <div class="ui-card p-6 lg:col-span-1">
                <div class="flex items-center justify-between gap-3">
                    <div class="font-extrabold text-slate-900 text-lg">Yoklama</div>

                    @if ($aktifPencere)
                        <span class="ui-badge">
                            Açık • {{ \Carbon\Carbon::parse($aktifPencere->bitis_zamani)->format('H:i') }}’e kadar
                        </span>
                    @else
                        <span class="ui-badge">Kapalı</span>
                    @endif
                </div>

                {{-- Pencere aç formu (sadece kapalıyken) --}}
                @if (!$aktifPencere)
                    <form method="POST" action="{{ route('hoca.yoklama.pencereAc', $oturum->id) }}" class="mt-4 space-y-3">
                        @csrf

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Süre (dk)</label>
                                <input type="number" name="sure_dakika" min="1" max="60" value="1"
                                    class="ui-input mt-1">
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-slate-700">Yenileme (sn)</label>
                                <select name="yenileme_saniye" class="ui-select mt-1">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                        </div>

                        <button class="ui-btn-primary w-full" type="submit">
                            Yoklama Başlat
                        </button>
                    </form>
                @else
                    {{-- Pencere kapat (sadece açıkken) --}}
                    <form method="POST" action="{{ route('hoca.yoklama.pencereKapat', $aktifPencere->id) }}"
                        class="mt-4">
                        @csrf
                        <button class="ui-btn w-full" type="submit">Yoklamayı Kapat</button>
                    </form>
                @endif

                {{-- QR alanı --}}
                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <div class="font-semibold text-slate-900">Canlı QR</div>
                        <span class="ui-badge" x-text="qrStateText"></span>
                    </div>

                    <div class="mt-3 rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4 flex flex-col items-center">
                        <div id="qrBox" class="bg-white rounded-2xl p-3 ring-1 ring-slate-900/10"></div>

                        <div class="mt-3 w-full text-sm">
                            <div class="flex items-center justify-between text-slate-700">
                                <span>Adım</span>
                                <span class="font-bold" x-text="qr?.adim_no ?? '-'"></span>
                            </div>
                            <div class="flex items-center justify-between text-slate-700 mt-1">
                                <span>Kalan</span>
                                <span class="font-bold" x-text="(qr?.step_kalan_saniye ?? '-') + ' sn'"></span>
                            </div>
                            <div class="flex items-center justify-between text-slate-700 mt-1">
                                <span>Bitiş</span>
                                <span class="font-bold" x-text="qrBitisText"></span>
                            </div>
                        </div>

                        <div class="mt-3 w-full">
                            <a class="ui-btn w-full text-center"
                                :class="ogrenciLink === '#' ? 'opacity-50 pointer-events-none' : ''"
                                :href="ogrenciLink" target="_blank" rel="noopener">
                                Öğrenci Linki
                            </a>
                        </div>

                        <div class="mt-2 w-full text-xs text-slate-500 break-all">
                            <span class="font-semibold">Payload:</span>
                            <span x-text="qr?.payload ?? ''"></span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Sağ: Katılımlar --}}
            <div class="ui-card p-6 lg:col-span-2">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <div class="font-extrabold text-slate-900 text-lg">Katılımlar</div>
                        <div class="text-slate-600 text-sm mt-1">Liste otomatik yenilenir.</div>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" class="ui-btn" @click="fetchKatilimlar()">Yenile</button>
                    </div>
                </div>

                {{-- Özet --}}
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-2">
                    <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                        <div class="text-xs font-semibold text-slate-500">Toplam</div>
                        <div class="text-xl font-extrabold text-slate-900" x-text="stats.toplam"></div>
                    </div>
                    <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                        <div class="text-xs font-semibold text-slate-500">Katıldı</div>
                        <div class="text-xl font-extrabold text-slate-900" x-text="stats.katildi"></div>
                    </div>
                    <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                        <div class="text-xs font-semibold text-slate-500">Şüpheli</div>
                        <div class="text-xl font-extrabold text-slate-900" x-text="stats.supheli"></div>
                    </div>
                    <div class="rounded-3xl bg-white/70 ring-1 ring-slate-900/10 p-4">
                        <div class="text-xs font-semibold text-slate-500">Reddedildi</div>
                        <div class="text-xl font-extrabold text-slate-900" x-text="stats.reddedildi"></div>
                    </div>
                </div>

                {{-- Tablo --}}
                <div class="mt-4 overflow-auto rounded-3xl ring-1 ring-slate-900/10 bg-white/60">
                    <table class="w-full text-sm">
                        <thead class="text-left text-slate-600">
                            <tr class="border-b border-slate-900/10">
                                <th class="py-3 px-4">Öğrenci</th>
                                <th class="py-3 px-4">Durum</th>
                                <th class="py-3 px-4">Zaman</th>
                                <th class="py-3 px-4">IP</th>
                                <th class="py-3 px-4">Cihaz</th>
                                <th class="py-3 px-4">Adım</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="row in items" :key="row.id">
                                <tr class="border-b border-slate-900/5 align-top">
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-slate-900"
                                            x-text="row.ogrenci.ad || ('#'+row.ogrenci.id)"></div>
                                        <div class="text-xs text-slate-500" x-text="row.ogrenci.email || ''"></div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="ui-badge" :class="badgeClass(row.durum)" x-text="row.durum"></span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700" x-text="row.zaman"></td>
                                    <td class="py-3 px-4 text-slate-700" x-text="row.ip || '-'"></td>
                                    <td class="py-3 px-4 text-slate-700">
                                        <div class="text-xs" x-text="row.cihaz?.platform || '-'"></div>
                                        <div class="text-xs text-slate-500 font-mono"
                                            x-text="row.cihaz?.uuid ? row.cihaz.uuid.slice(0,16)+'…' : ''"></div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700" x-text="row.qr_adim_no ?? '-'"></td>
                                </tr>
                            </template>

                            <tr x-show="items.length === 0">
                                <td colspan="6" class="py-8 text-center text-slate-600">Henüz katılım yok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        function hocaYoklamaPage() {
            return {
                oturumId: @json($oturum->id),
                pencereId: @json($aktifPencere?->id),
                qr: null,

                // Blade -> JS: mutlaka array olsun
                items: @json($katilimItems),
                stats: {
                    toplam: 0,
                    katildi: 0,
                    supheli: 0,
                    reddedildi: 0
                },

                qrObj: null,
                qrTimer: null,
                listTimer: null,

                get pencereBadge() {
                    return this.pencereId ? 'Açık' : 'Kapalı';
                },

                get qrStateText() {
                    if (!this.pencereId) return 'Pencere yok';
                    if (!this.qr || !this.qr.payload) return 'QR bekleniyor';
                    return 'Canlı';
                },

                get qrBitisText() {
                    const b = this.qr?.bitis_zamani;
                    if (!b) return '-';
                    try {
                        const d = new Date(b);
                        return d.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } catch {
                        return '-';
                    }
                },

                get ogrenciLink() {
                    return this.qr?.ogrenci_url || '#';
                },

                init() {
                    this.computeStats();
                    this.initQR();

                    if (this.pencereId) {
                        this.startLoops();
                    } else {
                        this.renderClosedQR();
                    }

                    // sayfa kapanırken timer temizle (SPA değil ama garanti olsun)
                    window.addEventListener('beforeunload', () => this.stopLoops());
                },

                startLoops() {
                    this.stopLoops();
                    this.tickQR(true);
                    this.tickKatilimlar(true);
                },

                stopLoops() {
                    if (this.qrTimer) clearTimeout(this.qrTimer);
                    if (this.listTimer) clearTimeout(this.listTimer);
                    this.qrTimer = null;
                    this.listTimer = null;
                },

                initQR() {
                    const el = document.getElementById('qrBox');
                    if (!el) return;

                    el.innerHTML = '';

                    // QRCode lib yüklenmediyse patlamasın
                    if (typeof QRCode === 'undefined') {
                        el.innerHTML = `<div class="text-sm text-slate-600 p-4 text-center">
          QR kütüphanesi yüklenemedi.
        </div>`;
                        return;
                    }

                    this.qrObj = new QRCode(el, {
                        text: '...',
                        width: 240,
                        height: 240
                    });
                },

                renderClosedQR() {
                    this.qr = null;
                    if (this.qrObj && typeof this.qrObj.makeCode === 'function') {
                        this.qrObj.makeCode('Yoklama kapalı');
                    }
                },

                async tickQR(immediate = false) {
                    if (!this.pencereId) return;

                    // ilk çağrı hariç bekleme zaten timer ile
                    if (!immediate) {
                        // no-op
                    }

                    try {
                        const res = await fetch(`/hoca/yoklama/pencere/${this.pencereId}/qr`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        // 409 = kapalı / süre doldu (bizim controller böyle dönüyordu)
                        if (res.status === 409 || !res.ok) {
                            this.stopLoops();
                            this.pencereId = null;
                            this.renderClosedQR();
                            return;
                        }

                        const data = await res.json();
                        this.qr = data;

                        if (this.qrObj && data.payload) {
                            this.qrObj.makeCode(data.ogrenci_url || data.payload);
                        }

                        const stepKalan = Number.isFinite(data.step_kalan_saniye) ? data.step_kalan_saniye : 1;
                        const waitMs = Math.max(800, stepKalan * 1000);

                        this.qrTimer = setTimeout(() => this.tickQR(), waitMs);
                    } catch (e) {
                        this.qrTimer = setTimeout(() => this.tickQR(), 1500);
                    }
                },

                async tickKatilimlar(immediate = false) {
                    if (!immediate) {
                        // timer üzerinden dönecek
                    }

                    await this.fetchKatilimlar();
                    this.listTimer = setTimeout(() => this.tickKatilimlar(), 2000);
                },

                async fetchKatilimlar() {
                    try {
                        const res = await fetch(`/hoca/yoklama/oturum/${this.oturumId}/katilimlar`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (!res.ok) return;

                        const json = await res.json();
                        this.items = Array.isArray(json.items) ? json.items : [];
                        this.computeStats();
                    } catch (e) {
                        // sessiz geç
                    }
                },

                computeStats() {
                    const s = {
                        toplam: this.items.length,
                        katildi: 0,
                        supheli: 0,
                        reddedildi: 0
                    };
                    for (const i of this.items) {
                        if (i?.durum === 'katildi' || i?.durum === 'manuel' || i?.durum === 'gec_kaldi') s.katildi++;
                        if (i?.durum === 'supheli') s.supheli++;
                        if (i?.durum === 'reddedildi') s.reddedildi++;
                    }
                    this.stats = s;
                },

                badgeClass(d) {
                    // ui-badge üzerine ek class
                    if (d === 'katildi') return 'bg-emerald-50 text-emerald-700 ring-emerald-200/70';
                    if (d === 'supheli') return 'bg-amber-50 text-amber-700 ring-amber-200/70';
                    if (d === 'reddedildi') return 'bg-rose-50 text-rose-700 ring-rose-200/70';
                    if (d === 'gec_kaldi') return 'bg-indigo-50 text-indigo-700 ring-indigo-200/70';
                    return '';
                }
            };
        }
    </script>

@endsection
