<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-slate-50 text-slate-900">
    <div class="relative min-h-screen flex flex-col">
        
        <!-- Navegação Superior -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="h-8 w-8 bg-slate-800 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-800">Gestão CDI</span>
            </div>

            <nav class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('home') }}" class="btn-primary">Acessar Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Entrar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Criar Conta</a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Conteúdo Principal -->
        <main class="flex-grow flex flex-col items-center justify-center px-6">
            <div class="max-w-4xl w-full text-center py-12">
                
                <!-- Tag Sóbria -->
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-8">
                    Versão 1.0 — Gestão CDI
                </div>

                <!-- Título Hero -->
                <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 mb-6 tracking-tight">
                    O cuidado que seus idosos merecem, com a <span class="text-emerald-700">gestão que você precisa.</span>
                </h1>
                
                <p class="text-lg text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                    Sistema completo para o gerenciamento de Centros de Dia: cadastros de idosos, controle de frequências, atividades e equipe profissional.
                </p>

                <!-- Cards de Estatísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="h-10 w-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-900">{{ $totalIdosos }}</div>
                        <div class="text-sm text-slate-400 font-medium">Idosos Atendidos</div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-900">{{ $totalUsers }}</div>
                        <div class="text-sm text-slate-400 font-medium">Equipe Profissional</div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="h-10 w-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-900">100%</div>
                        <div class="text-sm text-slate-400 font-medium">Sistema Online</div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-900">Segurança</div>
                        <div class="text-sm text-slate-400 font-medium">Dados Protegidos</div>
                    </div>
                </div>

                <div class="mt-16 flex flex-col md:flex-row items-center justify-center space-y-4 md:space-y-0 md:space-x-4">
                    @auth
                        @can('admin-access')
                            <a href="{{ route('user.index') }}" class="btn-primary px-8 py-4 text-sm">Ir para Gerenciamento</a>
                        @endcan
                        <a href="{{ route('home') }}" class="btn-primary px-8 py-4 text-sm {{ Auth::user()->can('admin-access') ? 'bg-slate-800 hover:bg-slate-900' : '' }}">Acessar Painel</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary px-8 py-4 text-sm">Entrar no Sistema</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-white border border-slate-200 rounded-lg font-bold text-sm text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">Criar Nova Conta</a>
                    @endauth
                </div>
            </div>
        </main>

        <!-- Rodapé Minimalista -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-8 text-center text-slate-400 text-xs font-medium uppercase tracking-widest border-t border-slate-100">
            &copy; {{ date('Y') }} — UserAdmin Dashboard. Desenvolvido com Laravel & Tailwind.
        </footer>
    </div>
</body>
</html>
