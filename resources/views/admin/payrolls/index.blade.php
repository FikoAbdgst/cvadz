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
                    @php
                        $weekStart = \Carbon\Carbon::parse($available);
                        $weekEnd = $weekStart->copy()->addDays(5);
                        $isCurrentWeek = $available === $currentMonday;
                    @endphp
                    <option value="{{ $available }}" @selected($available === $period)>{{ $weekStart->translatedFormat('d M').' – '.$weekEnd->translatedFormat('d M Y') }}{{ $isCurrentWeek ? ' (Minggu Ini)' : '' }}</option>
                @empty
                    <option value="{{ $period }}">{{ $periodLabel }}</option>
                @endforelse
            </select>
        </form>

        @if ($period === $currentMonday && ! $currentWeekHasPayroll)
            <form method="POST" action="{{ route('admin.payrolls.generate') }}">
                @csrf
                <input type="hidden" name="period" value="{{ $period }}">
                <button type="submit" class="rounded-lg bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                    Generate Penggajian Minggu Ini
                </button>
            </form>
        @elseif ($period !== $currentMonday && $payrolls->isEmpty())
            <form method="POST" action="{{ route('admin.payrolls.generate') }}">
                @csrf
                <input type="hidden" name="period" value="{{ $period }}">
                <button type="submit" class="rounded-lg border border-amber-600 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-50">
                    Generate Periode Ini
                </button>
            </form>
        @endif
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
            @if ($period === $currentMonday)
                <p class="mt-2 text-sm text-graphite-500">Klik "Generate Penggajian Minggu Ini" untuk menghitung otomatis dari data absensi pekerja.</p>
            @else
                <p class="mt-2 text-sm text-graphite-500">Klik "Generate Periode Ini" untuk membuat penggajian dari data absensi periode ini.</p>
            @endif
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
                            <th class="px-4 py-3 lg:px-6">Pekerja</th>
                            <th class="px-4 py-3 lg:px-6">Hari</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Gaji Pokok</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Bonus</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Lemburan</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Uluar Kota</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Kasbon</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Net</th>
                            <th class="px-4 py-3 lg:px-6">Status</th>
                            <th class="px-4 py-3 lg:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrolls as $payroll)
                            <tr class="border-b border-line-200/60">
                                <td class="px-4 py-3 lg:px-6">
                                    <p class="font-medium text-graphite-900">{{ $payroll->worker->name }}</p>
                                    <p class="text-xs text-graphite-500">{{ $payroll->worker->position }} · Rp {{ number_format((float) $payroll->worker->salary, 0, ',', '.') }}/hari</p>
                                </td>
                                <td class="px-4 py-3 lg:px-6 text-graphite-500">{{ $payroll->total_days }}</td>
                                <td class="px-4 py-3 lg:px-6 text-right text-graphite-500">{{ number_format((float) $payroll->salary_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 lg:px-6 text-right text-graphite-500">{{ $payroll->bonus > 0 ? '+'.number_format((float) $payroll->bonus, 0, ',', '.') : '—' }}</td>
                                <td class="px-4 py-3 lg:px-6 text-right text-graphite-500">{{ $payroll->lemburan > 0 ? '+'.number_format((float) $payroll->lemburan, 0, ',', '.') : '—' }}</td>
                                <td class="px-4 py-3 lg:px-6 text-right text-graphite-500">{{ $payroll->uang_luar_kota > 0 ? '+'.number_format((float) $payroll->uang_luar_kota, 0, ',', '.') : '—' }}</td>
                                <td class="px-4 py-3 lg:px-6 text-right text-graphite-500">{{ $payroll->kasbon > 0 ? '-'.number_format((float) $payroll->kasbon, 0, ',', '.') : '—' }}</td>
                                <td class="px-4 py-3 lg:px-6 text-right font-semibold text-graphite-900">Rp {{ number_format((float) $payroll->net_salary, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 lg:px-6">
                                    @if ($payroll->status === 'approved')
                                        <span class="rounded bg-steel-100 px-2 py-0.5 font-mono text-xs font-semibold uppercase tracking-widest text-steel-700">Approved</span>
                                    @else
                                        <span class="rounded bg-amber-100 px-2 py-0.5 font-mono text-xs font-semibold uppercase tracking-widest text-amber-700">Draft</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 lg:px-6">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.payrolls.slip', $payroll) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Slip</a>
                                        @if ($payroll->status === 'draft')
                                            <a href="{{ route('admin.payrolls.edit', $payroll) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                            <form method="POST" action="{{ route('admin.payrolls.approve', $payroll) }}" onsubmit="return submitConfirm(this, 'Approve gaji ini? Pengeluaran akan otomatis dicatat ke kas.')">
                                                @csrf
                                                <button type="submit" class="rounded-lg bg-steel-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-steel-900">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.payrolls.destroy', $payroll) }}" onsubmit="return submitConfirm(this, 'Hapus draft penggajian ini?')">
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
