<form method="POST" action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store') }}" class="space-y-5">
    @csrf
    @if (isset($supplier))
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-graphite-900">Nama Supplier</label>
        <input type="text" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required
               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
    </div>

    <div>
        <label for="contact_name" class="block text-sm font-medium text-graphite-900">Nama Kontak <span class="text-graphite-500">(opsional)</span></label>
        <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name', $supplier->contact_name ?? '') }}"
               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="phone" class="block text-sm font-medium text-graphite-900">Telepon</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-graphite-900">Email <span class="text-graphite-500">(opsional)</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"
                   class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
        </div>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-graphite-900">Alamat <span class="text-graphite-500">(opsional)</span></label>
        <textarea id="address" name="address" rows="3"
                  class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">{{ old('address', $supplier->address ?? '') }}</textarea>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            {{ isset($supplier) ? 'Simpan Perubahan' : 'Simpan Supplier' }}
        </button>
        <a href="{{ route('admin.suppliers.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
    </div>
</form>
