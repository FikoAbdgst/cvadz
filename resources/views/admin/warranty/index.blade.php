@extends('layouts.admin')

@section('title', 'Cek Garansi — CV Adzra Engineering')
@section('page', 'Cek Garansi')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.warranty.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ $search }}" placeholder="Nama pelanggan / no. pesanan"
                   class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            <button type="submit" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">Cari</button>
            @if ($search)
                <a href="{{ route('admin.warranty.index') }}" class="text-sm text-graphite-500 hover:text-steel-700">Reset</a>
            @endif
        </form>
        <p class="text-sm text-graphite-500">Hanya pemesanan yang memiliki tanggal selesai garansi.</p>
    </div>

    @if ($orders->isEmpty())
        <div class="plate rounded p-12 text-center">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-lg font-semibold text-graphite-900">Tidak ada data garansi</p>
            <p class="mt-2 text-sm text-graphite-500">Belum ada pemesanan dengan garansi, atau hasil pencarian tidak ditemukan.</p>
        </div>
    @else
        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">No. Pesanan</th>
                            <th class="px-6 py-3">Pelanggan</th>
                            <th class="px-6 py-3">Produk / Layanan</th>
                            <th class="px-6 py-3">Selesai Garansi</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-mono text-steel-700">#{{ $order->id }}</td>
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $order->customer?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->itemLabel() }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->warranty_end_date?->format('d M Y') }}</td>
                                <td class="px-6 py-3">
                                    @if ($order->warrantyStatus() === 'aktif')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-600">Kedaluwarsa</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-line-200 px-6 py-4">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
@endsection
