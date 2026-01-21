@extends('layouts.app')

@section('baslik', 'Yoklama')

@section('icerik')
    <div class="max-w-2xl mx-auto px-4 py-6" x-data="autoYoklama()">

        <div class="ui-card p-6 md:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold text-slate-900">Yoklama</h1>
                    <p class="text-slate-600 mt-1">
                        QR linkinden geldiysen otomatik olarak yoklamaya katılacaksın.
                    </p>
                </div>
                <span class="ui-badge" x-text="badgeText"></span>
            </div>

            {{-- Durum alanı --}}
            <div class="mt-6">
                <template x-if="loading">
                    <div class="rounded-2xl bg-white/70 ring-1 ring-slate-900/10 p-5">
                        <div class="font-semibold text-slate-900">Kontrol ediliyor…</div>
                        <div class="text-slate-600 mt-1">Lütfen bekle.</div>
                    </div>
                </template>

                <template x-if="!loading">
                    <div class="rounded-2xl bg-white/70 ring-1 ring-slate-900/10 p-5">
                        <div class="text-lg font-extrabold" :class="statusClass" x-text="title"></div>
                        <div class="text-slate-700 mt-2" x-text="message"></div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="rounded-2xl bg-white/60 ring-1 ring-slate-900/10 p-4">
                                <div class="text-slate-500 font-semibold">Cihaz</div>
                                <div class="mt-1 font-mono text-slate-800 break-all" x-text="cihaz_uuid"></div>
                            </div>
                            <div class="rounded-2xl bg-white/60 ring-1 ring-slate-900/10 p-4">
                                <div class="text-slate-500 font-semibold">Platform</div>
                                <div class="mt-1 text-slate-800" x-text="platform"></div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col sm:flex-row gap-2">
                            <a class="ui-btn w-full sm:w-auto" href="{{ route('ogrenci.yoklama.scan') }}">
                                Kamerayla QR Okut
                            </a>
                            <button class="ui-btn-primary w-full sm:w-auto" type="button" @click="retry()">
                                Tekrar Dene
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            {{-- data yoksa uyarı --}}
            <div class="mt-5" x-show="!data">
                <div class="rounded-2xl bg-white/70 ring-1 ring-amber-300/60 p-5">
                    <div class="font-extrabold text-slate-900">QR verisi yok</div>
                    <p class="text-slate-700 mt-1">
                        Hocanın QR kodunu okutmak için “Kamerayla QR Okut” ekranını kullan.
                    </p>
                </div>
            </div>

        </div>

    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function autoYoklama() {
            return {
                data: @json(request('data', '')),
                loading: true,
                title: '',
                message: '',
                status: 'idle', // ok | supheli | error | idle
                cihaz_uuid: '',
                platform: '',

                get badgeText() {
                    if (this.loading) return 'İşleniyor';
                    if (this.status === 'ok') return 'Başarılı';
                    if (this.status === 'supheli') return 'Şüpheli';
                    if (this.status === 'error') return 'Hata';
                    return 'Beklemede';
                },

                get statusClass() {
                    if (this.status === 'ok') return 'text-emerald-600';
                    if (this.status === 'supheli') return 'text-amber-500';
                    if (this.status === 'error') return 'text-rose-600';
                    return 'text-slate-900';
                },

                init() {
                    this.platform = navigator.platform || 'web';
                    this.cihaz_uuid = this.getDeviceUuid();

                    if (this.data) {
                        this.send();
                    } else {
                        this.loading = false;
                        this.status = 'idle';
                        this.title = 'QR bekleniyor';
                        this.message = 'Hocanın QR linkinden gelmedin. Kamerayla okutabilirsin.';
                    }
                },

                async send() {
                    this.loading = true;

                    try {
                        const res = await fetch(
                            '{{ route('api.ogrenci.yoklama.katil') ?? '/api/ogrenci/yoklama/katil' }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    data: this.data,
                                    cihaz_uuid: this.cihaz_uuid,
                                    platform: this.platform
                                })
                            });

                        const json = await res.json().catch(() => ({}));
                        this.loading = false;

                        if (res.ok) {
                            const d = json.durum || 'katildi';
                            this.status = (d === 'supheli') ? 'supheli' : 'ok';
                            this.title = (this.status === 'supheli') ? 'Şüpheli kayıt' : 'Yoklama alındı';
                            this.message = json.message || 'İşlem tamamlandı.';
                        } else {
                            this.status = 'error';
                            this.title = 'Katılım alınamadı';
                            this.message = json.message || 'Yoklama kapalı olabilir.';
                        }
                    } catch (e) {
                        this.loading = false;
                        this.status = 'error';
                        this.title = 'Bağlantı hatası';
                        this.message = 'Sunucuya ulaşılamadı.';
                    }
                },

                retry() {
                    if (!this.data) {
                        this.status = 'error';
                        this.title = 'QR verisi yok';
                        this.message = 'Kamerayla QR okut ekranına geç.';
                        return;
                    }
                    this.send();
                },

                getDeviceUuid() {
                    const KEY = 'yk_device_uuid_v1';
                    let v = localStorage.getItem(KEY);
                    if (!v) {
                        v = (crypto?.randomUUID ? crypto.randomUUID() : (Date.now().toString(16) + Math.random().toString(
                            16).slice(2)));
                        v = String(v).replace(/-/g, '');
                        localStorage.setItem(KEY, v);
                    }
                    return v.slice(0, 64);
                }
            }
        }
    </script>
@endsection
