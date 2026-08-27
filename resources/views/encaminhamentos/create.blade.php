<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Início</a>
        <span class="mx-2">/</span>
        <a href="{{ route('encaminhamento.index') }}" class="hover:text-slate-600">Encaminhamentos</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">Novo Registro</span>
    </x-slot>

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Registrar Novo Encaminhamento') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8">
                    <form action="{{ route('encaminhamento.store') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf

                        <!-- Seleção do Idoso -->
                        <div>
                            <x-input-label for="idoso_id" :value="__('Idoso Beneficiário')" />
                            <select name="idoso_id" id="idoso_id" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" required>
                                <option value="">Selecione um idoso...</option>
                                @foreach($idosos as $idoso)
                                    <option value="{{ $idoso->id }}" {{ $idosoSelecionado == $idoso->id ? 'selected' : '' }}>
                                        {{ $idoso->nome }} ({{ $idoso->codigo_registro }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('idoso_id')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Destino -->
                            <div>
                                <x-input-label for="instituicao_destino" :value="__('Instituição de Destino')" />
                                <x-text-input id="instituicao_destino" name="instituicao_destino" :isError="$errors->has('instituicao_destino')" type="text" class="mt-1 block w-full" placeholder="Ex: Hospital Municipal, CRAS, UPA..." required />
                                <x-input-error class="mt-2" :messages="$errors->get('instituicao_destino')" />
                            </div>

                            <!-- Especialidade -->
                            <div>
                                <x-input-label for="especialidade" :value="__('Especialidade (opcional)')" />
                                <x-text-input id="especialidade" name="especialidade" :isError="$errors->has('especialidade')" type="text" class="mt-1 block w-full" placeholder="Ex: Cardiologia, Dentista, Geral..." />
                                <x-input-error class="mt-2" :messages="$errors->get('especialidade')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data -->
                            <div>
                                <x-input-label for="data_encaminhamento" :value="__('Data do Encaminhamento')" />
                                <x-text-input id="data_encaminhamento" name="data_encaminhamento" :isError="$errors->has('data_encaminhamento')" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('data_encaminhamento')" />
                            </div>

                            <!-- Prioridade -->
                            <div>
                                <x-input-label for="prioridade" :value="__('Classificação de Risco / Prioridade')" />
                                <select name="prioridade" id="prioridade" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" required>
                                    <option value="rotina">Rotina (Acompanhamento)</option>
                                    <option value="programado">Programado (Agendamento)</option>
                                    <option value="urgente">Urgente (Imediato)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('prioridade')" />
                            </div>
                        </div>

                        <!-- Motivo -->
                        <div>
                            <x-input-label for="motivo" :value="__('Motivo Detalhado')" />
                            <textarea id="motivo" name="motivo" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="4" placeholder="Descreva os sintomas ou o objetivo do encaminhamento..." required></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('motivo')" />
                        </div>

                        <div class="pt-4 flex items-center gap-4">
                            <x-primary-button class="bg-emerald-700 hover:bg-emerald-800">
                                {{ __('Registrar Encaminhamento') }}
                            </x-primary-button>
                            <a href="{{ route('encaminhamento.index') }}" class="text-sm text-slate-500 hover:text-slate-800 underline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
