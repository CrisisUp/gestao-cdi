<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-slate-900 dark:border-slate-800" role="navigation" aria-label="Menu principal">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" aria-label="Ir para o Painel Inicial">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-emerald-500" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" aria-label="Painel de Controle">
                        {{ __('Painel') }}
                    </x-nav-link>
                    <x-nav-link :href="route('idoso.index')" :active="request()->routeIs('idoso.*')" aria-label="Gerenciar Idosos">
                        {{ __('Idosos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('atividade.index')" :active="request()->routeIs('atividade.*')" aria-label="Gerenciar Oficinas e Atividades">
                        {{ __('Atividades') }}
                    </x-nav-link>
                    <x-nav-link :href="route('frequencia.index')" :active="request()->routeIs('frequencia.*')" aria-label="Registrar Frequência Diária">
                        {{ __('Frequência') }}
                    </x-nav-link>
                    <x-nav-link :href="route('encaminhamento.index')" :active="request()->routeIs('encaminhamento.*')" aria-label="Gerenciar Encaminhamentos">
                        {{ __('Encaminhamentos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('relatorios.movimentacao')" :active="request()->routeIs('relatorios.*')" aria-label="Relatórios de Controle Social">
                        {{ __('Relatórios') }}
                    </x-nav-link>
                    @can('admin-access')
                        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.*')" aria-label="Gerenciar Equipe">
                            {{ __('Equipe') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.logs.index')" :active="request()->routeIs('admin.logs.*')" aria-label="Logs de Auditoria">
                            {{ __('Logs') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Botão Alternar Tema (Dark/Light) -->
                <button @click="$store.theme.toggle()" 
                    class="p-2 text-slate-400 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-lg transition-colors"
                    aria-label="Alternar Tema de Cores">
                    <!-- Ícone Lua (Modo Escuro) -->
                    <svg x-show="!$store.theme.darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <!-- Ícone Sol (Modo Claro) -->
                    <svg x-show="$store.theme.darkMode" x-cloak class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.364 17.364l-.707.707M17.364 17.364l-.707-.707M6.364 6.364l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 hover:text-slate-700 dark:hover:text-slate-200 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Painel') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('idoso.index')" :active="request()->routeIs('idoso.*')">
                {{ __('Idosos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('atividade.index')" :active="request()->routeIs('atividade.*')">
                {{ __('Atividades') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('frequencia.index')" :active="request()->routeIs('frequencia.*')">
                {{ __('Frequência') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('encaminhamento.index')" :active="request()->routeIs('encaminhamento.*')">
                {{ __('Encaminhamentos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('relatorios.movimentacao')" :active="request()->routeIs('relatorios.*')">
                {{ __('Relatórios') }}
            </x-responsive-nav-link>
            @can('admin-access')
                <x-responsive-nav-link :href="route('user.index')" :active="request()->routeIs('user.*')">
                    {{ __('Equipe') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
