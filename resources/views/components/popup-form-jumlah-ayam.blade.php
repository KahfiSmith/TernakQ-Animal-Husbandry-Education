<div x-show="openModal === 'jumlahAyam'" class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition>
    <div class="bg-white w-1/2 p-6 rounded-lg shadow-lg relative">
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h2 class="text-2xl font-semibold mb-4">Form Input Data Populasi Ayam</h2>
        <form method="POST" action="#">
            @csrf
            <div class="space-y-4 mb-8">
                <div>
                    <x-input-label for="batchCode" :value="__('Kode Batch')" />
                    <x-text-input id="batchCode" name="batchCode" type="text" class="block mt-1 w-full" required
                        autofocus />
                </div>

                <div>
                    <x-input-label for="batchName" :value="__('Nama Batch')" />
                    <x-text-input id="batchName" name="batchName" type="text" class="block mt-1 w-full" required />
                </div>

                <div>
                    <x-input-label for="docDate" :value="__('Tanggal DOC')" />
                    <x-text-input id="docDate" name="docDate" type="date" class="block mt-1 w-full" required />
                </div>

                <div>
                    <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" />
                    <x-text-input id="chickenQuantity" name="chickenQuantity" type="number" class="block mt-1 w-full"
                        required />
                </div>
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/3 text-center bg-orangeCrayola">
                    {{ __('Simpan Data Harian') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
