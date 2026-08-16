@extends('layouts.admin')

@section('title', 'Daftar Transaksi — CV Adzra Engineering')
@section('page', 'Daftar Transaksi')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('staff.transactions.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="order_id" class="block text-sm font-medium text-graphite-900">No. Pesanan</label>
                <input type="number" min="1" id="order_id" name="order_id" value="{{ $orderId }}"
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
        <a href="{{ route('staff.transactions.create') }}" class="rounded-lg border border-amber-600 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-600 hover:text-white">+ Proses Pesanan</a>
    </div>

    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Faktur</th>
                        <th class="px-6 py-3">Pesanan</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Metode</th>
                        <th class="px-6 py-3">Jumlah</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Dicatat oleh</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-mono text-steel-700">#TRX-{{ $transaction->id }}</td>
                            <td class="px-6 py-3 font-mono text-graphite-900">#{{ $transaction->order_id }}</td>
                            <td class="px-6 py-3 text-graphite-900">{{ $transaction->order?->customer?->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ ucfirst($transaction->payment_type ?? '—') }}</td>
                            <td class="px-6 py-3 font-mono font-medium text-graphite-900">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                    {{ $transaction->status === \App\Enums\TransactionStatus::Lunas ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $transaction->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-graphite-500">{{ $transaction->staffUser?->name ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('staff.transactions.invoice', $transaction) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Faktur</a>
                                    <a href="{{ route('staff.transactions.edit', $transaction) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                    <form method="POST" action="{{ route('staff.transactions.destroy', $transaction) }}" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Catatan pemasukan di Buku Kas ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-graphite-500">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-line-200 px-6 py-4">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
