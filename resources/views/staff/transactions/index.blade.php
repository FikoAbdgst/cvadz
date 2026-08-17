@extends('layouts.admin')

@section('title', 'Daftar Transaksi — CV Adzra Engineering')
@section('page', 'Daftar Transaksi')

@section('content')
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex flex-wrap gap-1 border-b border-line-200 px-4 pt-3 sm:px-6">
            <a href="{{ route('staff.transactions.index', ['tab' => 'pesanan']) }}"
               class="flex items-center gap-2 rounded-t-lg border-b-2 px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $tab === 'pesanan' ? 'border-steel-700 text-steel-700' : 'border-transparent text-graphite-500 hover:text-graphite-900' }}">
                Perlu Dikerjakan
                <span class="rounded-full bg-paper-100 px-2 py-0.5 text-xs font-semibold text-graphite-500">{{ count($paidOrders) }}</span>
            </a>
            <a href="{{ route('staff.transactions.index', ['tab' => 'transaksi']) }}"
               class="flex items-center gap-2 rounded-t-lg border-b-2 px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $tab === 'transaksi' ? 'border-steel-700 text-steel-700' : 'border-transparent text-graphite-500 hover:text-graphite-900' }}">
                Riwayat Transaksi
            </a>
        </div>

        @if ($tab === 'pesanan')
            <div class="border-b border-line-200 px-6 py-4">
                <p class="text-sm text-graphite-500">Pesanan yang sudah dibayar (DP / Lunas) oleh admin. Verifikasi bukti pembayaran dan mulai kerjakan.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Pelanggan</th>
                            <th class="px-6 py-3">Produk / Layanan</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status Bayar</th>
                            <th class="px-6 py-3">Status Kerja</th>
                            <th class="px-6 py-3">Bukti</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paidOrders as $order)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-mono text-steel-700">#{{ $order->id }}</td>
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $order->customer?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->itemLabel() }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->total ? 'Rp '.number_format((float) $order->total, 0, ',', '.') : '—' }}</td>
                                <td class="px-6 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ ($order->payment_status?->value ?? 'belum') === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $order->payment_status?->label() ?? 'DP' }}
                                    </span>
                                </td>
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
                                    @if ($order->proofUrl())
                                        <a href="{{ $order->proofUrl() }}" target="_blank" class="text-steel-700 hover:underline">
                                            <img src="{{ $order->proofUrl() }}" alt="Bukti" class="h-8 rounded border border-line-200 object-cover">
                                        </a>
                                    @else
                                        <span class="text-graphite-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        @if (($order->payment_status?->value ?? null) === 'dp' && $order->transactions->first())
                                            <form method="POST" action="{{ route('staff.transactions.verify', $order->transactions->first()) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Verasifikasi pembayaran ini sebagai LUNAS? Pastikan sudah cek platform ATM.')"
                                                        class="rounded-lg border border-green-600 px-3 py-1.5 text-xs font-semibold text-green-700 transition hover:bg-green-600 hover:text-white">ACC Lunas</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('staff.orders.edit', $order) }}"
                                           class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Progress</a>
                                        @if ($order->transactions->first())
                                            <a href="{{ route('staff.transactions.invoice', $order->transactions->first()) }}"
                                               class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Faktur</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-graphite-500">Belum ada pesanan yang perlu dikerjakan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="border-b border-line-200 px-6 py-4">
                <form method="GET" action="{{ route('staff.transactions.index') }}" class="flex flex-wrap items-end gap-3">
                    <input type="hidden" name="tab" value="transaksi">
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
            </div>

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
        @endif
    </div>
@endsection
