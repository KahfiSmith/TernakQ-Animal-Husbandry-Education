<section id="forum" class="flex flex-col space-y-2 mb-20 pt-20">
    <div class="flex flex-col bg-pewterBlue px-14 py-11">
        <h2 class="text-3xl font-semibold mb-11 text-center">Memulai Peternakan Ayam Broiler</h2>
        <div class="flex justify-between space-x-20">
            <div
                class="flex flex-col justify-center items-center p-4 ring-2 rounded-md ring-gray-700 shadow-[6px_6px_0px_4px_#374151] space-y-3">
                <img src="{{ asset('images/icons8-question-and-answer.svg') }}" alt="icon" class="w-14 h-14">
                <h3 class="text-center font-semibold text-2xl">Tanya Jawab dan Berbagi Pengalaman</h3>
                <span class="text-center text-lg">Ajukan pertanyaan, temukan jawaban, dan pelajari pengalaman peternak
                    lainnya untuk mengatasi
                    berbagai tantangan.</span>
            </div>
            <div
                class="flex flex-col justify-center items-center p-4 ring-2 rounded-md ring-gray-700 shadow-[6px_6px_0px_4px_#374151] space-y-3">
                <img src="{{ asset('images/icons8-solution.svg') }}" alt="icon" class="w-14 h-14">
                <h3 class="text-center font-semibold text-2xl">Solusi dari Peternak untuk Peternak</h3>
                <span class="text-center text-lg">Dapatkan saran langsung dari peternak berpengalaman atau bantu
                    menjawab pertanyaan sesama anggota
                    komunitas.</span>
            </div>
        </div>
    </div>
    <div class="flex justify-between items-center space-x-14 px-14">
        <div class="flex flex-col space-y-5 w-[55%]">
            <h1 class="text-3xl font-bold tracking-wide leading-tight">Bersama Membangun Komunitas Peternak Ayam Broiler
                yang <span class="text-orange-500">Solid dan Berdaya Saing</span></h1>
            <p class="font-medium text-lg">Temukan solusi, berbagi pengalaman, dan bangun kolaborasi dengan
                sesama peternak untuk mencapai hasil yang lebih baik.</p>
            <a href="#artikel">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 bg-orangeCrayola">
                    Coba Forum
                </x-primary-button>
            </a>
        </div>
        <img src="{{ asset('images/forum.png') }}" alt="icon" class="w-[40%]" loading="lazy">
    </div>
</section>
