<!-- resources/views/components/popup-form-edit-harian-ayam.blade.php -->
@props(['batches']) <!-- Mendefinisikan props 'batches' -->

<div
    x-show="openModal === 'editHarianAyam'"
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <!-- Tombol Close -->
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Judul Modal -->
        <h2 class="text-2xl font-semibold mb-4">Edit Data Harian Ayam</h2>

        <!-- Form Edit Harian Ayam -->
        <form method="POST" :action="`/harian/${editData.id}`" @submit.prevent="submitEditHarian">
            @csrf
            @method('PUT')
            <div class="space-y-4 mb-8">
                <!-- Nama Batch -->
                <div>
                    <x-input-label for="dailyBatchName" :value="__('Nama Batch')" />
                    <select id="dailyBatchName" name="dailyBatchName"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        required
                        x-model="editData.id_populasi">
                        <option value="" disabled>Pilih Batch</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->nama_batch }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Input -->
                <div>
                    <x-input-label for="tanggal_input" :value="__('Tanggal Input')" />
                    <x-text-input id="tanggal_input" name="tanggal_input" type="date"
                        class="block mt-1 w-full py-2.5" x-model="editData.tanggal_input" required />
                </div>

                <!-- Jumlah Ayam Sakit -->
                <div>
                    <x-input-label for="jumlah_ayam_sakit" :value="__('Jumlah Ayam Sakit')" />
                    <x-text-input id="jumlah_ayam_sakit" name="jumlah_ayam_sakit" type="number"
                        class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_sakit" required />
                </div>

                <!-- Jumlah Ayam Mati -->
                <div>
                    <x-input-label for="jumlah_ayam_mati" :value="__('Jumlah Ayam Mati')" />
                    <x-text-input id="jumlah_ayam_mati" name="jumlah_ayam_mati" type="number"
                        class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_mati" required />
                </div>
            </div>

            <!-- Tombol Update -->
            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola">
                    {{ __('Perbarui Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
