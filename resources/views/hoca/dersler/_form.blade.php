@php
    $ders = $ders ?? null;
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
            <div class="text-xs font-semibold tracking-wider uppercase text-slate-500">Ders Tanımı</div>
            <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">
                {{ $ders?->id ? 'Dersi Düzenle' : 'Yeni Ders Oluştur' }}
            </h2>
            <p class="mt-1 text-sm text-slate-600">Kod, isim ve isteğe bağlı açıklama bilgilerini gir.</p>
        </div>

        <div
            class="h-11 w-11 rounded-2xl grid place-items-center text-white shadow-sm
                    bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
            <x-heroicon-o-book-open class="h-6 w-6" />
        </div>
    </div>

    <div class="mt-6 space-y-4">
        <div>
            <label class="text-sm font-semibold text-slate-800">Ders Kodu</label>
            <input name="ders_kodu" value="{{ old('ders_kodu', $ders?->ders_kodu) }}" class="ui-input mt-1"
                placeholder="Örn: CSE101" required>
            <p class="mt-1 text-xs text-slate-500">Kısa ve benzersiz bir kod kullan.</p>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-800">Ders Adı</label>
            <input name="ders_adi" value="{{ old('ders_adi', $ders?->ders_adi) }}" class="ui-input mt-1"
                placeholder="Örn: Programlamaya Giriş" required>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-800">Açıklama</label>
            <textarea name="aciklama" rows="4" class="ui-input mt-1 resize-none" placeholder="İsteğe bağlı...">{{ old('aciklama', $ders?->aciklama) }}</textarea>
            <p class="mt-1 text-xs text-slate-500">Öğrencilerin göreceği kısa açıklama (opsiyonel).</p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-2">
            <button class="ui-btn-primary sm:w-auto w-full">
                <x-heroicon-o-check class="h-5 w-5" />
                Kaydet
            </button>

            <a href="{{ route('hoca.dersler.index') }}" class="ui-btn sm:w-auto w-full">
                <x-heroicon-o-x-mark class="h-5 w-5 text-slate-700" />
                İptal
            </a>
        </div>
    </div>
</div>
