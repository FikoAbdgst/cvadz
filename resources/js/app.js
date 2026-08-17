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

const siteNav = document.getElementById('site-nav');

if (siteNav) {
    const solidClasses = ['bg-steel-900/95', 'backdrop-blur', 'border-b', 'border-white/10'];
    const onScroll = () => {
        if (window.scrollY > 20) {
            siteNav.classList.add(...solidClasses);
            siteNav.classList.remove('border-transparent');
        } else {
            siteNav.classList.remove(...solidClasses);
            siteNav.classList.add('border-transparent');
        }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

const rupiahInputs = document.querySelectorAll('[data-rupiah]');

rupiahInputs.forEach((input) => {
    const format = () => {
        const digits = input.value.replace(/\D/g, '');
        input.value = digits === '' ? '' : Number(digits).toLocaleString('id-ID');
    };

    input.addEventListener('input', format);
    format();

    input.form?.addEventListener('submit', () => {
        input.value = input.value.replace(/\D/g, '');
    });
});

/* ─── Scroll animations (IntersectionObserver) ─── */
(function () {
    const animated = document.querySelectorAll('.anim');
    if (!animated.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        animated.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    animated.forEach(el => observer.observe(el));
})();
