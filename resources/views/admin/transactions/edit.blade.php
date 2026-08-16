@extends('layouts.admin')

@section('title', 'Edit Transaksi — CV Adzra Engineering')
@section('page', 'Edit Transaksi')

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

        <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="order_id" class="block text-sm font-medium text-graphite-900">Pemesanan</label>
                <select id="order_id" name="order_id" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}" @selected(old('order_id', $transaction->order_id) == $order->id)>
                            #{{ $order->id }} — {{ $order->customer?->name }} — {{ $order->product?->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-graphite-900">Jumlah (Rp)</label>
                <input type="number" step="0.01" min="0" id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="transaction_date" class="block text-sm font-medium text-graphite-900">Tanggal Transaksi</label>
                <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-graphite-900">Status Pembayaran</label>
                <select id="status" name="status" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $transaction->status->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan</button>
                <a href="{{ route('admin.sales.index', ['tab' => 'transaksi']) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
