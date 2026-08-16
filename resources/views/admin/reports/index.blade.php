@extends('layouts.admin')

@section('title', 'Laporan — CV Adzra Engineering')
@section('page', 'Laporan')

@section('content')
    <div class="mb-6 flex flex-wrap gap-2 border-b border-line-200">
        @foreach ([
            'penjualan' => 'Penjualan',
            'stok' => 'Stok',
            'kas' => 'Arus Kas',
            'penggajian' => 'Penggajian',
        ] as $key => $label)
            <a href="{{ route('admin.reports.index', array_merge(['tab' => $key], $key === 'penjualan' || $key === 'kas' ? ['from' => $from->toDateString(), 'to' => $to->toDateString()] : [])) }}"
               class="-mb-px border-b-2 px-4 py-2.5 text-sm font-medium transition
                   {{ $tab === $key ? 'border-steel-700 text-steel-700' : 'border-transparent text-graphite-500 hover:text-steel-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($tab === 'penjualan' || $tab === 'kas')
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div>
                    <label for="from" class="block text-sm font-medium text-graphite-900">Dari Tanggal</label>
                    <input type="date" id="from" name="from" value="{{ $from->format('Y-m-d') }}"
                           class="mt-1 rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>
                <div>
                    <label for="to" class="block text-sm font-medium text-graphite-900">Sampai Tanggal</label>
                    <input type="date" id="to" name="to" value="{{ $to->format('Y-m-d') }}"
                           class="mt-1 rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Tampilkan</button>
                <a href="{{ route('admin.reports.index', ['tab' => $tab]) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-center text-sm font-medium text-graphite-500 transition hover:text-steel-700">Reset</a>
            </form>
        </div>
    @endif

    @if ($tab === 'penjualan')
        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Pendapatan (Lunas)</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Jumlah Transaksi Lunas</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">{{ $totalTransactions }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Belum Lunas</p>
                <p class="mt-2 font-mono text-3xl font-bold text-amber-700">Rp {{ number_format((float) $unpaidAmount, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="plate rounded bg-white">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <div class="border-b border-line-200 px-6 py-4">
                    <h2 class="font-display text-lg font-bold text-steel-900">Rekap Harian</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daily as $row)
                                <tr class="border-b border-line-200/60">
                                    <td class="px-6 py-3 text-graphite-900">{{ \Carbon\Carbon::parse($row->transaction_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-3 text-graphite-500">{{ $row->total_count }} transaksi</td>
                                    <td class="px-6 py-3 text-right font-medium text-graphite-900">Rp {{ number_format((float) $row->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-graphite-500">Tidak ada transaksi pada periode ini.</td>
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
                    <h2 class="font-display text-lg font-bold text-steel-900">Rekap per Produk / Layanan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                                <th class="px-6 py-3">Item</th>
                                <th class="px-6 py-3">Qty</th>
                                <th class="px-6 py-3 text-right">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($byItem as $row)
                                <tr class="border-b border-line-200/60">
                                    <td class="px-6 py-3 text-graphite-900">{{ $row['label'] }}</td>
                                    <td class="px-6 py-3 text-graphite-500">{{ $row['quantity'] }}</td>
                                    <td class="px-6 py-3 text-right font-medium text-graphite-900">Rp {{ number_format((float) $row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-graphite-500">Tidak ada data pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($tab === 'stok')
        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Stok</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">{{ $totalStock }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Stok Kritis</p>
                <p class="mt-2 font-mono text-3xl font-bold {{ $criticalStock > 0 ? 'text-amber-600' : 'text-steel-700' }}">{{ $criticalStock }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Nilai Stok</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">Rp {{ number_format((float) $stockValue, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="plate mt-6 rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Stok</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockItems as $product)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $product->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $product->category?->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 font-mono text-graphite-900">{{ $product->stock }}</td>
                                <td class="px-6 py-3">
                                    @if ($product->stockStatus() === 'aman')
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Aman</span>
                                    @elseif ($product->stockStatus() === 'kritis')
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">Kritis</span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-600">Habis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-graphite-500">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($tab === 'kas')
        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Pemasukan</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">Rp {{ number_format((float) $cashIncome, 0, ',', '.') }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Pengeluaran</p>
                <p class="mt-2 font-mono text-3xl font-bold text-amber-600">Rp {{ number_format((float) $cashExpense, 0, ',', '.') }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Saldo</p>
                <p class="mt-2 font-mono text-3xl font-bold text-graphite-900">Rp {{ number_format((float) $cashBalance, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="plate mt-6 rounded bg-white">
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
                        @forelse ($cashEntries as $entry)
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
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-graphite-500">Tidak ada transaksi kas pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($tab === 'penggajian')
        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Slip Gaji</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">{{ $payrollTotal }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Disetujui</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">{{ $payrollApprovedTotal }}</p>
            </div>
            <div class="plate rounded bg-white p-6">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500">Total Terbayar</p>
                <p class="mt-2 font-mono text-3xl font-bold text-steel-700">Rp {{ number_format((float) $payrollApprovedAmount, 0, ',', '.') }}</p>
            </div>
        </div>

        @forelse ($payrollsByPeriod as $period => $rows)
            <div class="plate mt-6 rounded bg-white">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <div class="border-b border-line-200 px-6 py-4">
                    <p class="font-display text-sm font-semibold text-graphite-900">{{ \Carbon\Carbon::parse($period.'-01')->translatedFormat('F Y') }}</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                                <th class="px-6 py-3">Pekerja</th>
                                <th class="px-6 py-3">Hari Kerja</th>
                                <th class="px-6 py-3">Upah Harian</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $payroll)
                                <tr class="border-b border-line-200/60">
                                    <td class="px-6 py-3 font-medium text-graphite-900">{{ $payroll->worker?->name }}</td>
                                    <td class="px-6 py-3 text-graphite-500">{{ $payroll->total_days }} hari</td>
                                    <td class="px-6 py-3 text-graphite-500">Rp {{ number_format((float) $payroll->worker?->salary, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 font-mono font-medium text-graphite-900">Rp {{ number_format((float) $payroll->salary_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3">
                                        @if ($payroll->status === 'approved')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Disetujui</span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">Draft</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="plate mt-6 rounded p-12 text-center">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <p class="font-display text-lg font-semibold text-graphite-900">Belum ada data penggajian</p>
                <p class="mt-2 text-sm text-graphite-500">Buat slip gaji lewat menu Penggajian terlebih dahulu.</p>
            </div>
        @endforelse
    @endif
@endsection
