@extends('layouts.dashboard-layout')

@section('title', 'Tambah Sub Artikel')

@section('content')
    <main class="flex flex-col space-y-6 w-full">
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
                    <a href="{{ route('add-article') }}" class="text-gray-500 hover:text-gray-700 hover:underline">Grup
                        Artikel</a>
                </li>
                <li>
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{ route('add-article-detail') }}"
                        class="text-gray-500 hover:text-gray-700 hover:underline">Artikel</a>
                </li>
                <li>
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </li>
                <li class="text-gray-500 font-normal">Sub Artikel</li>
            </ol>
        </nav>

        <div class="flex justify-between gap-6 w-full">
            <div class="w-full">
                <div class="p-6 bg-white shadow-md rounded-lg ring-2 ring-gray-700 w-full">
                    <h2 class="text-xl font-bold mb-6 text-orangeCrayola">Tambah Banyak Sub Artikel Sekaligus</h2>
                    <form method="POST"
                        action="{{ isset($subArticle) ? route('user-article-sub.update', ['id' => $subArticle->id]) : route('user-article-sub.store-multiple') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($subArticle)
                            @method('PUT')
                        @endisset

                        <div class="flex flex-col space-y-1 mb-6">
                            <x-input-label for="article_id" :value="__('Pilih Artikel Induk')" required />
                            <select name="article_id" id="article_id"
                                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 
                                rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 
                                text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                @if (!isset($subArticle)) onchange="location.href='{{ route('add-article-sub') }}?article_id=' + this.value" @endif>
                                <option value="" selected disabled>Pilih Artikel</option>
                                @foreach ($articles as $article)
                                    <option value="{{ $article->id }}"
                                        {{ $article->id == old('article_id', optional($selectedArticle)->id) ? 'selected' : '' }}>
                                        {{ $article->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('article_id')" class="mt-2" />
                        </div>

                        <div id="sub-articles-container">
                            <div class="sub-article-item ring-2 ring-gray-700 rounded-lg p-6 mb-6 relative">
                                <div class="mb-6">
                                    <label class="block font-medium text-sm text-gray-700 mb-3">
                                        Judul Sub Artikel 1<span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="sub_articles[0][title]"
                                        value="{{ old('sub_articles.0.title', optional($subArticle)->title) }}"
                                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                        focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                        rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                                        text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                        placeholder="Judul Sub Artikel">
                                    <x-input-error :messages="$errors->get('sub_articles.0.title')" class="mt-2" />
                                </div>

                                <div class="mb-6">
                                    <label class="block font-medium text-sm text-gray-700 mb-3">
                                        Konten Sub Artikel 1<span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="sub_articles[0][content]" rows="3"
                                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                        focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                        rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                                        text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                        placeholder="Konten Sub Artikel">{{ old('sub_articles.0.content', optional($subArticle)->content) }}
                                    </textarea>
                                    <x-input-error :messages="$errors->get('sub_articles.0.content')" class="mt-2" />
                                </div>

                                <div class="mb-6"><label class="block font-medium text-sm text-gray-700 mb-3">
                                        Urutan 1<span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="sub_articles[0][order_number]"
                                        value="{{ old('sub_articles.0.order_number', optional($subArticle)->order_number) }}"
                                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                                    focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                                    rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                                    text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                                        placeholder="Urutan">
                                    <x-input-error :messages="$errors->get('sub_articles.0.order_number')" class="mt-2" />
                                </div>

                                <div class="flex flex-col">
                                    <x-input-label :for="'image_0'" :value="__('Gambar Artikel 1')" />
                                    <div class="relative w-full" id="image-upload-container_0">
                                        <label for="image_0"
                                            class="cursor-pointer flex flex-col items-center justify-center 
                                                      border-2 border-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                                                      rounded-md p-6 hover:bg-gray-100 transition duration-150 ease-in-out">

                                            <div id="noPreview_0"
                                                class="flex flex-col items-center space-y-2 {{ optional($subArticle)->image ? 'hidden' : '' }}">
                                                <i class="fa-solid fa-image text-4xl text-gray-700"></i>
                                                <span class="text-gray-700 font-medium">Klik Gambar di Sini</span>
                                            </div>

                                            <div id="preview_0"
                                                class="relative w-full flex justify-center {{ optional($subArticle)->image ? '' : 'hidden' }}">
                                                <img id="imagePreview_0"
                                                    class="w-[250px] h-[200px] rounded-md shadow-md object-cover"
                                                    src="{{ optional($subArticle)->image ? asset('storage/' . $subArticle->image) : '' }}" />
                                                <button type="button"
                                                    class="absolute top-0 right-0 bg-gray-800 text-white 
                                                           rounded-full p-1 -mt-2 -mr-2 shadow-md 
                                                           hover:bg-red-600 transition w-8 h-8"
                                                    onclick="clearPreview(0)">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                        </label>

                                        <input type="file" id="image_0" name="sub_articles[0][image]" accept="image/*"
                                            class="hidden" onchange="previewImage(event, 0)">

                                        <input type="hidden" id="remove_image_0" name="sub_articles[0][remove_image]"
                                            value="0">
                                        <x-input-error :messages="$errors->get('sub_articles.0.image')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-4">
                            <x-primary-button type="button" id="add-sub-article"
                                class="bg-pewterBlue ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                            text-white hover:shadow-[2px_2px_0px_2px_#374151]
                            hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">
                                + Tambah Sub Artikel
                            </x-primary-button>
                            <x-primary-button type="submit"
                                class="bg-orangeCrayola ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                            text-white hover:shadow-[2px_2px_0px_2px_#374151]
                            hover:translate-y-0.5 hover:translate-x-0.5 py-2.5 px-4 rounded">
                                Simpan Sub Artikel
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex flex-col gap-6 w-1/2">
                @if ($selectedArticle)
                    <div class="bg-white p-6 rounded-lg shadow-md ring-2 ring-gray-700 w-full">
                        <h2 class="text-xl font-bold mb-4 text-orangeCrayola">Artikel Induk</h2>
                        <div class="border-b border-gray-300 pb-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $selectedArticle->title }}
                            </h3>
                            <p class="text-gray-700 mt-2">{{ $selectedArticle->description }}</p>
                            <p class="text-sm text-gray-500">Status: {{ $selectedArticle->status }}</p>
                            @if ($selectedArticle->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $selectedArticle->image) }}" alt="Gambar Artikel"
                                        class="w-40 h-40 object-cover rounded-md shadow-md">
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="bg-white p-6 rounded-lg shadow-md ring-2 ring-gray-700 w-full">
                    <h2 class="text-xl font-bold text-orangeCrayola">Preview Sub Artikel</h2>

                    @if ($selectedArticle)
                        @if (isset($subArticle))
                            <div class="border-b border-gray-300 py-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $subArticle->title }}</h3>
                                <p class="text-gray-700 mt-2 break-words">{{ $subArticle->content }}</p>
                                <p class="text-sm text-gray-500">Urutan: {{ $subArticle->order_number }}</p>
                                @if ($subArticle->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $subArticle->image) }}" alt="Gambar Sub Artikel"
                                            class="w-40 h-40 object-cover rounded-md shadow-md">
                                    </div>
                                @endif

                                <a href="{{ route('user-article-sub.edit', ['id' => $subArticle->id]) }}"
                                    class="text-blue-500 hover:underline">Edit</a>

                                <form action="{{ route('user-article-sub.destroy', ['id' => $subArticle->id]) }}"
                                    method="POST" class="inline-block ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:underline swal-delete-user-subarticle">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @elseif ($subArticles->isNotEmpty())
                            @foreach ($subArticles as $sub)
                                <div class="border-b border-gray-300 py-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $sub->title }}</h3>
                                    <p class="text-gray-700 mt-2 break-words">{{ $sub->content }}</p>
                                    <p class="text-sm text-gray-500">Urutan: {{ $sub->order_number }}</p>
                                    @if ($sub->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $sub->image) }}" alt="Gambar Sub Artikel"
                                                class="w-40 h-40 object-cover rounded-md shadow-md">
                                        </div>
                                    @endif

                                    <div class="mt-4">
                                        <a href="{{ route('user-article-sub.edit', ['id' => $sub->id]) }}"
                                            class="text-blue-500 hover:underline font-medium">Edit</a>

                                        <button type="button"
                                            class="swal-delete-user-subarticle text-red-500 hover:underline ml-4 font-medium"
                                            data-id="{{ $sub->id }}"
                                            data-url="{{ route('user-article-sub.destroy', $sub->id) }}">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">Belum ada sub-artikel untuk artikel ini.</p>
                        @endif
                    @else
                        <p class="text-gray-500">Silakan pilih artikel untuk melihat sub-artikel.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        function previewImage(event, index) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = document.getElementById(`imagePreview_${index}`);
                    const previewContainer = document.getElementById(`preview_${index}`);
                    const noPreview = document.getElementById(`noPreview_${index}`);
                    imgPreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    noPreview.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function clearPreview(index) {
            const input = document.getElementById(`image_${index}`);
            const previewContainer = document.getElementById(`preview_${index}`);
            const noPreview = document.getElementById(`noPreview_${index}`);
            input.value = "";
            previewContainer.classList.add('hidden');
            noPreview.classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            let subArticlesContainer = document.getElementById('sub-articles-container');
            let addSubArticleBtn = document.getElementById('add-sub-article');
            let subArticleIndex = 1;

            addSubArticleBtn.addEventListener('click', function() {
                let newSubArticle = document.createElement('div');
                newSubArticle.classList.add('sub-article-item', 'border-2', 'border-gray-700', 'rounded-lg',
                    'p-6', 'mb-6', 'relative');

                newSubArticle.innerHTML = `
                    <button type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center remove-sub-article">
                        <i class="fa-solid fa-times"></i>
                    </button>

                    <label class="block font-medium text-sm text-gray-700 mb-3">Judul Sub Artikel ${subArticleIndex + 1}<span class="text-red-500">*</span></label>
                    <input type="text" name="sub_articles[${subArticleIndex}][title]"  class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] 
                                    focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none 
                                    focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out 
                                    block mt-1 w-full py-2.5 mb-6" placeholder="Judul Sub Artikel">
                                    <div class="error-message mt-2 mb-4 text-sm text-red-600 space-y-1" id="error_sub_articles_${subArticleIndex}_title"></div>

                    <label class="block font-medium text-sm text-gray-700 mb-3">Konten Sub Artikel ${subArticleIndex + 1}<span class="text-red-500">*</span></label>
                    <textarea name="sub_articles[${subArticleIndex}][content]" rows="3" class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] 
                                    focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none 
                                    focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out 
                                    block mt-1 w-full py-2.5 mb-6" placeholder="Konten Sub Artikel"></textarea>
                                    <div class="error-message mt-2 mb-4 text-sm text-red-600 space-y-1" id="error_sub_articles_${subArticleIndex}_content"></div>

                    <label class="block font-medium text-sm text-gray-700 mb-3">Urutan ${subArticleIndex + 1}<span class="text-red-500">*</span></label>
                    <input type="number" name="sub_articles[${subArticleIndex}][order_number]" required value="${subArticleIndex + 1}"
                         class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] 
                                    focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none 
                                    focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out 
                                    block mt-1 w-full py-2.5 mb-6" placeholder="Urutan">
                                    <div class="error-message mt-2 mb-4 text-sm text-red-600 space-y-1" id="error_sub_articles_${subArticleIndex}_order_number"></div>

                    <label class="block font-medium text-sm text-gray-700 mb-3">Gambar (Opsional) ${subArticleIndex + 1}</label>
                    <div class="relative w-full" id="image-upload-container_${subArticleIndex}">
                        <label for="image_${subArticleIndex}" class="cursor-pointer flex flex-col items-center justify-center border-2 border-gray-700 shadow-[4px_4px_0px_2px_#374151] rounded-md p-6 hover:bg-gray-100 transition duration-150 ease-in-out">
                            <div id="noPreview_${subArticleIndex}" class="flex flex-col items-center space-y-2">
                                <i class="fa-solid fa-image text-4xl text-gray-700"></i>
                                <span class="text-gray-700 font-medium mb-4">Klik Gambar di Sini</span>
                            </div>
                            <div id="preview_${subArticleIndex}" class="hidden relative w-full flex justify-center">
                                <img id="imagePreview_${subArticleIndex}" class="w-[250px] h-[200px] rounded-md shadow-md object-cover" />
                                <button type="button" class="absolute top-0 right-0 bg-gray-800 text-white rounded-full p-1 -mt-2 -mr-2 shadow-md hover:bg-red-600 transition w-8 h-8"
                                    onclick="clearPreview(${subArticleIndex})">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </label>
                        <input type="file" id="image_${subArticleIndex}" name="sub_articles[${subArticleIndex}][image]" accept="image/*" class="hidden" onchange="previewImage(event, ${subArticleIndex})">
                    </div>
                `;

                subArticlesContainer.appendChild(newSubArticle);
                subArticleIndex++;
            });

            subArticlesContainer.addEventListener('click', function(event) {
                if (event.target.closest('.remove-sub-article')) {
                    event.target.closest('.sub-article-item').remove();
                }
            });
        });
    </script>

    <script>
        function previewImage(event, index) {
            const reader = new FileReader();
            reader.onload = function() {
                const previewImg = document.getElementById('imagePreview_' + index);
                const previewContainer = document.getElementById('preview_' + index);
                const noPreview = document.getElementById('noPreview_' + index);

                previewImg.src = reader.result;

                previewContainer.classList.remove('hidden');
                noPreview.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function clearPreview(index) {
            const previewImg = document.getElementById('imagePreview_' + index);
            const previewContainer = document.getElementById('preview_' + index);
            const noPreview = document.getElementById('noPreview_' + index);
            const fileInput = document.getElementById('image_' + index);

            previewImg.src = '';
            previewContainer.classList.add('hidden');
            noPreview.classList.remove('hidden');
            fileInput.value = '';
        }
    </script>

    <script>
        function clearPreview(index) {
            const previewImg = document.getElementById('imagePreview_' + index);
            const previewContainer = document.getElementById('preview_' + index);
            const noPreview = document.getElementById('noPreview_' + index);
            const fileInput = document.getElementById('image_' + index);
            const removeImage = document.getElementById('remove_image_' + index);

            previewImg.src = '';
            previewContainer.classList.add('hidden');
            noPreview.classList.remove('hidden');

            fileInput.value = '';

            removeImage.value = '1';
        }

        function previewImage(event, index) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function() {
                const previewImg = document.getElementById('imagePreview_' + index);
                const previewContainer = document.getElementById('preview_' + index);
                const noPreview = document.getElementById('noPreview_' + index);
                const removeImage = document.getElementById('remove_image_' + index);

                previewImg.src = reader.result;
                previewContainer.classList.remove('hidden');
                noPreview.classList.add('hidden');

                removeImage.value = '0';
            };
            if (file) {
                reader.readAsDataURL(file);
            }
        }
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
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').content,
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json' // Menambahkan header untuk meminta respons JSON
                                        },
                                        body: JSON.stringify({
                                            _method: 'DELETE'
                                        })
                                    })
                                    .then(response => {
                                        // Periksa status respons terlebih dahulu
                                        if (!response.ok) {
                                            throw new Error(
                                                'Server merespons dengan error: ' +
                                                response.status);
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            Swal.fire('Terhapus!', data.message,
                                                    'success')
                                                .then(() => location.reload());
                                        } else {
                                            Swal.fire('Gagal!', data.message ||
                                                'Terjadi kesalahan tidak diketahui',
                                                'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        Swal.fire('Gagal!',
                                            'Terjadi kesalahan saat menghapus data',
                                            'error');
                                    });
                            }
                        });
                    });
                });
            }
            handleDelete('.swal-delete-user-subarticle', 'Sub Artikel');
        });
    </script>

@endsection
