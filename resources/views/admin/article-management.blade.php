@extends('layouts.admin-layout')

@section('title', 'Admin - Manajemen Artikel')

@section('content')
    <main class="w-full">
        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Artikel</h2>

            @if (session('status'))
                <div
                    class="p-4 mb-4 text-sm rounded-lg {{ session('status') == 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ session('message') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Judul Artikel</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Catatan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Gambar</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($articles as $article)
                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $article->title }}</td>
                                <td class="px-4 py-3">{{ Str::limit($article->description, 100) }}</td>
                                <td class="px-4 py-3">{{ $article->catatan ? Str::limit($article->catatan, 100) : '-' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-3 py-1 rounded text-xs font-semibold 
                                        {{ $article->status == 'Tertunda' ? 'bg-yellow-100 text-yellow-700' : ($article->status == 'Disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $article->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" alt="Image"
                                            class="w-24 h-24 object-cover rounded mx-auto">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.article-management.edit', $article->id) }}"
                                        class="px-2 py-3 rounded font-semibold bg-gray-300 text-white-700 flex justify-center items-center gap-2 cursor-pointer">
                                        <span><i class="fa-solid fa-pen-clip"></i></span>
                                        <p>Review</p>
                                     </a>                                     
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $articles->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
