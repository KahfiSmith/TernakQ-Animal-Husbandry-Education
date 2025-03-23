@props(['kandang'])

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

                <div>
                    <x-input-label for="kandang_id" :value="__('Pilih Kandang')" required />
                    <select id="kandang_id" name="kandang_id"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        required>
                        <option value="" disabled selected>Pilih Kandang</option>
                        @foreach ($kandang as $item)
                            <option value="{{ $item->id }}" {{ old('kandang_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kandang }} - Kapasitas: {{ $item->kapasitas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Kode Batch: Pisahkan menjadi Prefix dan Suffix -->
                <div>
                    <x-input-label for="batchCodeSuffix" :value="__('Kode Populasi')" required />
                    <div class="flex space-x-4">
                        <!-- Input untuk Prefix statis "BATCH-" -->
                        <x-text-input id="batchPrefix" name="batchPrefix" type="text"
                            class="flex-none block mt-1 w-1/4 py-2.5 border border-r-0 border-gray-300 rounded-l-md text-gray-700"
                            value="POPULASI-" readonly />
                        <!-- Input untuk Suffix yang dapat diedit -->
                        <x-text-input id="batchCodeSuffix" name="batchCodeSuffix" type="text"
                            class="flex-1 block mt-1 w-full py-2.5 border border-gray-300 rounded-r-md" required
                            pattern="[a-zA-Z0-9]{3}" maxlength="3"
                            title="Masukkan kombinasi huruf dan angka, maksimal 3 karakter" placeholder="A01"
                            uppercase />
                    </div>
                </div>

                <!-- Input Nama Batch -->
                <div>
                    <x-input-label for="batchName" :value="__('Nama Populasi')" required />
                    <x-text-input id="batchName" name="batchName" type="text" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Input Tanggal DOC -->
                <div>
                    <x-input-label for="docDate" :value="__('Tanggal DOC')" required />
                    <x-text-input id="docDate" name="docDate" type="date" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Input Jumlah Ayam Masuk -->
                <div>
                    <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" required />
                    <x-text-input id="chickenQuantity" name="chickenQuantity" type="text"
                        class="block mt-1 w-full py-2.5" required oninput="validateNumber(this)" />
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola py-2.5">
                    {{ __('Simpan Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<script>
    function validateNumber(input) {
        // Hanya menerima angka (menghapus karakter selain angka)
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>
