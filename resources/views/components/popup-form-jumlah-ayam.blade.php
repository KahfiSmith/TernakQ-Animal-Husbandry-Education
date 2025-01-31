<div x-show="openModal === 'jumlahAyam'" x-cloak class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h2 class="text-2xl font-semibold mb-4">Form Input Data Populasi Ayam</h2>
        <form method="POST" action="{{ route('populasi.store') }}">
            @csrf
            <div class="space-y-4 mb-8">
                <!-- Input kode batch -->
                <div>
                    <x-input-label for="batchCode" :value="__('Kode Batch')" />
                    <x-text-input id="batchCode" name="batchCode" type="text"
                        class="block mt-1 w-full cursor-text py-2.5" required />
                </div>

                <!-- Input nama batch -->
                <div>
                    <x-input-label for="batchName" :value="__('Nama Batch')" />
                    <x-text-input id="batchName" name="batchName" type="text" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Input tanggal DOC -->
                <div>
                    <x-input-label for="docDate" :value="__('Tanggal DOC')" />
                    <x-text-input id="docDate" name="docDate" type="date" class="block mt-1 w-full py-2.5"
                        required />
                </div>

                <!-- Input jumlah ayam -->
                <div>
                    <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" />
                    <x-text-input id="chickenQuantity" name="chickenQuantity" type="number"
                        class="block mt-1 w-full py-2.5" required />
                </div>
            </div>

            <!-- Tombol simpan -->
            <div class="flex items-center justify-end">
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola">
                    {{ __('Simpan Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const batchCodeInput = document.getElementById('batchCode');

        function setBatchCode() {
            const prefix = 'BATCH-';
            if (!batchCodeInput.value.startsWith(prefix)) {
                batchCodeInput.value = prefix; // Set nilai awal
            }
        }

        function enforceBatchPrefix(event) {
            const prefix = 'BATCH-';

            // Cegah pengguna menghapus prefix
            if (!batchCodeInput.value.startsWith(prefix)) {
                batchCodeInput.value = prefix;
            }

            // Cegah perubahan di dalam prefix
            const cursorPosition = batchCodeInput.selectionStart;
            if (cursorPosition < prefix.length) {
                event.preventDefault();
                batchCodeInput.setSelectionRange(prefix.length, prefix
                .length); // Pindahkan kursor ke akhir prefix
            }
        }

        // Atur nilai awal saat halaman dimuat
        setBatchCode();

        // Tambahkan event listener untuk mencegah penghapusan prefix
        batchCodeInput.addEventListener('input', setBatchCode);
        batchCodeInput.addEventListener('keydown', enforceBatchPrefix);

        // **Saat Edit Diklik, Isi Form dengan Data dari Baris Tabel**
        window.addEventListener("open-edit-modal", function(event) {
            const {
                batchId,
                batchCode,
                batchName,
                docDate,
                chickenQuantity
            } = event.detail;

            document.getElementById("modalTitle").textContent = "Edit Data Populasi Ayam";
            document.getElementById("submitButton").textContent = "Perbarui Data";
            document.getElementById("batchCode").value = batchCode;
            document.getElementById("batchCode").setAttribute("readonly", true);
            document.getElementById("batchName").value = batchName;
            document.getElementById("docDate").value = docDate;
            document.getElementById("chickenQuantity").value = chickenQuantity;

            // Ganti Form Action untuk Update
            document.getElementById("batchForm").action = `/populasi/${batchId}`;
            document.getElementById("formMethod").value = "PUT";
        });

        // Reset modal saat tombol Tambah diklik
        document.querySelectorAll("[data-modal='jumlahAyam']").forEach(button => {
            button.addEventListener("click", function() {
                setBatchCode();
                document.getElementById("modalTitle").textContent =
                    "Form Input Data Populasi Ayam";
                document.getElementById("submitButton").textContent = "Simpan Data";
                document.getElementById("batchCode").removeAttribute("readonly");
                document.getElementById("batchName").value = "";
                document.getElementById("docDate").value = "";
                document.getElementById("chickenQuantity").value = "";
                document.getElementById("batchForm").action = "{{ route('populasi.store') }}";
                document.getElementById("formMethod").value = "POST";
            });
        });
    });
</script>
