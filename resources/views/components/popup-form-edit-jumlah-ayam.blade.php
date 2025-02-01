<!-- resources/views/components/popup-form-edit-jumlah-ayam.blade.php -->
@props(['editData']) <!-- Mendefinisikan props 'editData' -->

<div
    x-show="openModal === 'editJumlahAyam'"
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition
>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <!-- Tombol Close -->
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Judul Modal -->
        <h2 class="text-2xl font-semibold mb-4">Edit Data Populasi Ayam</h2>

        <!-- Form Edit -->
        <form 
            id="editBatchForm" 
            method="POST" 
            @submit.prevent="submitEdit"
        >
            @csrf
            @method('PUT') <!-- Menetapkan metode HTTP ke PUT untuk update -->

            <div class="space-y-4 mb-8">
                <!-- Input Kode Batch (Static "BATCH-" dan Editable Suffix) -->
                <div>
                    <x-input-label for="batchCodeSuffix" :value="__('Kode Batch')" />
                    <div class="flex">
                        <span class="flex items-center px-3 bg-gray-200 text-gray-700 border border-r-0 border-gray-300 rounded-l-md">
                            BATCH-
                        </span>
                        <x-text-input 
                            id="batchCodeSuffix" 
                            name="batchCodeSuffix" 
                            type="text"
                            class="flex-1 block mt-1 w-full py-2.5 border border-gray-300 rounded-r-md"
                            required 
                            pattern="\d{3}" 
                            title="Masukkan 3 digit angka"
                            x-model="editData.batchCodeSuffix" 
                            placeholder="001"
                        />
                    </div>
                </div>

                <!-- Input Nama Batch -->
                <div>
                    <x-input-label for="batchName" :value="__('Nama Batch')" />
                    <x-text-input 
                        id="batchName" 
                        name="batchName" 
                        type="text"
                        class="block mt-1 w-full py-2.5" 
                        required 
                        x-model="editData.nama_batch" 
                    />
                </div>

                <!-- Input Tanggal DOC -->
                <div>
                    <x-input-label for="docDate" :value="__('Tanggal DOC')" />
                    <x-text-input 
                        id="docDate" 
                        name="docDate" 
                        type="date"
                        class="block mt-1 w-full py-2.5" 
                        required 
                        x-model="editData.tanggal_doc" 
                    />
                </div>

                <!-- Input Jumlah Ayam Masuk -->
                <div>
                    <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" />
                    <x-text-input 
                        id="chickenQuantity" 
                        name="chickenQuantity" 
                        type="number"
                        class="block mt-1 w-full py-2.5" 
                        required 
                        x-model="editData.jumlah_ayam_masuk" 
                    />
                </div>
            </div>

            <!-- Tombol Update -->
            <div class="flex items-center justify-end">
                <x-primary-button
                    id="submitButton"
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola"
                >
                    {{ __('Perbarui Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
