<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#059669">

        <title>{{ config('app.name', 'Gestão CDI') }}</title>

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex items-center space-x-2">
                    <div class="h-10 w-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-black text-sm">CDI</span>
                    </div>
                    <span class="text-xl font-bold text-slate-800 dark:text-slate-100">Gestão CDI</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-slate-800 shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <button @click="$store.theme.toggle()" class="text-sm text-slate-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                    <span x-show="!$store.theme.darkMode">🌙 Modo escuro</span>
                    <span x-show="$store.theme.darkMode" x-cloak>☀️ Modo claro</span>
                </button>
            </div>
        </div>

        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            }
        </script>
    </body>
</html>
