<div>
    <div class="w-full p-6 bg-white rounded-lg shadow-md ring-2 ring-gray-700">
        <h2 class="text-2xl font-bold mb-4">Create New Topic</h2>

        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <x-input-label for="title" class="block text-gray-700 text-sm font-bold mb-2">Judul
                    topik</x-input-label>
                <x-text-input wire:model="title" id="title" class="block mt-1 w-full py-2.5" type="text"
                    name="title" required />
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <x-input-label for="content" class="block text-gray-700 text-sm font-bold mb-2">Pesan</x-input-label>
                <textarea wire:model="content" id="content" rows="6"
                    class="block mt-1 w-full h-[100px] resize-none py-2.5 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151]
                    focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 focus:translate-x-0.5
                    rounded-md focus:outline-none focus:border-none focus:ring-2 focus:ring-gray-700
                    text-gray-700 leading-5 transition duration-150 ease-in-out"></textarea>
                @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-start w-full">
                <button type="submit"
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 text-center bg-orange-500 py-2.5 px-4 rounded">
                    Buat topik
                </button>
            </div>
        </form>
    </div>
</div>