<section id="faq" class="flex flex-col px-14 mb-20">
    <div class="flex flex-col space-y-4 justify-center mx-auto mb-14">
        <h2 class="text-3xl font-semibold text-center">Pertanyaan yang Sering Diajukan</h2>
        <p class="font-medium text-xl text-center">Kami di sini untuk membantu menjawab semua pertanyaan Anda</p>
    </div>
    <div class="flex flex-col justify-center items-center space-y-4">
        <div x-data="{ open: false }" class="flex flex-col space-y-4 w-full max-w-3xl">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer">
                <div class="flex space-x-6 items-center">
                    <img src="{{ asset('images/icons8-farmer.svg') }}" alt="icon"
                        class="w-12 h-12 ring-2 ring-gray-700 p-1.5 rounded-lg">
                    <span class="font-medium text-lg">Apa itu TernakQ?</span>
                </div>
                <i class="fa-solid fa-chevron-down ml-2 text-gray-700 transition-transform duration-300 ease-in-out"
                    :class="{ '-rotate-180': open }"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 ease-in-out" x-ref="content"
                x-bind:style="open ? 'max-height: ' + $refs.content.scrollHeight + 'px' : 'max-height: 0;'">
                <p class="text-gray-700 text-base leading-relaxed">
                    TernakQ adalah platform edukasi daring yang menyediakan informasi praktis untuk mendukung peternakan
                    ayam broiler.
                </p>
            </div>
        </div>
        <div x-data="{ open: false }" class="flex flex-col space-y-4 w-full max-w-3xl">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer">
                <div class="flex space-x-6 items-center">
                    <img src="{{ asset('images/icons8-guidebook.svg') }}" alt="icon"
                        class="w-12 h-12 ring-2 ring-gray-700 p-1.5 rounded-lg">
                    <span class="font-medium text-lg">Apakah website ini memberikan panduan lengkap untuk pemula?</span>
                </div>
                <i class="fa-solid fa-chevron-down ml-2 text-gray-700 transition-transform duration-300 ease-in-out"
                    :class="{ '-rotate-180': open }"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 ease-in-out" x-ref="content"
                x-bind:style="open ? 'max-height: ' + $refs.content.scrollHeight + 'px' : 'max-height: 0;'">
                <p class="text-gray-700 text-base leading-relaxed">
                    Ya, kami menyediakan artikel edukasi, tips, dan panduan langkah demi langkah bagi pemula untuk
                    memulai peternakan ayam broiler.
                </p>
            </div>
        </div>
        <div x-data="{ open: false }" class="flex flex-col space-y-4 w-full max-w-3xl">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer">
                <div class="flex space-x-6 items-center">
                    <img src="{{ asset('images/icons8-disease.svg') }}" alt="icon"
                        class="w-12 h-12 ring-2 ring-gray-700 p-1.5 rounded-lg">
                    <span class="font-medium text-lg">Apakah ada informasi tentang cara mencegah penyakit pada
                        ayam?</span>
                </div>
                <i class="fa-solid fa-chevron-down ml-2 text-gray-700 transition-transform duration-300 ease-in-out"
                    :class="{ '-rotate-180': open }"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 ease-in-out" x-ref="content"
                x-bind:style="open ? 'max-height: ' + $refs.content.scrollHeight + 'px' : 'max-height: 0;'">
                <p class="text-gray-700 text-base leading-relaxed">
                    Tentu! Kami memiliki artikel dan diskusi di forum yang membahas cara mencegah serta menangani
                    penyakit umum pada ayam broiler.
                </p>
            </div>
        </div>
        <div x-data="{ open: false }" class="flex flex-col space-y-4 w-full max-w-3xl">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer">
                <div class="flex space-x-6 items-center">
                    <img src="{{ asset('images/icons8-market.svg') }}" alt="icon"
                        class="w-12 h-12 ring-2 ring-gray-700 p-1.5 rounded-lg">
                    <span class="font-medium text-lg">Bisakah saya mendapatkan informasi tentang pemasaran hasil
                        ternak?</span>
                </div>
                <i class="fa-solid fa-chevron-down ml-2 text-gray-700 transition-transform duration-300 ease-in-out"
                    :class="{ '-rotate-180': open }"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 ease-in-out" x-ref="content"
                x-bind:style="open ? 'max-height: ' + $refs.content.scrollHeight + 'px' : 'max-height: 0;'">
                <p class="text-gray-700 text-base leading-relaxed">
                    Kami belum menyediakan informasi terkait hal tersebut di platform kami. Kami akan terus berusaha
                    memperbarui dan menambah fitur serta informasi yang relevan di masa mendatang.
                </p>
            </div>
        </div>
        <div x-data="{ open: false }" class="flex flex-col space-y-4 w-full max-w-3xl">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer">
                <div class="flex space-x-6 items-center">
                    <img src="{{ asset('images/icons8-forum.svg') }}" alt="icon"
                        class="w-12 h-12 ring-2 ring-gray-700 p-1.5 rounded-lg">
                    <span class="font-medium text-lg">Bagaimana cara bergabung di forum diskusi?</span>
                </div>
                <i class="fa-solid fa-chevron-down ml-2 text-gray-700 transition-transform duration-300 ease-in-out"
                    :class="{ '-rotate-180': open }"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 ease-in-out" x-ref="content"
                x-bind:style="open ? 'max-height: ' + $refs.content.scrollHeight + 'px' : 'max-height: 0;'">
                <p class="text-gray-700 text-base leading-relaxed">
                    Anda dapat mendaftar akun di website kami, lalu masuk ke menu Forum untuk bergabung dengan komunitas
                    peternak ayam broiler.
                </p>
            </div>
        </div>
        <div x-data="{ open: false }" class="flex flex-col space-y-4 w-full max-w-3xl">
            <div @click="open = !open" class="flex items-center justify-between cursor-pointer">
                <div class="flex space-x-6 items-center">
                    <img src="{{ asset('images/icons8-rupiah.svg') }}" alt="icon"
                        class="w-12 h-12 ring-2 ring-gray-700 p-1.5 rounded-lg">
                    <span class="font-medium text-lg">Apakah ada biaya untuk menggunakan website ini?</span>
                </div>
                <i class="fa-solid fa-chevron-down ml-2 text-gray-700 transition-transform duration-300 ease-in-out"
                    :class="{ '-rotate-180': open }"></i>
            </div>
            <div class="overflow-hidden transition-all duration-300 ease-in-out" x-ref="content"
                x-bind:style="open ? 'max-height: ' + $refs.content.scrollHeight + 'px' : 'max-height: 0;'">
                <p class="text-gray-700 text-base leading-relaxed">
                    Semua fitur di website kami sepenuhnya gratis dan dapat diakses tanpa dipungut biaya. Kami
                    berkomitmen untuk menyediakan edukasi dan informasi peternakan ayam broiler secara bebas agar dapat
                    dimanfaatkan oleh semua peternak tanpa hambatan.
                </p>
            </div>
        </div>
    </div>
</section>
