@extends('layouts.dashboard-layout')

@section('title', 'Dashboard')

@section('content')
    <main class="gap-6 flex flex-col">
        <div class="flex gap-6 w-full justify-between">
            <div class="flex flex-col justify-between w-full space-y-6">
                <div
                    class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center w-full space-y-6 ring-2 ring-gray-700">
                    <div class="flex justify-between w-full items-center">
                        <div class="flex flex-col space-y-1">
                            <h3 class="text-md font-semibold text-gray-500 uppercase tracking-wide">
                                Jumlah Kandang
                            </h3>
                            <span class="text-4xl font-bold text-gray-600">{{ $totalKandangAyams }}</span>
                        </div>
                        <div>
                            <img src="{{ asset('images/cage.svg') }}" alt="Kandang Icon"
                                class="bg-pewterBlue w-14 h-14 p-3 rounded-full shadow-sm">
                        </div>
                    </div>
                    <div class="flex flex-col w-full border-t pt-4">
                        <div class="flex justify-between w-full items-center">
                            <span class="text-sm text-gray-500 font-medium">Kapasitas Maksimum</span>
                            <div class="flex items-center space-x-1">
                                <p class="text-gray-500 font-bold text-sm">{{ $totalCapacity }}</p>
                                <p class="text-xs text-gray-400">Ayam</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center w-full space-y-6 ring-2 ring-gray-700">
                    <div class="flex justify-between w-full items-center">
                        <div class="flex flex-col space-y-1">
                            <h3 class="text-md font-semibold text-gray-500 uppercase tracking-wide">
                                Angka Kematian Ayam
                            </h3>
                            <span class="text-4xl font-bold text-gray-600">{{ $totalDeathsThisMonth }}</span>
                        </div>
                        <div>
                            <img src="{{ asset('images/chicken.svg') }}" alt="Ayam Icon"
                                class="bg-pewterBlue w-14 h-14 p-3 rounded-full shadow-sm">
                        </div>
                    </div>
                    <div class="flex flex-col w-full border-t pt-4">
                        <div class="flex justify-between w-full items-center">
                            <div class="flex items-center space-x-1 justify-between w-full">
                                @php
                                    $formattedChange = number_format(abs($percentageChange), 2) . '%';
                                    if ($percentageChange < 0) {
                                        $changeColor = 'text-green-500';
                                        $sign = '-';
                                    } elseif ($percentageChange > 0) {
                                        $changeColor = 'text-red-500';
                                        $sign = '+';
                                    } else {
                                        $changeColor = 'text-gray-500';
                                        $sign = '';
                                    }
                                @endphp
                                <span class="text-sm text-gray-500 font-medium">
                                    Persentase perubahan (bulan ini dengan bulan lalu)
                                </span>
                                <div class="flex items-center space-x-1">
                                    <p class="{{ $changeColor }} font-bold text-sm">
                                        {{ $sign }}{{ $formattedChange }}
                                    </p>
                                    <p class="text-xs text-gray-400">Dari bulan lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ring-2 ring-gray-700 bg-white p-6 rounded-lg shadow-lg w-3/4 gap-4 flex flex-col">
                <h2 class="text-2xl font-semibold mb-4 text-slate-600 tracking-tight flex items-center">
                    Manajemen Keuangan Bulanan
                </h2>
                <div class="flex flex-col gap-3 backdrop-blur-sm bg-white/50 p-4 rounded-lg border-2 border-slate-300">
                    <h2 class="text-xl font-semibold text-green-400 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                clip-rule="evenodd" />
                        </svg>
                        Pendapatan
                    </h2>
                    <div>
                        <span class="text-green-400 text-3xl font-bold">Rp.
                            {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-3 backdrop-blur-sm bg-white/5 p-4 rounded-lg border-2 border-slate-300">
                    <h2 class="text-xl font-semibold text-red-400 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z"
                                clip-rule="evenodd" />
                        </svg>
                        Pengeluaran
                    </h2>
                    <div>
                        <span class="text-red-400 text-3xl font-bold">Rp.
                            {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-6 w-full">
            <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
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
                            @foreach ($KandangAyams as $index => $kandang)
                                @php
                                    // Hitung persentase berdasarkan total ayam
                                    $total = $kandang->total_ayam;
                                    $sick = $kandang->total_sick;
                                    $dead = $kandang->total_dead;
                                    $maxPersen = 0;
                                    if ($total > 0) {
                                        $persenSick = ($sick / $total) * 100;
                                        $persenDead = ($dead / $total) * 100;
                                        $maxPersen = max($persenSick, $persenDead);
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-4">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4">{{ $kandang->nama_kandang }}</td>
                                    <td class="px-4 py-4">{{ $kandang->total_ayam }}</td>
                                    <td class="px-4 py-4">{{ $kandang->total_sick }}</td>
                                    <td class="px-4 py-4">{{ $kandang->total_dead }}</td>
                                    <td class="px-4 py-4">{{ $kandang->kapasitas }}</td>
                                    <td class="px-4 py-4">
                                        @if ($maxPersen > 30)
                                            <span class="px-3 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">
                                                Darurat
                                            </span>
                                        @elseif ($maxPersen >= 10)
                                            <span
                                                class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                Perlu Perhatian
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">
                                                Sehat
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $KandangAyams->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </main>
@endsection
