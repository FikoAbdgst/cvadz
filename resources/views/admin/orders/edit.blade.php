@extends('layouts.admin')

@section('title', 'Ubah Status Pemesanan — CV Adzra Engineering')
@section('page', 'Ubah Status Pemesanan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-graphite-500">Pelanggan</dt>
                    <dd class="mt-1 font-medium text-graphite-900">{{ $order->customer?->name }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Telepon</dt>
                    <dd class="mt-1 font-medium text-graphite-900">{{ $order->customer?->phone }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Produk / Layanan</dt>
                    <dd class="mt-1 font-medium text-graphite-900">{{ $order->itemLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Jumlah</dt>
                    <dd class="mt-1 font-medium text-graphite-900">{{ $order->quantity }}</dd>
                </div>
                @if ($order->total)
                    <div>
                        <dt class="text-graphite-500">Total Harga</dt>
                        <dd class="mt-1 font-medium text-graphite-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</dd>
                    </div>
                @endif
                @if ($order->hasWarranty())
                    <div>
                        <dt class="text-graphite-500">Selesai Garansi</dt>
                        <dd class="mt-1 font-medium text-graphite-900">
                            {{ $order->warranty_end_date->format('d M Y') }}
                            <span class="ml-1 rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $order->isWarrantyActive() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $order->warrantyStatus() === 'aktif' ? 'Aktif' : 'Kedaluwarsa' }}
                            </span>
                        </dd>
                    </div>
                @endif
                <div>
                    <dt class="text-graphite-500">Tanggal</dt>
                    <dd class="mt-1 font-medium text-graphite-900">{{ $order->created_at->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Status Saat Ini</dt>
                    <dd class="mt-1">
                        <span class="rounded-full px-3 py-1 text-xs font-medium bg-amber-100 text-amber-700">{{ $order->status->label() }}</span>
                    </dd>
                </div>
                @if ($order->notes)
                    <div class="col-span-2">
                        <dt class="text-graphite-500">Catatan</dt>
                        <dd class="mt-1 whitespace-pre-line font-medium text-graphite-900">{{ $order->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="mt-6 rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="font-display text-lg font-bold text-steel-900">Ubah Status</h2>

            @if ($errors->any())
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-4 space-y-5">
                @csrf
                @method('PUT')

                <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                <input type="hidden" name="product_id" value="{{ $order->product_id }}">
                <input type="hidden" name="service_id" value="{{ $order->service_id }}">
                <input type="hidden" name="quantity" value="{{ $order->quantity }}">
                <input type="hidden" name="total" value="{{ $order->total }}">
                <input type="hidden" name="warranty_end_date" value="{{ $order->warranty_end_date?->format('Y-m-d') }}">

                <div>
                    <label for="status" class="block text-sm font-medium text-graphite-900">Status Pemesanan</label>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        @foreach ($statuses as $status)
                            <label class="flex cursor-pointer items-center gap-3 rounded border border-line-200 px-4 py-3 transition has-[:checked]:border-steel-700 has-[:checked]:ring-2 has-[:checked]:ring-steel-700/20">
                                <input type="radio" name="status" value="{{ $status->value }}" @checked($order->status === $status)
                                       class="rounded-full border-line-200 text-steel-700 focus:ring-steel-700">
                                <span class="text-sm font-medium text-graphite-900">{{ $status->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-graphite-900">Catatan</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">{{ old('notes', $order->notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan Perubahan</button>
                    <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
