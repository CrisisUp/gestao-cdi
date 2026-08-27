<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Início</a>
        <span class="mx-2">/</span>
        <a href="{{ route('atividade.index') }}" class="hover:text-slate-600">Atividades</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">Nova Atividade</span>
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Registrar Nova Atividade') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8">
                    <form action="{{ route('atividade.store') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf

                        <div>
                            <x-input-label for="nome" :value="__('Nome da Atividade/Oficina')" />
                            <x-text-input id="nome" name="nome" :isError="$errors->has('nome')" type="text" class="mt-1 block w-full" placeholder="Ex: Fisioterapia em Grupo, Oficina de Música..." required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="facilitador" :value="__('Facilitador/Responsável')" />
                                <x-text-input id="facilitador" name="facilitador" :isError="$errors->has('facilitador')" type="text" class="mt-1 block w-full" placeholder="Nome do profissional" />
                                <x-input-error class="mt-2" :messages="$errors->get('facilitador')" />
                            </div>

                            <div>
                                <x-input-label for="dia_semana" :value="__('Dia da Semana')" />
                                <x-select-input name="dia_semana" :isError="$errors->has('dia_semana')" id="dia_semana" class="mt-1 block w-full" required>
                                    <option value="segunda">Segunda-feira</option>
                                    <option value="terca">Terça-feira</option>
                                    <option value="quarta">Quarta-feira</option>
                                    <option value="quinta">Quinta-feira</option>
                                    <option value="sexta">Sexta-feira</option>
                                    <option value="sabado">Sábado</option>
                                    <option value="domingo">Domingo</option>
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('dia_semana')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="horario" :value="__('Horário de Início')" />
                            <x-text-input id="horario" name="horario" :isError="$errors->has('horario')" type="time" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('horario')" />
                        </div>

                        <div>
                            <x-input-label for="descricao" :value="__('Descrição/Objetivo (opcional)')" />
                            <textarea id="descricao" name="descricao" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="3"></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="bg-emerald-700 hover:bg-emerald-800">
                                {{ __('Criar Atividade') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
