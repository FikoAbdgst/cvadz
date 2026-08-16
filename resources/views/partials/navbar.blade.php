<header class="pointer-events-none fixed inset-x-0 top-0 z-50">
    <div id="nav-shell" class="pointer-events-auto relative mx-auto flex items-center justify-between px-5">
        <span class="nav-rivet absolute left-2 top-1/2 -translate-y-1/2"></span>

        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center">
                <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
            </span>
            <span class="nav-full hidden font-display text-base font-bold text-white sm:inline">
                CV Adzra <span class="text-steel-400">Engineering</span>
            </span>
            <span class="nav-short font-display text-sm font-bold tracking-wide text-white sm:hidden">ADZRA</span>
        </a>

        <nav class="hidden items-center gap-6 md:flex">
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
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded border border-white/20 text-white md:hidden"
            aria-label="Buka menu" aria-expanded="false">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <span class="nav-rivet absolute right-2 top-1/2 -translate-y-1/2"></span>
    </div>

    <nav id="nav-mobile" class="pointer-events-auto mx-auto mt-2 hidden max-w-sm rounded-lg border border-white/10 bg-steel-900/95 px-4 py-3 backdrop-blur md:hidden">
        <a href="{{ route('home') }}"
            class="block py-2 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Beranda</a>
        <a href="{{ route('products.index') }}"
            class="block py-2 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Produk</a>
        <a href="{{ route('services.index') }}"
            class="block py-2 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Layanan</a>
        <a href="{{ route('about') }}"
            class="block py-2 font-mono text-xs font-semibold uppercase tracking-widest text-steel-400 hover:text-white">Tentang
            Kami</a>
    </nav>
</header>
