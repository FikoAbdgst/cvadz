<form method="POST" action="{{ isset($pekerja) ? route('staff.workers.update', $pekerja) : route('staff.workers.store') }}" class="space-y-5">
    @csrf
    @if (isset($pekerja))
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-graphite-900">Nama Pekerja</label>
        <input type="text" id="name" name="name" value="{{ old('name', $pekerja->name ?? '') }}" required
               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
    </div>

    <div>
        <label for="position" class="block text-sm font-medium text-graphite-900">Jabatan <span class="text-graphite-500">(opsional)</span></label>
        <input type="text" id="position" name="position" value="{{ old('position', $pekerja->position ?? '') }}" placeholder="contoh: Tukang Las, Operator CNC"
               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="phone" class="block text-sm font-medium text-graphite-900">Telepon <span class="text-graphite-500">(opsional)</span></label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $pekerja->phone ?? '') }}"
                   class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
        </div>

        <div>
            <label for="salary" class="block text-sm font-medium text-graphite-900">Upah Harian (Rp)</label>
            <input type="number" step="0.01" min="0" id="salary" name="salary" value="{{ old('salary', $pekerja->salary ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            <p class="mt-1 text-xs text-graphite-500">Dipakai sebagai dasar perhitungan penggajian bulanan.</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            {{ isset($pekerja) ? 'Simpan Perubahan' : 'Simpan Pekerja' }}
        </button>
        <a href="{{ route('staff.workers.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
    </div>
</form>
