<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                Folha de Ponto: {{ $user->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('ponto.exportar', ['user' => $user->id, 'mes' => $mes, 'ano' => $ano]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Baixar PDF
                </a>
                <a href="{{ Auth::user()->can('admin-access') ? route('user.index') : route('home') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md font-semibold text-xs text-slate-700 dark:text-slate-300 uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtro de Mês/Ano -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 mb-8">
                <form action="{{ route('ponto.historico', $user) }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <x-input-label for="mes" :value="__('Mês')" class="dark:text-slate-400" />
                        <select id="mes" name="mes" class="mt-1 block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-slate-500 dark:focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600 dark:text-slate-300">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                    {{ ucfirst(\Carbon\Carbon::create()->month($m)->locale('pt_BR')->monthName) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="ano" :value="__('Ano')" class="dark:text-slate-400" />
                        <select id="ano" name="ano" class="mt-1 block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-slate-500 dark:focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600 dark:text-slate-300">
                            @foreach(range(date('Y')-2, date('Y')) as $a)
                                <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-primary-button class="bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600">
                        Filtrar
                    </x-primary-button>
                </form>
            </div>

            <!-- Tabela de Histórico -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-6">Detalhamento de {{ $mesNome }} de {{ $ano }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <th class="py-4 px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Data</th>
                                    <th class="py-4 px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Dia</th>
                                    <th class="py-4 px-4 text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Entrada</th>
                                    <th class="py-4 px-4 text-xs font-bold text-red-600 dark:text-rose-400 uppercase tracking-wider">Saída</th>
                                    <th class="py-4 px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Horas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                                @forelse($pontos as $ponto)
                                    @php
                                        $entrada = \Carbon\Carbon::parse($ponto->entrada);
                                        $saida = $ponto->saida ? \Carbon\Carbon::parse($ponto->saida) : null;
                                        $horas = $saida ? $entrada->diffInHours($saida) . 'h ' . ($entrada->diffInMinutes($saida) % 60) . 'min' : '-';
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-4 px-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ \Carbon\Carbon::parse($ponto->data)->format('d/m/Y') }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px]">
                                            {{ \Carbon\Carbon::parse($ponto->data)->locale('pt_BR')->dayName }}
                                        </td>
                                        <td class="py-4 px-4 text-sm font-bold text-emerald-700 dark:text-emerald-400">
                                            {{ \Carbon\Carbon::parse($ponto->entrada)->format('H:i') }}
                                        </td>
                                        <td class="py-4 px-4 text-sm font-bold text-red-700 dark:text-rose-400">
                                            {{ $ponto->saida ? \Carbon\Carbon::parse($ponto->saida)->format('H:i') : '--:--' }}
                                        </td>
                                        <td class="py-4 px-4 text-sm font-medium text-slate-600 dark:text-slate-400">
                                            {{ $horas }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500 italic text-sm">
                                            Nenhum registro de ponto encontrado para este período.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
