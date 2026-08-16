@extends('layouts.admin')

@section('title', 'Progress Pemesanan — CV Adzra Engineering')
@section('page', 'Progress Pemesanan')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('staff.orders.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="q" class="block text-sm font-medium text-graphite-900">Cari</label>
                <input type="text" id="q" name="q" value="{{ $search }}" placeholder="Nama pelanggan / no. pesanan"
                       class="mt-1 rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-graphite-900">Status</label>
                <select id="status" name="status"
                        class="mt-1 rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <option value="">Semua</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected($statusFilter === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">Cari</button>
        </form>
    </div>

    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">No.</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Produk / Layanan</th>
                        <th class="px-6 py-3">Qty</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-mono text-steel-700">#{{ $order->id }}</td>
                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $order->customer?->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->itemLabel() }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->quantity }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->total ? 'Rp '.number_format((float) $order->total, 0, ',', '.') : '—' }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                    {{ $order->status->value === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $order->status->value === 'diproses' ? 'bg-blue-100 text-steel-700' : '' }}
                                    {{ $order->status->value === 'selesai' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status->value === 'batal' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ $order->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end">
                                    <a href="{{ route('staff.orders.edit', $order) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Update Progress</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-graphite-500">Tidak ada pemesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-line-200 px-6 py-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
