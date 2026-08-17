@php
    $navItems = [
        ['route' => 'home',          'label' => 'Beranda'],
        ['route' => 'products.index','label' => 'Produk'],
        ['route' => 'services.index','label' => 'Layanan'],
        ['route' => 'about',         'label' => 'Tentang Kami'],
    ];
@endphp

<header id="site-nav" class="fixed inset-x-0 top-0 z-50 border-b border-transparent transition-colors duration-200">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="nav-anim nav-anim-delay-1 flex shrink-0 items-center gap-2.5">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center">
                <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
            </span>
            <span class="font-display text-base font-bold text-white">
                CV Adzra <span class="text-steel-400">Engineering</span>
            </span>
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            @foreach ($navItems as $i => $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="nav-anim nav-anim-delay-{{ $i + 2 }} relative font-mono text-xs font-semibold uppercase tracking-widest transition {{ $isActive ? 'text-white' : 'text-steel-400 hover:text-white' }}">
                    {{ $item['label'] }}
                    @if ($isActive)
                        <span class="absolute -bottom-1.5 left-0 h-px w-full bg-amber-500"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <button id="nav-toggle" type="button"
            class="nav-anim nav-anim-delay-2 flex h-9 w-9 shrink-0 items-center justify-center rounded border border-white/15 text-white md:hidden"
            aria-label="Buka menu" aria-expanded="false">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <nav id="nav-mobile" class="hidden border-t border-white/10 bg-steel-900 px-4 py-2 md:hidden">
        @foreach ($navItems as $item)
            @php
                $isActive = request()->routeIs($item['route']);
            @endphp
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 py-3 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $isActive ? 'text-white' : 'text-steel-400 hover:text-white' }}">
                @if ($isActive)
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-500"></span>
                @else
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-steel-400/30"></span>
                @endif
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</header>
