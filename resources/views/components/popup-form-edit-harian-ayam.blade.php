<div x-data="{ 
    openModal: null, 
    editData: {},
    jumlahAyamTersedia: 0
}"
@open-edit-harian.window="
    openModal = 'editHarianAyam'; 
    editData = $event.detail;
    fetchAvailableChickenCount(editData.id_populasi, editData.id);
"
x-show="openModal === 'editHarianAyam'" x-cloak
class="fixed inset-0 flex items-center justify-center z-50 bg-black/15" x-transition>
<div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
    <button @click="openModal = null"
        class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <h2 class="text-2xl font-semibold mb-4">Edit Data Harian Ayam</h2>
    <form method="POST" :action="`/harian/${editData.id}`" onsubmit="return validateEditForm()">
        @csrf
        @method('PUT')

        <div class="space-y-4 mb-8">
            <div>
                <x-input-label for="dailyBatchName" :value="__('Nama Populasi')" required />
                <select id="dailyBatchName" name="dailyBatchName"
                     class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5 rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700 text-gray-700 leading-5 transition duration-150 ease-in-out block mt-1 w-full py-2.5 mb-1"
                    required x-model="editData.id_populasi" @change="fetchAvailableChickenCount(editData.id_populasi, editData.id)">
                    <option value="" disabled>Pilih Populasi</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->nama_batch }}
                            ({{ $batch->jumlah_ayam_masuk }} Ayam)</option>
                    @endforeach
                </select>
                <div class="text-sm mt-3 p-2 border rounded-md bg-gray-50">
                    <div id="editJumlahAyamTersedia" class="text-gray-600">
                        Memuat data ketersediaan...
                    </div>
                </div>
            </div>

            <div>
                <x-input-label for="tanggal_input" :value="__('Tanggal Input')" required />
                <x-text-input id="tanggal_input" name="tanggal_input" type="date"
                    class="block mt-1 w-full py-2.5" x-model="editData.tanggal_input" required />
            </div>

            <div>
                <x-input-label for="jumlah_ayam_sakit" :value="__('Jumlah Ayam Sakit')" required />
                <x-text-input id="jumlah_ayam_sakit" name="jumlah_ayam_sakit" type="text"
                    class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_sakit" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateEditChickenCounts()" />
                <span class="text-red-500 text-sm" id="errorEditSick"></span>
            </div>

            <div>
                <x-input-label for="jumlah_ayam_mati" :value="__('Jumlah Ayam Mati')" required />
                <x-text-input id="jumlah_ayam_mati" name="jumlah_ayam_mati" type="text"
                    class="block mt-1 w-full py-2.5" x-model="editData.jumlah_ayam_mati" required
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); validateEditChickenCounts()" />
                <span class="text-red-500 text-sm" id="errorEditDead"></span>
            </div>
        </div>

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
let editJumlahAyamTersedia = 0;
let originalSick = 0;
let originalDead = 0;

function fetchAvailableChickenCount(batchId, recordId) {
    if (!batchId || !recordId) return;
    
    document.getElementById("editJumlahAyamTersedia").innerHTML = 
        `<span class="text-yellow-500">Sedang memuat data...</span>`;
    
    fetch(`/get-harian-record/${recordId}`)
        .then(response => response.json())
        .then(recordData => {
            if (recordData.success) {
                originalSick = recordData.data.jumlah_ayam_sakit;
                originalDead = recordData.data.jumlah_ayam_mati;
                
                return fetch(`/get-available-chicken-count/${batchId}/${recordId}`);
            } else {
                throw new Error('Failed to fetch record data');
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                editJumlahAyam = data.total_population;
                
                const otherRecordsSick = data.recorded_sick;
                const otherRecordsDead = data.recorded_dead;
                
                editJumlahAyamTersedia = data.total_population - otherRecordsSick - otherRecordsDead;
                
                let statusHtml = `<span class="font-semibold text-green-600">Jumlah ayam tersedia untuk diperbarui: ${editJumlahAyamTersedia}</span> dari total ${data.total_population}<br>`;
                
                if (otherRecordsSick > 0 || otherRecordsDead > 0) {
                    statusHtml += `<span class="text-yellow-600">${otherRecordsSick} sakit</span>, <span class="text-red-600">${otherRecordsDead} mati</span> (dari data lain)`;
                    statusHtml += `<br><span class="text-blue-600">Data saat ini: ${originalSick} sakit, ${originalDead} mati</span>`;
                } else {
                    statusHtml += `<span class="text-gray-500">Belum ada catatan ayam sakit atau mati dari data lain</span>`;
                    statusHtml += `<br><span class="text-blue-600">Data saat ini: ${originalSick} sakit, ${originalDead} mati</span>`;
                }
                
                document.getElementById("editJumlahAyamTersedia").innerHTML = statusHtml;
                
                validateEditChickenCounts();
            } else {
                console.error('Error:', data.message);
                document.getElementById("editJumlahAyamTersedia").innerHTML = 
                    `<span class="text-red-600">Error: ${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById("editJumlahAyamTersedia").innerHTML = 
                `<span class="text-red-600">Gagal memuat data: ${error.message}</span>`;
        });
}

function validateEditChickenCounts() {
    let sickChicken = parseInt(document.getElementById("jumlah_ayam_sakit").value) || 0;
    let deadChicken = parseInt(document.getElementById("jumlah_ayam_mati").value) || 0;
    let totalInput = sickChicken + deadChicken;

    let errorSick = document.getElementById("errorEditSick");
    let errorDead = document.getElementById("errorEditDead");
    let submitButton = document.getElementById("editSubmitButton");

    errorSick.innerText = "";
    errorDead.innerText = "";

    if (sickChicken < 0) {
        errorSick.innerText = "Jumlah ayam sakit tidak boleh negatif.";
    }
    if (deadChicken < 0) {
        errorDead.innerText = "Jumlah ayam mati tidak boleh negatif.";
    }
    
    if (totalInput > editJumlahAyamTersedia) {
        errorSick.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + editJumlahAyamTersedia;
        errorDead.innerText = "Total ayam sakit dan mati tidak boleh lebih dari " + editJumlahAyamTersedia;
        submitButton.disabled = true;
    } else {
        submitButton.disabled = errorSick.innerText !== "" || errorDead.innerText !== "";
    }
}

function validateEditForm() {
    validateEditChickenCounts();
    return !(document.getElementById("errorEditSick").innerText || document.getElementById("errorEditDead").innerText);
}

function validateNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>