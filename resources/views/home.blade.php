<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Laravel</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="flex flex-col">
        <x-navbar />
        @livewire('pages.home.hero')
        @livewire('pages.home.article')
        @livewire('pages.home.forum')
        @livewire('pages.home.about-us')
        @livewire('pages.home.faq')
        @livewire('pages.home.footer')
    </div>
</body>

</html>
