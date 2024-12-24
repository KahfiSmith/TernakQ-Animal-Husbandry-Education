<div class="w-full fixed top-0 z-50 bg-cosmicLatte shadow-md">
    <nav class="flex justify-between py-5 px-14 items-center border-b-2 border-gray-700">
        <div>
            <img src="{{ asset('images/logo.svg') }}" alt="logo" />
        </div>
        <ul class="flex flex-row space-x-7 text-base font-medium">
            <li>
                <a href="#beranda"
                    class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">Beranda</a>
            </li>
            <li>
                <a href="#article"
                    class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">Artikel</a>
            </li>
            <li>
                <a href="#forum"
                    class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">Forum</a>
            </li>
            <li>
                <a href="#about-us"
                    class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">Tentang
                    Kami</a>
            </li>
            <li>
                <a href="#faq"
                    class="text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">FAQ</a>
            </li>
        </ul>
        <div class="flex items-center space-x-6">
            <a href="{{ route('login') }}" wire:navigate>
                <x-primary-button class="ring-2 ring-gray-700 hover:bg-pewterBlue bg-transparent">Masuk
                </x-primary-button>
            </a>
            <div class="h-7 border-l-2 border-gray-700"></div>
            <a href="{{ route('register') }}" wire:navigate>
                <x-primary-button
                    class="ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] text-white hover:shadow-[2px_2px_0px_2px_#374151] hover:translate-y-0.5 hover:translate-x-0.5 bg-orangeCrayola">
                    Daftar
                </x-primary-button>
            </a>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('section[id]');

        const setActiveLink = () => {
            let scrollY = window.scrollY;

            sections.forEach((section) => {
                const sectionHeight = section.offsetHeight;
                const sectionTop = section.offsetTop - 100;
                const sectionId = section.getAttribute('id');

                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    navLinks.forEach((link) => {
                        link.classList.remove('font-bold', 'text-gray-700');
                    });
                    const activeLink = document.querySelector(`a[href="#${sectionId}"]`);
                    if (activeLink) {
                        activeLink.classList.add('font-bold', 'text-gray-700');
                    }
                }
            });
        };

        const defaultActiveLink = document.querySelector('a[href="#beranda"]');
        if (defaultActiveLink) {
            defaultActiveLink.classList.add('font-bold', 'text-gray-700');
        }

        window.addEventListener('scroll', setActiveLink);
    });
</script>
