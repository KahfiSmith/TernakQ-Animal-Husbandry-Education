@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Manajemen Ayam')

@section('content')

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    <main class="flex flex-col">
        <div x-data="{ openModal: null }" class="flex gap-8 mb-4">
            <div class="flex flex-col p-6 border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/5 hover:bg-orangeCrayola/15 hover:shadow-lg transition duration-300 w-full cursor-pointer"
                @click="openModal = 'jumlahAyam'">
                <div class="mb-4 text-orangeCrayola text-2xl">
                    <div
                        class="w-14 h-14 text-orangeCrayola flex items-center justify-center border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/15">
                        <i class="fa-solid fa-pen-to-square text-3xl"></i>
                    </div>
                </div>
                <h3 class="font-semibold text-2xl text-orangeCrayola">Form Input Data Populasi Ayam</h3>
                <div class="mt-4">
                    <span class="py-2 px-4 rounded-lg bg-orangeCrayola/25 text-orangeCrayola font-semibold">
                        29 Ekor Ayam
                    </span>
                </div>
            </div>
            <div class="flex flex-col p-6 border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/5 hover:bg-orangeCrayola/15 hover:shadow-lg transition duration-300 w-full cursor-pointer"
                @click="openModal = 'harianAyam'">
                <div class="mb-4 text-orangeCrayola text-2xl">
                    <div
                        class="w-14 h-14 text-orangeCrayola flex items-center justify-center border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/15">
                        <i class="fa-solid fa-pen-to-square text-3xl"></i>
                    </div>
                </div>
                <h3 class="font-semibold text-2xl text-orangeCrayola">Form Input Data Harian Ayam</h3>
                <div class="mt-4">
                    <span class="py-2 px-4 rounded-lg bg-orangeCrayola/25 text-orangeCrayola font-semibold">
                        29 Ekor Ayam Sakit dan Mati
                    </span>
                </div>
            </div>
            <x-popup-form-jumlah-ayam />
            <x-popup-form-harian-ayam />
        </div>
        <h2 class="text-xl font-semibold mb-2">Data Populasi Ayam</h2>
        <div class="bg-white p-6 rounded-lg shadow-md w-full mb-4">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Kode Batch</th>
                            <th class="px-4 py-3">Nama Batch</th>
                            <th class="px-4 py-3">Tanggal DOC</th>
                            <th class="px-4 py-3">Jumlah Ayam Masuk</th>
                            <th class="px-4 py-3">Status Ayam</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Cetak</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="px-4 py-4">1</td>
                            <td class="px-4 py-4 font-medium">BATCH-2025</td>
                            <td class="px-4 py-4">BATCH S1</td>
                            <td class="px-4 py-4">4 Januari 2025</td>
                            <td class="px-4 py-4">1200</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    Proses
                                </span>
                            </td>
                            <td class="px-4 py-4 flex gap-3 justify-center items-center">
                                <span
                                    class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </span>
                                <span
                                    class="px-3 py-3 rounded text-xs font-semibold bg-red-100 text-red-700 flex justify-center items-center w-12 h-12 cursor-pointer">
                                    <i class="fa-solid fa-trash text-lg"></i>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-3 py-3 rounded font-semibold bg-orange-100 text-orange-700 flex justify-center items-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-print text-base"></i>
                                    <p>Cetak</p>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h2 class="text-xl font-semibold mb-2">Data Harian Ayam</h2>
        <div class="bg-white p-6 rounded-lg shadow-md w-full mb-4">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-300">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Batch</th>
                            <th class="px-4 py-3">Tanggal Input</th>
                            <th class="px-4 py-3">Jumlah Ayam Sakit</th>
                            <th class="px-4 py-3">Jumlah Ayam Mati</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="px-4 py-4">1</td>
                            <td class="px-4 py-4">BATCH S1</td>
                            <td class="px-4 py-4">4 Januari 2025</td>
                            <td class="px-4 py-4">120</td>
                            <td class="px-4 py-4">12</td>
                            <td class="px-4 py-4 flex gap-3 justify-center items-center">
                                <span
                                    class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </span>
                                <span
                                    class="px-3 py-3 rounded text-xs font-semibold bg-red-100 text-red-700 flex justify-center items-center w-12 h-12 cursor-pointer">
                                    <i class="fa-solid fa-trash text-lg"></i>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
