@props(['batches'])

<!-- Modal untuk Form Input Data Harian Ayam -->
<div x-show="openModal === 'harianAyam'" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">

        <!-- Tombol Close -->
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Judul Form -->
        <h2 class="text-2xl font-semibold mb-4">Form Input Data Harian Ayam</h2>

        <!-- Form Input -->
        <form method="POST" action="{{ route('harian.store') }}">
            @csrf
            <div class="space-y-4 mb-8">

                <!-- Nama Batch -->
                <div>
                    <x-input-label for="dailyBatchName" :value="__('Nama Batch')" />
                    <select id="dailyBatchName" name="dailyBatchName"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        required>
                        <option value="" disabled selected>Pilih Batch</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->nama_batch }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <x-input-label for="dailyDate" :value="__('Tanggal Input')" />
                    <x-text-input id="dailyDate" name="dailyDate" type="date" class="block mt-1 w-full py-2.5" required />
                </div>

                <!-- Jumlah Ayam Sakit -->
                <div>
                    <x-input-label for="sickChicken" :value="__('Jumlah Ayam Sakit')" />
                    <x-text-input id="sickChicken" name="sickChicken" type="number" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Jumlah Ayam Mati -->
                <div>
                    <x-input-label for="deadChicken" :value="__('Jumlah Ayam Mati')" />
                    <x-text-input id="deadChicken" name="deadChicken" type="number" class="block mt-1 w-full py-2.5"
                        required />
                </div>
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola">
                    {{ __('Simpan Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
