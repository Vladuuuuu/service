<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Mulțumim că te-ai înregistrat! Înainte de a continua, te rugăm să verifici adresa de email accesând linkul trimis. Dacă nu l-ai primit, îți putem retrimite unul.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Un nou link de verificare a fost trimis la adresa de email introdusă la înregistrare.

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Retrimite emailul de verificare
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Deconectează-te
            </button>
        </form>
    </div>
</x-guest-layout>
