@props(['card'])

<a href="{{ route('cards.articles', ['id' => $card->id]) }}"
    class="flex flex-col ring-2 ring-gray-700 overflow-hidden group relative rounded-md
            h-full min-h-[420px] cursor-pointer shadow-[0px_4px_0px_2px_#374151] hover:shadow-[0px_2px_0px_2px_#374151] hover:translate-y-1 transition ease-in-out duration-200 bg-white/50">
            <img src="{{ asset('storage/' . $card->image) }}" alt="Image" loading="lazy"
        class="rounded-t-md w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
    <div class="flex flex-col flex-grow px-4 space-y-1 pb-5 pt-4">
        <h3 class="font-semibold text-xl line-clamp-2">{{ $card->title }}</h3>
        <p class="text-sm flex-grow line-clamp-3">
            {{ Str::limit($card->description, 120) }}
        </p>
    </div>
    <div class="flex px-4 w-full mb-4 space-x-8">
        <div class="flex space-x-2 items-center">
            <i class="fa-solid fa-receipt"></i>
            <span class="text-sm">{{ $card->articles_count }} Artikel</span>
        </div>
        <div class="flex space-x-2 items-center">
            <i class="fa-solid fa-clock"></i>
            <span class="text-sm">{{ $card->readingTime }} Menit</span>
        </div>
    </div>
    <div class="border-b-2 border-gray-700"></div>
    <div class="flex justify-end px-4 py-3 mt-auto">
        <div class="flex space-x-2 items-center text-sm">
            <span>Baca artikel</span>
            <i class="fa-solid fa-arrow-right flex items-center h-6"></i>
        </div>
    </div>
</a>
