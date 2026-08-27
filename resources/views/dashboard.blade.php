<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Boas-vindas e Registro de Ponto -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-800 mb-8">
                <div class="p-8 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold mb-2 text-slate-800 dark:text-slate-100">Olá, {{ Auth::user()->name }}!</h3>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Bem-vindo ao Sistema de Gestão CDI. Veja o resumo de hoje.</p>
                    </div>
                    
                    <div class="mt-6 md:mt-0 flex space-x-3">
                        @if(!$meuPonto)
                            <form action="{{ route('ponto.entrada') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-sm transition-all shadow-lg hover:scale-105 active:scale-95">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Registrar Entrada
                                </button>
                            </form>
                        @elseif(!$meuPonto->saida)
                            <form action="{{ route('ponto.saida') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm transition-all shadow-lg hover:scale-105 active:scale-95">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Registrar Saída
                                </button>
                            </form>
                            <div class="text-right ml-4 hidden md:block">
                                <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">Entrada às:</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($meuPonto->entrada)->format('H:i') }}</span>
                            </div>
                        @else
                            <div class="flex flex-col items-end">
                                <div class="bg-slate-50 dark:bg-slate-800 px-6 py-3 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold text-sm flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Jornada Finalizada
                                </div>
                                <a href="{{ route('ponto.historico', Auth::user()) }}" class="text-xs text-slate-400 font-bold uppercase mt-2 hover:text-emerald-600 transition-colors">
                                    Ver meu histórico mensal
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estatísticas Reais -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card Idosos -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-10 w-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-emerald-700 dark:text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Atendidos</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalIdosos }}</div>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Idosos cadastrados</p>
                </div>

                <!-- Card Equipe Presente -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-700 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">No Posto</span>
                    </div>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">{{ $equipeHoje }}</div>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Colaboradores presentes</p>
                </div>
            </div>

            <!-- Dashboards Visuais -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de Movimentação Mensal -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <h4 class="text-slate-800 dark:text-slate-100 font-bold mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Movimentação Mensal (Últimos 6 meses)
                    </h4>
                    <div class="h-48 sm:h-64">
                        <canvas id="movimentacaoChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Faixa Etária -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <h4 class="text-slate-800 dark:text-slate-100 font-bold mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Perfil Etário (Faixas)
                    </h4>
                    <div class="h-48 sm:h-64">
                        <canvas id="faixasChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico de Grau de Dependência -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <h4 class="text-slate-800 dark:text-slate-100 font-bold mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Grau de Dependência
                    </h4>
                    <div class="h-48 sm:h-64">
                        <canvas id="dependenciaChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Atividades/Oficinas -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <h4 class="text-slate-800 dark:text-slate-100 font-bold mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Adesão por Oficina / Atividade
                    </h4>
                    <div class="h-48 sm:h-64">
                        <canvas id="atividadesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Atalhos Rápidos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-slate-800 dark:bg-slate-700 p-8 rounded-xl shadow-lg text-white">
                    <h4 class="text-lg font-bold mb-4">Ações Rápidas de Cadastro</h4>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('idoso.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-bold text-sm transition-colors">
                            + Novo Idoso
                        </a>
                        @can('admin-access')
                            <a href="{{ route('user.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 dark:bg-slate-600 hover:bg-slate-500 rounded-lg font-bold text-sm transition-colors border border-slate-500">
                                + Nova Equipe
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col justify-center">
                    <h4 class="text-slate-800 dark:text-slate-100 font-bold mb-2">Prontuários Atualizados</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Acesse rapidamente a lista completa para consultar prontuários e informações de saúde.</p>
                    <a href="{{ route('idoso.index') }}" class="text-emerald-700 dark:text-emerald-400 font-bold text-sm hover:underline flex items-center">
                        Ver Lista de Idosos
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cores padrão CDI
            const colors = {
                emerald: '#059669',
                blue: '#2563eb',
                slate: '#64748b',
                red: '#dc2626',
                amber: '#d97706',
                indigo: '#4f46e5'
            };

            // 1. Gráfico de Movimentação
            new Chart(document.getElementById('movimentacaoChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($movimentacaoMensal['labels']) !!},
                    datasets: [
                        {
                            label: 'Admissões',
                            data: {!! json_encode($movimentacaoMensal['admissoes']) !!},
                            borderColor: colors.emerald,
                            backgroundColor: colors.emerald + '20',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Desligamentos',
                            data: {!! json_encode($movimentacaoMensal['desligamentos']) !!},
                            borderColor: colors.red,
                            backgroundColor: colors.red + '20',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // 2. Gráfico de Faixas Etárias
            new Chart(document.getElementById('faixasChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode(array_keys($statsFaixas)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($statsFaixas)) !!},
                        backgroundColor: [colors.emerald, colors.blue, colors.indigo, colors.amber, colors.red, colors.slate]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // 3. Gráfico de Dependência
            new Chart(document.getElementById('dependenciaChart'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($statsGrau)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($statsGrau)) !!},
                        backgroundColor: [colors.emerald, colors.blue, colors.amber, colors.slate]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // 4. Gráfico de Atividades
            new Chart(document.getElementById('atividadesChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($statsAtividades)) !!},
                    datasets: [{
                        label: 'Nº de Idosos Inscritos',
                        data: {!! json_encode(array_values($statsAtividades)) !!},
                        backgroundColor: colors.blue
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
