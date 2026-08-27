<x-guest-layout>
    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Criar Conta</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="name" :value="__('Nome completo')" :required="true" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :isError="$errors->has('name')" :value="old('name')" required autofocus autocomplete="name" placeholder="Seu nome" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- E-mail -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('E-mail')" :required="true" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :isError="$errors->has('email')" :value="old('email')" required autocomplete="email" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" :required="true" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :isError="$errors->has('password')" required autocomplete="new-password" placeholder="Mínimo 6 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar Senha -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar senha')" :required="true" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" :isError="$errors->has('password_confirmation')" required autocomplete="new-password" placeholder="Repita a senha" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 underline transition-colors" href="{{ route('login') }}">
                Já tem conta? Entrar
            </a>

            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                Criar conta
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
