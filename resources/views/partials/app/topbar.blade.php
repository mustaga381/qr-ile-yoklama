@php
    $rol = auth()->user()->rol;
    $cfg = config('yan_menu');

    $ogeler = collect($cfg['ogeler'] ?? [])
        ->filter(fn($i) => in_array($rol, $i['roller'] ?? [], true))
        ->groupBy('grup');

    $altButonlar = collect($cfg['alt']['butonlar'] ?? [])->filter(fn($i) => in_array($rol, $i['roller'] ?? [], true));
    $marka = $cfg['marka'] ?? [];
    $markaIkon = 'heroicon-o-' . ($marka['ikon'] ?? 'qr-code');
@endphp

<header class="sticky top-0 z-30 px-2 sm:px-4 md:px-6 py-3">
    <div class="bg-white/55 backdrop-blur-xl shadow-[0_8px_30px_rgba(15,23,42,0.08)] rounded-3xl">
        <div class="mx-auto flex items-center justify-between gap-3 px-3 sm:px-4 py-2.5">

            <div class="flex items-center gap-2 min-w-0">
                {{-- Mobil menü butonu --}}
                <button type="button" class="md:hidden ui-btn px-3" data-drawer-open>
                    <x-heroicon-o-bars-3 class="h-5 w-5 text-slate-700" />
                </button>

                <div class="hidden md:block min-w-0">
                    <div class="text-xs text-slate-500">Hoş geldin</div>
                    <div class="text-sm font-semibold text-slate-900 truncate">
                        {{ auth()->user()->ad_soyad }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Mobil Drawer Overlay --}}
    <div class="hidden fixed inset-0 z-40 bg-black/30 backdrop-blur-sm" data-drawer-overlay></div>

    {{-- Mobil Drawer Panel --}}
    <div class="hidden md:hidden fixed inset-y-0 left-0 z-50 w-[320px] max-w-[88vw]
                bg-white/75 backdrop-blur-xl
                shadow-[0_0_0_1px_rgba(15,23,42,0.06),0_16px_40px_rgba(15,23,42,0.18)]
                rounded-r-3xl
                transform -translate-x-full transition-transform duration-200"
        data-drawer>

        <div class="p-4">
            <div class="flex items-center justify-between">
                <a href="{{ $marka['anasayfa'] ?? '/' }}"
                    class="inline-flex items-center gap-2 font-extrabold tracking-tight text-slate-900">
                    <span
                        class="h-9 w-9 rounded-2xl grid place-items-center text-white shadow-sm
                                 bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                        <x-dynamic-component :component="$markaIkon" class="h-5 w-5" />
                    </span>
                    <span>
                        {{ $marka['yazi'] ?? 'QR' }}<span
                            class="text-indigo-600">{{ $marka['vurgu'] ?? 'Yoklama' }}</span>
                    </span>
                </a>

                <button class="ui-btn px-3" type="button" data-drawer-close>
                    <x-heroicon-o-x-mark class="h-5 w-5 text-slate-700" />
                </button>
            </div>

            <div class="mt-4 rounded-3xl bg-white/70 p-4 shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                <div class="text-sm font-semibold text-slate-900">{{ auth()->user()->ad_soyad }}</div>
                <div class="text-xs text-slate-600 mt-1">Rol: {{ strtoupper($rol) }}</div>
            </div>

            {{-- Drawer Menü: yan_menu ogeler --}}
            <div class="mt-4 space-y-4">
                @foreach ($ogeler as $grup => $items)
                    <div>
                        <div class="px-1 text-[11px] uppercase tracking-wider text-slate-500">
                            {{ $grup }}
                        </div>

                        <div class="mt-2 space-y-2">
                            @foreach ($items as $item)
                                @php
                                    $aktifList = $item['aktif'] ?? [$item['rota']];
                                    $aktif = request()->routeIs(...$aktifList);
                                @endphp

                                <a href="{{ route($item['rota']) }}"
                                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-semibold transition
                                          {{ $aktif ? 'text-white bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 shadow-sm' : 'text-slate-800 bg-white/70 hover:bg-white' }}">
                                    <x-dynamic-component :component="'heroicon-o-' . ($item['ikon'] ?? 'squares-2x2')"
                                        class="h-5 w-5 {{ $aktif ? 'text-white' : 'text-slate-600' }}" />
                                    {{ $item['etiket'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Drawer Alt Butonlar: yan_menu alt --}}
            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach ($altButonlar as $btn)
                    @php
                        $bcomp = 'heroicon-o-' . ($btn['ikon'] ?? 'user-circle');
                        $tip = $btn['tip'] ?? 'link';
                    @endphp

                    @if ($tip === 'form')
                        <form method="POST" action="{{ route($btn['rota']) }}">
                            @csrf
                            <button
                                class="w-full rounded-2xl px-4 py-3 font-semibold text-white shadow-sm hover:opacity-95 active:scale-[0.99]
                                       bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600 transition inline-flex items-center justify-center gap-2">
                                <x-dynamic-component :component="$bcomp" class="h-5 w-5" />
                                {{ $btn['etiket'] }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route($btn['rota']) }}"
                            class="rounded-2xl px-4 py-3 font-semibold text-slate-800 bg-white/70 hover:bg-white transition text-center inline-flex items-center justify-center gap-2">
                            <x-dynamic-component :component="$bcomp" class="h-5 w-5 text-slate-700" />
                            {{ $btn['etiket'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</header>
