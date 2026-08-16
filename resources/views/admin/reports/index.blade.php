@extends('layouts.admin')

@section('title', 'Laporan Penjualan — CV Adzra Engineering')
@section('page', 'Laporan Penjualan')

@section('content')
    <div class="plate rounded bg-white p-6">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div>
                <label for="from" class="block text-sm font-medium text-graphite-900">Dari Tanggal</label>
                <input type="date" id="from" name="from" value="{{ $from->format('Y-m-d') }}"
                       class="mt-1 rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>
            <div>
                <label for="to" class="block text-sm font-medium text-graphite-900">Sampai Tanggal</label>
                <input type="date" id="to" name="to" value="{{ $to->format('Y-m-d') }}"
                       class="mt-1 rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>
            <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Tampilkan</button>
            <a href="{{ route('admin.reports.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-center text-sm font-medium text-graphite-500 transition hover:text-steel-700">Reset</a>
        </form>
    </div>

    <div class="mt-6 grid gap-6 sm:grid-cols-3">
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Pendapatan (Lunas)</p>
            <p class="mt-2 font-mono text-3xl font-bold text-steel-700">Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Jumlah Transaksi Lunas</p>
            <p class="mt-2 font-mono text-3xl font-bold text-steel-700">{{ $totalTransactions }}</p>
        </div>
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Belum Lunas</p>
            <p class="mt-2 font-mono text-3xl font-bold text-amber-700">Rp {{ number_format((float) $unpaidAmount, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="border-b border-line-200 px-6 py-4">
                <h2 class="font-display text-lg font-bold text-steel-900">Rekap Harian</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daily as $row)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 text-graphite-900">{{ \Carbon\Carbon::parse($row->transaction_date)->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $row->total_count }} transaksi</td>
                                <td class="px-6 py-3 text-right font-medium text-graphite-900">Rp {{ number_format((float) $row->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-graphite-500">Tidak ada transaksi pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="border-b border-line-200 px-6 py-4">
                <h2 class="font-display text-lg font-bold text-steel-900">Rekap per Produk</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">Qty</th>
                            <th class="px-6 py-3 text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($byProduct as $row)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 text-graphite-900">{{ $row['product']->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $row['quantity'] }}</td>
                                <td class="px-6 py-3 text-right font-medium text-graphite-900">Rp {{ number_format((float) $row['total'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-graphite-500">Tidak ada data produk pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
