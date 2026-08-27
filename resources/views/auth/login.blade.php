<x-guest-layout>
    <!-- Status da Sessão -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Entrar no Sistema</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('E-mail')" :required="true" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :isError="$errors->has('email')" :value="old('email')" required autofocus autocomplete="email" placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" :required="true" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :isError="$errors->has('password')" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Lembrar de mim -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-500 dark:bg-slate-700 shadow-sm" name="remember">
                <span class="ms-2 text-sm text-slate-500 dark:text-slate-400">Lembrar de mim</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-slate-500 dark:text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 underline transition-colors" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif

            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                Entrar
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                Ainda não tem conta?
                <a href="{{ route('register') }}" class="font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                    Criar conta
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
