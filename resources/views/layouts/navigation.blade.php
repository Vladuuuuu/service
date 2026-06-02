<nav x-data="{ open: false }" class="bg-white border-b border-gray-200/60 sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-lg font-bold text-gray-900">
                    <span class="w-8 h-8 rounded-lg bg-gray-900 flex items-center justify-center text-white text-xs">S</span>
                    ServiceAuto
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-1">
                    @auth
                        @if(auth()->user()->isClient())
                        <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('client.cars.index')" :active="request()->routeIs('client.cars.*')">
                            Mașini
                        </x-nav-link>
                        <x-nav-link :href="route('client.interventions')" :active="request()->routeIs('client.interventions')">
                            Intervenții
                        </x-nav-link>
                        <x-nav-link :href="route('client.invoices')" :active="request()->routeIs('client.invoices')">
                            Facturi
                        </x-nav-link>
                        @endif
                        @if(auth()->user()->isService())
                        <x-nav-link :href="route('service.dashboard')" :active="request()->routeIs('service.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('service.analytics')" :active="request()->routeIs('service.analytics')">
                            Serviciul meu
                        </x-nav-link>
                        <x-nav-link :href="route('service.interventions')" :active="request()->routeIs('service.interventions*')">
                            Intervenții
                        </x-nav-link>
                        <x-nav-link :href="route('service.settings')" :active="request()->routeIs('service.settings')">
                            Setări
                        </x-nav-link>
                        @endif
                        @if(auth()->user()->isAdmin())
                        <x-nav-link href="/admin" :active="request()->is('admin*')">
                            Admin Panel
                        </x-nav-link>
                        @endif
                    @endauth
                    @if(!auth()->check() || !auth()->user()->isService())
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                        Service-uri
                    </x-nav-link>
                    @endif
                </div>
            </div>

            @auth
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                Deconectare
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <div class="hidden sm:flex sm:items-center gap-2">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 rounded-lg font-medium transition-colors">Autentificare</a>
                <a href="{{ route('register') }}" class="btn-primary btn-sm">Înregistrare</a>
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="py-3 px-4 space-y-1">
            @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Panou principal
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('login')">
                Autentificare
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">
                Înregistrare
            </x-responsive-nav-link>
            @endauth
        </div>

        @auth
        <div class="py-3 px-4 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        Deconectare
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
