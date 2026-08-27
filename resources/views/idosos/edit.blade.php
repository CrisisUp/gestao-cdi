<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Início</a>
        <span class="mx-2">/</span>
        <a href="{{ route('idoso.index') }}" class="hover:text-slate-600">Idosos</a>
        <span class="mx-2">/</span>
        <a href="{{ route('idoso.show', $idoso) }}" class="hover:text-slate-600">{{ $idoso->nome }}</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">Editar</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Editar Cadastro: ') }} {{ $idoso->nome }}
            </h1>
            <a href="{{ route('idoso.show', $idoso) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                Voltar ao Prontuário
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8 text-slate-900">
                    <form action="{{ route('idoso.update', $idoso) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Situação do Usuário -->
                        <div>
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Situação do Usuário</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-4 bg-slate-50 rounded-lg border border-slate-100">
                                <div>
                                    <x-input-label for="sexo" :value="__('Sexo / Gênero')" />
                                    <x-select-input id="sexo" name="sexo" :isError="$errors->has('sexo')" class="mt-1 block w-full" required>
                                        <option value="cis_f" {{ old('sexo', $idoso->sexo) == 'cis_f' ? 'selected' : '' }}>Cisgênero Feminino</option>
                                        <option value="cis_m" {{ old('sexo', $idoso->sexo) == 'cis_m' ? 'selected' : '' }}>Cisgênero Masculino</option>
                                        <option value="trans_f" {{ old('sexo', $idoso->sexo) == 'trans_f' ? 'selected' : '' }}>Transgênero Feminino</option>
                                        <option value="trans_m" {{ old('sexo', $idoso->sexo) == 'trans_m' ? 'selected' : '' }}>Transgênero Masculino</option>
                                        <option value="agenero" {{ old('sexo', $idoso->sexo) == 'agenero' ? 'selected' : '' }}>Agênero</option>
                                        <option value="nao_declarado" {{ old('sexo', $idoso->sexo) == 'nao_declarado' ? 'selected' : '' }}>Não declarado</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('sexo')" />
                                </div>
                                <div>
                                    <x-input-label for="raca_cor" :value="__('Raça / Cor')" />
                                    <x-select-input id="raca_cor" name="raca_cor" :isError="$errors->has('raca_cor')" class="mt-1 block w-full" required>
                                        <option value="branca" {{ old('raca_cor', $idoso->raca_cor) == 'branca' ? 'selected' : '' }}>Branca</option>
                                        <option value="preta" {{ old('raca_cor', $idoso->raca_cor) == 'preta' ? 'selected' : '' }}>Preta</option>
                                        <option value="parda" {{ old('raca_cor', $idoso->raca_cor) == 'parda' ? 'selected' : '' }}>Parda</option>
                                        <option value="amarela" {{ old('raca_cor', $idoso->raca_cor) == 'amarela' ? 'selected' : '' }}>Amarela</option>
                                        <option value="indigena" {{ old('raca_cor', $idoso->raca_cor) == 'indigena' ? 'selected' : '' }}>Indígena</option>
                                        <option value="nao_informado" {{ old('raca_cor', $idoso->raca_cor) == 'nao_informado' ? 'selected' : '' }}>Não informado</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('raca_cor')" />
                                </div>
                                <div>
                                    <x-input-label for="grau_dependencia" :value="__('Grau de Dependência')" />
                                    <x-select-input id="grau_dependencia" name="grau_dependencia" :isError="$errors->has('grau_dependencia')" class="mt-1 block w-full" required>
                                        <option value="I" {{ old('grau_dependencia', $idoso->grau_dependencia) == 'I' ? 'selected' : '' }}>Grau I</option>
                                        <option value="II" {{ old('grau_dependencia', $idoso->grau_dependencia) == 'II' ? 'selected' : '' }}>Grau II</option>
                                        <option value="III" {{ old('grau_dependencia', $idoso->grau_dependencia) == 'III' ? 'selected' : '' }}>Grau III</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('grau_dependencia')" />
                                </div>
                                <div>
                                    <x-input-label for="data_admissao" :value="__('Data de Admissão')" />
                                    <x-text-input id="data_admissao" name="data_admissao" :isError="$errors->has('data_admissao')" type="date" class="mt-1 block w-full" :value="old('data_admissao', $idoso->data_admissao)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('data_admissao')" />
                                </div>
                                <div class="md:col-span-1">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Status Atual</span>
                                    <div class="mt-2">
                                        @if($idoso->data_desligamento)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200 uppercase">Inativo / Desligado</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Ativo no Centro</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Campos de Desligamento -->
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-rose-50/30 rounded-lg border border-rose-100">
                                <div>
                                    <x-input-label for="data_desligamento" :value="__('Data do Desligamento (Se houver)')" class="text-rose-800" />
                                    <x-text-input id="data_desligamento" name="data_desligamento" :isError="$errors->has('data_desligamento')" type="date" class="mt-1 block w-full border-rose-200 focus:border-rose-500 focus:ring-rose-500" :value="old('data_desligamento', $idoso->data_desligamento)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('data_desligamento')" />
                                </div>
                                <div>
                                    <x-input-label for="motivo_desligamento" :value="__('Motivo do Desligamento')" class="text-rose-800" />
                                    <x-select-input id="motivo_desligamento" name="motivo_desligamento" :isError="$errors->has('motivo_desligamento')" class="mt-1 block w-full border-rose-200 focus:border-rose-500 focus:ring-rose-500">
                                        <option value="">-- Selecione o Motivo --</option>
                                        <option value="Falecimento" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Falecimento' ? 'selected' : '' }}>Óbito</option>
                                        <option value="Mudança de Cidade" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Mudança de Cidade' ? 'selected' : '' }}>Mudança de endereço</option>
                                        <option value="Transferência para outra Instituição" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Transferência para outra Instituição' ? 'selected' : '' }}>Acolhimento institucional</option>
                                        <option value="Solicitação da Família" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Solicitação da Família' ? 'selected' : '' }}>Desistência/Recusa</option>
                                        <option value="Inviabilidade para o transporte" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Solicitação da Família' ? 'selected' : '' }}>Inviabilidade para o transporte</option>
                                        <option value="Impossibilidade para o transporte" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Solicitação da Família' ? 'selected' : '' }}>Impossibilidade para o transporte</option>
                                        <option value="Melhora do Quadro / Autonomia" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Melhora do Quadro / Autonomia' ? 'selected' : '' }}>Melhora do Quadro / Autonomia</option>
                                        <option value="Outros" {{ old('motivo_desligamento', $idoso->motivo_desligamento) == 'Outros' ? 'selected' : '' }}>Outros</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('motivo_desligamento')" />
                                </div>
                            </div>
                        </div>

                        <!-- Dados Pessoais -->
                        <div class="pt-8 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Dados Pessoais</h3>

                            <div class="mb-6 flex items-center space-x-6">
                                <div class="shrink-0 flex flex-col items-center">
                                    @if($idoso->foto)
                                        <img class="h-16 w-16 object-cover rounded-full border border-slate-200 shadow-sm mb-2" src="{{ asset('storage/' . $idoso->foto) }}" alt="Foto atual">
                                        <div class="flex items-center space-x-1">
                                            <input type="checkbox" name="remover_foto" id="remover_foto" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            <label for="remover_foto" class="text-xs font-bold text-rose-600 uppercase">Remover</label>
                                        </div>
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <label class="block">
                                    <span class="sr-only">Escolher foto</span>
                                    <input type="file" name="foto" id="foto" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="nome" :value="__('Nome Completo')" />
                                    <x-text-input id="nome" name="nome" :isError="$errors->has('nome')" type="text" class="mt-1 block w-full" :value="old('nome', $idoso->nome)" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                                </div>
                                <div>
                                    <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
                                    <x-text-input id="data_nascimento" name="data_nascimento" :isError="$errors->has('data_nascimento')" type="date" class="mt-1 block w-full" :value="old('data_nascimento', $idoso->data_nascimento)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('data_nascimento')" />
                                </div>
                                <div>
                                    <x-input-label for="cpf" :value="__('CPF (opcional)')" />
                                    <x-text-input id="cpf" name="cpf" :isError="$errors->has('cpf')" type="text" class="mt-1 block w-full" :value="old('cpf', $idoso->cpf)" x-data x-mask="999.999.999-99" placeholder="000.000.000-00" />
                                    <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                                </div>
                                <div>
                                    <x-input-label for="nis" :value="__('Número NIS')" />
                                    <x-text-input id="nis" name="nis" :isError="$errors->has('nis')" type="text" class="mt-1 block w-full" :value="old('nis', $idoso->nis)" x-data x-mask="999.99999.99-9" placeholder="000.00000.00-0" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('nis')" />
                                </div>
                            </div>
                        </div>

                        <!-- Emergência -->
                        <div class="pt-8 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Contato de Emergência</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="contato_emergencia_nome" :value="__('Nome do Responsável')" />
                                    <x-text-input id="contato_emergencia_nome" name="contato_emergencia_nome" :isError="$errors->has('contato_emergencia_nome')" type="text" class="mt-1 block w-full" :value="old('contato_emergencia_nome', $idoso->contato_emergencia_nome)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('contato_emergencia_nome')" />
                                </div>
                                <div>
                                    <x-input-label for="contato_emergencia_telefone" :value="__('Telefone de Contato')" />
                                    <x-text-input id="contato_emergencia_telefone" name="contato_emergencia_telefone" :isError="$errors->has('contato_emergencia_telefone')" type="text" class="mt-1 block w-full" :value="old('contato_emergencia_telefone', $idoso->contato_emergencia_telefone)" required x-data x-mask="(99) 99999-9999" placeholder="(00) 00000-0000" />
                                    <x-input-error class="mt-2" :messages="$errors->get('contato_emergencia_telefone')" />
                                </div>
                            </div>
                        </div>

                        <!-- Saúde -->
                        <div class="pt-8 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Informações de Saúde</h3>
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="alergias" :value="__('Alergias')" />
                                    <textarea id="alergias" name="alergias" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="2" placeholder="Descreva alergias se houver...">{{ old('alergias', $idoso->alergias) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('alergias')" />
                                </div>
                                <div>
                                    <x-input-label for="medicamentos" :value="__('Medicamentos em Uso')" />
                                    <textarea id="medicamentos" name="medicamentos" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="2" placeholder="Liste os medicamentos e horários...">{{ old('medicamentos', $idoso->medicamentos) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('medicamentos')" />
                                </div>
                                <div>
                                    <x-input-label for="observacoes" :value="__('Observações Gerais')" />
                                    <textarea id="observacoes" name="observacoes" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="3">{{ old('observacoes', $idoso->observacoes) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('observacoes')" />
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 flex items-center gap-4">
                            <x-primary-button class="bg-emerald-700 hover:bg-emerald-800">
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                            <a href="{{ route('idoso.show', $idoso) }}" class="text-sm text-slate-500 hover:text-slate-800 underline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
