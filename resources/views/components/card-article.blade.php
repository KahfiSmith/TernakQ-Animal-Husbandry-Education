@props(['article'])

<a href="{{ route('articles.detail', ['id' => $article->id]) }}"
    class="flex flex-col ring-2 ring-gray-700 overflow-hidden group relative rounded-md shadow-[0px_4px_0px_2px_#374151] hover:shadow-[0px_2px_0px_2px_#374151] hover:translate-y-1 transition ease-in-out duration-200 p-4 bg-cosmicLatte cursor-pointer">
    <div class="flex mb-8">
        <img src="{{ asset('images/ayam.jpg') }}" alt="{{ $article->title }}" class="w-[135px] rounded-md mr-4 object-cover">
        <div class="flex flex-col w-full">
            <div class="mb-4 flex flex-wrap gap-3">
                @forelse ($article->tags as $tag)
                    <span class="text-orangeCrayola bg-orangeCrayola/15 ring-1 ring-orangeCrayola text-sm py-1 px-3 rounded">
                        {{ $tag->name }}
                    </span>
                @empty
                    <span class="text-gray-500 text-sm py-1 px-3">Tanpa Tag</span>
                @endforelse
            </div>
            <h3 class="text-xl font-semibold mb-1">{{ $article->title }}</h3>
            <span class="leading-snug min-h-12 tracking-wide">{{ Str::limit($article->description, 120) }}</span>
        </div>
    </div>
    <div class="flex justify-end text-sm">
        Dipublikasikan pada : {{ $article->created_at->format('d M Y') }}
    </div>
</a>
