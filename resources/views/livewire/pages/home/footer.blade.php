<footer class="flex flex-col bg-pewterBlue px-14 py-10">
    <div class="flex justify-between items-start mb-16">
        <div class="flex flex-col space-y-1">
            <h2 class="text-2xl font-medium">Edukasi dan Solusi Peternakan Ayam Broiler</h2>
            <p class="font-medium">Langkah cerdas bagi peternak untuk meningkatkan produktivitas dan
                efisiensi usaha mereka.</p>
        </div>
        <div class="flex flex-col space-y-4">
            <h2 class="text-2xl font-medium">Informasi</h2>
            <ul class="flex flex-col space-y-2">
                <li>
                    <a href="#tentang-kami" class="font-medium text-gray-700 hover:font-semibold">Tentang Kami</a>
                </li>
                <li>
                    <a href="#tentang-kami" class="font-medium text-gray-700 hover:font-semibold">FAQ</a>
                </li>
            </ul>
        </div>
        <div class="flex flex-col space-y-4">
            <h2 class="text-2xl font-medium">Layanan</h2>
            <ul class="flex flex-col space-y-2">
                <li>
                    <a href="#tentang-kami" class="font-medium text-gray-700 hover:font-semibold">Artikel</a>
                </li>
                <li>
                    <a href="#tentang-kami" class="font-medium text-gray-700 hover:font-semibold">Forum</a>
                </li>
            </ul>
        </div>
        <div class="flex flex-col space-y-4">
            <h2 class="text-2xl font-medium">Kontak Kami</h2>
            <ul class="flex flex-col space-y-2">
                <li>
                    <a href="#tentang-kami"
                        class="flex space-x-2 font-medium text-gray-700 hover:font-semibold items-center">
                        <x-feathericon-phone class="w-5 h-5" />
                        <span>+6281264385621</span>
                    </a>
                </li>
                <li>
                    <a href="#tentang-kami"
                        class="flex space-x-2 font-medium text-gray-700 hover:font-semibold items-center">
                        <x-tabler-mail class="w-6 h-6" />
                        <span>ternakq@gmail.com</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="border-b-2 border-gray-700"></div>
    <div class="flex justify-between items-start pt-4">
        <div class="flex space-x-4">
            <a href="https://www.linkedin.com/in/mohamad-al-kahfi-b48107271/" target="_blank" class="bg-cosmicLatte p-2 rounded-full flex justify-center items-center">
                <x-bi-linkedin class="w-6 h-6 text-polishedPine" />
            </a>
            <a href="https://www.facebook.com/groups/270376331661364" target="_blank" class="bg-cosmicLatte p-2 rounded-full flex justify-center items-center">
                <x-bi-facebook class="w-6 h-6 text-polishedPine" />
            </a>
            <a href="https://www.instagram.com/alkaahfi__/" target="_blank" class="bg-cosmicLatte p-2 rounded-full flex justify-center items-center">
                <x-bi-instagram class="w-6 h-6 text-polishedPine" />
            </a>
            <a href="https://github.com/KahfiSmith" target="_blank" class="bg-cosmicLatte p-2 rounded-full flex justify-center items-center">
                <x-bi-github class="w-6 h-6 text-polishedPine" />
            </a>
        </div>
        <span x-data="{ year: new Date().getFullYear() }" class="font-medium">
            &copy;<span x-text="year"></span> TernakQ
        </span>
    </div>
</footer>