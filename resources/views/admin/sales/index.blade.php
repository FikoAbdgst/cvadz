@extends('layouts.admin')

@section('title', 'Transaksi — CV Adzra Engineering')
@section('page', 'Transaksi')

@section('content')
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex flex-wrap gap-1 border-b border-line-200 px-4 pt-3 sm:px-6">
            <a href="{{ route('admin.sales.index', ['tab' => 'pelanggan']) }}"
               class="flex items-center gap-2 rounded-t-lg border-b-2 px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $tab === 'pelanggan' ? 'border-steel-700 text-steel-700' : 'border-transparent text-graphite-500 hover:text-graphite-900' }}">
                Pelanggan
                <span class="rounded-full bg-paper-100 px-2 py-0.5 text-xs font-semibold text-graphite-500">{{ $counts['customers'] }}</span>
            </a>
            <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}"
               class="flex items-center gap-2 rounded-t-lg border-b-2 px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $tab === 'pemesanan' ? 'border-steel-700 text-steel-700' : 'border-transparent text-graphite-500 hover:text-graphite-900' }}">
                Pemesanan
                <span class="rounded-full bg-paper-100 px-2 py-0.5 text-xs font-semibold text-graphite-500">{{ $counts['orders'] }}</span>
            </a>
            <a href="{{ route('admin.sales.index', ['tab' => 'transaksi']) }}"
               class="flex items-center gap-2 rounded-t-lg border-b-2 px-4 py-2.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $tab === 'transaksi' ? 'border-steel-700 text-steel-700' : 'border-transparent text-graphite-500 hover:text-graphite-900' }}">
                Transaksi
                <span class="rounded-full bg-paper-100 px-2 py-0.5 text-xs font-semibold text-graphite-500">{{ $counts['transactions'] }}</span>
            </a>
        </div>

        @if ($tab === 'pelanggan')
            <div class="flex flex-col gap-3 border-b border-line-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <form method="GET" action="{{ route('admin.sales.index', ['tab' => 'pelanggan']) }}" class="flex gap-2">
                    <input type="hidden" name="tab" value="pelanggan">
                    <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama / telepon / email..."
                           class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <button type="submit" class="rounded-lg bg-paper-100 px-4 py-2 text-sm font-medium text-graphite-900 transition hover:bg-line-200">Cari</button>
                </form>
                <a href="{{ route('admin.customers.create') }}" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                    + Tambah Pelanggan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Telepon</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Alamat</th>
                            <th class="px-6 py-3">Pemesanan</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $customer->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $customer->phone }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $customer->email ?? '—' }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $customer->address ?? '—' }}</td>
                                <td class="px-6 py-3">
                                    @if ($customer->orders_count)
                                        <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan', 'customer' => $customer->id]) }}"
                                           class="font-medium text-steel-700 hover:underline">{{ $customer->orders_count }} pemesanan</a>
                                    @else
                                        <span class="text-graphite-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.customers.edit', $customer) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                        <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini? Pemesanan terkait ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-graphite-500">Belum ada pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages())
                <div class="px-6 py-4">
                    {{ $customers->links() }}
                </div>
            @endif
        @endif

        @if ($tab === 'pemesanan')
            <div class="flex flex-col gap-3 border-b border-line-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.sales.index', array_filter(['tab' => 'pemesanan', 'customer' => $customerId])) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium transition {{ blank($status) ? 'border-steel-700 bg-steel-700 text-white' : 'border-line-200 bg-white text-graphite-500 hover:text-steel-700' }}">
                        Semua
                    </a>
                    @foreach ($statuses as $statusOption)
                        <a href="{{ route('admin.sales.index', array_filter(['tab' => 'pemesanan', 'status' => $statusOption->value, 'customer' => $customerId])) }}"
                           class="rounded-full border px-4 py-1.5 text-sm font-medium transition {{ $status === $statusOption->value ? 'border-steel-700 bg-steel-700 text-white' : 'border-line-200 bg-white text-graphite-500 hover:text-steel-700' }}">
                            {{ $statusOption->label() }}
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('admin.orders.create') }}" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                    + Tambah Pemesanan
                </a>
            </div>

            @if ($activeCustomer)
                <div class="flex items-center gap-2 border-b border-line-200 bg-paper-100 px-6 py-3 text-sm text-graphite-500">
                    <span>Filter pelanggan:</span>
                    <span class="font-semibold text-graphite-900">{{ $activeCustomer->name }}</span>
                    <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}" class="font-medium text-steel-700 hover:underline">Hapus filter</a>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Pelanggan</th>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">Qty</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Transaksi</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $order->customer?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->product?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->quantity }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3">
                                    @if ($order->transactions_count)
                                        <a href="{{ route('admin.sales.index', ['tab' => 'transaksi', 'order' => $order->id]) }}"
                                           class="font-medium text-steel-700 hover:underline">{{ $order->transactions_count }} transaksi</a>
                                        <span class="block text-xs text-graphite-500">Rp {{ number_format((float) ($order->transactions_sum_amount ?? 0), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-graphite-500">—</span>
                                    @endif
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
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('admin.transactions.create', ['order_id' => $order->id]) }}" class="rounded-lg border border-amber-600 px-3 py-1.5 text-xs font-medium text-amber-600 transition hover:bg-amber-50">Buat Transaksi</a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Ubah Status</a>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Yakin ingin menghapus pemesanan ini? Transaksi terkait ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-graphite-500">Belum ada pemesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="px-6 py-4">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif

        @if ($tab === 'transaksi')
            <div class="flex flex-col gap-3 border-b border-line-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.sales.index', array_filter(['tab' => 'transaksi', 'order' => $orderId])) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium transition {{ blank($status) ? 'border-steel-700 bg-steel-700 text-white' : 'border-line-200 bg-white text-graphite-500 hover:text-steel-700' }}">
                        Semua
                    </a>
                    @foreach ($transactionStatuses as $statusOption)
                        <a href="{{ route('admin.sales.index', array_filter(['tab' => 'transaksi', 'status' => $statusOption->value, 'order' => $orderId])) }}"
                           class="rounded-full border px-4 py-1.5 text-sm font-medium transition {{ $status === $statusOption->value ? 'border-steel-700 bg-steel-700 text-white' : 'border-line-200 bg-white text-graphite-500 hover:text-steel-700' }}">
                            {{ $statusOption->label() }}
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('admin.transactions.create') }}" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                    + Tambah Transaksi
                </a>
            </div>

            @if ($activeOrder)
                <div class="flex items-center gap-2 border-b border-line-200 bg-paper-100 px-6 py-3 text-sm text-graphite-500">
                    <span>Filter pemesanan:</span>
                    <span class="font-semibold text-graphite-900">#{{ $activeOrder->id }} — {{ $activeOrder->customer?->name }} — {{ $activeOrder->product?->name }}</span>
                    <a href="{{ route('admin.sales.index', ['tab' => 'transaksi']) }}" class="font-medium text-steel-700 hover:underline">Hapus filter</a>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Pemesanan</th>
                            <th class="px-6 py-3">Pelanggan</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 text-graphite-500">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.sales.index', ['tab' => 'transaksi', 'order' => $transaction->order_id]) }}"
                                       class="text-graphite-500 transition hover:text-steel-700">
                                        #{{ $transaction->order_id }} — {{ $transaction->order?->product?->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $transaction->order?->customer?->name ?? '—' }}</td>
                                <td class="px-6 py-3 font-medium text-graphite-900">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ $transaction->status->value === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $transaction->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.transactions.edit', $transaction) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                        <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-graphite-500">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="px-6 py-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
