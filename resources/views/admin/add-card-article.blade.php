@extends('layouts.admin-layout')

@section('title', 'Dashboard - Tambah Grup Artikel')

@section('content')
    <main class="flex flex-col space-y-6 w-full" x-data="{
        editMode: false,
        articleId: '',
        title: '',
        description: '',
        image: '',
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
                    Grup Artikel
                </li>
            </ol>
        </nav>

        <div class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">
                <span x-text="editMode ? 'Edit Grup Artikel' : 'Tambah Grup Artikel'"></span>
            </h2>
            <form method="POST"
                :action="editMode ? '{{ url('/admin/add-article') }}/' + articleId : '{{ route('admin.user-article.store') }}'"
                class="space-y-6 max-full" enctype="multipart/form-data">
                @csrf

                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="flex flex-col space-y-1">
                    <x-input-label for="title" :value="__('Judul Grup Artikel')" />
                    <x-text-input id="title" name="title" type="text" class="block mt-1 w-full py-2.5" required
                        x-model="title" />
                </div>

                <!-- Input Deskripsi -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="description" :value="__('Deskripsi Grup Artikel')" required/>
                    <textarea id="description" name="description"
                        class="block mt-1 w-full h-[100px] resize-none py-2.5 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                        focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                        rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                        text-gray-700 leading-5 transition duration-150 ease-in-out"
                        x-model="description"></textarea>
                </div>

                <!-- Input Gambar -->
                <div class="flex flex-col space-y-1">
                    <x-input-label for="image" :value="__('Gambar Grup Artikel')" required/>

                    <div x-data="{ imagePreview: null }" class="relative w-full">
                        <label for="image"
                            class="cursor-pointer flex flex-col items-center justify-center border-2 border-gray-700 
                            shadow-[4px_4px_0px_2px_#374151] rounded-md p-6 hover:bg-gray-100 transition duration-150 ease-in-out">

                            <div x-show="!imagePreview" class="flex flex-col items-center space-y-2">
                                <i class="fa-solid fa-image text-4xl"></i>
                                <span class="text-gray-700 font-medium">Klik Gambar di Sini</span>
                            </div>

                            <!-- Preview Image -->
                            <div x-show="imagePreview" class="relative w-full flex justify-center">
                                <img :src="imagePreview" class="w-[250px] h-[200px] rounded-md shadow-md object-cover" />
                                <button type="button"
                                    class="absolute top-0 right-0 bg-gray-800 text-white rounded-full p-1 
                                    -mt-2 -mr-2 shadow-md hover:bg-red-600 transition w-8 h-8"
                                    @click="imagePreview = null; document.getElementById('image').value = ''">
                                    <i class="fa-solid fa-xmark"></i> <!-- FontAwesome X icon -->
                                </button>
                            </div>

                            <!-- Menampilkan gambar yang sudah ada -->
                            <div x-show="!imagePreview && image">
                                <img :src="image" alt="Image"
                                    class="w-[250px] h-[200px] rounded-md shadow-md object-cover" />
                            </div>
                        </label>

                        <input type="file" id="image" name="image" accept="image/*" class="hidden"
                            @change="const file = $event.target.files[0]; 
                                     if (file) { 
                                         const reader = new FileReader();
                                         reader.onload = (e) => imagePreview = e.target.result;
                                         reader.readAsDataURL(file);
                                     }" />
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-start">
                    <x-primary-button type="submit"
                        class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded"
                        x-text="editMode ? 'Update Grup Artikel' : 'Tambah Grup Artikel'">
                    </x-primary-button>
                    <x-primary-button type="button" x-show="editMode"
                        @click="editMode = false; title = ''; description = ''; artikelId = '';"
                        class="ml-5 bg-gray-500 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded">
                        Batal
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div class="flex space-x-6 items-center justify-between">
            <div>
                <a href="{{ route('add-article-detail') }}" wire:navigate
                    class="inline-flex justify-center items-center text-center font-medium text-base tracking-widest focus:outline-none focus-visible:outline-none transition ease-in-out duration-150 bg-pewterBlue ring-2
                ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151]
                hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">Tambah
                    Artikel</a>
            </div>
            <div class="flex space-x-6 items-center">
                <!-- Artikel Tertunda -->
                <div class="flex flex-col justify-center items-center space-y-1 bg-orange-200 py-2 px-4 rounded-md ring-2 ring-orange-400">
                    <h3 class="font-medium text-normal text-orange-700">Artikel Tertunda</h3>
                    <span class="font-semibold text-xl text-orange-700">{{ $pendingCount }}</span>
                </div>

                <!-- Artikel Disetujui -->
                <div class="flex flex-col justify-center items-center space-y-1 bg-blue-200 py-2 px-4 rounded-md ring-2 ring-blue-400">
                    <h3 class="font-medium text-normal text-blue-700">Artikel Disetujui</h3>
                    <span class="font-semibold text-xl text-blue-700">{{ $approvedCount }}</span>
                </div>

                <!-- Artikel Ditolak -->
                <div class="flex flex-col justify-center items-center space-y-1 bg-red-200 py-2 px-4 rounded-md  ring-2 ring-red-400">
                    <h3 class="font-medium text-normal text-red-700">Artikel Ditolak</h3>
                    <span class="font-semibold text-xl text-red-700">{{ $rejectedCount }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Grup Artikel</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead class="text-gray-600 uppercase text-sm tracking-wide">
                        <tr class="border-b-2 border-gray-700">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Gambar</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($articles as $article)
                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $article->title }}</td>
                                <td class="px-4 py-3">{{ Str::limit($article->description, 50) }}</td>
                                <td class="px-4 py-3">
                                    @if ($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" alt="Image"
                                            class="w-24 h-24 object-cover rounded mx-auto">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                    <button type="button"
                                        class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                        @click="editMode = true; 
                                        articleId = '{{ $article->id }}';  <!-- gunakan articleId -->
                                        title = '{{ $article->title }}';
                                        description = '{{ $article->description }}';
                                        image = '{{ asset('storage/' . $article->image) }}';
                                        console.log('Artikel ID:', articleId);">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>

                                    <!-- Tombol Hapus Artikel -->
                                    <button type="button"
                                        class="swal-delete-user-article px-3 py-3 rounded bg-red-100 text-red-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                        data-id="{{ $article->id }}"
                                        data-url="{{ route('admin.user-article.destroy', $article->id) }}">
                                        <i class="fa-solid fa-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $articles->links('pagination::tailwind') }}
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
            handleDelete('.swal-delete-user-article', 'Tambah Artikel');
        });
    </script>
@endsection
