@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Manajemen Ayam')

@section('content')

    <main class="flex flex-col">
        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let status = "{{ session('status') }}"; // Misalnya: 'success', 'error'
                    let message = "{{ session('message') }}";

                    // Call Toastr dynamically
                    switch (status) {
                        case 'success':
                            toastr.success(message, "Success");
                            break;
                        case 'error':
                            toastr.error(message, "Error");
                            break;
                        case 'info':
                            toastr.info(message, "Info");
                            break;
                        case 'warning':
                            toastr.warning(message, "Warning");
                            break;
                        default:
                            toastr.info(message, "Notification");
                    }
                });
            </script>
        @endif

        <!-- Alpine.js Scope -->
        <div x-data="harianHandler()" class="flex flex-col">

            <div class="flex gap-8 mb-4">
                <!-- Card Tambah Data Populasi Ayam -->
                <div class="flex flex-col p-6 border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/5 hover:bg-orangeCrayola/15 hover:shadow-lg transition duration-300 w-full cursor-pointer"
                    @click="openModal = 'jumlahAyam'; console.log('Opened jumlahAyam modal')">
                    <div class="mb-4 text-orangeCrayola text-2xl">
                        <div
                            class="w-12 h-12 text-orangeCrayola flex items-center justify-center border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/15">
                            <i class="fa-solid fa-pen-to-square text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-semibold text-2xl text-orangeCrayola">Form Input Data Populasi Ayam</h3>
                    <div class="mt-4">
                        <span class="py-2 px-4 rounded-lg bg-orangeCrayola/25 text-orangeCrayola font-semibold">
                            29 Ekor Ayam
                        </span>
                    </div>
                </div>

                <!-- Card Tambah Data Harian Ayam -->
                <div class="flex flex-col p-6 border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/5 hover:bg-orangeCrayola/15 hover:shadow-lg transition duration-300 w-full cursor-pointer"
                    @click="openModal = 'harianAyam'; console.log('Opened harianAyam modal')">
                    <div class="mb-4 text-orangeCrayola text-2xl">
                        <div
                            class="w-12 h-12 text-orangeCrayola flex items-center justify-center border-2 border-orangeCrayola rounded-lg bg-orangeCrayola/15">
                            <i class="fa-solid fa-pen-to-square text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="font-semibold text-2xl text-orangeCrayola">Form Input Data Harian Ayam</h3>
                    <div class="mt-4">
                        <span class="py-2 px-4 rounded-lg bg-orangeCrayola/25 text-orangeCrayola font-semibold">
                            29 Ekor Ayam Sakit dan Mati
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Populasi Ayam -->
            <h2 class="text-xl font-semibold mb-2">Data Populasi Ayam</h2>
            <div class="bg-white p-6 rounded-lg shadow-md w-full mb-4">
                <div class="overflow-x-auto min-h-[420px] max-h-[420px]">
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
                            @foreach ($populasi as $key => $item)
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-3">{{ $populasi->firstItem() + $key }}</td>
                                    <td class="px-4 py-3">{{ $item->kode_batch }}</td>
                                    <td class="px-4 py-3">{{ $item->nama_batch }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal_doc)->format('d F Y') }}
                                    </td>
                                    <td class="px-4 py-3">{{ $item->jumlah_ayam_masuk }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            {{ $item->status_ayam }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                        <button
                                            class="edit-btn-populasi px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                            @click="openModal = 'editJumlahAyam'; editData = { 
                                                id: {{ $item->id }}, 
                                                batchCodeSuffix: '{{ substr($item->kode_batch, 6) }}', // Mengambil suffix
                                                nama_batch: '{{ addslashes($item->nama_batch) }}', 
                                                tanggal_doc: '{{ \Carbon\Carbon::parse($item->tanggal_doc)->format('Y-m-d') }}', 
                                                jumlah_ayam_masuk: {{ $item->jumlah_ayam_masuk }} 
                                            };">
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
                                        <span
                                            class="px-3 py-3 rounded font-semibold bg-orange-100 text-orange-700 flex justify-center items-center gap-2 cursor-pointer">
                                            <i class="fa-solid fa-print text-base"></i>
                                            <p>Cetak</p>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $populasi->links('pagination::tailwind') }}
                </div>
            </div>

            <!-- Tabel Data Harian Ayam -->
            <h2 class="text-xl font-semibold mb-2">Data Harian Ayam</h2>
            <div class="bg-white p-6 rounded-lg shadow-md w-full mb-4">
                <div class="overflow-x-auto min-h-[420px] max-h-[420px]">
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
                            @foreach ($harian as $key => $item)
                                <tr class="hover:bg-gray-50 border-b border-gray-200">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $item->nama_batch }}</td>
                                    <td class="px-4 py-3">{{ $item->tanggal_input }}</td>
                                    <td class="px-4 py-3">{{ $item->jumlah_ayam_mati }}</td>
                                    <td class="px-4 py-3">{{ $item->jumlah_ayam_sakit }}</td>
                                    <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                        <button
                                            class="edit-btn-harian px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                            @click="
                                            openModal = 'editHarianAyam'; 
                                            editData = { 
                                                id: {{ $item->id }}, 
                                                id_populasi: {{ $item->id_populasi }}, 
                                                tanggal_input: '{{ \Carbon\Carbon::parse($item->tanggal_input)->format('Y-m-d') }}', 
                                                jumlah_ayam_sakit: {{ $item->jumlah_ayam_sakit }}, 
                                                jumlah_ayam_mati: {{ $item->jumlah_ayam_mati }} 
                                            };
                                            ">
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
                <!-- Pagination Harian -->
                <div class="mt-4">
                    {{ $harian->links('pagination::tailwind') }}
                </div>
            </div>

            <!-- Popup Components -->
            <x-popup-form-jumlah-ayam />
            <x-popup-form-harian-ayam :batches="$batches" />
            <x-popup-form-edit-jumlah-ayam />
            <x-popup-form-edit-harian-ayam :batches="$batches" />
        </div>

    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('harianHandler', () => ({
                openModal: null,
                editData: {},

                submitEdit(event) {
                // Membuat form data
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('batchCodeSuffix', this.editData.batchCodeSuffix);
                formData.append('nama_batch', this.editData.nama_batch);
                formData.append('tanggal_doc', this.editData.tanggal_doc);
                formData.append('jumlah_ayam_masuk', this.editData.jumlah_ayam_masuk);

                // Mengirim data ke server menggunakan Fetch API
                fetch(`/populasi/${this.editData.id}`, {
                        method: 'POST', // Laravel menggunakan metode POST dengan _method override
                        body: formData,
                    })
                    .then(response => {
                        if (response.status === 422) {
                            return response.json().then(data => {
                                throw new Error(Object.values(data.errors).flat().join(' '));
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message, "Success", {
                                "closeButton": true,
                                "progressBar": true,
                                "timeOut": "1000",
                                "extendedTimeOut": "1000",
                                "onHidden": function() {
                                    location.reload();
                                }
                            });
                            this.openModal = null;
                        } else {
                            toastr.error(data.message, "Error");
                        }
                    })
                    .catch(error => {
                        toastr.error(error.message || 'Terjadi kesalahan server.', "Error");
                        console.error('Error:', error);
                    });
            },

                submitEditHarian() {
                    const actionUrl = `/harian/${this.editData.id}`;
                    const data = {
                        dailyBatchName: this.editData.id_populasi,
                        tanggal_input: this.editData.tanggal_input,
                        jumlah_ayam_sakit: this.editData.jumlah_ayam_sakit,
                        jumlah_ayam_mati: this.editData.jumlah_ayam_mati
                    };

                    fetch(actionUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => {
                            if (response.status === 422) {
                                return response.json().then(data => {
                                    throw new Error(Object.values(data.errors).flat().join(
                                        ' '));
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                toastr.success(data.message, "Success", {
                                    "closeButton": true,
                                    "progressBar": true,
                                    "timeOut": "1000",
                                    "extendedTimeOut": "1000",
                                    "onHidden": function() {
                                        location.reload();
                                    }
                                });
                                this.openModal = null;
                            } else {
                                toastr.error(data.message, "Error");
                            }
                        })
                        .catch(error => {
                            toastr.error(error.message || 'Terjadi kesalahan server.', "Error");
                            console.error('Error:', error);
                        });
                },

                init() {
                    this.$watch('editData.batchCodeSuffix', value => {
                        // Membatasi hanya 3 digit angka
                        this.editData.batchCodeSuffix = value.replace(/[^0-9]/g, '').slice(0,
                        3);
                    });
                },

            }));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Fungsi untuk menghapus data dengan SweetAlert2
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

            // Panggil fungsi untuk kedua tabel
            handleDelete('.swal-delete-btn', 'Populasi Ayam');
            handleDelete('.swal-delete-harian', 'Harian Ayam');

        });
    </script>

@endsection
