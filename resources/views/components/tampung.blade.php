<h2 class="text-2xl font-semibold mb-2">Form Input Data Populasi Ayam</h2>
<div class="bg-white border-2 border-gray-700 rounded-lg mb-4">
    <form action="#" method="POST" class="p-6">
        @csrf
        <div class="grid grid-cols-2 gap-8 mb-10">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <div>
                    <x-input-label for="batch-code" :value="__('Kode Batch')" />
                    <x-text-input id="batch-code" name="batch-code" type="text" class="block mt-1 w-full"
                        required autofocus />
                    <x-input-error :messages="$errors->get('batch-code')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="batch-name" :value="__('Nama Batch')" />
                    <x-text-input id="batch-name" name="batch-name" type="text" class="block mt-1 w-full"
                        required />
                    <x-input-error :messages="$errors->get('batch-name')" class="mt-2" />
                </div>

                <div x-data="{ open: false, selected: 'Proses' }" class="relative w-full">
                    <button @click="open = !open" type="button"
                        class="w-full bg-white ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-gray-700 px-4 py-2 rounded-md flex justify-between items-center">
                        <span x-text="selected"></span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute z-10 mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                        <ul>
                            <li @click="selected = 'Proses'; open = false"
                                class="px-4 py-2 hover:bg-orangeCrayola hover:text-white cursor-pointer">Proses
                            </li>
                            <li @click="selected = 'Siap Panen'; open = false"
                                class="px-4 py-2 hover:bg-orangeCrayola hover:text-white cursor-pointer">Siap Panen
                            </li>
                            <li @click="selected = 'Sudah Panen'; open = false"
                                class="px-4 py-2 hover:bg-orangeCrayola hover:text-white cursor-pointer">Sudah
                                Panen
                            </li>
                        </ul>
                    </div>
                </div>


            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <div>
                    <x-input-label for="doc-date" :value="__('Tanggal DOC')" />
                    <x-text-input id="doc-date" name="doc-date" type="date" class="block mt-1 w-full"
                        required />
                    <x-input-error :messages="$errors->get('doc-date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="chicken-quantity" :value="__('Jumlah Ayam Masuk')" />
                    <x-text-input id="chicken-quantity" name="chicken-quantity" type="number"
                        class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('chicken-quantity')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex items-center justify-end">
            <x-primary-button
                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/3 text-center bg-orangeCrayola">
                {{ __('Simpan Data') }}
            </x-primary-button>
        </div>
    </form>
</div>
<h2 class="text-2xl font-semibold mb-2">Form Input Data Harian Ayam</h2>
<div class="bg-white border-2 border-gray-700 rounded-lg">
    <form action="#" method="POST" class="p-6">
        @csrf
        <div class="grid grid-cols-2 gap-8 mb-10">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <!-- ID Populasi -->
                {{-- <div>
                    <x-input-label for="population-id" :value="__('ID Populasi')" />
                    <select id="population-id" name="population-id"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-orange-500 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full"
                        required>
                        <option value="">-- Pilih ID Populasi --</option>
                        @foreach ($populations as $population)
                            <option value="{{ $population->id }}">{{ $population->id }} -
                                {{ $population->batch_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('population-id')" class="mt-2" />
                </div> --}}

                <!-- Nama Batch (Auto) -->
                <div>
                    <x-input-label for="batch-name" :value="__('Nama Batch')" />
                    <x-text-input id="batch-name" name="batch-name" type="text" class="block mt-1 w-full"
                        readonly />
                    <x-input-error :messages="$errors->get('batch-name')" class="mt-2" />
                </div>

                <!-- Tanggal Input -->
                <div>
                    <x-input-label for="input-date" :value="__('Tanggal Input')" />
                    <x-text-input id="input-date" name="input-date" type="date" class="block mt-1 w-full"
                        required />
                    <x-input-error :messages="$errors->get('input-date')" class="mt-2" />
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <!-- Jumlah Ayam Sakit -->
                <div>
                    <x-input-label for="sick-chickens" :value="__('Jumlah Ayam Sakit')" />
                    <x-text-input id="sick-chickens" name="sick-chickens" type="number" min="0"
                        class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('sick-chickens')" class="mt-2" />
                </div>

                <!-- Jumlah Ayam Mati -->
                <div>
                    <x-input-label for="dead-chickens" :value="__('Jumlah Ayam Mati')" />
                    <x-text-input id="dead-chickens" name="dead-chickens" type="number" min="0"
                        class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('dead-chickens')" class="mt-2" />
                </div>

                <!-- Penyebab -->
                <div>
                    <x-input-label for="cause" :value="__('Penyebab Kematian')" />
                    <x-text-input id="cause" name="cause" type="text" class="block mt-1 w-full"
                        placeholder="Contoh: Penyakit, Cedera, Lainnya" required />
                    <x-input-error :messages="$errors->get('cause')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex items-center justify-end">
            <x-primary-button
                class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/3 text-center bg-orangeCrayola">
                {{ __('Simpan Data Harian') }}
            </x-primary-button>
        </div>
    </form>
</div>