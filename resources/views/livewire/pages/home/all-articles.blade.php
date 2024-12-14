<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Semua Artikel</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <section class="flex flex-col min-h-screen">
        <div class="w-full fixed top-0 z-50 backdrop-blur-md">
            <nav class="flex justify-between py-6 px-14 items-center border-b-2 border-gray-700">
                <div>
                    <img src="{{ asset('images/logo.svg') }}" alt="logo" />
                </div>
                <x-search-input placeholder="Cari artikel..." />
            </nav>
        </div>
    </section>
</body>

</html>
