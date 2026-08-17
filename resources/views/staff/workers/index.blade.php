@extends('layouts.admin')

@section('title', 'Data Pekerja — CV Adzra Engineering')
@section('page', 'Data Pekerja')

@section('content')
    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex items-center justify-between gap-4 border-b border-line-200 px-6 py-4">
            <form method="GET" action="{{ route('staff.workers.index') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari pekerja..."
                       class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <button type="submit" class="rounded-lg border border-line-200 px-4 py-2 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Cari</button>
            </form>
            <a href="{{ route('staff.workers.create') }}" class="shrink-0 rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                + Tambah Pekerja
            </a>
        </div>

        <div class="px-6 py-4">
            <p class="text-sm text-graphite-500">{{ $pekerja->total() }} pekerja</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Jabatan</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3 text-right">Upah Harian</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pekerja as $worker)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $worker->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $worker->position ?: '—' }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $worker->phone ?: '—' }}</td>
                            <td class="px-6 py-3 text-right font-mono text-graphite-900">Rp {{ number_format((float) $worker->salary, 0, ',', '.') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('staff.workers.edit', $worker) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                    <form method="POST" action="{{ route('staff.workers.destroy', $worker) }}" onsubmit="return submitConfirm(this, 'Yakin ingin menghapus pekerja ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-graphite-500">Belum ada pekerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pekerja->hasPages())
            <div class="px-6 py-4">
                {{ $pekerja->links() }}
            </div>
        @endif
    </div>
@endsection
