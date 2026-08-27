<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#059669">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="description" content="Sistema de Gestão para Centro de Dia para Idosos">

        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/favicon.ico">

        <title>{{ config('app.name', 'Gestão CDI') }}</title>

        <!-- Script para evitar flash de cor branca no carregamento -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-100 dark:bg-slate-950 dark:text-slate-100 selection:bg-emerald-100 transition-colors duration-200">
        <!-- Skip Link -->
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-4 focus:bg-emerald-600 focus:text-white focus:font-bold focus:rounded-b-lg focus:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            Pular para o conteúdo principal
        </a>

        <div class="min-h-screen transition-colors duration-200">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-slate-900 shadow-sm border-b border-slate-200 dark:border-slate-800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @isset($breadcrumbs)
                            <nav class="flex mb-4 text-slate-400 dark:text-slate-500 text-xs font-bold uppercase tracking-widest" aria-label="Breadcrumb">
                                {{ $breadcrumbs }}
                            </nav>
                        @endisset
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main id="main-content" tabindex="-1" class="focus:outline-none">
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
