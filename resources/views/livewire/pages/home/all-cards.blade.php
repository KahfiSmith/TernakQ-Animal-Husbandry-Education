@extends('layouts.home')

@section('title', 'Semua Konten')

@section('content')
    <section class="flex flex-col min-h-screen mb-12">
        <!-- Navigation -->
        <div class="w-full fixed top-0 z-50 bg-cosmicLatte shadow-md">
            <nav class="flex justify-between py-4 px-14 items-center border-b-2 border-gray-700">
                <!-- Logo -->
                <div>
                    <a href="{{ url('/') }}" wire:navigate>
                        <img src="{{ asset('images/logo.svg') }}" alt="logo" />
                    </a>
                </div>
                <!-- Search Input -->
                <form action="{{ route('cards') }}" method="GET" class="mb-4 flex space-x-4">
                    <x-search-input name="search" placeholder="Cari grup artikel..."
                        value="{{ request()->get('search') }}" />
                </form>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex flex-col px-14 pt-28">
            <!-- Header Section -->
            <div class="flex flex-col mb-7">
                <h2 class="text-3xl font-semibold mb-1">
                    Semua yang Perlu Anda Tahu tentang Peternakan Ayam Broiler
                </h2>
                <p class="font-medium text-lg text-gray-700 leading-relaxed">
                    Jelajahi koleksi lengkap artikel edukasi kami yang mencakup berbagai aspek peternakan ayam broiler,
                    mulai dari manajemen kandang hingga pencegahan penyakit. Tingkatkan pengetahuan dan keterampilan Anda di
                    sini.
                </p>
            </div>

            <!-- Card Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                @forelse ($cardArticles as $card)
                    <x-card-content :card="$card" />
                @empty
                    <p class="col-span-full text-center text-gray-500">
                        Tidak ada konten yang tersedia saat ini.
                    </p>
                @endforelse
            </div>

            <div class="flex justify-center mt-6">
                <div class="w-full max-w-md">
                    {{ $cardArticles->links() }}
                </div>
            </div>
        </div>

        <!-- Floating Home Button -->
        <a href="{{ url('/') }}" wire:navigate>
            <x-primary-button
                class="fixed bottom-6 right-6 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 text-center bg-orangeCrayola py-2.5">
                Kembali ke Home
            </x-primary-button>
        </a>

    </section>
@endsection
