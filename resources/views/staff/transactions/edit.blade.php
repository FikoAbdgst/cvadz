@extends('layouts.admin')

@section('title', 'Edit Transaksi #'.$transaction->id.' — CV Adzra Engineering')
@section('page', 'Edit Transaksi #'.$transaction->id)

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

        <div class="rounded-lg border border-line-200 bg-paper-100 p-5">
            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-graphite-500">Pemesanan</dt>
                    <dd class="mt-0.5 font-mono font-medium text-graphite-900">#{{ $transaction->order_id }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Pelanggan</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $transaction->order?->customer?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Produk / Layanan</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $transaction->order?->itemLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Jumlah Pesanan</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">Rp {{ number_format((float) ($transaction->order?->total ?? 0), 0, ',', '.') }}</dd>
                </div>
            </dl>
        </div>

        <form method="POST" action="{{ route('staff.transactions.update', $transaction) }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="order_id" value="{{ $transaction->order_id }}">

            <div>
                <label for="amount" class="block text-sm font-medium text-graphite-900">Jumlah Pembayaran (Rp)</label>
                <input type="number" step="0.01" min="1" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-graphite-900">Metode Pembayaran</label>
                    <select id="payment_type" name="payment_type" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        <option value="tunai" @selected(old('payment_type', $transaction->payment_type) === 'tunai')>Tunai</option>
                        <option value="transfer" @selected(old('payment_type', $transaction->payment_type) === 'transfer')>Transfer</option>
                        <option value="lainnya" @selected(old('payment_type', $transaction->payment_type) === 'lainnya')>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-graphite-900">Status Pembayaran</label>
                    <select id="status" name="status" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $transaction->status->value) === $status->value)>
                                {{ $status->value === 'lunas' ? 'Lunas (Sekaligus)' : 'DP / Termin' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="transaction_date" class="block text-sm font-medium text-graphite-900">Tanggal Transaksi</label>
                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date?->toDateString()) }}" required
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>
            </div>

            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                <p class="font-medium">Catatan otomatis:</p>
                <ul class="mt-1 list-inside list-disc space-y-0.5">
                    <li>Catatan <strong>Pemasukan</strong> di Buku Kas ikut diperbarui mengikuti nominal & tanggal baru.</li>
                    <li>Jika status diubah menjadi <strong>Lunas</strong>, status pesanan otomatis menjadi <strong>Selesai</strong>.</li>
                    <li>Menghapus transaksi juga menghapus catatan pemasukannya di Buku Kas. Status pesanan tidak dikembalikan otomatis.</li>
                </ul>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan Perubahan</button>
                <a href="{{ route('staff.transactions.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
