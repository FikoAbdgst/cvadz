<form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-5">
    @csrf
    @if (isset($user))
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-graphite-900">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required
               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="email" class="block text-sm font-medium text-graphite-900">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-graphite-900">Peran</label>
            <select id="role" name="role" required
                    class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
                <option value="staff" @selected(old('role', $user->role ?? '') === 'staff')>Staff</option>
            </select>
        </div>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-graphite-900">
            Password @if (isset($user))<span class="text-graphite-500">(kosongkan jika tidak diubah)</span>@endif
        </label>
        <input type="password" id="password" name="password" autocomplete="new-password"
               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            {{ isset($user) ? 'Simpan Perubahan' : 'Simpan Akun' }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
    </div>
</form>
