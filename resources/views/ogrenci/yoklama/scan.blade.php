@extends('layouts.app')

@section('baslik', 'QR Okut')

@section('icerik')
    <div class="max-w-2xl mx-auto px-4 py-6" x-data="scanPage()">

        <div class="ui-card p-6 md:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-extrabold text-slate-900">Kamerayla QR Okut</h1>
                    <p class="text-slate-600 mt-1">
                        Hocanın ekrandaki QR kodunu okut. Okuyunca otomatik yoklamaya katılır.
                    </p>
                </div>
                <a class="ui-btn" href="{{ route('ogrenci.yoklama.form') }}">Geri</a>
            </div>

            <div class="mt-6 rounded-3xl overflow-hidden ring-1 ring-slate-900/10 bg-black/5">
                <div id="reader" class="w-full"></div>
            </div>

            <div class="mt-5 rounded-2xl bg-white/70 ring-1 ring-slate-900/10 p-5">
                <div class="font-semibold text-slate-900">Durum</div>
                <div class="text-slate-700 mt-1" x-text="statusText"></div>

                <div class="mt-4 flex flex-col sm:flex-row gap-2">
                    <button class="ui-btn-primary w-full sm:w-auto" type="button" @click="start()">
                        Kamerayı Başlat
                    </button>
                    <button class="ui-btn w-full sm:w-auto" type="button" @click="stop()">
                        Durdur
                    </button>
                </div>
            </div>

        </div>

    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script>

    <script>
        function scanPage() {
            return {
                statusText: 'Kamerayı başlat.',
                reader: null,

                init() {
                    this.reader = new Html5Qrcode("reader");
                },

                async start() {
                    this.statusText = 'Kamera açılıyor…';
                    try {
                        await this.reader.start({
                                facingMode: "environment"
                            }, {
                                fps: 10,
                                qrbox: {
                                    width: 260,
                                    height: 260
                                }
                            },
                            (decodedText) => {
                                // decodedText = payload (p:..|o:..|a:..|s:..)
                                this.statusText = 'QR okundu. Yönlendiriliyorsun…';
                                const url = new URL('{{ route('ogrenci.yoklama.form') }}', window.location.origin);
                                url.searchParams.set('data', decodedText);
                                window.location.href = url.toString();
                            },
                            () => {}
                        );
                        this.statusText = 'QR bekleniyor…';
                    } catch (e) {
                        this.statusText = 'Kamera açılamadı. İzin verildiğinden emin ol.';
                    }
                },

                async stop() {
                    try {
                        await this.reader.stop();
                        this.statusText = 'Durduruldu.';
                    } catch (e) {
                        this.statusText = 'Zaten duruyor.';
                    }
                }
            }
        }
    </script>
@endsection
