@php
    $rol = auth()->user()->rol;
    $cfg = config('yan_menu');

    $ogeler = collect($cfg['ogeler'] ?? [])
        ->filter(fn($i) => in_array($rol, $i['roller'] ?? [], true))
        ->groupBy('grup');

    $altButonlar = collect($cfg['alt']['butonlar'] ?? [])->filter(fn($i) => in_array($rol, $i['roller'] ?? [], true));
@endphp

<aside
    class="hidden md:flex md:flex-col shrink-0 sticky top-0 h-screen w-64
              bg-white/65 backdrop-blur-xl
              shadow-[0_0_0_1px_rgba(15,23,42,0.06),0_18px_40px_rgba(15,23,42,0.10)]
              overflow-hidden">

    <div class="h-full flex flex-col">

        {{-- Logo --}}
        <div class="px-5 pt-6 pb-4">
            <a href="{{ $cfg['marka']['anasayfa'] ?? '/' }}"
                class="inline-flex items-center gap-2 font-extrabold tracking-tight text-slate-900">
                <span
                    class="h-9 w-9 rounded-2xl grid place-items-center text-white shadow-sm
                     bg-gradient-to-br from-slate-900 via-indigo-700 to-fuchsia-600">
                    <x-dynamic-component :component="'heroicon-o-' . ($cfg['marka']['ikon'] ?? 'qr-code')" class="h-5 w-5" />
                </span>
                <span>
                    {{ $cfg['marka']['yazi'] ?? 'QR' }}<span
                        class="text-indigo-600">{{ $cfg['marka']['vurgu'] ?? 'Yoklama' }}</span>
                </span>
            </a>

            <div class="mt-3 flex items-center justify-between">
                <span class="text-xs text-slate-500">Panel</span>
                <span
                    class="text-[11px] rounded-full px-2 py-1 bg-white/70 text-slate-600
                     shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                    {{ $cfg['marka']['versiyon'] ?? 'v1' }}
                </span>
            </div>
        </div>

        <div class="px-5">
            <div class="h-px bg-slate-200/60"></div>
        </div>

        {{-- Menü --}}
        <nav class="px-3 py-4 space-y-3 flex-1 overflow-y-auto">
            @foreach ($ogeler as $grup => $items)
                <div class="px-3 text-[11px] uppercase tracking-wider text-slate-500">
                    {{ $grup }}
                </div>

                <div class="space-y-1">
                    @foreach ($items as $item)
                        @php
                            $aktifList = $item['aktif'] ?? [$item['rota']];
                            $aktif = request()->routeIs(...$aktifList);
                            $comp = 'heroicon-o-' . ($item['ikon'] ?? 'squares-2x2');
                        @endphp

                        <a href="{{ route($item['rota']) }}"
                            class="group flex items-center gap-3 rounded-2xl px-3 py-2.5 transition
                      {{ $aktif
                          ? 'text-white shadow-sm bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600'
                          : 'text-slate-700 hover:bg-white/70' }}">
                            <x-dynamic-component :component="$comp"
                                class="h-5 w-5 {{ $aktif ? 'text-white' : 'text-slate-500 group-hover:text-slate-700' }}" />
                            <span class="font-semibold">{{ $item['etiket'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        {{-- Alt Aksiyonlar --}}
        <div class="p-4">
            <div
                class="rounded-3xl bg-white/70 backdrop-blur-xl p-4
                  shadow-[0_0_0_1px_rgba(15,23,42,0.06)]">
                <div class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->ad_soyad }}</div>
                <div class="text-xs text-slate-600 mt-1">Rol: {{ $rol }}</div>

                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach ($altButonlar as $btn)
                        @php $bcomp = 'heroicon-o-' . ($btn['ikon'] ?? 'user-circle'); @endphp

                        @if (($btn['tip'] ?? 'link') === 'form')
                            <form method="POST" action="{{ route($btn['rota']) }}">
                                @csrf
                                <button
                                    class="w-full rounded-2xl px-3 py-2 text-sm font-semibold text-white
                               bg-gradient-to-r from-slate-900 via-indigo-700 to-fuchsia-600
                               hover:opacity-95 active:scale-[0.99] transition inline-flex items-center justify-center gap-2">
                                    <x-dynamic-component :component="$bcomp" class="h-5 w-5" />
                                    {{ $btn['etiket'] }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route($btn['rota']) }}"
                                class="rounded-2xl px-3 py-2 text-sm font-semibold text-slate-800 bg-white/80 hover:bg-white transition
                        shadow-[0_0_0_1px_rgba(15,23,42,0.06)] inline-flex items-center justify-center gap-2">
                                <x-dynamic-component :component="$bcomp" class="h-5 w-5 text-slate-700" />
                                {{ $btn['etiket'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</aside>
