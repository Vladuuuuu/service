<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Bine ai revenit</h2>
        <p class="text-sm text-gray-500 mt-1">Conectează-te la contul tău</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@exemplu.ro" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" :value="'Parolă'" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-500">Ține-mă minte</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" href="{{ route('password.request') }}">
                    Ai uitat parola?
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Autentifică-te
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-500">
            Nu ai cont?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Înregistrează-te</a>
        </p>
    </form>
</x-guest-layout>
