@extends('layouts.admin-layout')

@section('title', 'Dashboard - Tambah Sub Artikel')

@section('content')
    <main class="flex flex-col space-y-6 w-full" x-data="{
        subArticles: [{
            title: '',
            content: '',
            order_number: 1,
            image: null,
        }],
        addSubArticle() {
            this.subArticles.push({ title: '', content: '', order_number: 1, image: null });
        },
        removeSubArticle(index) {
            this.subArticles.splice(index, 1);
        }
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
        <div class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700">
            <h2 class="text-xl font-bold mb-6 text-orangeCrayola">Tambah Banyak Sub Artikel Sekaligus</h2>
            <form method="POST" action="{{ route('user-article-sub.store-multiple') }}" enctype="multipart/form-data">
                @csrf

                <!-- Pilih Artikel Induk -->
                <div class="flex flex-col space-y-1 mb-6">
                    <x-input-label for="article_id" :value="__('Pilih Artikel Induk')" />
                    <select name="article_id" required
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                                rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 
                                text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5">
                        @foreach ($articles as $article)
                            <option value="{{ $article->id }}">{{ $article->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Form Dinamis untuk Sub Artikel -->
                <template x-for="(sub, index) in subArticles" :key="index">
                    <div class="border-2 border-gray-700 rounded-lg p-4 mb-6 relative space-y-6">
                        <button type="button" @click="removeSubArticle(index)"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <i class="fa-solid fa-times"></i>
                        </button>

                        <div>
                            <!-- Label dinamis dengan Alpine.js -->
                            <label for="sub_title" class="block font-medium text-sm text-gray-700 mb-4 !important"
                                x-text="`Judul Sub Artikel ${index + 1}`"></label>
                            <input type="text" :name="'sub_articles[' + index + '][title]'" required
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] 
                                      focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none 
                                      focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out 
                                      block mt-1 w-full py-2.5 mb-6 !important"
                                x-model="sub.title" />

                            <label for="sub_content" class="block font-medium text-sm text-gray-700 mb-4 !important"
                                x-text="`Konten Sub Artikel ${index + 1}`"></label>
                            <textarea :name="'sub_articles[' + index + '][content]'" rows="3" required
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] 
                                         focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none 
                                         focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out 
                                         block mt-1 w-full py-2.5 mb-6 !important"
                                x-model="sub.content"></textarea>

                            <label for="sub_order" class="block font-medium text-sm text-gray-700 mb-4 !important"
                                x-text="`Urutan ${index + 1}`"></label>
                            <input type="number" min="1" :name="'sub_articles[' + index + '][order_number]'"
                                required
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] 
                                      focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none 
                                      focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out 
                                      block mt-1 w-full py-2.5 mb-6 !important"
                                x-model="sub.order_number" />

                            <label for="sub_image" class="block font-medium text-sm text-gray-700 mb-4 !important"
                                x-text="`Gambar (Opsional) ${index + 1}`"></label>
                                <div x-data="{ imagePreview: null }" class="relative w-full">
                                    <!-- Custom File Upload -->
                                    <label for="image" class="cursor-pointer flex flex-col items-center justify-center border-2 border-gray-700 
                                                             shadow-lg rounded-md p-6 hover:bg-gray-100 transition duration-150 ease-in-out">
                                        <!-- Preview Image -->
                                        <div x-show="!imagePreview" class="flex flex-col items-center space-y-2">
                                            <i class="fa-solid fa-image text-4xl text-gray-700"></i>
                                            <span class="text-gray-700 font-medium">Klik untuk Unggah Gambar</span>
                                        </div>
                                
                                        <!-- Preview the Image once uploaded -->
                                        <div x-show="imagePreview" class="relative w-full flex justify-center">
                                            <img :src="imagePreview" class="w-[250px] h-[200px] object-cover rounded-md shadow-md" />
                                            <button type="button" class="absolute top-0 right-0 bg-gray-800 text-white rounded-full p-1 -mt-2 -mr-2 shadow-md hover:bg-red-600 transition w-8 h-8"
                                                    @click="imagePreview = null; document.getElementById('image').value = ''">
                                                <i class="fa-solid fa-xmark"></i> <!-- Close button -->
                                            </button>
                                        </div>
                                    </label>
                                
                                    <!-- Actual File Input -->
                                    <input type="file" :name="'sub_articles[' + index + '][image]'" id="image" class="hidden"
                                           @change="const file = $event.target.files[0]; if(file) { 
                                                const reader = new FileReader();
                                                reader.onload = (e) => imagePreview = e.target.result;
                                                reader.readAsDataURL(file);
                                           }" />
                                </div>
                                
                        </div>
                    </div>
                </template>

                <div class="flex justify-between">
                    <x-primary-button type="button" @click="addSubArticle()"
                        class="bg-pewterBlue ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded">
                        + Tambah Sub Artikel
                    </x-primary-button>
                    <x-primary-button type="submit"
                        class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                        text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 
                        hover:translate-x-0.5 py-2.5 px-4 rounded">
                        Simpan Semua Sub Artikel
                    </x-primary-button>
                </div>
            </form>
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
            handleDelete('.swal-delete-sub-article', 'Tambah Sub Artikel');
        });
    </script>
@endsection
