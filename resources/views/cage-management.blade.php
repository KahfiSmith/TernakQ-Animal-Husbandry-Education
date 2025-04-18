@extends('layouts.dashboard-layout')

@section('title', 'Manajemen Pakan')

@section('content')
    <main class="flex space-x-6 w-full" x-data="{
        editMode: false,
        kandangId: '',
        namaKandang: '',
        kapasitas: '',
        statusKandang: 'Aktif'
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

        <div
            class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200 w-1/2 max-h-[480px]">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">
                <span x-text="editMode ? 'Edit Kandang' : 'Tambah Kandang'"></span>
            </h2>
            <form method="POST"
                :action="editMode ? '{{ url('cage-management') }}/' + kandangId : '{{ route('kandang.store') }}'"
                class="space-y-4 max-full">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="justify-between flex flex-col space-y-10">
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col space-y-1">
                            <x-input-label for="nama_kandang" :value="__('Nama Kandang')" required/>
                            <x-text-input id="nama_kandang" name="nama_kandang" type="text"
                                class="block mt-1 w-full py-2.5" x-model="namaKandang" />
                            <x-input-error :messages="$errors->get('nama_kandang')" class="mt-2" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="kapasitas" :value="__('Kapasitas')" required/>
                            <x-text-input id="kapasitas" name="kapasitas" type="text" class="block mt-1 w-full py-2.5"
                                x-model="kapasitas" oninput="validateNumber(this)" />
                            <x-input-error :messages="$errors->get('kapasitas')" class="mt-2" />
                        </div>

                        <div class="flex flex-col space-y-1">
                            <x-input-label for="status_kandang" :value="__('Status Kandang')" required/>
                            <select id="status_kandang" name="status_kandang"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                                rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 
                                text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                x-model="statusKandang">
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <x-primary-button type="submit"
                            class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded"
                            x-text="editMode ? 'Update Kandang' : 'Tambah Kandang'">
                        </x-primary-button>
                        <x-primary-button type="button" x-show="editMode"
                            @click="editMode = false; namaKandang = ''; kapasitas = ''; kandangId = '';"
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
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Kandang Ayam</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Kandang</th>
                            <th class="px-4 py-3">Kapasitas</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($kandang as $key => $kdg)
                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $kdg->nama_kandang }}</td>
                                <td class="px-4 py-3">{{ $kdg->kapasitas }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-3 py-1 rounded text-xs font-semibold {{ $kdg->status_kandang == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $kdg->status_kandang }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                    <button type="button"
                                        class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 
                                        flex justify-center items-center w-12 h-12 cursor-pointer"
                                        @click="
                                    editMode = true;
                                    kandangId = '{{ $kdg->id }}';
                                    namaKandang = '{{ $kdg->nama_kandang }}';
                                    kapasitas = '{{ $kdg->kapasitas }}';
                                    statusKandang = '{{ $kdg->status_kandang }}';
                                    ">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>

                                    <button type="button"
                                        class="swal-delete-kandang px-3 py-3 rounded bg-red-100 text-red-700 
                                            flex justify-center items-center w-12 h-12 cursor-pointer"
                                        data-id="{{ $kdg->id }}" data-url="{{ route('kandang.destroy', $kdg->id) }}">
                                        <i class="fa-solid fa-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kandang->links('pagination::tailwind') }}
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

            handleDelete('.swal-delete-kandang', 'Kandang Ayam');
        });
    </script>

    <script>
        function validateNumber(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
@endsection
