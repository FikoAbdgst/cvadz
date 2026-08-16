@extends('layouts.admin')

@section('title', 'Kelola Akun — CV Adzra Engineering')
@section('page', 'Kelola Akun')

@section('content')
    <div class="plate rounded bg-white">
        <span class="plate-corner-bl"></span>
        <span class="plate-corner-br"></span>
        <div class="flex items-center justify-between gap-4 border-b border-line-200 px-6 py-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama/email..."
                       class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <button type="submit" class="rounded-lg border border-line-200 px-4 py-2 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Cari</button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="shrink-0 rounded-lg bg-steel-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-steel-900">
                + Tambah Akun
            </a>
        </div>

        <div class="px-6 py-4">
            <p class="text-sm text-graphite-500">{{ $users->total() }} akun</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line-200 text-xs uppercase tracking-wider text-graphite-500">
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Peran</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-line-200/60">
                            <td class="px-6 py-3 font-medium text-graphite-900">{{ $user->name }}</td>
                            <td class="px-6 py-3 text-graphite-500">{{ $user->email }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded px-2 py-0.5 font-mono text-xs font-semibold uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-steel-100 text-steel-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">Edit</a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-50">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-graphite-500">Belum ada akun.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
