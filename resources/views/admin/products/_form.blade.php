<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if (isset($product))
        @method('PUT')
    @endif

    <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="font-display text-lg font-bold text-steel-900">Informasi Dasar</h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-graphite-900">Nama Produk</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-graphite-900">Kategori</label>
                <select id="category_id" name="category_id" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <option value="">Pilih kategori...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-graphite-900">Slug <span class="text-graphite-500">(opsional, otomatis dari nama)</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" placeholder="contoh: mesin-rotary-dryer"
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-graphite-900">Harga (Rp) <span class="text-graphite-500">(kosongkan jika belum tahu)</span></label>
                <input type="text" inputmode="numeric" id="price" name="price" value="{{ old('price', isset($product) && $product->price !== null ? number_format((float) $product->price, 0, ',', '.') : '') }}" data-rupiah
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium text-graphite-900">Stok <span class="text-graphite-500">(≤ {{ \App\Models\Product::LOW_STOCK_THRESHOLD }} dianggap kritis)</span></label>
                <input type="number" step="1" min="0" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="warranty_months" class="block text-sm font-medium text-graphite-900">Lama Garansi <span class="text-graphite-500">(bulan, 0 jika tanpa garansi)</span></label>
                <input type="number" step="1" min="0" max="240" id="warranty_months" name="warranty_months" value="{{ old('warranty_months', $product->warranty_months ?? 0) }}"
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div class="flex items-center gap-3 sm:col-span-2">
                <label class="flex items-center gap-2 text-sm text-graphite-900">
                    <input type="checkbox" name="is_featured" value="1" @checked($product->is_featured ?? false)
                           class="rounded border-line-200 text-steel-700 focus:ring-steel-700">
                    Jadikan Produk Unggulan
                </label>
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-graphite-900">Deskripsi</label>
                <textarea id="description" name="description" rows="5"
                          class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-lg font-bold text-steel-900">Spesifikasi Teknis</h2>
            <button type="button" id="add-spec" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">+ Tambah Baris</button>
        </div>

        <div id="specs-container" class="mt-5 space-y-3">
            @foreach (old('specifications', $product->specifications ?? []) as $key => $spec)
                @php $spec = is_object($spec) ? ['spec_key' => $spec->spec_key, 'spec_value' => $spec->spec_value] : $spec; @endphp
                <div class="spec-row grid gap-3 sm:grid-cols-2">
                    <input type="text" name="specifications[{{ $key }}][spec_key]" value="{{ $spec['spec_key'] }}" placeholder="Nama spesifikasi (contoh: Kapasitas)"
                           class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                    <div class="flex gap-2">
                        <input type="text" name="specifications[{{ $key }}][spec_value]" value="{{ $spec['spec_value'] }}" placeholder="Nilai (contoh: 1 ton/jam)"
                               class="w-full rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                        <button type="button" class="remove-spec shrink-0 rounded-lg border border-red-200 px-3 text-xs font-medium text-red-500 hover:bg-red-50">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-lg font-bold text-steel-900">Video Produk</h2>
            <button type="button" id="add-video" class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">+ Tambah Video</button>
        </div>
        <p class="mt-1 text-xs text-graphite-500">Masukkan URL YouTube atau link file video. Kosongkan untuk menghapus.</p>

        <div id="videos-container" class="mt-5 space-y-3">
            @foreach (old('videos', $product->videos ?? []) as $key => $video)
                @php $video = is_object($video) ? ['video_url' => $video->video_url, 'caption' => $video->caption] : $video; @endphp
                <div class="video-row grid gap-3 sm:grid-cols-2">
                    <input type="url" name="videos[{{ $key }}][video_url]" value="{{ $video['video_url'] }}" placeholder="https://youtube.com/watch?v=..."
                           class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                    <div class="flex gap-2">
                        <input type="text" name="videos[{{ $key }}][caption]" value="{{ $video['caption'] }}" placeholder="Keterangan (opsional)"
                               class="w-full rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                        <button type="button" class="remove-video shrink-0 rounded-lg border border-red-200 px-3 text-xs font-medium text-red-500 hover:bg-red-50">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="font-display text-lg font-bold text-steel-900">Galeri Gambar</h2>
        <p class="mt-1 text-xs text-graphite-500">Gambar pertama yang diunggah otomatis menjadi gambar utama bila belum ada.</p>

        <label class="mt-5 block cursor-pointer rounded border-2 border-dashed border-line-200 bg-paper-100 p-6 text-center transition hover:border-steel-700">
            <span class="text-sm font-medium text-graphite-500">Klik untuk memilih gambar (bisa banyak, maks 2MB per gambar)</span>
            <input type="file" name="images[]" multiple accept="image/*" class="hidden">
        </label>

        @if (isset($product) && $product->images->isNotEmpty())
            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($product->images as $image)
                    <div class="rounded border border-line-200 p-2">
                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar produk" class="aspect-[4/3] w-full rounded-lg object-cover">
                        <label class="mt-2 flex items-center gap-2 text-xs text-graphite-900">
                            <input type="radio" name="primary_image" value="{{ $image->id }}" @checked($image->is_primary)
                                   class="rounded-full border-line-200 text-steel-700 focus:ring-steel-700">
                            Gambar utama
                        </label>
                        <label class="mt-1 flex items-center gap-2 text-xs text-red-500">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="rounded border-red-300 text-red-500 focus:ring-red-500">
                            Hapus
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            {{ isset($product) ? 'Simpan Perubahan' : 'Simpan Produk' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
    </div>
</form>

<script>
    const specsContainer = document.getElementById('specs-container');
    const videosContainer = document.getElementById('videos-container');

    function specRowHtml(key) {
        return `
            <div class="spec-row grid gap-3 sm:grid-cols-2">
                <input type="text" name="specifications[${key}][spec_key]" placeholder="Nama spesifikasi (contoh: Kapasitas)" class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                <div class="flex gap-2">
                    <input type="text" name="specifications[${key}][spec_value]" placeholder="Nilai (contoh: 1 ton/jam)" class="w-full rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                    <button type="button" class="remove-spec shrink-0 rounded-lg border border-red-200 px-3 text-xs font-medium text-red-500 hover:bg-red-50">Hapus</button>
                </div>
            </div>`;
    }

    function videoRowHtml(key) {
        return `
            <div class="video-row grid gap-3 sm:grid-cols-2">
                <input type="url" name="videos[${key}][video_url]" placeholder="https://youtube.com/watch?v=..." class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                <div class="flex gap-2">
                    <input type="text" name="videos[${key}][caption]" placeholder="Keterangan (opsional)" class="w-full rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                    <button type="button" class="remove-video shrink-0 rounded-lg border border-red-200 px-3 text-xs font-medium text-red-500 hover:bg-red-50">Hapus</button>
                </div>
            </div>`;
    }

    function nextIndex() {
        return Math.max(0, ...Array.from(document.querySelectorAll('[name^="specifications["], [name^="videos["]')).map((el) => parseInt(el.name.match(/\[(\d+)\]/)[1], 10))) + 1;
    }

    document.getElementById('add-spec').addEventListener('click', () => {
        specsContainer.insertAdjacentHTML('beforeend', specRowHtml(nextIndex()));
    });

    document.getElementById('add-video').addEventListener('click', () => {
        videosContainer.insertAdjacentHTML('beforeend', videoRowHtml(nextIndex()));
    });

    specsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-spec')) e.target.closest('.spec-row').remove();
    });

    videosContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-video')) e.target.closest('.video-row').remove();
    });
</script>
