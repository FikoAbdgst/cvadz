@extends('layouts.admin')

@section('title', 'Produk — CV Adzra Engineering')
@section('page', 'Produk')

@section('content')
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">{{ session('error') }}</div>
    @endif

    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex flex-col gap-3 border-b border-line-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-2">
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari produk..."
                       class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <button type="submit" class="rounded-lg bg-paper-100 px-4 py-2 text-sm font-medium text-graphite-900 transition hover:bg-line-200">Cari</button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                + Tambah Produk
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Unggulan</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-12 shrink-0 overflow-hidden rounded-lg bg-paper-100">
                                        @if ($product->primaryImage())
                                            <img src="{{ asset('storage/'.$product->primaryImage()->image_path) }}" alt="" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                    <span class="font-medium text-graphite-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-graphite-500">{{ $product->category?->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $product->price ? 'Rp '.number_format((float) $product->price, 0, ',', '.') : '—' }}</td>
                            <td class="px-6 py-3">
                                <span class="font-mono font-medium
                                    {{ $product->stockStatus() === 'aman' ? 'text-graphite-900' : ($product->stockStatus() === 'kritis' ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if ($product->is_featured)
                                    <span class="rounded-full bg-amber-600/15 px-3 py-1 text-xs font-medium text-amber-700">Unggulan</span>
                                @else
                                    <span class="text-graphite-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Lihat</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Yakin ingin menghapus produk ini beserta semua gambarnya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-graphite-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
