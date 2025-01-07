<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/sidebar.js'])
</head>

<body class="flex antialiased min-h-screen">

    <!-- Sidebar -->
    <div>
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
                    'url' => route('dashboard'),
                    'icon' => asset('images/chicken.svg'),
                    'active' => request()->routeIs('chicken-management*'),
                ],
                [
                    'title' => 'Manajemen Kandang',
                    'url' => route('dashboard'),
                    'icon' => asset('images/cage.svg'),
                    'active' => request()->routeIs('cage-management*'),
                ],
                [
                    'title' => 'Manajemen Pakan',
                    'url' => route('dashboard'),
                    'icon' => asset('images/feed.svg'),
                    'active' => request()->routeIs('food-management*'),
                ],
                [
                    'title' => 'Keuangan',
                    'url' => route('dashboard'),
                    'icon' => asset('images/money.svg'),
                    'active' => request()->routeIs('finance*'),
                ],
            ];

            $bottomMenus = [
                [
                    'title' => 'Profile',
                    'url' => route('profile'),
                    'icon' => asset('images/profile.svg'),
                    'active' => request()->routeIs('profile'),
                ],
                [
                    'title' => 'Logout',
                    'url' => route('logout'),
                    'icon' => asset('images/logout.svg'),
                    'active' => false,
                ],
            ];
        @endphp

        <x-sidebar.sidebar :menus="$menus" :bottomMenus="$bottomMenus" />
    </div>

    <!-- Main Content -->
    <div id="content" class="min-h-screen w-full p-6 transition-all duration-500">
        @yield('content')
    </div>

</body>

</html>
