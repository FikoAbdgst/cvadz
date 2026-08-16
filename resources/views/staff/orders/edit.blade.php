@extends('layouts.admin')

@section('title', 'Update Progress Pesanan #'.$order->id.' — CV Adzra Engineering')
@section('page', 'Update Progress Pesanan #'.$order->id)

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
                    <dt class="text-graphite-500">Pelanggan</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $order->customer?->name }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Produk / Layanan</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $order->itemLabel() }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Jumlah</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $order->quantity }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Total Harga</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $order->total ? 'Rp '.number_format((float) $order->total, 0, ',', '.') : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Tanggal Pesanan</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $order->created_at->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-graphite-500">Selesai Garansi</dt>
                    <dd class="mt-0.5 font-medium text-graphite-900">{{ $order->warranty_end_date?->format('d M Y') ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <form method="POST" action="{{ route('staff.orders.update', $order) }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="block text-sm font-medium text-graphite-900">Status Pekerjaan & Pengiriman</label>
                <select id="status" name="status" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $order->status->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-graphite-900">Catatan <span class="text-graphite-500">(opsional)</span></label>
                <textarea id="notes" name="notes" rows="3"
                          class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan Progress</button>
                <a href="{{ route('staff.orders.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Kembali</a>
            </div>
        </form>
    </div>
@endsection
