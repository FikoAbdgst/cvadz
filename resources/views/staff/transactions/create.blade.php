@extends('layouts.admin')

@section('title', 'Proses Pesanan — CV Adzra Engineering')
@section('page', 'Proses Pesanan')

@section('content')
    <div class="mx-auto max-w-2xl rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('staff.transactions.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="order_id" class="block text-sm font-medium text-graphite-900">Pemesanan</label>
                <select id="order_id" name="order_id" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <option value="">Pilih pemesanan...</option>
                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}" @selected(old('order_id', $preselectedOrder) == $order->id)>
                            #{{ $order->id }} — {{ $order->customer?->name }} — {{ $order->itemLabel() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-graphite-900">Jumlah Pembayaran (Rp)</label>
                <input type="number" step="0.01" min="1" id="amount" name="amount" value="{{ old('amount') }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <p class="mt-1 text-xs text-graphite-500">Isi sesuai nominal pembayaran yang diterima (DP, Termin, atau Pelunasan).</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-graphite-900">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        <option value="tunai" @selected(old('payment_type', 'tunai') === 'tunai')>Tunai</option>
                        <option value="transfer" @selected(old('payment_type') === 'transfer')>Transfer</option>
                        <option value="lainnya" @selected(old('payment_type') === 'lainnya')>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-graphite-900">Status Pembayaran</label>
                    <select id="status" name="status" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(old('status', 'belum_lunas') === $status->value)>
                                {{ $status->value === 'lunas' ? 'Lunas (Sekaligus)' : 'DP / Termin' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="transaction_date" class="block text-sm font-medium text-graphite-900">Tanggal Transaksi</label>
                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>
            </div>

            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                <p class="font-medium">Catatan otomatis:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    <li>Setiap pembayaran (DP/Termin/Lunas) dicatat otomatis sebagai <strong>Pemasukan</strong> di Buku Kas.</li>
                    <li>Jika status <strong>Lunas</strong>, status pesanan otomatis diperbarui menjadi <strong>Selesai</strong>.</li>
                </ul>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan & Cetak Faktur</button>
                <a href="{{ route('staff.transactions.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
