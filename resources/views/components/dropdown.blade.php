<ul class="flex flex-row space-x-7 text-base font-medium">
    @foreach ($items as $item)
        @if (isset($item['dropdown']))
            <li class="relative group">
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
    let activeDropdown = null;

    function toggleDropdown(element) {
        const dropdown = element.closest('li').querySelector('ul');
        const icon = element.querySelector('.chevron-icon');

        if (activeDropdown && activeDropdown !== dropdown) {
            closeDropdown(activeDropdown);
        }

        dropdown.classList.toggle('hidden');
        icon.classList.toggle('-rotate-180');

        activeDropdown = dropdown.classList.contains('hidden') ? null : dropdown;

        event.stopPropagation();
    }

    function closeDropdown(dropdown) {
        if (dropdown) {
            dropdown.classList.add('hidden');
            const icon = dropdown.closest('li').querySelector('.chevron-icon');
            if (icon) icon.classList.remove('-rotate-180');
        }
    }

    document.addEventListener('click', (event) => {
        if (activeDropdown && !event.target.closest('li.group')) {
            closeDropdown(activeDropdown);
            activeDropdown = null;
        }
    });

    document.querySelectorAll('li.group ul li a').forEach(link => {
        link.addEventListener('click', () => {
            if (activeDropdown) {
                closeDropdown(activeDropdown);
                activeDropdown = null;
            }
        });
    });
</script>




