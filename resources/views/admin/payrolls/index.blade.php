@extends('layouts.admin')

@section('title', 'Penggajian — CV Adzra Engineering')
@section('page', 'Penggajian')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.payrolls.index') }}" class="flex items-center gap-2">
            <label for="period" class="text-sm font-medium text-graphite-900">Periode</label>
            <select id="period" name="period" onchange="this.form.submit()"
                    class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                @forelse ($periods as $available)
                    <option value="{{ $available }}" @selected($available === $period)>{{ \Carbon\Carbon::parse($available.'-01')->translatedFormat('F Y') }}</option>
                @empty
                    <option value="{{ $period }}">{{ $periodLabel }}</option>
                @endforelse
            </select>
        </form>

        <form method="POST" action="{{ route('admin.payrolls.generate') }}">
            @csrf
            <input type="hidden" name="period" value="{{ $period }}">
            <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                Buat Penggajian {{ $periodLabel }}
            </button>
        </form>
    </div>

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-2xl font-bold text-steel-700">{{ $workerCount }}</p>
            <p class="text-xs text-graphite-500">Pekerja dengan upah harian</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-2xl font-bold text-steel-700">{{ $approvalCount }}</p>
            <p class="text-xs text-graphite-500">Penggajian di-approve</p>
        </div>
        <div class="plate rounded bg-white p-5">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-2xl font-bold text-graphite-900">Rp {{ number_format((float) $approvedAmount, 0, ',', '.') }}</p>
            <p class="text-xs text-graphite-500">Total ter-approve bulan ini</p>
        </div>
    </div>

    @if ($payrolls->isEmpty())
        <div class="plate rounded p-12 text-center">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-lg font-semibold text-graphite-900">Belum ada penggajian untuk {{ $periodLabel }}</p>
            <p class="mt-2 text-sm text-graphite-500">Klik "Buat Penggajian" untuk menghitung otomatis dari data absensi pekerja.</p>
        </div>
    @else
        <div class="plate rounded bg-white">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <div class="border-b border-line-200 px-6 py-4">
                <p class="font-display text-sm font-semibold text-graphite-900">Daftar Penggajian — {{ $periodLabel }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                            <th class="px-6 py-3">Pekerja</th>
                            <th class="px-6 py-3">Posisi</th>
                            <th class="px-6 py-3">Upah Harian</th>
                            <th class="px-6 py-3">Hari</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrolls as $payroll)
                            <tr class="border-b border-line-200/60">
                                <td class="px-6 py-3 font-medium text-graphite-900">{{ $payroll->worker->name }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $payroll->worker->position }}</td>
                                <td class="px-6 py-3 text-graphite-500">Rp {{ number_format((float) $payroll->worker->salary, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-graphite-500">{{ $payroll->total_days }} hari</td>
                                <td class="px-6 py-3 font-semibold text-graphite-900">Rp {{ number_format((float) $payroll->salary_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-3">
                                    @if ($payroll->status === 'approved')
                                        <span class="rounded bg-steel-100 px-2 py-0.5 font-mono text-xs font-semibold uppercase tracking-widest text-steel-700">Approved</span>
                                    @else
                                        <span class="rounded bg-amber-100 px-2 py-0.5 font-mono text-xs font-semibold uppercase tracking-widest text-amber-700">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        @if ($payroll->status === 'draft')
                                            <form method="POST" action="{{ route('admin.payrolls.approve', $payroll) }}" onsubmit="return confirm('Approve gaji ini? Pengeluaran akan otomatis dicatat ke kas.')">
                                                @csrf
                                                <button type="submit" class="rounded-lg bg-steel-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-steel-900">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.payrolls.destroy', $payroll) }}" onsubmit="return confirm('Hapus draft penggajian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-graphite-500">{{ $payroll->approver?->name }} · {{ $payroll->approved_at?->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
