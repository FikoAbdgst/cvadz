<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — CV Adzra Engineering')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper-100 font-body text-graphite-900 antialiased">
    <div class="flex min-h-screen">
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform bg-steel-900 text-white transition-transform duration-200 lg:static lg:translate-x-0">
            <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
                </span>
                <div>
                    <p class="font-display text-sm font-bold leading-tight">CV Adzra Engineering</p>
                    <p class="label-mono text-steel-400">Panel Admin</p>
                </div>
            </div>

            <nav class="space-y-1 p-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Kategori
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.products.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Produk
                </a>
                <a href="{{ route('admin.sales.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.sales.*') || request()->routeIs('admin.customers.*') || request()->routeIs('admin.orders.*') || request()->routeIs('admin.transactions.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Transaksi
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.reports.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Laporan Penjualan
                </a>
            </nav>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-line-200 bg-white px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" id="sidebar-toggle" class="rounded border border-line-200 p-2 text-graphite-500 lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-display text-lg font-bold text-graphite-900">@yield('page', 'Panel Admin')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500 transition hover:text-steel-700">Lihat Situs</a>
                    <span class="hidden text-sm text-graphite-500 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-steel-700 px-4 py-2 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-steel-900">
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
