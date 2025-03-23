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
        <form method="POST" action="{{ route('harian.store') }}" onsubmit="return validateForm()">
            @csrf
            <div class="space-y-4 mb-8">

                <!-- Nama Batch -->
                <div>
                    <x-input-label for="dailyBatchName" :value="__('Nama Populasi')" required/>
                    <select id="dailyBatchName" name="dailyBatchName" onchange="updateBatchInfo()"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5 mb-1"
                        required>
                        <option value="" disabled selected>Pilih Populasi</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" data-jumlah="{{ $batch->jumlah_ayam_masuk }}">
                                {{ $batch->nama_batch }} ({{ $batch->jumlah_ayam_masuk }} Ayam)
                            </option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-600" id="jumlahAyamText">Jumlah ayam dalam populasi: -</span>
                </div>

                <!-- Tanggal -->
                <div>
                    <x-input-label for="dailyDate" :value="__('Tanggal Input')" required/>
                    <x-text-input id="dailyDate" name="dailyDate" type="date" class="block mt-1 w-full py-2.5" required />
                </div>

                <!-- Jumlah Ayam Sakit -->
                <div>
                    <x-input-label for="sickChicken" :value="__('Jumlah Ayam Sakit')" required/>
                    <x-text-input id="sickChicken" name="sickChicken" type="number"
                        class="block mt-1 w-full py-2.5 mb-1" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateChickenCounts()" />
                    <span class="text-red-500 text-sm" id="errorSick"></span>
                </div>

                <!-- Jumlah Ayam Mati -->
                <div>
                    <x-input-label for="deadChicken" :value="__('Jumlah Ayam Mati')" required/>
                    <x-text-input id="deadChicken" name="deadChicken" type="text"
                        class="block mt-1 w-full py-2.5 mb-1" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateChickenCounts()" />
                    <span class="text-red-500 text-sm" id="errorDead"></span>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <x-primary-button id="submitButton"
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola py-2.5">
                    {{ __('Simpan Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

{{-- <script>
    let jumlahAyam = 0;

    function updateBatchInfo() {
        let select = document.getElementById("dailyBatchName");
        let selectedOption = select.options[select.selectedIndex];
        jumlahAyam = selectedOption.dataset.jumlah ? parseInt(selectedOption.dataset.jumlah) : 0;
        document.getElementById("jumlahAyamText").innerText = `Jumlah ayam dalam batch: ${jumlahAyam}`;
        validateChickenCounts(); // Pastikan validasi diupdate setiap batch berubah
    }

    function validateChickenCounts() {
        let sickChicken = parseInt(document.getElementById("sickChicken").value) || 0;
        let deadChicken = parseInt(document.getElementById("deadChicken").value) || 0;
        let totalInput = sickChicken + deadChicken;

        let errorSick = document.getElementById("errorSick");
        let errorDead = document.getElementById("errorDead");
        let submitButton = document.getElementById("submitButton");

        // Reset error messages
        errorSick.innerText = "";
        errorDead.innerText = "";

        if (sickChicken < 0) {
            errorSick.innerText = "Jumlah ayam sakit tidak boleh negatif.";
        }
        if (deadChicken < 0) {
            errorDead.innerText = "Jumlah ayam mati tidak boleh negatif.";
        }
        if (totalInput > jumlahAyam) {
            errorSick.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + jumlahAyam;
            errorDead.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + jumlahAyam;
        }

        // Nonaktifkan tombol submit jika ada error
        submitButton.disabled = errorSick.innerText !== "" || errorDead.innerText !== "";
    }

    function validateForm() {
        validateChickenCounts();
        return !(document.getElementById("errorSick").innerText || document.getElementById("errorDead").innerText);
    }

    function closeModal() {
        document.getElementById("harianAyamModal").classList.add("hidden");
    }
</script>   --}}
