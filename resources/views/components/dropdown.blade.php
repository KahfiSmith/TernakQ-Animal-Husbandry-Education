<ul class="flex flex-row space-x-7 text-base font-medium">
    @foreach ($items as $item)
        @if (isset($item['dropdown']))
            <li class="relative group">
                <!-- Add 'onclick' to trigger the dropdown toggle function -->
                <a href="javascript:void(0);" onclick="toggleDropdown(this)" class="inline-flex items-center">
                    {{ $item['name'] }}
                    <x-heroicon-o-chevron-down
                        class="ml-2 w-4 h-4 text-gray-700 chevron-icon transition-transform duration-300 ease-in-out" />
                </a>
                <ul class="absolute left-0 mt-2 bg-white shadow-lg rounded-md w-48 hidden ring-2 ring-gray-700 transition-all duration-300 ease-in-out">
                    @foreach ($item['dropdown'] as $dropdownItem)
                        <li class="px-4 py-2 hover:bg-gray-200 rounded-md">
                            <a href="{{ $dropdownItem['url'] }}">{{ $dropdownItem['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li>
                <a href="{{ $item['url'] }}" class="text-gray-700">{{ $item['name'] }}</a>
            </li>
        @endif
    @endforeach
</ul>

<script>
    function toggleDropdown(element) {
        const dropdown = element.closest('li').querySelector('ul'); // Menemukan menu dropdown
        const icon = element.querySelector('.chevron-icon'); // Menemukan ikon chevron

        if (dropdown) {
            // Menyembunyikan atau menampilkan dropdown dengan transisi
            dropdown.classList.toggle('hidden'); // Menggunakan class 'hidden' untuk visibilitas

            // Ganti ikon berdasarkan kondisi dropdown
            if (dropdown.classList.contains('hidden')) {
                icon.setAttribute('class', 'ml-2 w-4 h-4 text-gray-700 chevron-icon transition-transform duration-300 ease-in-out'); // Chevron-down
            } else {
                icon.setAttribute('class', 'ml-2 w-4 h-4 text-gray-700 chevron-icon -rotate-180 transition-transform duration-300 ease-in-out'); // Chevron-up
            }
        }
    }
</script>
