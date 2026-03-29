<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-[Inter] text-gray-800 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-gray-50">
            <div class="mb-8">
                <a href="/" class="flex items-center gap-2.5 text-2xl font-bold text-gray-900">
                    <span class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-white text-sm font-bold">S</span>
                    ServiceAuto
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-lg shadow-gray-200/50 border border-gray-100 rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
