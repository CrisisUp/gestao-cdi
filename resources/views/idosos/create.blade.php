<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600">Início</a>
        <span class="mx-2">/</span>
        <a href="{{ route('idoso.index') }}" class="hover:text-slate-600">Idosos</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">Novo Cadastro</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Cadastrar Novo Idoso') }}
            </h2>
            <a href="{{ route('idoso.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                Listar Todos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8 text-slate-900">
                    <form action="{{ route('idoso.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Dados Pessoais -->
                        <div>
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Dados Pessoais</h3>
                            
                            <div class="mb-6">
                                <x-input-label for="foto" :value="__('Foto do Idoso')" />
                                <input type="file" name="foto" id="foto" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="nome" :value="__('Nome Completo')" />
                                    <x-text-input id="nome" name="nome" :isError="$errors->has('nome')" type="text" class="mt-1 block w-full" :value="old('nome')" required autofocus autocomplete="name" />
                                    <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                                </div>
                                <div>
                                    <x-input-label for="sexo" :value="__('Sexo / Identidade de Gênero')" />
                                    <x-select-input id="sexo" name="sexo" :isError="$errors->has('sexo')" class="mt-1 block w-full" required>
                                        <option value="cis_f" {{ old('sexo') == 'cis_f' ? 'selected' : '' }}>Cisgênero Feminino</option>
                                        <option value="cis_m" {{ old('sexo') == 'cis_m' ? 'selected' : '' }}>Cisgênero Masculino</option>
                                        <option value="trans_f" {{ old('sexo') == 'trans_f' ? 'selected' : '' }}>Transgênero Feminino</option>
                                        <option value="trans_m" {{ old('sexo') == 'trans_m' ? 'selected' : '' }}>Transgênero Masculino</option>
                                        <option value="agenero" {{ old('sexo') == 'agenero' ? 'selected' : '' }}>Agênero</option>
                                        <option value="nao_declarado" {{ old('sexo') == 'nao_declarado' ? 'selected' : '' }}>Não declarado</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('sexo')" />
                                </div>
                                <div>
                                    <x-input-label for="raca_cor" :value="__('Raça / Cor')" />
                                    <x-select-input id="raca_cor" name="raca_cor" :isError="$errors->has('raca_cor')" class="mt-1 block w-full" required>
                                        <option value="branca" {{ old('raca_cor') == 'branca' ? 'selected' : '' }}>Branca</option>
                                        <option value="preta" {{ old('raca_cor') == 'preta' ? 'selected' : '' }}>Preta</option>
                                        <option value="parda" {{ old('raca_cor') == 'parda' ? 'selected' : '' }}>Parda</option>
                                        <option value="amarela" {{ old('raca_cor') == 'amarela' ? 'selected' : '' }}>Amarela</option>
                                        <option value="indigena" {{ old('raca_cor') == 'indigena' ? 'selected' : '' }}>Indígena</option>
                                        <option value="nao_informado" {{ old('raca_cor') == 'nao_informado' ? 'selected' : '' }}>Não informado</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('raca_cor')" />
                                </div>
                                <div>
                                    <x-input-label for="grau_dependencia" :value="__('Grau de Dependência')" />
                                    <x-select-input id="grau_dependencia" name="grau_dependencia" :isError="$errors->has('grau_dependencia')" class="mt-1 block w-full" required>
                                        <option value="I" {{ old('grau_dependencia') == 'I' ? 'selected' : '' }}>Grau I (Independente)</option>
                                        <option value="II" {{ old('grau_dependencia') == 'II' ? 'selected' : '' }}>Grau II (Dependência Leve/Moderada)</option>
                                        <option value="III" {{ old('grau_dependencia') == 'III' ? 'selected' : '' }}>Grau III (Dependência Grave/Total)</option>
                                    </x-select-input>
                                    <x-input-error class="mt-2" :messages="$errors->get('grau_dependencia')" />
                                </div>
                                <div>
                                    <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
                                    <x-text-input id="data_nascimento" name="data_nascimento" :isError="$errors->has('data_nascimento')" type="date" class="mt-1 block w-full" :value="old('data_nascimento')" required 
                                        onchange="calcularFaixaEtaria(this.value)" />
                                    <div id="faixa_etaria_display" class="mt-2 text-sm font-medium text-emerald-600 hidden">
                                        Categoria: <span id="faixa_etaria_texto"></span>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('data_nascimento')" />
                                </div>
                                <div>
                                    <x-input-label for="data_admissao" :value="__('Data de Admissão')" />
                                    <x-text-input id="data_admissao" name="data_admissao" :isError="$errors->has('data_admissao')" type="date" class="mt-1 block w-full" :value="old('data_admissao', date('Y-m-d'))" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('data_admissao')" />
                                </div>

                                <script>
                                    function calcularFaixaEtaria(dataNascimento) {
                                        if (!dataNascimento) return;
                                        const hoje = new Date();
                                        const nascimento = new Date(dataNascimento);
                                        let idade = hoje.getFullYear() - nascimento.getFullYear();
                                        const m = hoje.getMonth() - nascimento.getMonth();
                                        if (m < 0 || (m === 0 && hoje.getDate() < nascimento.getDate())) idade--;

                                        let categoria, cor;
                                        if (idade >= 60 && idade <= 64) { categoria = "60-64 anos"; cor = "text-emerald-600"; }
                                        else if (idade >= 65 && idade <= 69) { categoria = "65-69 anos"; cor = "text-emerald-600"; }
                                        else if (idade >= 70 && idade <= 74) { categoria = "70-74 anos"; cor = "text-emerald-600"; }
                                        else if (idade >= 75) { categoria = "75 anos ou mais"; cor = "text-emerald-600"; }
                                        else { categoria = "Menor de 60 anos (Não elegível)"; cor = "text-amber-600"; }

                                        const display = document.getElementById('faixa_etaria_display');
                                        const texto = document.getElementById('faixa_etaria_texto');
                                        display.classList.remove('hidden');
                                        texto.innerText = categoria;
                                        display.className = `mt-2 text-sm font-medium ${cor}`;
                                    }
                                </script>
                                <div>
                                    <x-input-label for="cpf" :value="__('CPF (opcional)')" />
                                    <x-text-input id="cpf" name="cpf" :isError="$errors->has('cpf')" type="text" class="mt-1 block w-full" :value="old('cpf')" x-data x-mask="999.999.999-99" placeholder="000.000.000-00" />
                                    <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
                                </div>
                                <div>
                                    <x-input-label for="nis" :value="__('Número NIS')" />
                                    <x-text-input id="nis" name="nis" :isError="$errors->has('nis')" type="text" class="mt-1 block w-full" :value="old('nis')" x-data x-mask="999.99999.99-9" placeholder="000.00000.00-0" required />
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
                                    <x-text-input id="contato_emergencia_nome" name="contato_emergencia_nome" :isError="$errors->has('contato_emergencia_nome')" type="text" class="mt-1 block w-full" :value="old('contato_emergencia_nome')" required autocomplete="off" />
                                    <x-input-error class="mt-2" :messages="$errors->get('contato_emergencia_nome')" />
                                </div>
                                <div>
                                    <x-input-label for="contato_emergencia_telefone" :value="__('Telefone de Contato')" />
                                    <x-text-input id="contato_emergencia_telefone" name="contato_emergencia_telefone" :isError="$errors->has('contato_emergencia_telefone')" type="text" class="mt-1 block w-full" :value="old('contato_emergencia_telefone')" required x-data x-mask="(99) 99999-9999" placeholder="(00) 00000-0000" />
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
                                    <textarea id="alergias" name="alergias" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="2" placeholder="Descreva alergias se houver...">{{ old('alergias') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('alergias')" />
                                </div>
                                <div>
                                    <x-input-label for="medicamentos" :value="__('Medicamentos em Uso')" />
                                    <textarea id="medicamentos" name="medicamentos" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="2" placeholder="Liste os medicamentos e horários...">{{ old('medicamentos') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('medicamentos')" />
                                </div>
                                <div>
                                    <x-input-label for="observacoes" :value="__('Observações Gerais')" />
                                    <textarea id="observacoes" name="observacoes" class="mt-1 block w-full border-slate-200 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm text-slate-600" rows="3">{{ old('observacoes') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('observacoes')" />
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 flex flex-wrap items-center gap-4">
                            <x-primary-button class="bg-emerald-700 hover:bg-emerald-800">
                                {{ __('Finalizar Cadastro') }}
                            </x-primary-button>
                            <a href="{{ route('idoso.index') }}" class="text-sm text-slate-500 hover:text-slate-800 underline">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
