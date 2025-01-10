@extends('layouts.home')

@section('title', 'Semua Konten')

@section('content')
<section class="flex flex-col min-h-screen mb-12">
    <!-- Navigation -->
    <div class="w-full fixed top-0 z-50 bg-cosmicLatte shadow-md">
        <nav class="flex justify-between py-4 px-14 items-center border-b-2 border-gray-700">
            <!-- Logo -->
            <div>
                <img src="{{ asset('images/logo.svg') }}" alt="logo" />
            </div>
            <!-- Search Input -->
            <x-search-input placeholder="Cari konten..." />
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
                mulai dari manajemen kandang hingga pencegahan penyakit. Tingkatkan pengetahuan dan keterampilan Anda di sini.
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
    </div>
</section>
@endsection
