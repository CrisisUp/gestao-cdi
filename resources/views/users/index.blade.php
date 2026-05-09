<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600 dark:hover:text-slate-400">Início</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600 dark:text-slate-400">Equipe</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ __('Equipe / Usuários') }}
            </h2>
            <a href="{{ route('user.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-800 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                + Novo Usuário
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
                <form action="{{ route('user.index') }}" method="GET" class="flex items-center space-x-4">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ $search }}" 
                            placeholder="Buscar por nome ou e-mail..." 
                            class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-slate-500 dark:focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm pl-10 text-slate-600 dark:text-slate-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-slate-800 dark:bg-slate-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-slate-600 focus:outline-none transition ease-in-out duration-150">
                        Filtrar
                    </button>
                    @if($search)
                        <a href="{{ route('user.index') }}" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 text-sm font-medium">Limpar</a>
                    @endif
                </form>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center w-20">ID</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Usuário</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">E-mail</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center w-36">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 text-center text-slate-400 dark:text-slate-500 text-xs font-mono">#{{ $user->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold mr-3 border border-slate-200 dark:border-slate-700 shadow-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="font-bold text-slate-800 dark:text-slate-100">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-5">
                                            <a href="{{ route('ponto.historico', $user) }}" class="text-slate-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" title="Folha de Ponto">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este usuário?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 transition-colors cursor-pointer" title="Excluir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                        Nenhum usuário encontrado para "{{ $search }}".
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 sm:flex sm:items-center sm:justify-between">
                <div class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Mostrando <strong>{{ $users->firstItem() ?? 0 }}</strong> a <strong>{{ $users->lastItem() ?? 0 }}</strong> de <strong>{{ $users->total() }}</strong> usuários
                </div>
                <div class="mt-4 sm:mt-0">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
