@props(['article'])

<div class="flex flex-col ring-2 ring-gray-700 shadow-md overflow-hidden group cursor-pointer relative rounded-md">
    <!-- Gambar Artikel -->
    <img src="{{ asset($article->image ?? 'images/default.jpg') }}" 
         alt="gambar artikel"
         loading="lazy" 
         class="rounded-t-md w-full object-cover transition-transform duration-500 group-hover:scale-110">

    <!-- Konten -->
    <div class="flex flex-col px-4 space-y-1 pb-5 pt-4">
        <h3 class="font-semibold text-xl">{{ $article->title }}</h3>
        <p class="text-sm">{{ Str::limit($article->description, 100) }}</p>
    </div>

    <!-- Info Tambahan -->
    <div class="flex px-4 w-full mb-4 space-x-8">
        <div class="flex space-x-2 items-center">
            <i class="fa-solid fa-clock"></i>
            <span class="text-sm">45 Menit</span>
        </div>
    </div>

    <!-- Aksi -->
    <div class="border-b-2 border-gray-700"></div>
    <div class="flex justify-end px-4 py-3">
        <a href="{{ route('articles.show', $article->id) }}" 
           class="flex space-x-2 justify-center items-center">
            <span class="text-sm">Baca artikel</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
