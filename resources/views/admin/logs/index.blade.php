<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600 dark:hover:text-slate-400">Início</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600 dark:text-slate-400">Administração</span>
        <span class="mx-2">/</span>
        <span class="text-slate-600 dark:text-slate-400">Logs de Auditoria</span>
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ __('Logs de Auditoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="p-6 text-slate-900 dark:text-slate-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                            <thead class="text-xs text-slate-700 dark:text-slate-300 uppercase bg-slate-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">Data/Hora</th>
                                    <th class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">Usuário</th>
                                    <th class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">Ação</th>
                                    <th class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">Modelo</th>
                                    <th class="px-6 py-3 border-b border-slate-200 dark:border-slate-700">Alterações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-7 w-7 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mr-2 text-[10px] font-bold text-slate-500">
                                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                                </div>
                                                <span class="font-medium">{{ $log->user->name ?? 'Sistema' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                                {{ $log->action == 'created' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                                {{ $log->action == 'updated' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                                {{ $log->action == 'deleted' || $log->action == 'soft_deleted' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400' : '' }}">
                                                {{ $log->action == 'soft_deleted' ? 'EXCLUÍDO' : strtoupper($log->action) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500">
                                            {{ str_replace('App\Models\\', '', $log->model_type) }} <span class="ml-1 opacity-50">#{{ $log->model_id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($log->action == 'updated')
                                                <details class="group cursor-pointer">
                                                    <summary class="text-blue-600 dark:text-blue-400 hover:underline flex items-center font-bold text-xs uppercase tracking-widest">
                                                        Ver Detalhes
                                                        <svg class="w-3 h-3 ml-1 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </summary>
                                                    <div class="mt-3 p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 text-[10px] leading-relaxed shadow-inner overflow-hidden">
                                                        <div class="mb-2"><strong class="text-rose-600 dark:text-rose-400 uppercase">ANTERIOR:</strong> <code class="text-slate-600 dark:text-slate-400 break-all">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></div>
                                                        <div><strong class="text-emerald-600 dark:text-emerald-400 uppercase">ATUALIZADO:</strong> <code class="text-slate-600 dark:text-slate-400 break-all">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></div>
                                                    </div>
                                                </details>
                                            @elseif($log->action == 'created')
                                                <span class="text-slate-400 dark:text-slate-600 italic text-xs">Novo registro inserido</span>
                                            @else
                                                <span class="text-rose-400 dark:text-rose-700 italic text-xs">Registro removido do sistema</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
