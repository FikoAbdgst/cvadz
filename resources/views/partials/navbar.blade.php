<header id="site-nav" class="fixed inset-x-0 top-0 z-50 border-b border-transparent transition-colors duration-200">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center">
                <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
            </span>
            <span class="font-display text-base font-bold text-white">
                CV Adzra <span class="text-steel-400">Engineering</span>
            </span>
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            <a href="{{ route('home') }}"
                class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 transition hover:text-white">Beranda</a>
            <a href="{{ route('products.index') }}"
                class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 transition hover:text-white">Produk</a>
            <a href="{{ route('services.index') }}"
                class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 transition hover:text-white">Layanan</a>
            <a href="{{ route('about') }}"
                class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 transition hover:text-white">Tentang
                Kami</a>
        </nav>

        <button id="nav-toggle" type="button"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded border border-white/15 text-white md:hidden"
            aria-label="Buka menu" aria-expanded="false">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <nav id="nav-mobile" class="hidden border-t border-white/10 bg-steel-900 px-4 py-2 md:hidden">
        <a href="{{ route('home') }}"
            class="block py-3 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Beranda</a>
        <a href="{{ route('products.index') }}"
            class="block py-3 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Produk</a>
        <a href="{{ route('services.index') }}"
            class="block py-3 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Layanan</a>
        <a href="{{ route('about') }}"
            class="block py-3 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Tentang
            Kami</a>
    </nav>
</header>
