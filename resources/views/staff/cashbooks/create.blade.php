@extends('layouts.admin')

@section('title', 'Catat Pengeluaran Operasional — CV Adzra Engineering')
@section('page', 'Catat Pengeluaran Operasional')

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="font-display text-lg font-bold text-steel-900">Form Pengeluaran</h2>
            <p class="mt-1 text-sm text-graphite-500">Catat pembelian bensin, bahan, dan kebutuhan operasional lainnya. Otomatis masuk Buku Kas sebagai <strong>Pengeluaran</strong>.</p>

            <form method="POST" action="{{ route('staff.cashbooks.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="description" class="block text-sm font-medium text-graphite-900">Keterangan</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" placeholder="contoh: Bensin mesin, Plat baja, dll." required
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-graphite-900">Jumlah (Rp)</label>
                        <input type="number" step="0.01" min="1" id="amount" name="amount" value="{{ old('amount') }}" required
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-graphite-900">Tanggal</label>
                        <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>

        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="border-b border-line-200 px-6 py-4">
                <h2 class="font-display text-lg font-bold text-steel-900">Pengeluaran Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recent as $entry)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 text-graphite-500">{{ $entry->transaction_date->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-graphite-900">{{ $entry->description }}</td>
                                <td class="px-6 py-3 text-right font-mono font-medium text-amber-600">Rp {{ number_format((float) $entry->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-graphite-500">Belum ada pengeluaran tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
