@props(['article'])

<div class="flex flex-col ring-2 ring-gray-700 overflow-hidden group relative rounded-md shadow-[0px_4px_0px_2px_#374151] hover:shadow-[0px_2px_0px_2px_#374151] hover:translate-y-1 transition ease-in-out duration-200 p-4 bg-cosmicLatte cursor-pointer">
    <a href="{{ route('articles.detail', ['id' => $article->id]) }}" class="flex flex-col justify-between">
        <div class="flex mb-8 space-x-6 h-full">
            <img src="{{ asset('storage/' . $article->image) }}" alt="Image"
                class="w-[200px] rounded-md mr-4 object-cover ring-2 ring-gray-300">
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

                <!-- Menampilkan judul sub artikel jika ada -->
                @if ($article->subArticles && $article->subArticles->isNotEmpty())
                    <div class="mt-4">
                        <h4 class="text-lg font-semibold">Sub Artikel:</h4>
                        <ul class="list-disc pl-5">
                            @foreach ($article->subArticles as $subArticle)
                                <li>
                                    <a href="{{ route('articles.detail', ['id' => $article->id, 'sub_article_id' => $subArticle->id]) }}"
                                       class="text-gray-600 hover:underline block">
                                        {{ $subArticle->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex justify-end text-sm mt-4">
            Dipublikasikan pada : {{ $article->created_at->format('d M Y') }}
        </div>
    </a>
</div>
