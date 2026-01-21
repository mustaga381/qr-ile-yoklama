@php
    $hoca = $hoca ?? null;
@endphp

@if ($errors->any())
    <div class="ui-card border-red-200 bg-red-50/80 p-4 text-sm text-red-700">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mt-6 space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="text-sm font-semibold text-slate-800">Ad Soyad</label>
            <input name="ad_soyad" value="{{ old('ad_soyad', $hoca?->ad_soyad) }}" class="ui-input mt-1" required>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-800">E-posta</label>
            <input type="email" name="eposta" value="{{ old('eposta', $hoca?->eposta) }}" class="ui-input mt-1"
                required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="text-sm font-semibold text-slate-800">Personel No</label>
            <input name="personel_no" value="{{ old('personel_no', $hoca?->personel_no) }}" class="ui-input mt-1"
                required>
        </div>

        <div>
            <label class="text-sm font-semibold text-slate-800">Bölüm</label>
            <select name="bolum_id" class="ui-input mt-1" required>
                <option value="">Seçiniz</option>
                @foreach ($bolumler as $b)
                    <option value="{{ $b->id }}" @selected(old('bolum_id', $hoca?->bolum_id) == $b->id)>
                        {{ $b->bolum_adi }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="ui-card p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-sm font-extrabold tracking-tight text-slate-900">Şifre</div>
                <div class="text-sm text-slate-600 mt-1">
                    @if ($hoca)
                        Boş bırakırsan değişmez.
                    @else
                        İlk şifreyi burada belirle.
                    @endif
                </div>
            </div>
            <span class="ui-badge bg-white/70">Min 8</span>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <label class="text-sm font-semibold text-slate-800">Şifre</label>
                <input type="password" name="sifre" class="ui-input mt-1" @required(!$hoca) minlength="8">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-800">Şifre Tekrar</label>
                <input type="password" name="sifre_confirmation" class="ui-input mt-1" @required(!$hoca) minlength="8">
            </div>
        </div>
    </div>

    <div class="pt-2 flex flex-col sm:flex-row gap-2">
        <button class="ui-btn-primary">
            <x-heroicon-o-check class="h-5 w-5" />
            Kaydet
        </button>
        <a href="{{ route('admin.hocalar.index') }}" class="ui-btn">
            <x-heroicon-o-x-mark class="h-5 w-5 text-slate-700" />
            İptal
        </a>
    </div>
</div>
