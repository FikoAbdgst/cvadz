@extends('layouts.admin')

@section('title', 'Kelola Stok — CV Adzra Engineering')
@section('page', 'Kelola Stok')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('staff.stock.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari produk..."
                   class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            <button type="submit" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">Cari</button>
        </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-1">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="font-display text-lg font-bold text-steel-900">Penyesuaian Stok</h2>
            <p class="mt-1 text-sm text-graphite-500">Tambah atau kurangi stok barang. Stok tidak bisa kurang dari 0.</p>

            <form method="POST" action="{{ route('staff.stock.update') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="product_id" class="block text-sm font-medium text-graphite-900">Produk</label>
                    <select id="product_id" name="product_id" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        <option value="">Pilih produk...</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->name }} (stok: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="action" class="block text-sm font-medium text-graphite-900">Tindakan</label>
                    <select id="action" name="action" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        <option value="tambah" @selected(old('action', 'tambah') === 'tambah')>Tambah Stok</option>
                        <option value="kurang" @selected(old('action') === 'kurang')>Kurangi Stok</option>
                    </select>
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-graphite-900">Jumlah</label>
                    <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity') }}" required
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>

                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan Penyesuaian</button>
            </form>
        </div>

        <div class="plate rounded bg-white lg:col-span-2">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Stok</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $product->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $product->category?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 font-mono font-medium text-graphite-900">{{ $product->stock }}</td>
                                <td class="px-6 py-3">
                                    @if ($product->stockStatus() === 'aman')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Aman</span>
                                    @elseif ($product->stockStatus() === 'kritis')
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">Kritis</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-600">Habis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-graphite-500">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-line-200 px-6 py-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
