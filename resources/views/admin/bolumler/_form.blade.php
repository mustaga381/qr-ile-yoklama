@php $bolum = $bolum ?? null; @endphp

@if ($errors->any())
    <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mt-6 space-y-4">
    <div>
        <label class="text-xs font-semibold text-slate-600">Bölüm Adı</label>
        <input name="bolum_adi" value="{{ old('bolum_adi', $bolum?->bolum_adi) }}" class="ui-input mt-1"
            placeholder="Örn: Bilgisayar Mühendisliği" required>
    </div>

    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
        <input type="checkbox" name="aktif_mi" value="1" class="rounded border-slate-300"
            @checked(old('aktif_mi', $bolum?->aktif_mi ?? true))>
        Aktif
    </label>

    <div class="flex gap-2 pt-2">
        <button class="ui-btn-primary">Kaydet</button>
        <a href="{{ route('admin.bolumler.index') }}" class="ui-btn">İptal</a>
    </div>
</div>
