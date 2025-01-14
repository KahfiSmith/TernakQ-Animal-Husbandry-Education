<div>
    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-1/2 p-6 rounded-lg shadow-lg relative">
                <!-- Tombol Close -->
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl font-bold">
                    &times;
                </button>

                <h2 class="text-2xl font-semibold mb-4">Form Input Data Populasi Ayam</h2>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <x-input-label for="batchCode" :value="__('Kode Batch')" />
                        <x-text-input wire:model="batchCode" id="batchCode" type="text" class="block mt-1 w-full" required autofocus />
                        @error('batchCode') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="batchName" :value="__('Nama Batch')" />
                        <x-text-input wire:model="batchName" id="batchName" type="text" class="block mt-1 w-full" required />
                        @error('batchName') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="docDate" :value="__('Tanggal DOC')" />
                        <x-text-input wire:model="docDate" id="docDate" type="date" class="block mt-1 w-full" required />
                        @error('docDate') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" />
                        <x-text-input wire:model="chickenQuantity" id="chickenQuantity" type="number" class="block mt-1 w-full" required />
                        @error('chickenQuantity') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Simpan Data</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
