@extends('layouts.home')

@section('title', 'Semua Artikel')

@section('content')
    <section class="flex flex-col min-h-screen mb-12">
        <div class="w-full fixed top-0 z-50 bg-cosmicLatte">
            <nav class="flex justify-between py-4 px-14 items-center border-b-2 border-gray-700">
                <div>
                    <a href="{{ url('/') }}" wire:navigate>
                        <img src="{{ asset('images/logo.svg') }}" alt="logo" />
                    </a>
                </div>
                <x-search-input placeholder="Cari artikel..." />
            </nav>
        </div>

        <div class="flex flex-col px-14 pt-28">
            <nav class="text-sm text-gray-600 mb-4 font-medium" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('cards') }}" wire:navigate
                            class="text-gray-500 hover:text-gray-700 inline-flex items-center ease-in-out duration-300 hover:underline">
                            Grup Artikel
                        </a>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                    <li aria-current="page" class="text-gray-500 font-normal">
                        @if(isset($card))
                            {{ $card->title }}
                        @else
                            Card tidak ditemukan
                        @endif
                    </li>
                </ol>
            </nav>

            @if(isset($card))
                <div class="flex space-x-8 mb-9">
                    <img src="{{ asset('storage/' . $card->image) }}" alt="Image"
                        class="w-[450px] h-[300px] rounded-md ring-2 ring-gray-300 object-cover">
                    <div class="flex flex-col mb-7">
                        <h2 class="text-4xl font-semibold mb-3">{{ $card->title }}</h2>
                        <p class="text-lg mb-7 leading-relaxed tracking-wide">{{ $card->description }} </p>
                        <div class="flex w-full mb-4 space-x-11">
                            <div class="flex space-x-2 items-center">
                                <i class="fa-solid fa-receipt text-2xl text-gray-600"></i>
                                <span class="text-base">{{ $card->articles->count() }} Artikel</span>
                            </div>
                            <div class="flex space-x-2 items-center">
                                <i class="fa-solid fa-clock text-2xl text-gray-600"></i>
                                <span class="text-base">{{ $card->readingTime }} Menit</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-xl">Card artikel tidak ditemukan.</p>
                </div>
            @endif

            <div class="flex flex-col">
                <h2 class="text-3xl font-semibold mb-4">Semua Artikel</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    @if(isset($articles) && $articles->isNotEmpty())
                        @foreach ($articles as $article)
                            <x-card-article :article="$article" />
                        @endforeach
                    @else
                        <p class="text-gray-500">Belum ada artikel.</p>
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ url('/') }}" wire:navigate>
            <x-primary-button
                class="fixed bottom-6 right-6 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 text-center bg-orangeCrayola py-2.5">
                Kembali ke Home
            </x-primary-button>
        </a>
    </section>
@endsection
