<section id="article" class="flex flex-col px-14 mb-20 pt-20">
    <div class="flex flex-col mb-7">
        <h2 class="text-3xl font-semibold mb-1">Memulai Peternakan Ayam Broiler</h2>
        <p class="font-medium text-lg">Pelajari langkah-langkah awal untuk membangun peternakan ayam broiler yang
            sukses. Dari perencanaan hingga
            persiapan kandang, semua informasi yang Anda butuhkan ada di sini.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4">
        @foreach($articles as $article)
            <x-card-article :article="$article" />
        @endforeach
    </div>
    <div class="flex justify-end">
        <a 
            wire:navigate 
            href="{{ route('all-articles') }}" 
            class="cursor-pointer hover:border-b-2 hover:border-gray-700 font-semibold hover:text-orangeCrayola transition duration-200 ease-in-out"
        >
            Lihat semua artikel...
        </a>
    </div>
</section>
