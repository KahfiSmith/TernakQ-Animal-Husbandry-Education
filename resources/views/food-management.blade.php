@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Manajemen Pakan')

@section('content')
    <main class="flex flex-col w-full" x-data="{
        editMode: false,
        pakanId: '',
        namaPakan: '',
        jenisPakan: '',
        berat: '',
        tanggalMasuk: '',
        hargaPerKg: ''
    }">
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

        <div class="flex gap-6 mb-6">
            <div class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200 w-1/2">
                <h2 class="text-xl font-bold mb-2 text-orangeCrayola">
                    <span x-text="editMode ? 'Edit Stok Pakan' : 'Tambah Stok Pakan'"></span>
                </h2>
                <form method="POST" :action="editMode ? '{{ url('food-management') }}/' + pakanId : '{{ route('pakan.store') }}'"
                    class="space-y-6 max-full">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="justify-between flex flex-col space-y-12">
                        <div class="flex flex-col space-y-6">
                            <!-- Input Nama Pakan (Dropdown/Text) -->
                            <div class="flex flex-col space-y-1">
                                <x-input-label for="nama_pakan" :value="__('Nama Pakan')" />
                                <x-text-input id="nama_pakan" name="nama_pakan" type="text"
                                    class="block mt-1 w-full py-2.5" required x-model="namaPakan"
                                    placeholder="Masukkan Nama Pakan" />
                            </div>

                            <!-- Input Jenis Pakan -->
                            <div class="flex flex-col space-y-1">
                                <x-input-label for="jenis_pakan" :value="__('Jenis Pakan')" />
                                <x-text-input id="jenis_pakan" name="jenis_pakan" type="text"
                                    class="block mt-1 w-full py-2.5" required x-model="jenisPakan"
                                    placeholder="Masukkan Jenis Pakan" />
                            </div>

                            <!-- Input Berat (kg) -->
                            <div class="flex flex-col space-y-1">
                                <x-input-label for="berat" :value="__('Berat (kg)')" />
                                <x-text-input id="berat" name="berat" type="number" class="block mt-1 w-full py-2.5"
                                    required min="1" x-model="berat" placeholder="Masukkan berat dalam kg" />
                            </div>

                            <!-- Input Tanggal Masuk -->
                            <div class="flex flex-col space-y-1">
                                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date"
                                    class="block mt-1 w-full py-2.5" required x-model="tanggalMasuk" />
                            </div>

                            <!-- Input Harga per kg (IDR) -->
                            <div class="flex flex-col space-y-1">
                                <x-input-label for="harga_per_kg" :value="__('Harga per kg (IDR)')" />
                                <x-text-input id="harga_per_kg" name="harga_per_kg" type="number"
                                    class="block mt-1 w-full py-2.5" required min="0" x-model="hargaPerKg"
                                    placeholder="Masukkan harga" />
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-start space-x-4">
                            <x-primary-button type="submit"
                                class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                            text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                            hover:translate-x-0.5 py-2.5 px-4 rounded"
                                x-text="editMode ? 'Update Pakan' : 'Tambah Pakan'">
                            </x-primary-button>

                            <x-primary-button type="button" x-show="editMode"
                                @click="editMode = false; namaPakan = ''; jenisPakan = ''; berat = ''; tanggalMasuk = ''; 
                                        hargaPerKg = '';"
                                class="bg-gray-500 ring-2 ring-gray-700 shadow-md text-white 
                                hover:shadow-lg hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded-md">
                                Batal
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>

            <div
                class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200 w-1/2">
                <h2 class="text-xl font-bold mb-8 text-orangeCrayola">Tambah Penggunaan Pakan</h2>
                <form method="POST" action="{{ route('food-usage.store') }}" class="flex flex-col gap-1">
                    @csrf
                
                    <div class="flex flex-col space-y-6">
                        <!-- Input Nama Pakan (Dropdown) -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="nama_pakan" :value="__('Nama Pakan')" />
                            <select id="nama_pakan" name="nama_pakan"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                required>
                                <option value="" disabled selected>Pilih Nama Pakan</option>
                                @foreach ($pakan as $pkn)
                                    <option value="{{ $pkn->nama_pakan }}">{{ $pkn->nama_pakan }} ({{ $pkn->berat }} kg tersedia)</option>
                                @endforeach
                            </select>
                        </div>
                
                        <!-- Input Tanggal Pakai -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="tanggal_pakai" :value="__('Tanggal Pakai')" />
                            <x-text-input id="tanggal_pakai" name="tanggal_pakai" type="date"
                                class="block mt-1 w-full py-2.5" required />
                        </div>
                
                        <!-- Input Jumlah yang Dipakai (kg) -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="jumlah_pakai" :value="__('Jumlah yang Dipakai (kg)')" />
                            <x-text-input id="jumlah_pakai" name="jumlah_pakai" type="number"
                                class="block mt-1 w-full py-2.5" required min="1" />
                        </div>
                    </div>
                
                    <!-- Tombol Submit -->
                    <div class="mt-12 flex justify-start">
                        <button type="submit"
                        class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded">
                            Tambah Penggunaan Pakan
                        </button>
                    </div>
                </form>
                
            </div>

        </div>

        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Stok Pakan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Pakan</th>
                            <th class="px-4 py-3">Jenis Pakan</th>
                            <th class="px-4 py-3">Berat (kg)</th>
                            <th class="px-4 py-3">Tanggal Masuk</th>
                            <th class="px-4 py-3">Harga per kg</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($pakan as $key => $pkn)
                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $pkn->nama_pakan }}</td>
                                <td class="px-4 py-3">{{ $pkn->jenis_pakan }}</td>
                                <td class="px-4 py-3">{{ $pkn->berat }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($pkn->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($pkn->harga_per_kg, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                    <button
                                        class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 
                                        flex justify-center items-center w-10 h-10 cursor-pointer"
                                        @click="
                                        editMode = true;
                                        pakanId = '{{ $pkn->id }}';
                                        namaPakan = '{{ $pkn->nama_pakan }}';
                                        jenisPakan = '{{ $pkn->jenis_pakan }}';
                                        berat = '{{ $pkn->berat }}';
                                        tanggalMasuk = '{{ $pkn->tanggal_masuk }}';
                                        hargaPerKg = '{{ $pkn->harga_per_kg }}';
                                    ">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button"
                                        class="swal-delete-pakan px-3 py-3 rounded bg-red-100 text-red-700 
                                        flex justify-center items-center w-10 h-10 cursor-pointer swal-delete-pakan"
                                        data-id="{{ $pkn->id }}" data-url="{{ route('pakan.destroy', $pkn->id) }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pakan->links('pagination::tailwind') }}
            </div>
        </div>
    </main>

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
                                        method: 'POST', // Laravel butuh POST dengan _method DELETE
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            _method: 'DELETE'
                                        }) // Simulasi DELETE
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(
                                            data
                                        ); // Debugging untuk memastikan response diterima

                                        if (data.success) {
                                            Swal.fire('Terhapus!', data.message,
                                                    'success')
                                                .then(() => location.reload());
                                        } else {
                                            Swal.fire('Gagal!', data.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:',
                                            error); // Debugging error
                                        Swal.fire('Gagal!', 'Terjadi kesalahan server.',
                                            'error');
                                    });
                            }
                        });
                    });
                });
            }

            // Panggil fungsi untuk tabel kandang
            handleDelete('.swal-delete-pakan', 'Management Pakan');
        });
    </script>

@endsection
