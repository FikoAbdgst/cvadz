@extends('layouts.admin')

@section('title', 'Dashboard Operasional — CV Adzra Engineering')
@section('page', 'Dashboard Operasional')

@section('content')
    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Menunggu Diproses</p>
            <p class="mt-1 font-display text-3xl font-bold text-amber-600">{{ $pendingCount }}</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Sedang Diproses</p>
            <p class="mt-1 font-display text-3xl font-bold text-steel-700">{{ $inProgressCount }}</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Hadir Hari Ini</p>
            <p class="mt-1 font-display text-3xl font-bold text-steel-700">{{ $presentCount }}</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="label-mono text-graphite-500">Stok Kritis</p>
            <p class="mt-1 font-display text-3xl font-bold {{ $criticalStock > 0 ? 'text-amber-600' : 'text-steel-700' }}">{{ $criticalStock }}</p>
        </div>
    </div>

    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex items-center justify-between border-b border-line-200 px-6 py-4">
            <div>
                <h2 class="font-display text-lg font-bold text-steel-900">Pesanan Menunggu Diproses</h2>
                <p class="mt-1 text-sm text-graphite-500">Pesanan yang sudah dibayar oleh admin dan perlu dikerjakan.</p>
            </div>
            <a href="{{ route('staff.transactions.index', ['tab' => 'pesanan']) }}" class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:underline">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">No.</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Produk / Layanan</th>
                        <th class="px-6 py-3">Qty</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Bayar</th>
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
                            <td class="px-6 py-3 text-graphite-500">{{ $order->quantity }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $order->total ? 'Rp '.number_format((float) $order->total, 0, ',', '.') : '—' }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                    {{ ($order->payment_status?->value ?? 'belum') === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $order->payment_status?->label() ?? 'DP' }}
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
                                            <button type="submit" onclick="return submitConfirm(this.closest('form'), 'Verifikasi pembayaran ini sebagai LUNAS?')"
                                                    class="rounded-lg border border-green-600 px-3 py-1.5 text-xs font-semibold text-green-700 transition hover:bg-green-600 hover:text-white">ACC</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('staff.orders.edit', $order) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Progress</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-graphite-500">Tidak ada pesanan yang menunggu diproses.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="border-b border-line-200 px-6 py-4">
                <h2 class="font-display text-lg font-bold text-steel-900">Transaksi Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Pelanggan</th>
                            <th class="px-6 py-3">Jumlah</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-mono text-steel-700">#{{ $transaction->id }}</td>
                                <td class="px-6 py-3 text-graphite-900">{{ $transaction->order?->customer?->name ?? '—' }}</td>
                                <td class="px-6 py-3 font-mono text-graphite-900">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ $transaction->status === \App\Enums\TransactionStatus::Lunas ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $transaction->status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-graphite-500">Belum ada transaksi.</td>
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
                <h2 class="font-display text-lg font-bold text-steel-900">Kehadiran Hari Ini</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Pekerja</th>
                            <th class="px-6 py-3">Jam Masuk</th>
                            <th class="px-6 py-3">Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($todayPresent as $attendance)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $attendance->worker?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-graphite-500">Belum ada absensi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
