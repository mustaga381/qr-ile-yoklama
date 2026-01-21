@if (session('ok'))
    <div class="mb-5 ui-card border-emerald-200 bg-emerald-50/80 p-4 text-sm text-emerald-800">
        <div class="font-semibold">Başarılı</div>
        <div class="mt-1">{{ session('ok') }}</div>
    </div>
@endif
