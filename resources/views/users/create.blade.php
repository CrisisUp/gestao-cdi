<x-app-layout>
    <x-slot name="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="hover:text-slate-600 dark:hover:text-slate-400">Início</a>
        <span class="mx-2">/</span>
        <a href="{{ route('user.index') }}" class="hover:text-slate-600 dark:hover:text-slate-400">Equipe</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600 dark:text-slate-400">Novo Membro</span>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                {{ __('Cadastrar Profissional') }}
            </h2>
            <a href="{{ route('user.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 dark:bg-slate-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 dark:hover:bg-slate-600 transition ease-in-out duration-150 shadow-sm">
                Listar Equipe
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="p-8">
                    <x-alert />

                    <form action="{{ route('user.store') }}" method="POST" class="space-y-6 max-w-xl">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nome Completo')" class="dark:text-slate-400" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300" :value="old('name')" :isError="$errors->has('name')" required autofocus placeholder="Nome do profissional" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('E-mail Corporativo')" class="dark:text-slate-400" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300" :value="old('email')" :isError="$errors->has('email')" required placeholder="email@exemplo.com" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Cargo/Perfil de Acesso')" class="dark:text-slate-400" />
                            <x-select-input id="role" name="role" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300" :isError="$errors->has('role')" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }} class="dark:bg-slate-800">Selecione o perfil</option>
                                <option value="funcionario" {{ old('role') == 'funcionario' ? 'selected' : '' }} class="dark:bg-slate-800">Funcionário (Leitura/Escrita)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }} class="dark:bg-slate-800">Administrador (Acesso Total)</option>
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="password" :value="__('Senha de Acesso')" class="dark:text-slate-400" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300" :isError="$errors->has('password')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" class="dark:text-slate-400" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300" :isError="$errors->has('password_confirmation')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                            </div>
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="bg-emerald-700 hover:bg-emerald-800 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                {{ __('Salvar Cadastro') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
