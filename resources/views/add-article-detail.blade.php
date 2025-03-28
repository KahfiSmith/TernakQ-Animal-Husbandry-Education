@extends('layouts.dashboard-layout')

@section('title', 'Dashboard - Tambah Artikel')

@section('content')
<main class="flex flex-col space-y-6 w-full" x-data="{
    editMode: false,
    articleId: '',
    title: '',
    description: '',
    image: '',
    status: 'Tertunda',
    cardId: '',
    tags: []
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
                <a href="{{ route('add-article') }}" wire:navigate
                    class="text-gray-500 hover:text-gray-700 inline-flex items-center ease-in-out duration-300 hover:underline">
                    Grup Artikel
                </a>
            </li>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li aria-current="page" class="text-gray-500 font-normal">
                Artikel
            </li>
        </ol>
    </nav>

    <!-- Form Input Artikel (Tambah & Edit) -->
    <div class="flex flex-col p-4 sm:p-6 bg-white shadow sm:rounded-lg ring-2 ring-gray-700 border-b-gray-200">
        <h2 class="text-xl font-bold mb-2 text-orangeCrayola">
            <span x-text="editMode ? 'Edit Artikel' : 'Tambah Artikel'"></span>
        </h2>
        <form method="POST"
            :action="editMode ? '{{ url('add-article-detail') }}/' + articleId : '{{ route('user-article-detail.store') }}'"
            class="space-y-6 max-full" enctype="multipart/form-data">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <!-- Dropdown untuk memilih CardArticle -->
            <div class="flex flex-col space-y-1">
                <x-input-label for="card_id" :value="__('Pilih Artikel Grup')" required/>
                <select id="card_id" name="card_id" x-model="cardId" class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5" required>
                    @foreach ($cardArticles as $card)
                        <option value="{{ $card->id }}" @selected(old('card_id') == $card->id)>{{ $card->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Input untuk Judul Artikel -->
            <div class="flex flex-col space-y-1">
                <x-input-label for="title" :value="__('Judul Artikel')" required/>
                <x-text-input id="title" name="title" type="text" class="block mt-1 w-full py-2.5" required x-model="title" />
            </div>

            <!-- Input untuk Deskripsi Artikel -->
            <div class="flex flex-col space-y-1">
                <x-input-label for="description" :value="__('Deskripsi Artikel')" required/>
                <textarea id="description" name="description"
                class="block mt-1 w-full h-[100px] resize-none py-2.5 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                text-gray-700 leading-5 transition duration-150 ease-in-out"
                    x-model="description"></textarea>
            </div>

            <div class="flex flex-col space-y-1">
                <x-input-label for="image" :value="__('Gambar Artikel')" required/>
                <div x-data="{ imagePreview: null }" class="relative w-full">
                    <label for="image"
                           class="cursor-pointer flex flex-col items-center justify-center border-2 border-gray-700 shadow-[4px_4px_0px_2px_#374151] rounded-md p-6 hover:bg-gray-100 transition duration-150 ease-in-out">
                        <div x-show="!imagePreview" class="flex flex-col items-center space-y-2">
                            <i class="fa-solid fa-image text-4xl"></i>
                            <span class="text-gray-700 font-medium">Klik Gambar di Sini</span>
                        </div>
                        <!-- Preview Image -->
                        <div x-show="imagePreview" class="relative w-full flex justify-center">
                            <img :src="imagePreview" class="w-[250px] h-[200px] rounded-md shadow-md object-cover" />
                            <button type="button"
                                    class="absolute top-0 right-0 bg-gray-800 text-white rounded-full p-1 -mt-2 -mr-2 shadow-md hover:bg-red-600 transition w-8 h-8"
                                    @click="imagePreview = null; document.getElementById('image').value = ''">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <!-- Menampilkan gambar yang sudah ada (jika dalam mode edit) -->
                        <div x-show="!imagePreview && image">
                            <img :src="image" alt="Image" class="w-[250px] h-[200px] rounded-md shadow-md object-cover" />
                        </div>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden"
                           @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }" />
                </div>
            </div>

            <input type="hidden" name="status" value="Tertunda">

            <div class="flex flex-col space-y-1">
                <x-input-label for="tags" :value="__('Pilih 3 Tag Artikel')" required/>
                <div class="relative">
                    <button type="button"
                            class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                            onclick="toggleTagDropdown()">
                            Pilih 3 Tag Artikel
                        <i class="fa fa-chevron-down ml-2"></i>
                    </button>
                    <div id="tags-dropdown" style="display: none;" class="absolute w-full mt-1 bg-white border-2 border-gray-300 rounded-md shadow-lg z-10">
                        @foreach($tags as $tag)
                            <label class="block px-4 py-2 text-gray-700 hover:bg-gray-100 cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                    onclick="updateSelectedTags({{ $tag->id }}, this)" class="mr-2">
                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
                          
            <!-- Tombol Submit -->
            <div class="flex justify-start">
                <x-primary-button type="submit"
                    class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white py-2.5 px-4 rounded"
                    x-text="editMode ? 'Update Artikel' : 'Tambah Artikel'">
                </x-primary-button>
                <x-primary-button type="button" x-show="editMode"
                    @click="editMode = false; articleId = ''; title = ''; description = ''; status = 'Tertunda'; cardId = ''; tags = [];"
                    class="ml-5 bg-gray-500 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">
                    Batal
                </x-primary-button>
            </div>
        </form>
    </div>

    <div class="flex space-x-6 items-center justify-between">
        <div>
            <a href="{{ route('add-article-sub') }}" wire:navigate class="inline-flex justify-center items-center text-center font-medium text-base tracking-widest focus:outline-none focus-visible:outline-none transition ease-in-out duration-150 bg-pewterBlue ring-2
            ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151]
            hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">Tambah Sub Artikel</a>
        </div>
        <div class="flex space-x-6 items-center">
            
            <!-- Artikel Tertunda -->
            <div class="flex flex-col justify-center items-center space-y-1 bg-orange-200 py-2 px-4 rounded-md ring-2 ring-orange-400">
                <h3 class="font-medium text-normal text-orange-700">Total Artikel</h3>
                <span class="font-semibold text-xl text-orange-700">{{ $totalArticles }}</span>
            </div>
    
            <!-- Artikel Disetujui -->
            <div class="flex flex-col justify-center items-center space-y-1 bg-blue-200 py-2 px-4 rounded-md ring-2 ring-blue-400">
                <h3 class="font-medium text-normal text-blue-700">Artikel Hari Ini</h3>
                <span class="font-semibold text-xl text-blue-700">{{ $todayArticles }}</span>
            </div>
        </div>
    </div>

    <!-- Tabel Artikel -->
    <div class="bg-white p-6 rounded-lg shadow-md w-full ring-2 ring-gray-700">
        <h2 class="text-xl font-bold mb-2 text-orangeCrayola">Data Artikel</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead class="text-gray-600 uppercase text-sm tracking-wide">
                    <tr class="border-b-2 border-gray-700">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Grup Artikel</th>
                        <th class="px-4 py-3">Judul Artikel</th>
                        <th class="px-4 py-3">Catatan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @foreach ($articles as $article)
                        <tr class="hover:bg-gray-50 border-b border-gray-200">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $article->cardArticle->title ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $article->title }}</td>
                            <td class="px-4 py-3">-</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-3 py-1 rounded text-xs font-semibold {{ $article->status == 'Tertunda' ? 'bg-yellow-100 text-yellow-700' : ($article->status == 'Disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $article->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="Image"
                                         class="w-24 h-24 object-cover rounded mx-auto">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td class="px-4 py-3 flex gap-3 justify-center items-center">
                                <!-- Tombol Edit -->
                                <button type="button"
                                    class="px-3 py-3 rounded text-xs font-semibold bg-blue-100 text-blue-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                    @click="
                                        editMode = true;
                                        articleId = '{{ $article->id }}';
                                        title = '{{ $article->title }}';
                                        description = '{{ $article->description }}';
                                        status = '{{ $article->status }}';
                                        cardId = '{{ $article->card_id }}';
                                        tags = {{ json_encode($article->tags->pluck('id')->toArray()) }};
                                        image = '{{ asset('storage/' . $article->image) }}';
                                    ">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </button>
                            
                                <!-- Tombol Hapus -->
                                <button type="button"
                                    class="swal-delete-article px-3 py-3 rounded bg-red-100 text-red-700 flex justify-center items-center w-12 h-12 cursor-pointer"
                                    data-id="{{ $article->id }}"
                                    data-url="{{ route('user-article-detail.destroy', $article->id) }}">
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
        handleDelete('.swal-delete-article', 'Artikel');
    });
</script>

<script>
    function toggleTagDropdown() {
        const dropdown = document.getElementById('tags-dropdown');
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    }
</script>

<script>
    let selectedTags = [];

    function updateSelectedTags(tagId, checkbox) {
        // Cek jika tag sudah terpilih atau tidak
        if (checkbox.checked) {
            if (selectedTags.length < 3) {
                selectedTags.push(tagId);
            } else {
                // Jika sudah memilih 3 tag, beri peringatan dan batal memilih tag ini
                alert("Anda hanya dapat memilih 3 tag.");
                checkbox.checked = false; // Batalkan pilihan tag ini
            }
        } else {
            // Hapus tag dari list jika dibatalkan
            selectedTags = selectedTags.filter(id => id !== tagId);
        }

        // Menonaktifkan checkbox lebih dari 3 tag yang dipilih
        const checkboxes = document.querySelectorAll('input[name="tags[]"]');
        checkboxes.forEach(function(checkbox) {
            if (selectedTags.length >= 3 && !checkbox.checked) {
                checkbox.disabled = true;  // Disable checkbox jika lebih dari 3 tag dipilih
            } else {
                checkbox.disabled = false; // Enable kembali checkbox jika ada yang dibatalkan
            }
        });
    }
</script>

@endsection
