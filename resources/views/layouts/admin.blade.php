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
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform bg-steel-900 text-white transition-transform duration-200 print:hidden lg:static lg:translate-x-0">
            <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
                </span>
                <div>
                    <p class="font-display text-sm font-bold leading-tight">CV Adzra Engineering</p>
                    <p class="label-mono text-steel-400">{{ auth()->user()->role === 'admin' ? 'Panel Admin' : 'Panel Staff' }}</p>
                </div>
            </div>

            @if (auth()->user()->role === 'admin')
            <nav class="space-y-1 overflow-y-auto p-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Dashboard
                </a>

                <p class="px-4 pt-4 pb-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-steel-400/60">Master Data</p>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Kategori
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.products.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Produk
                </a>
                <a href="{{ route('admin.suppliers.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Supplier
                </a>

                <p class="px-4 pt-4 pb-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-steel-400/60">Penjualan</p>
                <a href="{{ route('admin.sales.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.sales.*') || request()->routeIs('admin.customers.*') || request()->routeIs('admin.orders.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Transaksi
                </a>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.reports.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Laporan
                </a>
                <a href="{{ route('admin.warranty.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.warranty.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Cek Garansi
                </a>

                <p class="px-4 pt-4 pb-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-steel-400/60">HR & Keuangan</p>
                <a href="{{ route('admin.cashbooks.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.cashbooks.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Buku Kas
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Kelola Akun
                </a>
                <a href="{{ route('admin.attendances.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.attendances.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Rekap Absensi
                </a>
                <a href="{{ route('admin.payrolls.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('admin.payrolls.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Penggajian
                </a>
            </nav>
            @else
            <nav class="space-y-1 overflow-y-auto p-4">
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.dashboard') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Dashboard Operasional
                </a>

                <p class="px-4 pt-4 pb-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-steel-400/60">Kasir</p>
                <a href="{{ route('staff.transactions.create') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.transactions.create') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Proses Pesanan
                </a>
                <a href="{{ route('staff.transactions.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.transactions.index') || request()->routeIs('staff.transactions.invoice') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Daftar Transaksi
                </a>
                <a href="{{ route('staff.cashbooks.create') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.cashbooks.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Pengeluaran
                </a>

                <p class="px-4 pt-4 pb-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-steel-400/60">Operasional</p>
                <a href="{{ route('staff.orders.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.orders.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Progress Pemesanan
                </a>
                <a href="{{ route('staff.stock.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.stock.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Kelola Stok
                </a>

                <p class="px-4 pt-4 pb-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-steel-400/60">HR Harian</p>
                <a href="{{ route('staff.workers.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.workers.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Data Pekerja
                </a>
                <a href="{{ route('staff.attendances.index') }}"
                   class="flex items-center gap-3 rounded px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ request()->routeIs('staff.attendances.*') ? 'bg-amber-600 text-white' : 'text-steel-400 hover:bg-steel-700 hover:text-white' }}">
                    Absensi Harian
                </a>
            </nav>
            @endif
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/40 print:hidden lg:hidden"></div>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-line-200 bg-white px-4 print:hidden sm:px-6">
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
