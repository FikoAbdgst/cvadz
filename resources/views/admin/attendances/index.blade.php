@extends('layouts.admin')

@section('title', 'Rekap Absensi — CV Adzra Engineering')
@section('page', 'Rekap Absensi')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="flex items-center gap-2">
            <label for="month" class="text-sm font-medium text-graphite-900">Bulan</label>
            <select id="month" name="month" onchange="this.form.submit()"
                    class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                @forelse ($availableMonths as $available)
                    <option value="{{ $available }}" @selected($available === $month)>{{ \Carbon\Carbon::parse($available.'-01')->translatedFormat('F Y') }}</option>
                @empty
                    <option value="{{ $month }}">{{ $month }}</option>
                @endforelse
            </select>
        </form>
        <p class="text-sm text-graphite-500">Data absensi bersifat read-only (dicatat di area kerja).</p>
    </div>

    @if ($summary->isNotEmpty())
        <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($summary as $row)
                <div class="plate rounded bg-white p-5">
                    <span class="plate-corner-bl"></span>
                    <span class="plate-corner-br"></span>
                    <p class="font-display text-sm font-semibold text-graphite-900">{{ $row['worker']->name }}</p>
                    <p class="label-mono mt-1 text-graphite-500">{{ $row['worker']->position }}</p>
                    <div class="mt-4 flex items-center gap-6">
                        <div>
                            <p class="font-display text-2xl font-bold text-steel-700">{{ $row['present'] }}</p>
                            <p class="text-xs text-graphite-500">Hadir</p>
                        </div>
                        <div>
                            <p class="font-display text-2xl font-bold text-amber-600">{{ $row['incomplete'] }}</p>
                            <p class="text-xs text-graphite-500">Tanpa check-out</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($attendances->isEmpty())
        <div class="plate rounded p-12 text-center">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <p class="font-display text-lg font-semibold text-graphite-900">Tidak ada data absensi pada bulan ini</p>
            <p class="mt-2 text-sm text-graphite-500">Pilih bulan lain atau tambahkan absensi di area kerja.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($attendances as $date => $rows)
                <details class="group rounded border border-line-200 bg-white">
                    <summary class="flex cursor-pointer items-center justify-between px-6 py-4 select-none transition hover:bg-paper-100">
                        <div class="flex items-center gap-3">
                            <span class="font-display text-sm font-semibold text-graphite-900">{{ $date }}</span>
                            <span class="rounded bg-steel-100 px-2 py-0.5 font-mono text-xs font-semibold text-steel-700">{{ $rows->count() }} pekerja</span>
                        </div>
                        <svg class="h-4 w-4 text-graphite-500 transition duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>
                    <div class="border-t border-line-200">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-xs uppercase tracking-wider text-graphite-500">
                                        <th class="px-6 py-3">Pekerja</th>
                                        <th class="px-6 py-3">Posisi</th>
                                        <th class="px-6 py-3">Check-in</th>
                                        <th class="px-6 py-3">Check-out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $attendance)
                                        <tr class="border-t border-line-200/60">
                                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $attendance->worker->name }}</td>
                                            <td class="px-6 py-3 text-graphite-500">{{ $attendance->worker->position }}</td>
                                            <td class="px-6 py-3 text-graphite-500">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}</td>
                                            <td class="px-6 py-3 text-graphite-500">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
    @endif
@endsection
