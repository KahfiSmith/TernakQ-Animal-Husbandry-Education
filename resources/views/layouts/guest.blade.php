<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cosmicLatte">
        <div class="w-full sm:max-w-md mt-6 px-6 py-12 bg-white overflow-hidden sm:rounded-lg ring-2 ring-gray-700">
            <div class="flex flex-col justify-center items-center mb-10 space-y-2">
                <a href="/" wire:navigate>
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <h2 class="text-base font-medium text-gray-700">Solusi Tepat untuk Peternak Cerdas.</h2>
            </div>
            {{ $slot }}
        </div>
    </div>
</body>

</html>
