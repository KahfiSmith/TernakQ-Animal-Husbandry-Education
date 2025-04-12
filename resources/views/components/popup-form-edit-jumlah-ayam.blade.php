<div x-data="{ openModal: null, editData: {} }"
    @open-edit-populasi.window="
        openModal = 'editPopulasiAyam'; 
        editData = $event.detail;
    "
    x-show="openModal === 'editPopulasiAyam'" x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black/15" x-transition>

    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="text-2xl font-semibold mb-4">Edit Data Populasi Ayam</h2>

        <form method="POST" :action="`/populasi/${editData.id}`">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-8">
                <div>
                    <x-input-label for="kandang_id" :value="__('Pilih Kandang')" required />
                    <select id="kandang_id" name="kandang_id"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        x-model="editData.kandang_id">
                        <option value="" disabled selected>Pilih Kandang</option>
                        @foreach ($kandang as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kandang }} ({{ $item->kapasitas }}
                                Kapasitas)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="batchCodeSuffix" :value="__('Kode Populasi')" required />
                    <div class="flex space-x-4">
                        <x-text-input id="batchPrefix" name="batchPrefix" type="text" class="w-1/3"
                            value="POPULASI-" readonly />
                        <x-text-input id="batchCodeSuffix" name="batchCodeSuffix" type="text" class="w-full"
                            x-model="editData.batchCodeSuffix" pattern="[a-zA-Z0-9]{3}" maxlength="3"
                            title="Masukkan kombinasi huruf dan angka, maksimal 3 karakter" placeholder="A01"
                            uppercase />
                    </div>
                </div>
                <div>
                    <x-input-label for="nama_batch" :value="__('Nama Populasi')" required />
                    <x-text-input id="nama_batch" name="nama_batch" type="text" class="block mt-1 w-full py-2.5"
                        x-model="editData.nama_batch" />
                </div>
                <div>
                    <x-input-label for="tanggal_doc" :value="__('Tanggal DOC')" required />
                    <x-text-input id="tanggal_doc" name="tanggal_doc" type="date" class="block mt-1 w-full py-2.5"
                        x-model="editData.tanggal_doc" />
                </div>
                <div>
                    <x-input-label for="jumlah_ayam_masuk" :value="__('Jumlah Ayam Masuk')" required />
                    <x-text-input id="jumlah_ayam_masuk" name="jumlah_ayam_masuk" type="text"
                        class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_masuk"
                        oninput="validateNumber(this)" />
                </div>
                <div>
                    <x-input-label for="status_ayam" :value="__('Status Ayam')" required />
                    <select id="status_ayam" name="status_ayam"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        x-model="editData.status_ayam">
                        <option value="" disabled>Pilih Status Ayam</option>
                        <option value="Proses">Proses</option>
                        <option value="Siap Panen">Siap Panen</option>
                        <option value="Sudah Panen">Sudah Panen</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola py-2.5">
                    {{ __('Perbarui Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<script>
    function validateNumber(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>
