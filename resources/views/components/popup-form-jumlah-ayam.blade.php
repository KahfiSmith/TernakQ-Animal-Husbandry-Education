<div x-show="openModal === 'jumlahAyam'" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h2 class="text-2xl font-semibold mb-4">Form Input Data Populasi Ayam</h2>
        <form method="POST" action="{{ route('populasi.store') }}" id="populasiForm">
            @csrf
            <div class="space-y-4 mb-8">
                <!-- Input Kode Batch: Pisahkan menjadi Prefix dan Suffix -->
                <div>
                    <x-input-label for="batchCodeSuffix" :value="__('Kode Batch')" />
                    <div class="flex space-x-4">
                        <!-- Input untuk Prefix statis "BATCH-" -->
                        <x-text-input id="batchPrefix" name="batchPrefix" type="text"
                            class="flex-none block mt-1 w-1/4 py-2.5 border border-r-0 border-gray-300 rounded-l-md text-gray-700"
                            value="BATCH-" readonly />
                        <!-- Input untuk Suffix yang dapat diedit -->
                        <x-text-input id="batchCodeSuffix" name="batchCodeSuffix" type="text"
                            class="flex-1 block mt-1 w-full py-2.5 border border-gray-300 rounded-r-md" required
                            pattern="[a-zA-Z0-9]{3}" maxlength="3" 
                            title="Masukkan kombinasi huruf dan angka, maksimal 3 karakter" 
                            placeholder="A01" uppercase />
                    </div>
                </div>

                <!-- Input Nama Batch -->
                <div>
                    <x-input-label for="batchName" :value="__('Nama Batch')" />
                    <x-text-input id="batchName" name="batchName" type="text" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Input Tanggal DOC -->
                <div>
                    <x-input-label for="docDate" :value="__('Tanggal DOC')" />
                    <x-text-input id="docDate" name="docDate" type="date" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Input Jumlah Ayam Masuk -->
                <div>
                    <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" />
                    <x-text-input id="chickenQuantity" name="chickenQuantity" type="number"
                        class="block mt-1 w-full py-2.5" required />
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola">
                    {{ __('Simpan Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
