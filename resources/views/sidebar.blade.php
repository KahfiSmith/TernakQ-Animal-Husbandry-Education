<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/charts/barChart.js', 'resources/js/charts/pieChart.js', 'resources/js/sidebar.js'])

</head>

<body class="flex antialiased min-h-screen">

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
                    'active' => request()->routeIs('dashboard*'),
                ],
                [
                    'title' => 'Manajemen Kandang',
                    'url' => route('dashboard'),
                    'icon' => asset('images/cage.svg'),
                    'active' => request()->routeIs('dashboard*'),
                ],
                [
                    'title' => 'Manajemen Pakan',
                    'url' => route('dashboard'),
                    'icon' => asset('images/feed.svg'),
                    'active' => request()->routeIs('dashboard*'),
                ],
                [
                    'title' => 'Keuangan',
                    'url' => route('dashboard'),
                    'icon' => asset('images/money.svg'),
                    'active' => request()->routeIs('dashboard*'),
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
                    'url' => route('profile'),
                    'icon' => asset('images/logout.svg'),
                    'active' => request()->routeIs('profile'),
                ],
            ];
        @endphp

        <x-sidebar.sidebar :menus="$menus" :bottomMenus="$bottomMenus" />
    </div>

    <div id="content" class="min-h-screen w-full p-6 transition-all duration-500">
        <main class="gap-4 flex flex-col">
            <div class="flex gap-4 w-full justify-between">
                <div class="flex flex-col justify-between w-full">
                    <div class="bg-white px-6 py-6 rounded-lg shadow-md flex flex-col items-center w-full space-y-6">
                        <div class="flex justify-between w-full items-center">
                            <div class="flex flex-col space-y-1">
                                <h3 class="text-md font-semibold text-gray-500 uppercase tracking-wide">Jumlah Kandang
                                </h3>
                                <span class="text-4xl font-bold text-gray-600">12</span>
                            </div>
                            <div>
                                <img src="{{ asset('images/cage.svg') }}" alt="kandang Icon"
                                    class="bg-pewterBlue w-14 h-14 p-3 rounded-full shadow-sm">
                            </div>
                        </div>
                        <div class="flex flex-col w-full border-t pt-4">
                            <div class="flex justify-between w-full items-center">
                                <span class="text-sm text-gray-500 font-medium">Kapasitas Maksimum</span>
                                <div class="flex items-center space-x-1">
                                    <p class="text-gray-500 font-bold text-sm">3200</p>
                                    <p class="text-xs text-gray-400">Ayam</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white px-6 py-7 rounded-lg shadow-md flex flex-col items-center w-full space-y-6">
                        <div class="flex justify-between w-full items-center">
                            <div class="flex flex-col space-y-1">
                                <h3 class="text-md font-semibold text-gray-500 uppercase tracking-wide">Angka Kematian
                                    Ayam</h3>
                                <span class="text-4xl font-bold text-gray-600">126</span>
                            </div>
                            <div>
                                <img src="{{ asset('images/chicken.svg') }}" alt="ayam Icon"
                                    class="bg-pewterBlue w-14 h-14 p-3 rounded-full shadow-sm">
                            </div>
                        </div>
                        <div class="flex flex-col w-full border-t pt-4">
                            <div class="flex justify-between w-full items-center">
                                <span class="text-sm text-gray-500 font-medium">Persentase ayam mati dalam
                                    sebulan</span>
                                <div class="flex items-center space-x-1">
                                    <p class="text-red-500 font-bold text-sm">-2.33%</p>
                                    <p class="text-xs text-gray-400">Dari bulan lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-md shadow-sm w-3/4">
                    <h2 class="text-lg font-semibold mb-2">Manajemen Ayam Bulanan</h2>
                    <canvas id="myBarChart" class="w-full h-64"></canvas>
                </div>
            </div>
            <div class="flex gap-4 w-full">
                <div class="bg-white p-6 rounded-md shadow-sm w-1/4">
                    <h2 class="text-lg font-semibold mb-2">Manajemen Ayam Harian</h2>
                    <canvas id="myPieChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md w-full">
                    <h2 class="text-lg font-semibold mb-4">Manajemen Ayam</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead class="text-gray-600 uppercase text-sm tracking-wide">
                                <tr class="border-b-2 border-gray-300">
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Kandang</th>
                                    <th class="px-4 py-3">Jumlah Ayam</th>
                                    <th class="px-4 py-3">Ayam Sakit</th>
                                    <th class="px-4 py-3">Ayam Mati</th>
                                    <th class="px-4 py-3">Kapasitas</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm">
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-4">1</td>
                                    <td class="px-4 py-4 font-medium">Kandang 1</td>
                                    <td class="px-4 py-4">120</td>
                                    <td class="px-4 py-4">4</td>
                                    <td class="px-4 py-4">1</td>
                                    <td class="px-4 py-4">150</td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">
                                            Sehat
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-4">2</td>
                                    <td class="px-4 py-4 font-medium">Kandang 2</td>
                                    <td class="px-4 py-4">100</td>
                                    <td class="px-4 py-4">5</td>
                                    <td class="px-4 py-4">2</td>
                                    <td class="px-4 py-4">120</td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Perlu Perhatian
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-4">3</td>
                                    <td class="px-4 py-4 font-medium">Kandang 3</td>
                                    <td class="px-4 py-4">140</td>
                                    <td class="px-4 py-4">0</td>
                                    <td class="px-4 py-4">0</td>
                                    <td class="px-4 py-4">150</td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">
                                            Sehat
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-4">4</td>
                                    <td class="px-4 py-4 font-medium">Kandang 3</td>
                                    <td class="px-4 py-4">140</td>
                                    <td class="px-4 py-4">23</td>
                                    <td class="px-4 py-4">12</td>
                                    <td class="px-4 py-4">150</td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">
                                            Darurat
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-4">3</td>
                                    <td class="px-4 py-4 font-medium">Kandang 3</td>
                                    <td class="px-4 py-4">140</td>
                                    <td class="px-4 py-4">0</td>
                                    <td class="px-4 py-4">0</td>
                                    <td class="px-4 py-4">150</td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">
                                            Sehat
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                
                
            </div>
        </main>
    </div>

</body>

</html>
