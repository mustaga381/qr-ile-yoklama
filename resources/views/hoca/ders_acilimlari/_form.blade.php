@php
    $acilim = $dersAcilim ?? null;
@endphp

@if ($errors->any())
    <div class="ui-card border-red-200 bg-red-50/80 p-4 text-sm text-red-700">
        <div class="font-semibold mb-1">Bir şeyler ters gitti</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="ui-card p-6">
    <div class="flex items-start justify-between gap-3">
        <div>
            <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Ders Açılışı</div>
            <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">
                {{ $acilim?->id ? 'Açılışı Düzenle' : 'Yeni Açılış Oluştur' }}
            </h2>
            <p class="mt-1 text-sm text-slate-600">Akademik yıl, dönem ve şube bilgisini seç.</p>
        </div>

        <div
            class="h-11 w-11 rounded-2xl grid place-items-center text-white shadow-sm
                    bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
            <x-heroicon-o-academic-cap class="h-6 w-6" />
        </div>
    </div>

    <div class="mt-6 space-y-4">
        <div>
            <label class="text-sm font-semibold text-slate-800">Ders</label>
            <select name="ders_id" class="ui-input mt-1" required>
                <option value="">Seçiniz</option>
                @foreach ($dersler as $d)
                    @php
                        $selected = old('ders_id', $acilim?->ders_id ?? ($seciliDersId ?? null)) == $d->id;
                    @endphp
                    <option value="{{ $d->id }}" @selected($selected)>
                        {{ $d->ders_kodu }} — {{ $d->ders_adi }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">Bu açılış hangi ders için geçerli?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-sm font-semibold text-slate-800">Akademik Yıl</label>
                <select name="akademik_yil" class="ui-input mt-1" required>
                    @foreach ($akademikYillar as $y)
                        <option value="{{ $y }}" @selected(old('akademik_yil', $acilim?->akademik_yil) === $y)>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-800">Dönem</label>
                <select name="donem" class="ui-input mt-1" required>
                    <option value="guz" @selected(old('donem', $acilim?->donem) === 'guz')>Güz</option>
                    <option value="bahar" @selected(old('donem', $acilim?->donem) === 'bahar')>Bahar</option>
                    <option value="yaz" @selected(old('donem', $acilim?->donem) === 'yaz')>Yaz</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-sm font-semibold text-slate-800">Şube</label>
                <input name="sube" value="{{ old('sube', $acilim?->sube ?? '1') }}" class="ui-input mt-1"
                    placeholder="Örn: 1" required>
                <p class="mt-1 text-xs text-slate-500">Aynı dersin farklı şubeleri için ayrı açılış oluştur.</p>
            </div>

            <div class="flex items-center md:justify-end">
                <label
                    class="mt-6 md:mt-0 inline-flex items-center gap-2 text-sm font-semibold text-slate-800
                              rounded-2xl px-3 py-3 bg-white/70 ring-1 ring-slate-900/10 w-full md:w-auto justify-between">
                    <span class="flex items-center gap-2">
                        <x-heroicon-o-bolt class="h-5 w-5 text-slate-700" />
                        Aktif
                    </span>

                    <input type="checkbox" name="aktif_mi" value="1" class="h-5 w-5 rounded border-slate-300"
                        @checked(old('aktif_mi', $acilim?->aktif_mi ?? true))>
                </label>
            </div>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-2">
            <button class="ui-btn-primary sm:w-auto w-full">
                <x-heroicon-o-check class="h-5 w-5" />
                Kaydet
            </button>

            <a href="{{ route('hoca.ders_acilimlari.index') }}" class="ui-btn sm:w-auto w-full">
                <x-heroicon-o-x-mark class="h-5 w-5 text-slate-700" />
                İptal
            </a>
        </div>
    </div>
</div>
