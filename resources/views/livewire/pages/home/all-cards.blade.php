<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Semua Konten</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <section class="flex flex-col min-h-screen mb-12">
        <div class="w-full fixed top-0 z-50 bg-cosmicLatte">
            <nav class="flex justify-between py-4 px-14 items-center border-b-2 border-gray-700">
                <div>
                    <img src="{{ asset('images/logo.svg') }}" alt="logo" />
                </div>
                <x-search-input placeholder="Cari konten..." />
            </nav>
        </div>
        <div class="flex flex-col px-14 pt-28">
            <div class="flex flex-col mb-7">
                <h2 class="text-3xl font-semibold mb-1">Semua yang Perlu Anda Tahu tentang Peternakan Ayam Broiler</h2>
                <p class="font-medium text-lg">Jelajahi koleksi lengkap artikel edukasi kami yang mencakup berbagai aspek
                    peternakan ayam broiler, mulai dari manajemen kandang hingga pencegahan penyakit. Tingkatkan pengetahuan
                    dan keterampilan Anda di sini.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4">
                @foreach ($cardArticles as $card)
                    <x-card-content :card="$card" />
                @endforeach
            </div>
        </div>
    </section>
</body>

</html>
