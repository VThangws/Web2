(function () {
    'use strict';
    const { Dropdown } = window.bootstrap || {};
    const mql = window.matchMedia('(min-width: 992px)');

    function setupDropdownHover() {
        if (!Dropdown) return; // fallback to click-only if Bootstrap API not available
        document.querySelectorAll('.dropdown').forEach(dd => {
            const toggleEl = dd.querySelector('[data-bs-toggle="dropdown"]');
            const menuEl = dd.querySelector('.dropdown-menu');
            if (!toggleEl || !menuEl) return;
            const instance = Dropdown.getOrCreateInstance(toggleEl, { autoClose: true });

            let enter = () => { if (mql.matches) instance.show(); };
            let leave = () => { if (mql.matches) instance.hide(); };

            dd.addEventListener('mouseenter', enter);
            dd.addEventListener('mouseleave', leave);
        });
    }

    document.addEventListener('DOMContentLoaded', setupDropdownHover);

    window.addEventListener('load', () => {
        const main = document.querySelector('main');
        if (main) { main.setAttribute('tabindex', '-1'); main.focus({ preventScroll: true }); }
    });
})();