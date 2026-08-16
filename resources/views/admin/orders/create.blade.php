@extends('layouts.admin')

@section('title', 'Tambah Pemesanan — CV Adzra Engineering')
@section('page', 'Tambah Pemesanan')

@section('content')
    <div class="mx-auto max-w-2xl rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="customer_id" class="block text-sm font-medium text-graphite-900">Pelanggan</label>
                <select id="customer_id" name="customer_id" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <option value="">Pilih pelanggan...</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-graphite-500">Belum ada pelanggan? <a href="{{ route('admin.customers.create') }}" class="text-steel-700 hover:underline">Tambahkan dulu</a></p>
            </div>

            <div>
                <label for="product_id" class="block text-sm font-medium text-graphite-900">Produk</label>
                <select id="product_id" name="product_id" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <option value="">Pilih produk...</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-graphite-900">Jumlah</label>
                <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-graphite-900">Status</label>
                <select id="status" name="status" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', 'pending') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-graphite-900">Catatan <span class="text-graphite-500">(opsional)</span></label>
                <textarea id="notes" name="notes" rows="3"
                          class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan</button>
                <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
