<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/images/logo.svg" type="image/png">
    <title>Manajemen Ayam</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/charts/barChart.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="font-sans antialiased">
    <div class="flex min-h-screen">
        @include('components.sidebar')
        <div class="flex-1 flex flex-col ml-64">
            <livewire:layout.navigation />
            <main class="lg:p-6 mt-16">
                <div class="flex flex-col">
                    @if (session('status'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let status = "{{ session('status') }}";
                                let message = "{{ session('message') }}";

                                switch (status) {
                                    case 'success':
                                        toastr.success(message, "Success");
                                        break;
                                    case 'error':
                                        toastr.error(message, "Error");
                                        break;
                                    default:
                                        toastr.info(message, "Notification");
                                }
                            });
                        </script>
                    @endif

                    <div x-data="harianHandler()" class="flex flex-col">

                        <div class="flex gap-6 mb-6">
                            <div class="bg-white p-6 rounded-md shadow-sm w-3/4 ring-2 ring-gray-700">
                                <h2 class="text-lg font-semibold mb-2">Manajemen Ayam Bulanan</h2>
                                <canvas id="myBarChart" class="w-full h-64"></canvas>
                            </div>
                            <div class="flex flex-col justify-between gap-6">
                                <div class="flex flex-col p-6 ring-2 ring-gray-700 rounded-lg bg-white hover:bg-orangeCrayola/10 hover:shadow-lg transition duration-300 w-full cursor-pointer h-full"
                                    @click="openModal = 'jumlahAyam'; console.log('Opened jumlahAyam modal')">
                                    <div class="mb-4 text-orangeCrayola text-2xl">
                                        <div
                                            class="w-12 h-12 text-orangeCrayola flex items-center justify-center border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/10">
                                            <i class="fa-solid fa-pen-to-square text-2xl"></i>
                                        </div>
                                    </div>
                                    <h3 class="font-semibold text-2xl text-orangeCrayola">Form Input Data Populasi
                                        Ayam</h3>
                                    <div class="mt-4">
                                        <span
                                            class="py-2 px-4 rounded-lg bg-orangeCrayola/25 text-orangeCrayola font-semibold border-2 border-orangeCrayola">
                                            Jumlah ayam di kandang
                                            {{ $populasi->sum('jumlah_ayam_masuk') }} ekor
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-col p-6 ring-2 ring-gray-700 rounded-lg bg-white hover:bg-orangeCrayola/10 hover:shadow-lg transition duration-300 w-full cursor-pointer h-full"
                                    @click="openModal = 'harianAyam'; console.log('Opened harianAyam modal')">
                                    <div class="mb-4 text-orangeCrayola text-2xl">
                                        <div
                                            class="w-12 h-12 text-orangeCrayola flex items-center justify-center border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/10">
                                            <i class="fa-solid fa-pen-to-square text-2xl"></i>
                                        </div>
                                    </div>
                                    <h3 class="font-semibold text-2xl text-orangeCrayola">Form Input Data Harian
                                        Ayam</h3>
                                    <div class="mt-4">
                                        <span
                                            class="py-2 px-4 rounded-lg bg-orangeCrayola/25 text-orangeCrayola font-semibold border-2 border-orangeCrayola">
                                            {{ $harian->sum('jumlah_ayam_sakit') }} Ayam Sakit,
                                            {{ $harian->sum('jumlah_ayam_mati') }} Ayam Mati
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md w-full mb-6 ring-2 ring-gray-700">
                            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Populasi Ayam</h2>
                            <div class="overflow-x-auto min-h-[220px]">
                                <table class="w-full text-center border-collapse">
                                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                                        <tr class="border-b-2 border-gray-700">
                                            <th class="px-4 py-3">No</th>
                                            <th class="px-4 py-3">Nama Kandang</th>
                                            <th class="px-4 py-3">Kode Populasi</th>
                                            <th class="px-4 py-3">Nama Populasi</th>
                                            <th class="px-4 py-3">Tanggal DOC</th>
                                            <th class="px-4 py-3">Jumlah Ayam</th>
                                            <th class="px-4 py-3">Status Ayam</th>
                                            <th class="px-4 py-3">Aksi</th>
                                            <th class="px-4 py-3">Cetak</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 text-sm">
                                        @foreach ($populasi as $key => $item)
                                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                                <td class="px-4 py-3">{{ $populasi->firstItem() + $key }}</td>
                                                <td class="px-4 py-3">{{ $item->kandang->nama_kandang ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">{{ $item->kode_batch }}</td>
                                                <td class="px-4 py-3">{{ $item->nama_batch }}</td>
                                                <td class="px-4 py-3">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_doc)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="px-4 py-3">{{ $item->jumlah_ayam_masuk }}</td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="px-3 py-1 rounded text-xs font-semibold
                                                        {{ $item->status_ayam === 'Proses' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $item->status_ayam === 'Siap Panen' ? 'bg-orange-100 text-orange-700' : '' }}
                                                        {{ $item->status_ayam === 'Sudah Panen' ? 'bg-green-100 text-green-700' : '' }}">
                                                        {{ $item->status_ayam }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                                    <button
                                                        class="edit-btn-populasi px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                                        @click="$dispatch('open-edit-populasi', { 
                                                        id: {{ $item->id }},
                                                        batchCodeSuffix: '{{ substr($item->kode_batch, 9) }}',
                                                        nama_batch: '{{ addslashes($item->nama_batch) }}',
                                                        tanggal_doc: '{{ \Carbon\Carbon::parse($item->tanggal_doc)->format('Y-m-d') }}',
                                                        jumlah_ayam_masuk: {{ $item->jumlah_ayam_masuk }},
                                                        status_ayam: '{{ $item->status_ayam }}',
                                                        kandang_id: {{ $item->kandang_id }}
                                                    })">
                                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                                    </button>

                                                    <button type="button"
                                                        class="swal-delete-btn px-3 py-3 rounded bg-red-100 text-red-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                                        data-id="{{ $item->id }}"
                                                        data-url="{{ route('populasi.destroy', $item->id) }}">
                                                        <i class="fa-solid fa-trash text-lg"></i>
                                                    </button>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('populasi.cetak', $item->id) }}"
                                                        class="px-3 py-3 rounded font-semibold bg-gray-300 text-white-700 flex justify-center items-center gap-2 cursor-pointer">
                                                        <i class="fa-solid fa-print text-base"></i>
                                                        <p>Cetak</p>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $populasi->links('pagination::tailwind') }}
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md w-full mb-6 ring-2 ring-gray-700">
                            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Harian Ayam</h2>
                            <div class="overflow-x-auto min-h-[220px]">
                                <table class="w-full text-center border-collapse">
                                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                                        <tr class="border-b-2 border-gray-700">
                                            <th class="px-4 py-3">No</th>
                                            <th class="px-4 py-3">Nama Populasi</th>
                                            <th class="px-4 py-3">Tanggal Input</th>
                                            <th class="px-4 py-3">Jumlah Ayam Sakit</th>
                                            <th class="px-4 py-3">Jumlah Ayam Mati</th>
                                            <th class="px-4 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 text-sm">
                                        @foreach ($harian as $key => $item)
                                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-3">{{ $item->nama_batch }}</td>
                                                <td class="px-4 py-3">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_input)->translatedFormat('d F Y') }}
                                                </td>
                                                <td class="px-4 py-3">{{ $item->jumlah_ayam_sakit }}</td>
                                                <td class="px-4 py-3">{{ $item->jumlah_ayam_mati }}</td>
                                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                                    <button
                                                        class="edit-btn-harian px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                                        @click="$dispatch('open-edit-harian', { 
                                                        id: {{ $item->id }},
                                                        id_populasi: {{ $item->id_populasi }},
                                                        nama_batch: '{{ $item->nama_batch }}',
                                                        tanggal_input: '{{ \Carbon\Carbon::parse($item->tanggal_input)->format('Y-m-d') }}',
                                                        jumlah_ayam_sakit: {{ $item->jumlah_ayam_sakit }},
                                                        jumlah_ayam_mati: {{ $item->jumlah_ayam_mati }}
                                                    })">
                                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                                    </button>

                                                    <button type="button"
                                                        class="swal-delete-harian px-3 py-3 rounded bg-red-100 text-red-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                                        data-id="{{ $item->id }}"
                                                        data-url="{{ route('harian.destroy', $item->id) }}">
                                                        <i class="fa-solid fa-trash text-lg"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $harian->links('pagination::tailwind') }}
                            </div>
                        </div>

                        <x-popup-form-jumlah-ayam :kandang="$kandang" />
                        <x-popup-form-harian-ayam :batches="$batches" />
                        <x-popup-form-edit-jumlah-ayam :kandang="$kandang" :batches="$batches" />
                        <x-popup-form-edit-harian-ayam :batches="$batches" />
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('harianHandler', () => ({
                openModal: null,
                editData: {},

                init() {
                    this.$watch('editData.batchCodeSuffix', value => {
                        this.editData.batchCodeSuffix = value.replace(/[^a-zA-Z0-9]/g, '')
                            .slice(0, 3);
                    });
                },

            }));
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function handleDelete(buttonClass, entityName) {
                document.querySelectorAll(buttonClass).forEach(button => {
                    button.addEventListener('click', function() {
                        let itemId = this.dataset.id;
                        let deleteUrl = this.dataset.url;

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: `Data ${entityName} ini akan dihapus secara permanen!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(deleteUrl, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    }).then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire('Terhapus!', data.message,
                                                    'success')
                                                .then(() => location.reload());
                                        } else {
                                            Swal.fire('Gagal!', data.message, 'error');
                                        }
                                    }).catch(error => {
                                        Swal.fire('Gagal!', 'Terjadi kesalahan server.',
                                            'error');
                                    });
                            }
                        });
                    });
                });
            }

            handleDelete('.swal-delete-btn', 'Populasi Ayam');
            handleDelete('.swal-delete-harian', 'Harian Ayam');

        });
    </script>
    <script>
        window.monthlyData = @json($monthlyData);
        window.todayData = @json($todayData);
    </script>
    
</body>

</html>
