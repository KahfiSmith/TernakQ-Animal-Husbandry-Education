<!-- resources/views/components/popup-form-edit-jumlah-ayam.blade.php -->
<div
    x-show="openModal === 'editJumlahAyam'"
    x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black/15"
    x-transition
>
    <div class="bg-white w-1/3 p-6 rounded-lg shadow-lg relative">
        <!-- Tombol Close -->
        <button @click="openModal = null"
            class="absolute top-6 right-6 text-gray-500 hover:text-red-500 text-xl font-bold">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Judul Modal -->
        <h2 class="text-2xl font-semibold mb-4">Edit Data Populasi Ayam</h2>

        <!-- Form Edit -->
        <form 
            id="editBatchForm" 
            method="POST" 
            :action="`{{ url('/populasi') }}/${editData.id}`" 
            @submit.prevent="submitEdit"
        >
            @csrf
            @method('PUT') <!-- Menetapkan metode HTTP ke PUT untuk update -->

            <div class="space-y-4 mb-8">
                <!-- Input Kode Batch -->
                <div>
                    <x-input-label for="batchCode" :value="__('Kode Batch')" />
                    <x-text-input 
                        id="batchCode" 
                        name="batchCode" 
                        type="text"
                        class="block mt-1 w-full cursor-text py-2.5" 
                        required 
                        x-model="editData.kode_batch" 
                    />
                </div>

                <!-- Input Nama Batch -->
                <div>
                    <x-input-label for="batchName" :value="__('Nama Batch')" />
                    <x-text-input 
                        id="batchName" 
                        name="batchName" 
                        type="text"
                        class="block mt-1 w-full py-2.5" 
                        required 
                        x-model="editData.nama_batch" 
                    />
                </div>

                <!-- Input Tanggal DOC -->
                <div>
                    <x-input-label for="docDate" :value="__('Tanggal DOC')" />
                    <x-text-input 
                        id="docDate" 
                        name="docDate" 
                        type="date"
                        class="block mt-1 w-full py-2.5" 
                        required 
                        x-model="editData.tanggal_doc" 
                    />
                </div>

                <!-- Input Jumlah Ayam Masuk -->
                <div>
                    <x-input-label for="chickenQuantity" :value="__('Jumlah Ayam Masuk')" />
                    <x-text-input 
                        id="chickenQuantity" 
                        name="chickenQuantity" 
                        type="number"
                        class="block mt-1 w-full py-2.5" 
                        required 
                        x-model="editData.jumlah_ayam_masuk" 
                    />
                </div>
            </div>

            <!-- Tombol Update -->
            <div class="flex items-center justify-end">
                <x-primary-button
                    id="submitButton"
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 w-1/2 text-center bg-orangeCrayola"
                >
                    {{ __('Perbarui Data') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('harianHandler', () => ({
            openModal: null,
            editData: {},

            submitEdit() {
                const actionUrl = `/populasi/${this.editData.id}`;
                const data = {
                    kode_batch: this.editData.kode_batch,
                    nama_batch: this.editData.nama_batch,
                    tanggal_doc: this.editData.tanggal_doc,
                    jumlah_ayam_masuk: this.editData.jumlah_ayam_masuk
                };

                fetch(actionUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            throw new Error(Object.values(data.errors).flat().join(' '));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message, "Success");
                        this.openModal = null;
                        location.reload();
                    } else {
                        toastr.error(data.message, "Error");
                    }
                })
                .catch(error => {
                    toastr.error(error.message || 'Terjadi kesalahan server.', "Error");
                    console.error('Error:', error);
                });
            }
        }));
    });
</script>
