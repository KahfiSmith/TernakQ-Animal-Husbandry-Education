@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Manajemen Keuangan')

@section('content')
    <main class="flex flex-col space-y-6">

        <!-- Header Ringkasan Keuangan -->
        <div class="flex items-center justify-between bg-white p-6 rounded-lg shadow-md ring-2 ring-gray-700">
            <h2 class="text-2xl font-bold text-orangeCrayola">Laporan Keuangan</h2>

            <!-- Dropdown Pilih Bulan -->
            <div class="flex items-center gap-4">
                <select
                    class="py-2 px-4 bg-white hover:bg-gray-100 ring-2 ring-gray-700 rounded-lg font-semibold text-gray-700 focus:outline-none 
                        appearance-none pr-8 border-white ">
                    <option selected>Januari 2025</option>
                    <option>Februari 2025</option>
                    <option>Maret 2025</option>
                </select>

                <!-- Tombol Cetak -->
                <button
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition duration-200 ring-2 ring-gray-700">
                    <i class="fa-solid fa-print pr-2"></i> Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Ringkasan Keuangan Bulanan -->
        <div class="grid grid-cols-3 gap-6">
            <a href="{{ route('finance-management-income') }}" wire:navigate
                class="flex p-6 ring-2 ring-gray-700 rounded-lg bg-white hover:bg-green-100 hover:shadow-lg transition duration-300 w-full cursor-pointer justify-between items-start">
                <div class="flex flex-col">
                    <h3 class="text-xl font-semibold text-green-600">Total Pendapatan</h3>
                    <p class="text-2xl font-bold text-green-700 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="text-green-600 text-2xl">
                    <div
                        class="w-9 h-9 text-green-500 flex items-center justify-center border-2 border-green-500 rounded-lg bg-green-50">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('finance-management-outcome') }}" wire:navigate
                class="flex p-6 ring-2 ring-gray-700 rounded-lg bg-white hover:bg-red-100 hover:shadow-lg transition duration-300 w-full cursor-pointer justify-between items-start">
                <div class="flex flex-col">
                    <h3 class="text-xl font-semibold text-red-600">Total Pengeluaran</h3>
                    <p class="text-2xl font-bold text-red-700 mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="text-red-600 text-2xl">
                    <div
                        class="w-9 h-9 text-red-500 flex items-center justify-center border-2 border-red-500 rounded-lg bg-red-50">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </div>
                </div>
            </a>
            <div class="p-6 bg-white ring-2 ring-gray-700 rounded-lg shadow-md hover:bg-blue-100 hover:shadow-lg">
                <h3 class="text-xl font-semibold text-blue-600">Laba Bersih</h3>
                <p class="text-2xl font-bold text-blue-700 mt-2">Rp {{ number_format($labaBersih, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Laporan Keuangan Harian -->
        <div class="bg-white p-6 rounded-lg shadow-md ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-4 text-orangeCrayola">Detail Transaksi - 10 Januari 2025</h2>

            <table class="w-full border-collapse text-gray-700">
                <thead class="text-gray-600 uppercase text-sm">
                    <tr class="border-b-2 border-gray-700">
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3 text-right">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lastDate = null;
                    @endphp
                    @foreach ($detailTransaksi['transaksi'] as $item)
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-3">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                            <td class="px-4 py-3">{{ $item->keterangan }}</td>
                            <td class="px-4 py-3 text-center text-{{ $item->tipe === 'pendapatan' ? 'green' : 'red' }}-600 font-semibold">
                                {{ $item->tipe === 'pendapatan' ? '+' : '-' }} Rp {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right text-{{ $item->tipe === 'pendapatan' ? 'green' : 'red' }}-600 font-bold">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                        </tr>
            
                        {{-- Cek apakah tanggal berikutnya berbeda untuk menampilkan saldo hari ini --}}
                        @php
                            if ($lastDate !== $item->tanggal) {
                                $lastDate = $item->tanggal;
                            }
            
                            $nextItem = $loop->remaining > 0 ? $detailTransaksi['transaksi'][$loop->index + 1] : null;
                            $isLastOfDay = !$nextItem || $nextItem->tanggal !== $lastDate;
                        @endphp
            
                        @if ($isLastOfDay)
                            <tr class="border-b-2 border-gray-700 bg-gray-100">
                                <td colspan="3" class="px-4 py-3 font-bold text-gray-800">Total Saldo Hari Ini</td>
                                <td class="px-4 py-3 text-right text-blue-700 font-bold">
                                    Rp {{ number_format($detailTransaksi['saldoHarian'][$lastDate] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

    </main>
@endsection
