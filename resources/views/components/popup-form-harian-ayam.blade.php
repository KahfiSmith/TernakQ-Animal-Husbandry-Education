@props(['batches'])

<div x-show="openModal === 'harianAyam'" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">

        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2 class="text-2xl font-semibold mb-4">Form Input Data Harian Ayam</h2>
        <form method="POST" action="{{ route('harian.store') }}" onsubmit="return validateForm()">
            @csrf
            <div class="space-y-4 mb-8">
                <div>
                    <x-input-label for="dailyBatchName" :value="__('Nama Populasi')" required />
                    <select id="dailyBatchName" name="dailyBatchName" onchange="fetchBatchRemainingPopulation()"
                        class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5 mb-1">
                        <option value="" disabled selected>Pilih Populasi</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" data-jumlah="{{ $batch->jumlah_ayam_masuk }}">
                                {{ $batch->nama_batch }} ({{ $batch->jumlah_ayam_masuk }} Ayam)
                            </option>
                        @endforeach
                    </select>
                    <div class="text-sm mt-1">
                        <span class="text-green-600 font-semibold" id="jumlahAyamTersedia">Jumlah ayam tersedia:
                            -</span>
                    </div>
                </div>
                <div>
                    <x-input-label for="dailyDate" :value="__('Tanggal Input')" required />
                    <x-text-input id="dailyDate" name="dailyDate" type="date" class="block mt-1 w-full py-2.5" />
                </div>
                <div>
                    <x-input-label for="sickChicken" :value="__('Jumlah Ayam Sakit')" required />
                    <x-text-input id="sickChicken" name="sickChicken" type="number"
                        class="block mt-1 w-full py-2.5 mb-1"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateChickenCounts()" />
                    <span class="text-red-500 text-sm" id="errorSick"></span>
                </div>
                <div>
                    <x-input-label for="deadChicken" :value="__('Jumlah Ayam Mati')" required />
                    <x-text-input id="deadChicken" name="deadChicken" type="text"
                        class="block mt-1 w-full py-2.5 mb-1"
                        oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateChickenCounts()" />
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

<script>
    let jumlahAyam = 0;
    let jumlahAyamTersedia = 0;

    function fetchBatchRemainingPopulation() {
        let select = document.getElementById("dailyBatchName");
        if (!select.value) return;

        let batchId = select.value;
        let selectedOption = select.options[select.selectedIndex];
        jumlahAyam = selectedOption.dataset.jumlah ? parseInt(selectedOption.dataset.jumlah) : 0;

        document.getElementById("jumlahAyamTersedia").innerHTML =
            `<span class="text-yellow-500">Sedang memuat data...</span>`;

        fetch(`/get-available-chicken-count/${batchId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    jumlahAyamTersedia = data.available_count;

                    let statusHtml =
                        `<span class="font-semibold">Jumlah ayam tersedia: ${jumlahAyamTersedia}</span> dari total ${data.total_population}<br>`;
                    statusHtml +=
                        `<span class="text-yellow-600">${data.recorded_sick} sakit</span>, <span class="text-red-600">${data.recorded_dead} mati</span>`;

                    document.getElementById("jumlahAyamTersedia").innerHTML = statusHtml;

                    let sickInput = document.getElementById("sickChicken");
                    let deadInput = document.getElementById("deadChicken");

                    if (parseInt(sickInput.value || 0) + parseInt(deadInput.value || 0) > jumlahAyamTersedia) {
                        sickInput.value = "";
                        deadInput.value = "";
                    }

                    validateChickenCounts();
                } else {
                    console.error('Error:', data.message);
                    document.getElementById("jumlahAyamTersedia").innerHTML =
                        `<span class="text-red-600">Error: ${data.message}</span>`;
                }
            })
            .catch(error => {
                console.error('Error fetching available chicken count:', error);
                document.getElementById("jumlahAyamTersedia").innerHTML =
                    `<span class="text-red-600">Gagal memuat data: ${error.message}</span>`;
            });
    }

    function validateChickenCounts() {
        let sickChicken = parseInt(document.getElementById("sickChicken").value) || 0;
        let deadChicken = parseInt(document.getElementById("deadChicken").value) || 0;
        let totalInput = sickChicken + deadChicken;

        let errorSick = document.getElementById("errorSick");
        let errorDead = document.getElementById("errorDead");
        let submitButton = document.getElementById("submitButton");

        errorSick.innerText = "";
        errorDead.innerText = "";

        if (sickChicken < 0) {
            errorSick.innerText = "Jumlah ayam sakit tidak boleh negatif.";
        }
        if (deadChicken < 0) {
            errorDead.innerText = "Jumlah ayam mati tidak boleh negatif.";
        }

        if (totalInput > jumlahAyamTersedia) {
            errorSick.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + jumlahAyamTersedia;
            errorDead.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + jumlahAyamTersedia;
        }

        submitButton.disabled = errorSick.innerText !== "" || errorDead.innerText !== "";
    }

    function validateForm() {
        validateChickenCounts();
        return !(document.getElementById("errorSick").innerText || document.getElementById("errorDead").innerText);
    }
</script>
