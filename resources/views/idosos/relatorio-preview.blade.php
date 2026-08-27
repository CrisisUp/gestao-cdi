<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Prévia do Relatório: ') }} {{ $idoso->nome }}
            </h1>
            <a href="{{ route('idoso.show', $idoso) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                Voltar ao Prontuário
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros de Período -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-8">
                <form action="{{ route('idoso.relatorio-preview', $idoso) }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="w-full md:w-48">
                        <x-input-label for="mes" :value="__('Mês')" />
                        <select name="mes" id="mes" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                    {{ ucfirst(\Illuminate\Support\Carbon::create()->month($m)->locale('pt_BR')->monthName) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="w-full md:w-32">
                        <x-input-label for="ano" :value="__('Ano')" />
                        <select name="ano" id="ano" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600">
                            @for ($a = date('Y'); $a >= date('Y') - 5; $a--)
                                <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 transition transition-colors">
                        Atualizar Prévia
                    </button>

                    <div class="flex-grow flex justify-end">
                        <a href="{{ route('idoso.relatorio-pdf', ['idoso' => $idoso->id, 'mes' => $mes, 'ano' => $ano]) }}" class="inline-flex items-center px-6 py-3 bg-emerald-700 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-emerald-800 shadow-lg transition transition-all hover:scale-105 active:scale-95">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Confirmar e Gerar PDF
                        </a>
                    </div>
                </form>
            </div>

            <!-- Moldura do "Papel" -->
            <div class="bg-slate-200 p-8 md:p-12 rounded-xl border-4 border-dashed border-slate-300">
                <div class="bg-white mx-auto max-w-4xl shadow-2xl p-12 min-h-[800px] border border-slate-100">
                    
                    <!-- Simulação do Cabeçalho do PDF -->
                    <div class="text-center border-b-2 border-emerald-500 pb-6 mb-8">
                        <h2 class="text-2xl font-bold text-slate-900">Gestão CDI - Centro de Dia para Idosos</h2>
                        <p class="text-slate-500 font-medium">Relatório Mensal de Frequência</p>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
                        <div>
                            <p class="text-slate-400 font-bold uppercase tracking-wider text-xs mb-1">Idoso</p>
                            <p class="text-slate-800 font-bold text-lg leading-tight">{{ $idoso->nome }}</p>
                            <p class="text-slate-500 mt-1">CPF: {{ $idoso->cpf ?? 'Não informado' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 font-bold uppercase tracking-wider text-xs mb-1">Período</p>
                            <p class="text-slate-800 font-bold text-lg leading-tight">{{ $mesNome }} / {{ $ano }}</p>
                            <p class="text-slate-500 mt-1">Responsável: {{ $idoso->contato_emergencia_nome }}</p>
                        </div>
                    </div>

                    <!-- Tabela de Dados -->
                    <table class="w-full border-collapse border border-slate-200 text-sm">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="border border-slate-200 px-4 py-3 text-left text-slate-500 font-bold">Data</th>
                                <th class="border border-slate-200 px-4 py-3 text-left text-slate-500 font-bold">Dia da Semana</th>
                                <th class="border border-slate-200 px-4 py-3 text-left text-slate-500 font-bold w-24">Status</th>
                                <th class="border border-slate-200 px-4 py-3 text-left text-slate-500 font-bold">Observações / Intercorrências</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($frequencias as $freq)
                                <tr>
                                    <td class="border border-slate-200 px-4 py-3 text-slate-700 font-medium">
                                        {{ \Carbon\Carbon::parse($freq->data)->format('d/m/Y') }}
                                    </td>
                                    <td class="border border-slate-200 px-4 py-3 text-slate-500 text-xs">
                                        {{ ucfirst(\Carbon\Carbon::parse($freq->data)->locale('pt_BR')->dayName) }}
                                    </td>
                                    <td class="border border-slate-200 px-4 py-3">
                                        @if($freq->status == 'presente')
                                            <span class="text-emerald-600 font-bold text-xs uppercase">Presente</span>
                                        @else
                                            <span class="text-red-500 font-bold text-xs uppercase">Ausente</span>
                                        @endif
                                    </td>
                                    <td class="border border-slate-200 px-4 py-3">
                                        <p class="text-slate-700">{{ $freq->observacoes ?? '-' }}</p>
                                        @if($freq->profissional && $freq->observacoes)
                                            <p class="text-[9px] text-slate-400 mt-1 italic uppercase font-bold">Anotado por: {{ $freq->profissional->name }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border border-slate-200 px-4 py-10 text-center text-slate-400 italic">
                                        Nenhum registro de frequência encontrado para este período.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Simulação de Assinaturas -->
                    <div class="mt-20 grid grid-cols-2 gap-12">
                        <div class="text-center">
                            <div class="border-t border-slate-800 pt-2 text-xs font-bold text-slate-800 uppercase tracking-widest">
                                Assinatura do Responsável
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="border-t border-slate-800 pt-2 text-xs font-bold text-slate-800 uppercase tracking-widest">
                                Coordenação CDI
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
