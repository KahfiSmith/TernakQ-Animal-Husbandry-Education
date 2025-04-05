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
        <section id="about-us" class="flex justify-between items-center px-14 mb-20 pt-20">
            <img src="{{ asset('images/about-us.svg') }}" alt="icon" class="w-[45%]" loading="lazy" >
            <div class="flex flex-col space-y-5 w-[45%]">
                <h1 class="text-3xl font-bold tracking-wide leading-tight">Membangun Peternakan yang <span
                        class="text-orange-500">Lebih Baik</span>, Bersama Kami</h1>
                <p class="font-medium text-lg leading-7">TernakQ adalah platform edukasi dan komunitas daring yang hadir untuk
                    mendukung peternak ayam broiler dalam meningkatkan kualitas peternakan mereka. Kami percaya bahwa pendidikan
                    dan berbagi pengalaman adalah kunci untuk menciptakan peternakan yang sukses dan berkelanjutan.</p>
                <div class="flex space-x-8">
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <span class="font-semibold text-4xl">{{ $totalArticles }}</span>
                            <span class="font-medium text-2xl">+</span>
                        </div>
                        <span class="font-medium">Artikel Peternakan</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <span class="font-semibold text-4xl">{{ $totalUsers }}</span>
                            <span class="font-medium text-2xl">+</span>
                        </div>
                        <span class="font-medium">Pendaftar Baru</span>
                    </div>
                </div>
            </div>
        </section>
        
        @livewire('pages.home.faq')
        @livewire('pages.home.footer')
    </div>
</body>

</html>
