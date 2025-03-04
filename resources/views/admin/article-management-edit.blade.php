@extends('layouts.admin-layout')

@section('title', 'Admin - Edit Artikel')

@section('content')
    <main class="w-full">
        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-4 text-orangeCrayola">Edit Artikel</h2>

            @if (session('status'))
                <div
                    class="p-4 mb-4 text-sm rounded-lg {{ session('status') == 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.article.update', $article->id) }}" enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Judul Artikel -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="title" :value="__('Judul Artikel')" />
                    <x-text-input id="title" name="title" type="text"
                                  value="{{ old('title', $article->title) }}"
                                  class="block mt-1 w-full py-2.5" required />
                </div>

                <!-- Deskripsi Artikel -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="description" :value="__('Deskripsi')" />
                    <textarea id="description" name="description"
                              class="block mt-1 w-full h-[100px] resize-none py-2.5 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                     focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                     rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out"
                              required>{{ old('description', $article->description) }}</textarea>
                </div>

                <!-- Catatan -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="catatan" :value="__('Catatan (Opsional)')" />
                    <textarea id="catatan" name="catatan"
                              class="block mt-1 w-full h-[80px] resize-none py-2.5 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                     focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                     rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out">{{ old('catatan', $article->catatan) }}</textarea>
                </div>

                <!-- Status -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="status" :value="__('Status Artikel')" />
                    <select id="status" name="status"
                            class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151]
                                   focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700
                                   text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5" required>
                        <option value="Tertunda" {{ $article->status == 'Tertunda' ? 'selected' : '' }}>Tertunda</option>
                        <option value="Disetujui" {{ $article->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak" {{ $article->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Gambar -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="image" :value="__('Gambar (Opsional)')" />
                    <div x-data="{ imagePreview: '{{ asset('storage/' . $article->image) }}' }" class="relative w-full">
                        <label for="image"
                               class="cursor-pointer flex flex-col items-center justify-center border-2 border-gray-700 
                                      shadow-[4px_4px_0px_2px_#374151] rounded-md p-6 hover:bg-gray-100 transition duration-150 ease-in-out">
                            <div x-show="!imagePreview" class="flex flex-col items-center space-y-2">
                                <i class="fa-solid fa-image text-4xl"></i>
                                <span class="text-gray-700 font-medium">Klik untuk unggah gambar</span>
                            </div>

                            <div x-show="imagePreview" class="relative w-full flex justify-center">
                                <img :src="imagePreview" class="w-[250px] h-[200px] rounded-md shadow-md object-cover" />
                                <button type="button"
                                        class="absolute top-0 right-0 bg-gray-800 text-white rounded-full p-1 -mt-2 -mr-2 shadow-md hover:bg-red-600 transition w-8 h-8"
                                        @click="imagePreview = null; document.getElementById('image').value = ''">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }" />
                    </div>
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
