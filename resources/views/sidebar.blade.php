<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/sidebar.js'])
</head>

<body class="flex antialiased min-h-screen">

    <div>
        @php
            $menus = [
                [
                    'title' => 'Dashboard',
                    'url' => route('dashboard'),
                    'icon' => asset('images/menu.svg'),
                    'active' => request()->routeIs('dashboard'),
                ],
                [
                    'title' => 'Manajemen Ayam',
                    'url' => route('dashboard'),
                    'icon' => asset('images/chicken.svg'),
                    'active' => request()->routeIs('dashboard'),
                ],
                [
                    'title' => 'Manajemen Kandang',
                    'url' => route('dashboard'),
                    'icon' => asset('images/cage.svg'),
                    'active' => request()->routeIs('dashboard'),
                ],
                [
                    'title' => 'Manajemen Pakan',
                    'url' => route('dashboard'),
                    'icon' => asset('images/feed.svg'),
                    'active' => request()->routeIs('dashboard'),
                ],
                [
                    'title' => 'Keuangan',
                    'url' => route('dashboard'),
                    'icon' => asset('images/money.svg'),
                    'active' => request()->routeIs('dashboard'),
                ],
            ];

            $bottomMenus = [
                [
                    'title' => 'Profile',
                    'url' => route('profile'),
                    'icon' => asset('images/cage.svg'),
                    'active' => request()->routeIs('profile'),
                ],
                [
                    'title' => 'Logout',
                    'url' => route('profile'),
                    'icon' => asset('images/cage.svg'),
                    'active' => request()->routeIs('profile'),
                ],
            ];
        @endphp

        <x-sidebar.sidebar :menus="$menus" :bottomMenus="$bottomMenus" />
    </div>

    <div id="content" class="min-h-screen w-full p-6 transition-all duration-500">
        <main>
            <div class="bg-white p-6 rounded-md shadow-sm">
                <h2 class="text-lg font-semibold mb-2">Content Title</h2>
                <p class="text-gray-600">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam suscipit fuga error esse aut blanditiis
                    repellat quia. Magnam, impedit similique!
                </p>
            </div>
        </main>
    </div>

</body>

</html>
