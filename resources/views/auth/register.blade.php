<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-900">Creează cont</h2>
        <p class="text-sm text-gray-500 mt-1">Înregistrează-te pentru a folosi platforma</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="'Nume'" />
            <x-text-input id="name" class="block mt-1.5 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Numele tău" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div class="mt-5">
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@exemplu.ro" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="mt-5">
            <x-input-label for="role" :value="'Tip cont'" />
            <select id="role" name="role" class="input-modern mt-1.5" required>
                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                <option value="service" {{ old('role') == 'service' ? 'selected' : '' }}>Service Auto</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" :value="'Parolă'" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minim 8 caractere" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="'Confirmă parola'" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repetă parola" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Înregistrare
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-500">
            Ai deja cont?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Autentifică-te</a>
        </p>
    </form>
</x-guest-layout>
