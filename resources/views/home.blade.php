<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="/images/logo.svg" type="image/png">

    <title>Laravel</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="antialiased">
    <div class="flex flex-col">
        <x-navbar />
        @livewire('pages.home.hero')

        <section id="article" class="flex flex-col px-14 mb-20 pt-20">
            <div class="flex flex-col mb-7">
                <h2 class="text-3xl font-semibold mb-1">Artikel Terbaru</h2>
                <p class="font-medium text-lg">
                    Lihat langkah-langkah membangun peternakan ayam broiler yang sukses.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4">
                @foreach ($cardArticles as $card)
                    <x-card-content :card="$card" />
                @endforeach
            </div>

            <div class="flex justify-end">
                <a href="{{ route('cards') }}"
                    class="cursor-pointer hover:border-b-2 hover:border-gray-700 font-semibold hover:text-orangeCrayola transition duration-200 ease-in-out">
                    Lihat semua artikel...
                </a>
            </div>
        </section>

        @livewire('pages.home.forum')
        @livewire('pages.home.about-us')
        @livewire('pages.home.faq')
        @livewire('pages.home.footer')
    </div>
</body>

</html>
