const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
const toggle = document.getElementById('sidebar-toggle');

if (sidebar && overlay && toggle) {
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
}

const navToggle = document.getElementById('nav-toggle');
const navMobile = document.getElementById('nav-mobile');

if (navToggle && navMobile) {
    navToggle.addEventListener('click', () => {
        const willOpen = navMobile.classList.contains('hidden');
        navMobile.classList.toggle('hidden');
        navToggle.setAttribute('aria-expanded', String(willOpen));
    });
}

const navShell = document.getElementById('nav-shell');

if (navShell) {
    const onScroll = () => {
        navShell.classList.toggle('scrolled', window.scrollY > 20);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}
