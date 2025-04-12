@extends('layouts.admin-layout')

@section('title', 'Admin - Edit Artikel')

@section('content')
    <main class="w-full flex flex-col space-y-6">
        <nav class="text-sm text-gray-600 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.article-management') }}" wire:navigate
                        class="text-gray-500 hover:text-gray-700  inline-flex items-center ease-in-out duration-300 hover:underline">
                        Manajemen Artikel
                    </a>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li aria-current="page" class="text-gray-500 font-normal">
                    Artikel
                </li>
            </ol>
        </nav>
        <div class="p-6 bg-white/80 rounded-lg shadow-lg ring-2 ring-gray-700">
            <h1 class="text-4xl font-bold text-gray-500 leading-tight mb-4">
                {{ $article->title }}
            </h1>
            @if ($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" alt="Image"
                    class="object-cover rounded-lg shadow-md mb-2 mx-auto max-h-[400px] min-h-[380px] w-full">
            @else
                No Image
            @endif
            <div class="flex items-center text-sm text-gray-500 mb-6 space-x-2">
                <i class="fa-regular fa-calendar text-lg"></i>
                <span>Dipublikasikan pada: {{ $article->created_at->format('d M Y') }}</span>
            </div>
            <div class="prose prose-lg text-gray-700 leading-relaxed mb-8 tracking-wide">
                {{ $article->description }}
            </div>
            @if ($article->subArticles->count() > 0)
                <div class="border-t border-gray-300 pt-6">
                    <div class="space-y-6">
                        @foreach ($article->subArticles as $subArticle)
                            <div id="sub-article-{{ $subArticle->id }}"
                                class="p-4 bg-cosmicLatte rounded-lg shadow-sm transition">
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $subArticle->title }}</h3>
                                <p class="text-gray-600 leading-relaxed tracking-wide mb-1">
                                    {{ $subArticle->content }}
                                </p>
                                @if ($subArticle->image)
                                    <img src="{{ asset('storage/' . $subArticle->image) }}" alt="Image"
                                        class="object-cover rounded mx-auto max-h-[400px] min-h-[380px] w-full">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-gray-500 mt-6">Tidak ada sub-artikel untuk artikel ini.</p>
            @endif
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-4 text-orangeCrayola">Edit Artikel</h2>

            @if (session('status'))
                <div
                    class="p-4 mb-4 text-sm rounded-lg {{ session('status') == 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.article-management.update', $article->id) }}"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Status -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="status" :value="__('Status Artikel')" />
                    <select id="status" name="status"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151]
                                                   focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700
                                                   text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        required>
                        <option value="Disetujui" {{ $article->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ $article->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Catatan -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="catatan" :value="__('Catatan (Opsional)')" />
                    <textarea id="catatan" name="catatan"
                        class="block mt-1 w-full h-[80px] resize-none py-2.5 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                     focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                     rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out">{{ old('catatan', $article->catatan) }}</textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-start">
                    <x-primary-button type="submit"
                        class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                      text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">
                        Simpan Perubahan
                    </x-primary-button>
                    <a href="{{ route('admin.article-management') }}"
                        class="ml-4 bg-gray-500 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                       text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
