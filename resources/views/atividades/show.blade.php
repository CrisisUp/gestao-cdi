<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Início</a>
        <span class="mx-2">/</span>
        <a href="{{ route('atividade.index') }}" class="hover:text-slate-600">Atividades</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">{{ $atividade->nome }}</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ $atividade->nome }}
            </h1>
            <form action="{{ route('atividade.destroy', $atividade) }}" method="POST" onsubmit="return confirm('Excluir esta atividade permanentemente?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-400 hover:text-red-600 text-xs font-bold uppercase tracking-widest transition-colors">
                    Excluir Atividade
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Coluna Detalhes -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                        <div class="mb-6">
                            <span class="badge badge-info">{{ ucfirst($atividade->dia_semana) }} às {{ \Carbon\Carbon::parse($atividade->horario)->format('H:i') }}</span>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Facilitador</span>
                                <span class="text-sm text-slate-700 font-bold">{{ $atividade->facilitador ?? 'Não informado' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Descrição</span>
                                <span class="text-sm text-slate-600 leading-relaxed">{{ $atividade->descricao ?? 'Sem descrição.' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vincular Novo Idoso -->
                    <div class="bg-slate-800 p-6 rounded-xl shadow-lg text-white">
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-4">Adicionar Participante</h4>
                        <form action="{{ route('atividade.vincular', $atividade) }}" method="POST">
                            @csrf
                            <select name="idoso_id" class="w-full bg-slate-700 border-slate-600 text-white text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 mb-4" required>
                                <option value="">Selecione um idoso...</option>
                                @foreach($idososDisponiveis as $idoso)
                                    <option value="{{ $idoso->id }}">{{ $idoso->nome }} ({{ $idoso->codigo_registro }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 rounded-lg font-bold text-xs uppercase transition-colors">
                                Confirmar Vínculo
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Lista de Participantes -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 bg-slate-50 border-b border-slate-200">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Idosos Vinculados ({{ $atividade->idosos->count() }})</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-slate-400 uppercase bg-white border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">Registro</th>
                                        <th class="px-6 py-4">Nome do Idoso</th>
                                        <th class="px-6 py-4 text-right">Ação</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($atividade->idosos as $idoso)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $idoso->codigo_registro }}</td>
                                            <td class="px-6 py-4 font-bold text-slate-800">{{ $idoso->nome }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('atividade.desvincular', [$atividade, $idoso]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600 font-bold text-xs uppercase">Remover</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">Nenhum idoso vinculado a esta atividade.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
