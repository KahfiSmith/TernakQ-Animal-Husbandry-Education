<aside class="fixed top-0 left-0 h-full w-64 bg-polishedPine text-gray-100 shadow-lg border-r-2 border-white">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-center h-16 bg-polishedPine">
        <img id="logo" src="{{ asset('images/logo.svg') }}" class="w-24 duration-1000 transition-all ease-in-out">
    </div>

    <!-- Sidebar Menu -->
    <nav class="px-4 py-6">
        @php
            $menus = [
                [
                    'title' => 'Dashboard',
                    'url' => route('dashboard'),
                    'icon' => asset('images/menu.svg'),
                    'active' => request()->routeIs('dashboard*'),
                ],
                [
                    'title' => 'Manajemen Ayam',
                    'url' => route('chicken-management'),
                    'icon' => asset('images/chicken.svg'),
                    'active' => request()->routeIs('chicken-management*'),
                ],
                [
                    'title' => 'Manajemen Kandang',
                    'url' => route('cage-management'),
                    'icon' => asset('images/cage.svg'),
                    'active' => request()->routeIs('cage-management*'),
                ],
                [
                    'title' => 'Manajemen Pakan',
                    'url' => route('food-management'),
                    'icon' => asset('images/feed.svg'),
                    'active' => request()->routeIs('food-management*'),
                ],
                [
                    'title' => 'Keuangan',
                    'url' => route('finance'),
                    'icon' => asset('images/money.svg'),
                    'active' => request()->routeIs('finance*'),
                ],
            ];
        @endphp

        <ul class="space-y-4">
            @foreach ($menus as $menu)
                <li>
                    <a href="{{ $menu['url'] }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-md font-semibold transition hover:bg-pewterBlue hover:text-white {{ $menu['active'] ? 'bg-pewterBlue' : '' }}">
                        <img src="{{ $menu['icon'] }}" alt="{{ $menu['title'] }} Icon" class="w-6 h-6">
                        <span>{{ $menu['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
