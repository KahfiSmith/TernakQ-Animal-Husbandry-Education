@extends('layouts.dashboard-layout')

@section('title', 'Manajemen Keuangan Pengeluaran')

@section('content')
    <main class="flex flex-col space-y-6" x-data="{
        editMode: false,
        pengeluaranId: '',
        category: '',
        description: '',
        jumlah: '',
        satuan: '',
        hargaPerSatuan: '',
        totalBiaya: '',
        supplier: '',
        tanggalPembelian: ''
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

        <nav class="text-sm text-gray-600 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('finance-management') }}" wire:navigate
                        class="text-gray-500 hover:text-gray-700 inline-flex items-center ease-in-out duration-300 hover:underline">
                        Manajemen Keuangan
                    </a>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li aria-current="page" class="text-gray-500 font-normal">
                    Pengeluaran
                </li>
            </ol>
        </nav>

        <div class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">
                <span x-text="editMode ? 'Edit Pengeluaran' : 'Tambah Pengeluaran'"></span>
            </h2>

            <form method="POST"
                :action="editMode ? '{{ url('finance-management-outcome') }}/' + pengeluaranId :
                    '{{ route('pengeluaran.store') }}'"
                class="space-y-6 max-full">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="justify-between flex flex-col space-y-6">
                    <div class="flex flex-col space-y-6">
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="category" :value="__('Kategori')" required />
                            <select id="category" name="category"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                                rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 
                                text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                x-model="category">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Pembelian Ayam">Pembelian Ayam DOC</option>
                                <option value="Pakan Ayam">Pembelian Pakan Ayam</option>
                                <option value="Obat, Vitamin, Vaksin">Obat, Vitamin, Vaksin</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-1" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="description" :value="__('Deskripsi Pengeluaran')" required />
                            <x-text-input id="description" name="description" type="text"
                                class="block mt-1 w-full py-2.5" x-model="description" />
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="jumlah" :value="__('Jumlah')" required />
                            <x-text-input id="jumlah" name="jumlah" type="number" class="block mt-1 w-full py-2.5"
                                x-model="jumlah" oninput="validateNumber(this)" />
                            <x-input-error :messages="$errors->get('jumlah')" class="mt-1" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="satuan" :value="__('Satuan')" required />
                            <select id="satuan" name="satuan"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                                rounded-md focus:outline-none focus:ring-2 focus:ring-gray-700 
                                text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                x-model="satuan">
                                <option value="" disabled selected>Pilih Satuan</option>
                                <option value="ekor">Ekor</option>
                                <option value="kg">Kilogram</option>
                                <option value="karung">Karung</option>
                                <option value="botol">Botol</option>
                                <option value="unit">Unit</option>
                                <option value="paket">Paket</option>
                            </select>
                            <x-input-error :messages="$errors->get('satuan')" class="mt-1" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="harga_per_satuan" :value="__('Harga per Satuan (IDR)')" required />
                            <x-text-input id="harga_per_satuan" name="harga_per_satuan" type="number"
                                class="block mt-1 w-full py-2.5" min="0" x-model="hargaPerSatuan"
                                oninput="validateNumber(this)" />
                            <x-input-error :messages="$errors->get('harga_per_satuan')" class="mt-1" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="tanggal_pembelian" :value="__('Tanggal Pembelian')" required />
                            <x-text-input id="tanggal_pembelian" name="tanggal_pembelian" type="date"
                                class="block mt-1 w-full py-2.5" x-model="tanggalPembelian" />
                            <x-input-error :messages="$errors->get('tanggal_pembelian')" class="mt-1" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="supplier" :value="__('Supplier (Opsional)')" />
                            <x-text-input id="supplier" name="supplier" type="text" class="block mt-1 w-full py-2.5"
                                x-model="supplier" />
                            <x-input-error :messages="$errors->get('supplier')" class="mt-1" />
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <x-primary-button type="submit"
                            class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded"
                            x-text="editMode ? 'Update Pengeluaran' : 'Tambah Pengeluaran'"></x-primary-button>
                        <x-primary-button type="button" x-show="editMode"
                            @click="editMode = false; pengeluaranId = ''; category = ''; description = ''; jumlah = ''; satuan = ''; hargaPerSatuan = ''; totalBiaya = ''; supplier = ''; tanggalPembelian = '';"
                            class="ml-5 bg-gray-500 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded">
                            Batal
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Pengeluaran</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Satuan</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Total Biaya</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengeluaran as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->category === 'Listrik, Air, Peralatan' ? $item->custom_satuan : $item->satuan }}
                                </td>
                                <td>Rp {{ number_format($item->harga_per_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                                <td>{{ date('d M Y', strtotime($item->tanggal_pembelian)) }}</td>
                                <td>{{ $item->supplier }}</td>
                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                    <button type="button"
                                        class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 w-12 h-12"
                                        @click="
                                        editMode = true;
                                        pengeluaranId = '{{ $item->id }}';
                                        category = '{{ $item->category }}';
                                        description = '{{ $item->description }}';
                                        jumlah = '{{ $item->jumlah }}';
                                        satuan = '{{ $item->satuan }}';
                                        hargaPerSatuan = '{{ $item->harga_per_satuan }}';
                                        totalBiaya = '{{ $item->total_biaya }}';
                                        supplier = '{{ $item->supplier }}';
                                        tanggalPembelian = '{{ $item->tanggal_pembelian }}';
                                        ">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>

                                    <button type="button"
                                        class="swal-delete-pengeluaran px-3 py-3 bg-red-100 text-red-700 rounded w-12 h-12 cursor-pointer"
                                        data-id="{{ $item->id }}"
                                        data-url="{{ route('pengeluaran.destroy', $item->id) }}">
                                        <i class="fa-solid fa-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pengeluaran->links('pagination::tailwind') }}
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
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            _method: 'DELETE'
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(
                                            data
                                        );
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
                                            error);
                                        Swal.fire('Gagal!', 'Terjadi kesalahan server.',
                                            'error');
                                    });
                            }
                        });
                    });
                });
            }

            handleDelete('.swal-delete-pengeluaran', 'Pengeluaran');
        });
    </script>

    <script>
        function validateNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
@endsection
