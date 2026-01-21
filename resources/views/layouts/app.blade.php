<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('baslik', 'QR Yoklama')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-slate-900 bg-slate-50 flex">
    @auth
        <div class="flex-1 flex min-h-screen">
            @include('partials.app.sidebar')

            <div class="flex-1 min-w-0 flex flex-col">
                @include('partials.app.topbar')

                <main class="p-2 sm:p-4 md:p-6 max-w-full w-full">
                    @include('partials.app.flash')
                    @yield('icerik')
                </main>
            </div>
        </div>
    @else
        <main class="mx-auto flex-1 flex items-center justify-center relative">
            <div class="absolute inset-0 -z-10 overflow-hidden">
                <div
                    class="absolute -top-24 -right-24 h-80 w-80 rounded-full blur-3xl opacity-40 bg-gradient-to-br from-fuchsia-500 via-indigo-500 to-cyan-400">
                </div>
                <div
                    class="absolute -bottom-28 -left-28 h-96 w-96 rounded-full blur-3xl opacity-30 bg-gradient-to-tr from-emerald-400 via-sky-500 to-purple-500">
                </div>
                <div
                    class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(99,102,241,0.12),transparent_55%),radial-gradient(ellipse_at_bottom,rgba(34,197,94,0.10),transparent_55%)]">
                </div>
            </div>
            @yield('icerik')
        </main>
    @endauth



</body>

</html>
