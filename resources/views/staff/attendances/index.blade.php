@extends('layouts.admin')

@section('title', 'Absensi Harian — CV Adzra Engineering')
@section('page', 'Absensi Harian')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8 lg:col-span-1">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="font-display text-lg font-bold text-steel-900">Input Kehadiran</h2>
            <p class="mt-1 text-sm text-graphite-500">Catat jam masuk (check-in) dan jam pulang (check-out) pekerja.</p>

            <form method="POST" action="{{ route('staff.attendances.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="worker_id" class="block text-sm font-medium text-graphite-900">Pekerja</label>
                    <select id="worker_id" name="worker_id" required
                            class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        <option value="">Pilih pekerja...</option>
                        @foreach ($workers as $worker)
                            <option value="{{ $worker->id }}" @selected(old('worker_id') == $worker->id)>{{ $worker->name }} — {{ $worker->position }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-medium text-graphite-900">Tanggal</label>
                    <input type="date" id="date" name="date" value="{{ old('date', now()->toDateString()) }}" required
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="check_in" class="block text-sm font-medium text-graphite-900">Jam Masuk</label>
                        <input type="time" id="check_in" name="check_in" value="{{ old('check_in', '08:00') }}"
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>
                    <div>
                        <label for="check_out" class="block text-sm font-medium text-graphite-900">Jam Pulang</label>
                        <input type="time" id="check_out" name="check_out" value="{{ old('check_out') }}"
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>
                </div>

                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan Absensi</button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <form method="GET" action="{{ route('staff.attendances.index') }}" class="flex items-center gap-2">
                    <label for="date_filter" class="text-sm font-medium text-graphite-900">Tanggal</label>
                    <input type="date" id="date_filter" name="date" value="{{ $date->toDateString() }}" onchange="this.form.submit()"
                           class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </form>
                <p class="text-sm text-graphite-500">{{ $date->translatedFormat('l, d F Y') }} — {{ $presentCount }} pekerja hadir</p>
            </div>

            <div class="plate rounded bg-white">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                                <th class="px-6 py-3">Pekerja</th>
                                <th class="px-6 py-3">Posisi</th>
                                <th class="px-6 py-3">Check-in</th>
                                <th class="px-6 py-3">Check-out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr class="border-b border-line-200/60">
                                    <td class="px-6 py-3 font-medium text-graphite-900">{{ $attendance->worker->name }}</td>
                                    <td class="px-6 py-3 text-graphite-500">{{ $attendance->worker->position }}</td>
                                    <td class="px-6 py-3 text-graphite-500">{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}</td>
                                    <td class="px-6 py-3 text-graphite-500">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-graphite-500">Belum ada absensi pada tanggal ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
