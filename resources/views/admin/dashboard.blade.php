@extends('layouts.admin')

@section('title', 'Dashboard — CV Adzra Engineering')
@section('page', 'Dashboard')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Produk</p>
            <p class="mt-2 font-mono text-4xl font-bold text-steel-700">{{ $totalProducts }}</p>
        </div>
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Kategori</p>
            <p class="mt-2 font-mono text-4xl font-bold text-steel-700">{{ $totalCategories }}</p>
        </div>
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Pelanggan</p>
            <p class="mt-2 font-mono text-4xl font-bold text-steel-700">{{ $totalCustomers }}</p>
        </div>
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Pemesanan</p>
            <p class="mt-2 font-mono text-4xl font-bold text-steel-700">{{ $totalOrders }}</p>
            <p class="mt-1 font-mono text-xs text-graphite-500">{{ $pendingOrders }} menunggu diproses</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="plate rounded bg-white p-6 lg:col-span-2">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Penjualan Bulan Ini (Lunas)</p>
            <p class="mt-2 font-mono text-4xl font-bold text-steel-700">
                Rp {{ number_format((float) $monthlySales, 0, ',', '.') }}
            </p>
            <p class="mt-1 font-mono text-xs text-graphite-500">{{ $monthlyTransactions }} transaksi lunas</p>
        </div>
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Menu Cepat</p>
            <div class="mt-4 grid gap-3">
                <a href="{{ route('admin.products.create') }}" class="rounded bg-amber-600 px-4 py-2.5 text-center font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">Tambah Produk</a>
                <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}" class="rounded border border-line-200 px-4 py-2.5 text-center font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500 transition hover:border-steel-700 hover:text-steel-700">Kelola Pemesanan</a>
                <a href="{{ route('admin.sales.index', ['tab' => 'transaksi']) }}" class="rounded border border-line-200 px-4 py-2.5 text-center font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500 transition hover:border-steel-700 hover:text-steel-700">Lihat Transaksi</a>
                <a href="{{ route('admin.reports.index') }}" class="rounded border border-line-200 px-4 py-2.5 text-center font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500 transition hover:border-steel-700 hover:text-steel-700">Laporan Penjualan</a>
            </div>
        </div>
    </div>

    <div class="plate mt-6 rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex items-center justify-between border-b border-line-200 px-6 py-4">
            <h2 class="font-display text-lg font-bold text-steel-900">Pemesanan Terbaru</h2>
            <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}" class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 font-mono text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Qty</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $order->customer?->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->product?->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->quantity }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                    {{ $order->status->value === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $order->status->value === 'diproses' ? 'bg-blue-100 text-steel-700' : '' }}
                                    {{ $order->status->value === 'selesai' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status->value === 'batal' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-graphite-500">Belum ada pemesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
