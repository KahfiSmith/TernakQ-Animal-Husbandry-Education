@section('title', 'Dashboard TernakQ')

<x-app-layout>
    <x-slot name="title">
        Dashboard TernakQ
    </x-slot>

    <main class="gap-6 flex flex-col">
        <div class="flex gap-6 w-full justify-between">
            <!-- Kartu Informasi -->
            <div class="flex flex-col justify-between w-full space-y-6">
                <!-- Jumlah Kandang -->
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center w-full space-y-6 ring-2 ring-gray-700">
                    <div class="flex justify-between w-full items-center">
                        <div class="flex flex-col space-y-1">
                            <h3 class="text-md font-semibold text-gray-500 uppercase tracking-wide">
                                Jumlah Kandang
                            </h3>
                            <span class="text-4xl font-bold text-gray-600">{{ $totalKandangAyams }}</span>
                        </div>
                        <div>
                            <img src="{{ asset('images/cage.svg') }}" alt="Kandang Icon" class="bg-pewterBlue w-14 h-14 p-3 rounded-full shadow-sm">
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

                <!-- Angka Kematian Ayam -->
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center w-full space-y-6 ring-2 ring-gray-700">
                    <div class="flex justify-between w-full items-center">
                        <div class="flex flex-col space-y-1">
                            <h3 class="text-md font-semibold text-gray-500 uppercase tracking-wide">
                                Angka Kematian Ayam
                            </h3>
                            <span class="text-4xl font-bold text-gray-600">{{ $totalDeathsThisMonth }}</span>
                        </div>
                        <div>
                            <img src="{{ asset('images/chicken.svg') }}" alt="Ayam Icon" class="bg-pewterBlue w-14 h-14 p-3 rounded-full shadow-sm">
                        </div>
                    </div>
                    <div class="flex flex-col w-full border-t pt-4">
                        <div class="flex justify-between w-full items-center">
                            <div class="flex items-center space-x-1 justify-between w-full">
                                @php
                                    // Ambil nilai absolut untuk ditampilkan, karena tanda akan ditambahkan secara manual
                                    $formattedChange = number_format(abs($percentageChange), 2) . '%';
                                    // Tentukan warna berdasarkan perubahan
                                    if ($percentageChange < 0) {
                                        // Penurunan: kondisi baik, tampilkan dengan hijau dan tanda minus
                                        $changeColor = 'text-green-500';
                                        $sign = '-';
                                    } elseif ($percentageChange > 0) {
                                        // Peningkatan: kondisi negatif, tampilkan dengan merah dan tanda plus
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

            <!-- Chart Bulanan -->
            <div class="bg-white p-6 rounded-md shadow-sm w-3/4 ring-2 ring-gray-700">
                <h2 class="text-lg font-semibold mb-2">Manajemen Ayam Bulanan</h2>
                <canvas id="myBarChart" class="w-full h-64"></canvas>
            </div>
        </div>

        <div class="flex gap-6 w-full">
            <!-- Chart Harian -->
            <div class="bg-white p-6 rounded-md shadow-sm w-1/3 ring-2 ring-gray-700">
                <h2 class="text-lg font-semibold mb-2">Manajemen Keuangan</h2>
                <canvas id="myPieChart"></canvas>
            </div>

            <!-- Tabel Manajemen Ayam -->
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
                                            <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                Perlu Perhatian
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">
                                                Sehat
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script>
        window.monthlyData = @json($monthlyData);
        window.todayData = @json($todayData);
        window.pendapatanBulanIni = {{ $pendapatanBulanIni }};
        window.pengeluaranBulanIni = {{ $pengeluaranBulanIni }};
    </script>
</x-app-layout>
