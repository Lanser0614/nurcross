import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (!toggleButton || !mobileMenu) {
        return;
    }

    const openIcon = toggleButton.querySelector('[data-open-icon]');
    const closeIcon = toggleButton.querySelector('[data-close-icon]');
    const desktopQuery = window.matchMedia('(min-width: 768px)');

    const updateMenuDisplay = () => {
        if (desktopQuery.matches) {
            mobileMenu.classList.remove('hidden');
            openIcon?.classList.remove('hidden');
            closeIcon?.classList.add('hidden');
            toggleButton.setAttribute('aria-expanded', 'false');
            return;
        }

        const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
        mobileMenu.classList.toggle('hidden', !isExpanded);
        openIcon?.classList.toggle('hidden', isExpanded);
        closeIcon?.classList.toggle('hidden', !isExpanded);
    };

    const setMenuState = (isOpen) => {
        toggleButton.setAttribute('aria-expanded', String(isOpen));
        updateMenuDisplay();
    };

    toggleButton.addEventListener('click', () => {
        if (desktopQuery.matches) {
            return;
        }

        const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
        setMenuState(!isExpanded);
    });

    const handleQueryChange = () => updateMenuDisplay();
    if (desktopQuery.addEventListener) {
        desktopQuery.addEventListener('change', handleQueryChange);
    } else {
        desktopQuery.addListener(handleQueryChange);
    }

    updateMenuDisplay();
});
