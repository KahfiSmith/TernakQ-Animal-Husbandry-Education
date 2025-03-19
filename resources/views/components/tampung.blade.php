<!-- resources/views/components/popup-form-edit-harian-ayam.blade.php -->
@props(['batches']) <!-- Mendefinisikan props 'batches' -->

<div x-show="openModal === 'editHarianAyam'" x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black/15" x-transition>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <!-- Tombol Close -->
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Judul Modal -->
        <h2 class="text-2xl font-semibold mb-4">Edit Data Harian Ayam</h2>

        <!-- Form Edit Harian Ayam -->
        <form method="POST" id="editHarianForm" :action="`/harian/${editData.id}`" @submit.prevent="submitEditHarian">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-8">
                <!-- Nama Batch -->
                <div>
                    <x-input-label for="dailyBatchName" :value="__('Nama Populasi')" />
                    <select id="editDailyBatchName" name="dailyBatchName"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5"
                        required x-model="editData.id_populasi" @change="updateBatchInfoEdit()" x-init="updateBatchInfoEdit()">
                        <option value="" disabled>Pilih Populasi</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" data-jumlah="{{ $batch->jumlah_ayam_masuk }}">
                                {{ $batch->nama_batch }} ({{ $batch->jumlah_ayam_masuk }} Ayam)
                            </option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-600" id="editJumlahAyamText">Jumlah ayam dalam batch: -</span>

                </div>

                <!-- Tanggal Input -->
                <div>
                    <x-input-label for="tanggal_input" :value="__('Tanggal Input')" />
                    <x-text-input id="editTanggalInput" name="tanggal_input" type="date"
                        class="block mt-1 w-full py-2.5" x-model="editData.tanggal_input" required />
                </div>

                <!-- Jumlah Ayam Sakit -->
                <div>
                    <x-input-label for="jumlah_ayam_sakit" :value="__('Jumlah Ayam Sakit')" />
                    <x-text-input id="editJumlahAyamSakit" name="jumlah_ayam_sakit" type="number"
                        class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_sakit" required
                        oninput="validateEditChickenCounts()" />
                    <span class="text-red-500 text-sm" id="editErrorSick"></span>
                </div>

                <!-- Jumlah Ayam Mati -->
                <div>
                    <x-input-label for="jumlah_ayam_mati" :value="__('Jumlah Ayam Mati')" />
                    <x-text-input id="editJumlahAyamMati" name="jumlah_ayam_mati" type="number"
                        class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_mati" required
                        oninput="validateEditChickenCounts()" />
                    <span class="text-red-500 text-sm" id="editErrorDead"></span>
                </div>
            </div>

            <!-- Tombol Update -->
            <div class="flex items-center justify-end">
                <x-primary-button id="editSubmitButton"
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola py-2.5">
                    {{ __('Perbarui Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<script>
    let editJumlahAyam = 0;

    function updateBatchInfoEdit() {
        let select = document.getElementById("editDailyBatchName");
        let selectedOption = select.options[select.selectedIndex];

        if (selectedOption) {
            console.log("Selected Option:", selectedOption);
            console.log("Jumlah Ayam (data-jumlah):", selectedOption.dataset.jumlah);

            editJumlahAyam = selectedOption.dataset.jumlah ? parseInt(selectedOption.dataset.jumlah) : 0;
            document.getElementById("editJumlahAyamText").innerText = `Jumlah ayam dalam populasi: ${editJumlahAyam}`;
        }
        validateEditChickenCounts();
    }

    function validateEditChickenCounts() {
        let sickChicken = parseInt(document.getElementById("editJumlahAyamSakit").value) || 0;
        let deadChicken = parseInt(document.getElementById("editJumlahAyamMati").value) || 0;
        let totalInput = sickChicken + deadChicken;

        let errorSick = document.getElementById("editErrorSick");
        let errorDead = document.getElementById("editErrorDead");
        let submitButton = document.getElementById("editSubmitButton");

        // Reset error messages
        errorSick.innerText = "";
        errorDead.innerText = "";

        if (sickChicken < 0) {
            errorSick.innerText = "Jumlah ayam sakit tidak boleh negatif.";
        }
        if (deadChicken < 0) {
            errorDead.innerText = "Jumlah ayam mati tidak boleh negatif.";
        }
        if (totalInput > editJumlahAyam) {
            errorSick.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + editJumlahAyam;
            errorDead.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + editJumlahAyam;
        }

        // Nonaktifkan tombol submit jika ada error
        submitButton.disabled = errorSick.innerText !== "" || errorDead.innerText !== "";
    }

    function validateEditHarian() {
        validateEditChickenCounts();
        return !(document.getElementById("editErrorSick").innerText || document.getElementById("editErrorDead")
            .innerText);
    }
</script>
