<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased p-6">
    <section class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-lg ring-2 ring-gray-300">
        <div class="flex">
            <nav class="text-sm text-gray-600 mb-4 font-medium" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <!-- Breadcrumb Beranda -->
                    <li class="inline-flex items-center">
                        <a href="{{ route('cards') }}"
                            class="text-gray-500 hover:text-gray-700 inline-flex items-center ease-in-out duration-300">
                            Konten
                        </a>
                    </li>

                    <!-- Separator -->
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>

                    <!-- Breadcrumb Articles -->
                    <li class="inline-flex items-center">
                        <a href="{{ route('cards.articles', ['id' => $article->cardArticle->id]) }}"
                            class="text-gray-500 hover:text-gray-700 ease-in-out duration-300">
                            Artikel
                        </a>
                    </li>

                    <!-- Separator -->
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>

                    <!-- Breadcrumb Current Page -->
                    <li aria-current="page" class="text-gray-500 font-normal">{{ $article->title }}</li>
                </ol>
            </nav>
        </div>
        <!-- Judul Artikel -->
        <h1 class="text-4xl font-extrabold text-gray-500 leading-tight mb-4">
            {{ $article->title }}
        </h1>

        <!-- Gambar Artikel -->
        <img src="{{ asset('images/ayam.jpg') }}" alt="gambar ayam"
            class="w-full h-full object-cover rounded-lg shadow-md mb-6">

        <!-- Tanggal Publikasi -->
        <div class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
            <i class="fa-regular fa-calendar text-lg"></i>
            <span>Dipublikasikan pada: {{ $article->created_at->format('d M Y') }}</span>
        </div>

        <!-- Deskripsi Artikel -->
        <div class="prose prose-lg text-gray-700 leading-relaxed mb-8 tracking-wide">
            {{ $article->description }} Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quasi, totam? Quod quo
            sunt asperiores, minima temporibus mollitia consequatur cumque, doloremque ab, natus quia et odio placeat
            exercitationem quaerat recusandae est.
        </div>

        <!-- Sub-Artikel -->
        @if ($article->subArticles->count() > 0)
            <div class="border-t border-gray-300 pt-6">
                <div class="space-y-6">
                    @foreach ($article->subArticles as $subArticle)
                        <div class="p-4 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-50 transition">
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $subArticle->title }}</h3>
                            <p class="text-gray-600 leading-relaxed tracking-wide">
                                {{ $subArticle->content }} Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                Repellendus quis eum expedita voluptatibus, sequi facere odio nemo, obcaecati veritatis
                                praesentium nobis! Eius vel iusto, labore hic ab vitae quos aperiam tempore iure
                                nesciunt perferendis maxime quasi error in molestias aliquam, cumque rem quis
                                architecto! Vitae, harum quod. Odio, dignissimos necessitatibus!
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-gray-500 mt-6">Tidak ada sub-artikel untuk artikel ini.</p>
        @endif
    </section>
</body>

</html>
