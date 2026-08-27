<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600 dark:hover:text-slate-400">Início</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600 dark:text-slate-400">Encaminhamentos</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ __('Fluxos de Encaminhamento') }}
            </h1>
            <a href="{{ route('encaminhamento.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-800 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                + Novo Encaminhamento
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Barra de Busca -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 mb-8">
                <form action="{{ route('encaminhamento.index') }}" method="GET" class="flex items-center space-x-4">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ $search }}" 
                            placeholder="Buscar por idoso ou instituição..." 
                            class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm pl-10 text-slate-600 dark:text-slate-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-slate-800 dark:bg-slate-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-slate-600 focus:outline-none transition ease-in-out duration-150">
                        Filtrar
                    </button>
                </form>
            </div>

            <div class="table-container dark:bg-slate-900 dark:border-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="table-header dark:bg-slate-800 dark:border-slate-700">
                            <tr>
                                <th class="table-header-cell">Data</th>
                                <th class="table-header-cell">Idoso</th>
                                <th class="table-header-cell">Destino</th>
                                <th class="table-header-cell">Prioridade</th>
                                <th class="table-header-cell">Responsável</th>
                                <th class="table-header-cell text-center w-20">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($encaminhamentos as $item)
                                <tr class="table-row dark:bg-slate-900 dark:border-slate-800 dark:hover:bg-slate-800/50">
                                    <td class="table-cell font-mono text-xs dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($item->data_encaminhamento)->format('d/m/Y') }}
                                    </td>
                                    <td class="table-cell font-bold text-slate-900 dark:text-slate-100">
                                        {{ $item->idoso->nome }}
                                    </td>
                                    <td class="table-cell">
                                        <div class="text-slate-800 dark:text-slate-200 font-medium">{{ $item->instituicao_destino }}</div>
                                        <div class="text-xs text-slate-400 dark:text-slate-500">{{ $item->especialidade }}</div>
                                    </td>
                                    <td class="table-cell">
                                        @if($item->prioridade == 'urgente')
                                            <span class="badge badge-danger">Urgente</span>
                                        @elseif($item->prioridade == 'programado')
                                            <span class="badge badge-warning">Programado</span>
                                        @else
                                            <span class="badge badge-info">Rotina</span>
                                        @endif
                                    </td>
                                    <td class="table-cell text-xs text-slate-500 dark:text-slate-400 uppercase">
                                        {{ $item->profissional->name ?? 'Sistema' }}
                                    </td>
                                    <td class="table-cell text-center">
                                        <form action="{{ route('encaminhamento.destroy', $item) }}" method="POST" onsubmit="return confirm('Deseja excluir este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="table-row dark:bg-slate-900">
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                        Nenhum encaminhamento registrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                {{ $encaminhamentos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
