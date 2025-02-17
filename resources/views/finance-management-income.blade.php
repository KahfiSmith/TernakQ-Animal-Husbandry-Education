@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Manajemen Keuangan Pendapatan')

@section('content')
    <main class="flex flex-col space-y-6" x-data="{
        editMode: false,
        pendapatanId: '',
        kategori: '',
        jumlah: '',
        satuan: '',
        hargaPerSatuan: '',
        tanggalTransaksi: '',
        namaPembeli: '',
        namaPerusahaan: ''
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

        <!-- Form Tambah Pendapatan -->
        <div class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200"
            >
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">
                <span x-text="editMode ? 'Edit Pendapatan' : 'Tambah Pendapatan'"></span>
            </h2>

            <form method="POST" :action="editMode ? '{{ url('finance-management-income') }}/' + pendapatanId : '{{ route('pendapatan.store') }}'" 
                class="space-y-6 max-full">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="justify-between flex flex-col space-y-6">
                    <div class="flex flex-col space-y-6">
                        <!-- Kategori Pendapatan -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="kategori" :value="__('Kategori')" />
                            <select id="kategori" name="kategori"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                            focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                            rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 
                            text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                x-model="kategori">
                                <option value="Penjualan Ayam">Penjualan Ayam</option>
                                <option value="Penjualan Kotoran">Penjualan Kotoran</option>
                                <option value="Pendapatan Kemitraan">Pendapatan Kemitraan</option>
                            </select>
                        </div>

                        <!-- Jumlah -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="jumlah" :value="__('Jumlah')" />
                            <x-text-input id="jumlah" name="jumlah" type="number" class="block mt-1 w-full py-2.5"
                                required min="1" x-model="jumlah" />
                        </div>

                        <!-- Satuan -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="satuan" :value="__('Satuan')" />
                            <select id="satuan" name="satuan"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                            focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                            rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 
                            text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                x-model="satuan">
                                <option value="ekor">Ekor (Ayam)</option>
                                <option value="kg">Kilogram</option>
                                <option value="karung">Karung (Kotoran)</option>
                            </select>
                        </div>

                        <!-- Harga per Satuan -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="harga_per_satuan" :value="__('Harga per Satuan (IDR)')" />
                            <x-text-input id="harga_per_satuan" name="harga_per_satuan" type="number"
                                class="block mt-1 w-full py-2.5" required min="0" x-model="hargaPerSatuan" />
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="tanggal_transaksi" :value="__('Tanggal Transaksi')" />
                            <x-text-input id="tanggal_transaksi" name="tanggal_transaksi" type="date"
                                class="block mt-1 w-full py-2.5" required x-model="tanggalTransaksi" />
                        </div>

                        <!-- Nama Pembeli (Opsional) -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="nama_pembeli" :value="__('Nama Pembeli (Opsional)')" />
                            <x-text-input id="nama_pembeli" name="nama_pembeli" type="text"
                                class="block mt-1 w-full py-2.5" x-model="namaPembeli" />
                        </div>

                        <!-- Nama Perusahaan (Opsional) -->
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="nama_perusahaan" :value="__('Nama Perusahaan (Opsional)')" />
                            <x-text-input id="nama_perusahaan" name="nama_perusahaan" type="text"
                                class="block mt-1 w-full py-2.5" x-model="namaPerusahaan" />
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-start">
                        <x-primary-button type="submit" class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded" x-text="editMode ? 'Update Pendapatan' : 'Tambah Pendapatan'"></x-primary-button>
                        <x-primary-button type="button" x-show="editMode" 
                            @click="editMode = false; pendapatanId = ''; kategori = ''; jumlah = ''; satuan = ''; hargaPerSatuan = ''; tanggalTransaksi = ''; namaPembeli = ''; namaPerusahaan = '';"
                            class="ml-5 bg-gray-500 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded">
                            Batal
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Data Pendapatan -->
        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Pendapatan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Satuan</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Nama Pembeli</th>
                            <th class="px-4 py-3">Nama Perusahaan</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($pendapatan as $item)
                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $item->kategori }}</td>
                                <td class="px-4 py-3">{{ $item->jumlah }}</td>
                                <td class="px-4 py-3">{{ $item->satuan }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($item->harga_per_satuan, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($item->jumlah * $item->harga_per_satuan, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ date('d M Y', strtotime($item->tanggal_transaksi)) }}</td>
                                <td class="px-4 py-3">{{ $item->nama_pembeli ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $item->nama_perusahaan ?? '-' }}</td>
                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                    <button type="button"
                                        class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 w-12 h-12"
                                        @click="
                                            editMode = true;
                                            pendapatanId = '{{ $item->id }}';
                                            kategori = '{{ $item->kategori }}';
                                            jumlah = '{{ $item->jumlah }}';
                                            satuan = '{{ $item->satuan }}';
                                            hargaPerSatuan = '{{ $item->harga_per_satuan }}';
                                            tanggalTransaksi = '{{ $item->tanggal_transaksi }}';
                                            namaPembeli = '{{ $item->nama_pembeli }}';
                                            namaPerusahaan = '{{ $item->nama_perusahaan }}';
                                        ">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>

                                    <button type="button"
                                        class="swal-delete-pendapatan px-3 py-3 bg-red-100 text-red-700 rounded w-12 h-12 cursor-pointer"
                                        data-id="{{ $item->id }}" data-url="{{ route('pendapatan.destroy', $item->id) }}">
                                        <i class="fa-solid fa-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            handleDelete('.swal-delete-pendapatan', 'Pendapatan');
        });
    </script>

@endsection
