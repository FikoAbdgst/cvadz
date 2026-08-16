@extends('layouts.admin')

@section('title', 'Kategori — CV Adzra Engineering')
@section('page', 'Kategori')

@section('content')
    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>

        <div class="flex items-center justify-between border-b border-line-200 px-6 py-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.categories.index', ['type' => 'produk']) }}"
                   class="rounded-lg px-4 py-2 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $type === 'produk' ? 'bg-steel-700 text-white' : 'text-graphite-500 hover:text-steel-700' }}">
                    Produk
                </a>
                <a href="{{ route('admin.categories.index', ['type' => 'layanan']) }}"
                   class="rounded-lg px-4 py-2 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $type === 'layanan' ? 'bg-steel-700 text-white' : 'text-graphite-500 hover:text-steel-700' }}">
                    Layanan
                </a>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                + Tambah Kategori
            </a>
        </div>

        <div class="px-6 py-4">
            <p class="text-sm text-graphite-500">{{ $categories->total() }} kategori {{ $type }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Jumlah</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $category->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $category->slug }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $type === 'layanan' ? $category->services_count : $category->products_count }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-graphite-500">Belum ada kategori {{ $type }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
