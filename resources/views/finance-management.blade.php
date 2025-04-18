@extends('layouts.dashboard-layout')

@section('title', 'Manajemen Keuangan')

@section('content')
    <main class="flex flex-col space-y-6">

        <nav class="text-sm text-gray-600 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    Manajemen Keuangan
                </li>
            </ol>
        </nav>

        <!-- Header Ringkasan Keuangan -->
        <div class="flex items-center justify-between bg-white p-6 rounded-lg shadow-md ring-2 ring-gray-700">
            <h2 class="text-2xl font-bold text-orangeCrayola">Laporan Keuangan</h2>

            <!-- Form Filter Bulan -->
            <!-- Form untuk Filter Bulan dan Tahun -->
            <form method="GET" action="{{ route('finance-management') }}" class="flex items-center gap-4 w-1/2"
                x-data="{
                    tahun: '{{ request('tahun', date('Y')) }}',
                    bulan: '{{ request('bulan', date('m')) }}',
                    months: Array.from({ length: 12 }, (_, i) => i + 1),
                    filteredMonths() {
                        return this.tahun == '{{ date('Y') }}' ? this.months.filter(month => month <= {{ date('m') }}) : this.months;
                    }
                }">
                <!-- Dropdown Tahun -->
                <select name="tahun" x-model="tahun"
                    class="py-2 px-4 bg-white hover:bg-gray-100 ring-2 ring-gray-700 rounded-lg font-semibold text-gray-700 focus:outline-none appearance-none w-1/2"
                    @change="bulan = ''">
                    @foreach (range(2020, date('Y')) as $year)
                        <option value="{{ $year }}" :selected="tahun == '{{ $year }}'">{{ $year }}
                        </option>
                    @endforeach
                </select>

                <!-- Dropdown Bulan -->
                <select name="bulan" x-model="bulan"
                    class="py-2 px-4 bg-white hover:bg-gray-100 ring-2 ring-gray-700 rounded-lg font-semibold text-gray-700 focus:outline-none appearance-none w-1/2">
                    <template x-for="month in filteredMonths()" :key="month">
                        <option :value="month < 10 ? '0' + month : month"
                            :selected="bulan == (month < 10 ? '0' + month : month)">
                            <span x-text="new Date(0, month - 1).toLocaleString('id-ID', { month: 'long' })"></span>
                        </option>
                    </template>
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition duration-200 ring-2 ring-gray-700">
                    <span class="flex items-center">
                        <i class="fa-solid fa-filter pr-2"></i>
                        <span class="pl-2">Filter</span>
                    </span>
                </button>
            </form>

        </div>

        <!-- Ringkasan Keuangan Bulanan -->
        <div class="grid grid-cols-3 gap-6">
            <a href="{{ route('finance-management-income') }}" wire:navigate
                class="flex p-6 ring-2 ring-gray-700 rounded-lg bg-white hover:bg-green-100 hover:shadow-lg transition duration-300 w-full cursor-pointer justify-between items-start">
                <div class="flex flex-col">
                    <h3 class="text-xl font-semibold text-green-600">Total Pendapatan</h3>
                    <p class="text-2xl font-bold text-green-700 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
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
                    <p class="text-2xl font-bold text-red-700 mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-red-600 text-2xl">
                    <div
                        class="w-9 h-9 text-red-500 flex items-center justify-center border-2 border-red-500 rounded-lg bg-red-50">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </div>
                </div>
            </a>
            <div class="p-6 bg-white ring-2 ring-gray-700 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-blue-600">Laba Bersih</h3>
                <p class="text-2xl font-bold text-blue-700 mt-2">Rp {{ number_format($labaBersih, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabel Laporan Keuangan Harian -->
        <div class="bg-white p-6 rounded-lg shadow-md ring-2 ring-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-orangeCrayola">Detail Transaksi - {{ $namaBulan }}
                    {{ $tahun }}</h2>

                <a href="{{ route('finance-management.pdf', ['bulan' => request('bulan', date('m')), 'tahun' => request('tahun', date('Y'))]) }}"
                    target="_blank"
                    class="px-3 py-3 rounded font-semibold bg-gray-700 text-white flex justify-center items-center gap-2 hover:bg-gray-800 transition duration-200 ring-2 ring-gray-700">
                    <i class="fa-solid fa-file-pdf text-base"></i>
                    <p>Cetak PDF</p>
                </a>
            </div>

            <table class="w-full border-collapse text-gray-700">
                <thead class="text-gray-600 uppercase text-sm">
                    <tr class="border-b-2 border-gray-700">
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3 text-right">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($transaksi as $trx)
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($trx['tanggal'])->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-3">{{ Str::limit($trx['keterangan'], 50) }}</td>
                            <td
                                class="px-4 py-3 text-center font-semibold {{ $trx['tipe'] == 'pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $trx['tipe'] == 'pendapatan' ? '+' : '-' }} Rp
                                {{ number_format($trx['jumlah'], 0, ',', '.') }}
                            </td>
                            <td
                                class="px-4 py-3 text-right font-bold {{ $trx['tipe'] == 'pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($trx['jumlah'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach

                    <!-- Total Pendapatan Harian -->
                    <tr class="border-t-2 border-gray-700">
                        <td colspan="2" class="px-4 py-3 font-bold text-gray-800">Total Pendapatan Hari Ini</td>
                        <td colspan="2" class="px-4 py-3 text-right font-bold text-green-700">
                            Rp {{ number_format($totalPendapatanHarian, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- Total Pengeluaran Harian -->
                    <tr>
                        <td colspan="2" class="px-4 py-3 font-bold text-gray-800">Total Pengeluaran Hari Ini</td>
                        <td colspan="2" class="px-4 py-3 text-right font-bold text-red-700">
                            Rp {{ number_format($totalPengeluaranHarian, 0, ',', '.') }}
                        </td>
                    </tr>

                    <!-- Total Saldo Harian -->
                    <tr class="border-t-2 border-gray-700">
                        <td colspan="2" class="px-4 py-3 font-bold text-gray-800">Total Saldo Hari Ini</td>
                        <td colspan="2" class="px-4 py-3 text-right text-blue-700 font-bold">
                            Rp {{ number_format($totalSaldoHarian, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </main>
@endsection
