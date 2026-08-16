@extends('layouts.admin')

@section('title', 'Faktur #TRX-' . $transaction->id . ' — CV Adzra Engineering')
@section('page', 'Faktur Penjualan')

@section('content')
    <div class="mb-4 flex items-center gap-3 print:hidden">
        <button type="button" onclick="window.print()"
            class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            Cetak / Simpan PDF
        </button>
        <a href="{{ route('staff.transactions.index') }}"
            class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Kembali</a>
    </div>

    <div
        class="mx-auto max-w-2xl border border-line-200 bg-white shadow-sm print:max-w-none print:border-0 print:shadow-none">
        <div class="border-b border-dashed border-line-200 px-8 py-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                    <div>
                        <p class="font-display text-lg font-bold leading-tight text-steel-900">CV Adzra Engineering</p>
                        <p class="text-xs text-graphite-500">Fabrikasi & Jasa Mesin Industri<br>Bandung, Indonesia</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-display text-sm font-bold uppercase tracking-widest text-steel-700">Faktur Penjualan</p>
                    <p class="mt-1 font-mono text-sm text-graphite-900">#TRX-{{ $transaction->id }}</p>
                    <p class="text-xs text-graphite-500">{{ $transaction->transaction_date->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 px-8 py-6 sm:grid-cols-2">
            <div>
                <p class="label-mono text-graphite-500">Ditagihkan Kepada</p>
                <p class="mt-1 font-medium text-graphite-900">{{ $transaction->order?->customer?->name ?? '—' }}</p>
                <p class="mt-1 text-sm text-graphite-500">{{ $transaction->order?->customer?->phone ?? '' }}</p>
                <p class="text-sm text-graphite-500">{{ $transaction->order?->customer?->address ?? '' }}</p>
            </div>
            <div class="sm:text-right">
                <p class="label-mono text-graphite-500">Kasir</p>
                <p class="mt-1 font-medium text-graphite-900">{{ $transaction->staffUser?->name ?? '—' }}</p>
                <p class="mt-2 label-mono text-graphite-500">No. Pesanan</p>
                <p class="mt-1 font-mono text-sm text-steel-700">#{{ $transaction->order_id }}</p>
            </div>
        </div>

        <div class="px-8 pb-6">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-graphite-900 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="py-2">Produk / Layanan</th>
                        <th class="py-2 text-center">Qty</th>
                        <th class="py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-line-200">
                        <td class="py-3 font-medium text-graphite-900">{{ $transaction->order?->itemLabel() }}</td>
                        <td class="py-3 text-center text-graphite-500">{{ $transaction->order?->quantity }}</td>
                        <td class="py-3 text-right font-mono text-graphite-900">Rp
                            {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-8 pb-8">
            <div class="ml-auto max-w-xs space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-graphite-500">Metode</span>
                    <span class="font-medium text-graphite-900">{{ ucfirst($transaction->payment_type ?? '—') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-graphite-500">Status</span>
                    <span
                        class="rounded-full px-2.5 py-0.5 text-xs font-semibold
                        {{ $transaction->status === \App\Enums\TransactionStatus::Lunas ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $transaction->status->label() }}
                    </span>
                </div>
                <div class="flex items-center justify-between border-t-2 border-graphite-900 pt-3">
                    <span class="label-mono text-graphite-900">Total Dibayar</span>
                    <span class="font-mono text-xl font-bold text-steel-900">Rp
                        {{ number_format((float) $transaction->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-10 flex items-end justify-between">
                <p class="text-sm text-graphite-500">Terima kasih atas kepercayaan Anda.<br>Untuk pertanyaan:
                    wa.me/{{ config('services.whatsapp.number') }}</p>
                <div class="text-center">
                    <p class="text-xs text-graphite-500">Bandung, {{ $transaction->transaction_date->format('d M Y') }}</p>
                    <div class="mt-14">
                        <p class="font-medium text-graphite-900">{{ $transaction->staffUser?->name ?? 'Kasir' }}</p>
                        <p class="text-xs text-graphite-500">Kasir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
