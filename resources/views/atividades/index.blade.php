<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600 dark:hover:text-slate-400">Início</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600 dark:text-slate-400">Cronograma de Atividades</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ __('Atividades e Oficinas') }}
            </h1>
            <a href="{{ route('atividade.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-800 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                + Nova Atividade
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($atividades as $ativ)
                    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span class="badge badge-info">{{ ucfirst($ativ->dia_semana) }}</span>
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500">{{ \Carbon\Carbon::parse($ativ->horario)->format('H:i') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-1">{{ $ativ->nome }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $ativ->facilitador ?? 'Facilitador não informado' }}</p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">{{ $ativ->idosos_count }} Participantes</span>
                                <a href="{{ route('atividade.show', $ativ) }}" class="text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase hover:underline">
                                    Gerenciar Lista
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-slate-900 p-12 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 text-center">
                        <p class="text-slate-400 dark:text-slate-500 italic">Nenhuma atividade cadastrada no cronograma.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
