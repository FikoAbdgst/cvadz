@extends('layouts.admin')

@section('title', 'Buku Kas — CV Adzra Engineering')
@section('page', 'Buku Kas')

@section('content')
    <form method="GET" action="{{ route('admin.cashbooks.index') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label for="type" class="block text-sm font-medium text-graphite-900">Jenis</label>
            <select id="type" name="type"
                    class="mt-1 rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <option value="">Semua</option>
                <option value="pemasukan" @selected($type === 'pemasukan')>Pemasukan</option>
                <option value="pengeluaran" @selected($type === 'pengeluaran')>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label for="from" class="block text-sm font-medium text-graphite-900">Dari</label>
            <input type="date" id="from" name="from" value="{{ $from->toDateString() }}"
                   class="mt-1 rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
        </div>
        <div>
            <label for="to" class="block text-sm font-medium text-graphite-900">Sampai</label>
            <input type="date" id="to" name="to" value="{{ $to->toDateString() }}"
                   class="mt-1 rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
        </div>
        <button type="submit" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">Terapkan</button>
    </form>

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Pemasukan</p>
            <p class="mt-1 font-display text-2xl font-bold text-steel-700">Rp {{ number_format((float) $income, 0, ',', '.') }}</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Pengeluaran</p>
            <p class="mt-1 font-display text-2xl font-bold text-amber-600">Rp {{ number_format((float) $expense, 0, ',', '.') }}</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Saldo</p>
            <p class="mt-1 font-display text-2xl font-bold text-graphite-900">Rp {{ number_format((float) $balance, 0, ',', '.') }}</p>
        </div>
    </div>

    @if ($entries->isEmpty())
        <div class="plate rounded p-12 text-center">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-lg font-semibold text-graphite-900">Tidak ada transaksi kas</p>
            <p class="mt-2 text-sm text-graphite-500">Ubah rentang tanggal atau filter jenis transaksi.</p>
        </div>
    @else
        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Jenis</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3">Dicatat oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 text-graphite-500">{{ $entry->transaction_date->format('d M Y') }}</td>
                                <td class="px-6 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ $entry->type === 'pemasukan' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $entry->type === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-graphite-900">{{ $entry->description ?? '—' }}</td>
                                <td class="px-6 py-3 font-mono font-medium {{ $entry->type === 'pemasukan' ? 'text-steel-700' : 'text-amber-600' }}">
                                    Rp {{ number_format((float) $entry->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-graphite-500">{{ $entry->user?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-line-200 px-6 py-4">
                {{ $entries->links() }}
            </div>
        </div>
    @endif
@endsection
