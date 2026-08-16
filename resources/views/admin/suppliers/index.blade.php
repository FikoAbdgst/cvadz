@extends('layouts.admin')

@section('title', 'Supplier — CV Adzra Engineering')
@section('page', 'Supplier')

@section('content')
    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex items-center justify-between gap-4 border-b border-line-200 px-6 py-4">
            <form method="GET" action="{{ route('admin.suppliers.index') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari supplier..."
                       class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <button type="submit" class="rounded-lg border border-line-200 px-4 py-2 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Cari</button>
            </form>
            <a href="{{ route('admin.suppliers.create') }}" class="shrink-0 rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                + Tambah Supplier
            </a>
        </div>

        <div class="px-6 py-4">
            <p class="text-sm text-graphite-500">{{ $suppliers->total() }} supplier</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Kontak</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $supplier->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $supplier->contact_name ?: '—' }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $supplier->phone }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $supplier->email ?: '—' }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-graphite-500">Belum ada supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($suppliers->hasPages())
            <div class="px-6 py-4">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
@endsection
