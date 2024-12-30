document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleIcon = document.getElementById('toggle-icon');
    const logo = document.getElementById('logo');
    const menuTextElements = document.querySelectorAll('.menu-text');
    const content = document.getElementById('content');

    const enableActiveHover = () => {
        const activeMenu = document.querySelector('.bg-pewterBlue'); 
        if (activeMenu) {
            activeMenu.classList.add('hover:bg-pewterBlue', 'hover:text-white');
        }
    };

    const initializeSidebar = () => {
        if (sidebar.classList.contains('w-72')) {
            logo.classList.remove('hidden');
            toggleIcon.querySelector('i').classList.add('fa-chevron-left');
            toggleIcon.querySelector('i').classList.remove('fa-chevron-right');

            menuTextElements.forEach((menuText, index) => {
                setTimeout(() => {
                    menuText.classList.add('visible');
                }, index * 50);
            });

            if (content) content.style.marginLeft = '18rem';
        } else {
            logo.classList.add('hidden');
            toggleIcon.querySelector('i').classList.add('fa-chevron-right');
            toggleIcon.querySelector('i').classList.remove('fa-chevron-left');

            menuTextElements.forEach((menuText) => {
                menuText.classList.remove('visible');
            });

            if (content) content.style.marginLeft = '5rem';
        }

        enableActiveHover();
    };

    initializeSidebar();

    toggleIcon.addEventListener('click', function () {
        sidebar.classList.toggle('w-72');
        sidebar.classList.toggle('w-20');

        if (sidebar.classList.contains('w-20')) {
            logo.classList.add('hidden');
            toggleIcon.querySelector('i').classList.remove('fa-chevron-left');
            toggleIcon.querySelector('i').classList.add('fa-chevron-right');

            menuTextElements.forEach((menuText) => {
                menuText.classList.remove('visible');
            });

            if (content) content.style.marginLeft = '5rem';
        } else {
            logo.classList.remove('hidden');
            toggleIcon.querySelector('i').classList.remove('fa-chevron-right');
            toggleIcon.querySelector('i').classList.add('fa-chevron-left');

            menuTextElements.forEach((menuText, index) => {
                setTimeout(() => {
                    menuText.classList.add('visible');
                }, index * 50);
            });

            if (content) content.style.marginLeft = '18rem';
        }

        enableActiveHover();
    });
});
