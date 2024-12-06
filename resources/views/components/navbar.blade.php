<div class="w-full fixed top-0 z-50 backdrop-blur-md">
    <nav class="flex justify-between py-6 px-14 items-center">
        <div>
            <img src="{{ asset('images/logo.svg') }}" alt="logo" />
        </div>
        <ul class="flex flex-row space-x-7 text-base font-medium">
            <x-dropdown :items="[
                ['name' => 'Beranda', 'url' => '#beranda'],
                ['name' => 'Artikel', 'url' => '#artikel'],
                ['name' => 'Forum', 'url' => '#forum'],
                [
                    'name' => 'Informasi',
                    'dropdown' => [
                        ['name' => 'Tentang Kita', 'url' => '#tentang-kita'],
                        ['name' => 'FAQ', 'url' => '#faq'],
                    ],
                ],
            ]" />
        </ul>
        <div class="flex items-center space-x-6">
            <a href="{{ route('login') }}" wire:navigate>
                <x-primary-button class="ring-2 ring-gray-700 hover:bg-pewterBlue bg-transparent">Masuk
                </x-primary-button>
            </a>
            <div class="h-7 border-l-2 border-gray-700"></div>
            <a href="{{ route('register') }}" wire:navigate>
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 bg-orangeCrayola">
                    Daftar
                </x-primary-button>
            </a>
        </div>
    </nav>
</div>