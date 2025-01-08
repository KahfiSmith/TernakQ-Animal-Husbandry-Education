<aside id="sidebar"
    class="w-72 bg-polishedPine min-h-screen p-5 pt-8 fixed duration-500 border-white border-r-2 font-medium flex flex-col justify-between transition-all ease-in-out">
    <div id="toggle-icon" class="absolute cursor-pointer -right-3 top-8 border-gray-700 border-2 rounded-full w-7 h-7 flex items-center justify-center bg-maize">
        <i class="fa-solid fa-chevron-left text-xs"></i>
    </div>

    <div id="logo-container" class="flex items-center justify-center">
        <img id="logo" src="{{ asset('images/logo.svg') }}" class="w-24 duration-1000 transition-all ease-in-out">
    </div>

    <ul id="menu-list" class="pt-6 flex-grow duration-500">
        @foreach ($menus as $menu)
        <li class="mb-2">
            <x-sidebar.link :href="$menu['url']" :active="$menu['active']">
                <img src="{{ $menu['icon'] }}" alt="{{ $menu['title'] }} Icon" class="w-6 h-6 mr-2">
                <span class="menu-text">{{ $menu['title'] }}</span>
            </x-sidebar.link>
        </li>
        @endforeach
    </ul>    

    <ul id="bottom-menu-list" class="border-t-2 pt-4 border-gray-200">
        @foreach ($bottomMenus as $menu)
        <li class="mb-2">
            <x-sidebar.link :href="$menu['url']" :active="$menu['active']">
                <img src="{{ $menu['icon'] }}" alt="{{ $menu['title'] }} Icon" class="w-6 h-6 mr-2">
                <span class="menu-text">{{ $menu['title'] }}</span>
            </x-sidebar.link>
        </li>
        @endforeach
    </ul>
</aside>
