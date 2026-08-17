<form id="product-form" method="POST"
    action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
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
                <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}"
                    required
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
                <label for="slug" class="block text-sm font-medium text-graphite-900">Slug <span
                        class="text-graphite-500">(opsional, otomatis dari nama)</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}"
                    placeholder="contoh: mesin-rotary-dryer"
                    class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-graphite-900">Harga (Rp) <span
                        class="text-graphite-500">(kosongkan jika belum tahu)</span></label>
                <input type="text" inputmode="numeric" id="price" name="price"
                    value="{{ old('price', isset($product) && $product->price !== null ? number_format((float) $product->price, 0, ',', '.') : '') }}"
                    data-rupiah
                    class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium text-graphite-900">Stok <span
                        class="text-graphite-500">(≤ {{ \App\Models\Product::LOW_STOCK_THRESHOLD }} dianggap
                        kritis)</span></label>
                <input type="number" step="1" min="0" id="stock" name="stock"
                    value="{{ old('stock', $product->stock ?? 0) }}"
                    class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="warranty_months" class="block text-sm font-medium text-graphite-900">Lama Garansi <span
                        class="text-graphite-500">(bulan, 0 jika tanpa garansi)</span></label>
                <input type="number" step="1" min="0" max="240" id="warranty_months"
                    name="warranty_months" value="{{ old('warranty_months', $product->warranty_months ?? 0) }}"
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
            <div>
                <h2 class="font-display text-lg font-bold text-steel-900">Spesifikasi Teknis</h2>
                <p class="mt-1 text-xs text-graphite-500">Matrix perbandingan: kolom = model/varian, baris =
                    model/spesifikasi.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" id="add-attr"
                    class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">+
                    Baris Model</button>
                <button type="button" id="add-model"
                    class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">+
                    Kolom Model</button>
            </div>
        </div>

        <div id="spec-matrix-wrap" class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[500px] border-collapse text-sm">
                <thead id="spec-matrix-thead"></thead>
                <tbody id="spec-matrix-tbody"></tbody>
            </table>
        </div>
        <div id="spec-matrix-empty" class="mt-5 hidden text-center text-xs text-graphite-500">
            Klik "+ Baris Model" atau "+ Kolom Model" untuk memulai.
        </div>
    </div>

    <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-lg font-bold text-steel-900">Video Produk</h2>
            <button type="button" id="add-video"
                class="rounded-lg border border-line-200 px-3 py-1.5 text-xs font-medium text-graphite-500 transition hover:text-steel-700">+
                Tambah Video</button>
        </div>
        <p class="mt-1 text-xs text-graphite-500">Masukkan URL YouTube atau link file video. Kosongkan untuk menghapus.
        </p>

        <div id="videos-container" class="mt-5 space-y-3">
            @foreach (old('videos', $product->videos ?? []) as $key => $video)
                @php $video = is_object($video) ? ['video_url' => $video->video_url, 'caption' => $video->caption] : $video; @endphp
                <div class="video-row grid gap-3 sm:grid-cols-2">
                    <input type="url" name="videos[{{ $key }}][video_url]"
                        value="{{ $video['video_url'] }}" placeholder="https://youtube.com/watch?v=..."
                        class="rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                    <div class="flex gap-2">
                        <input type="text" name="videos[{{ $key }}][caption]"
                            value="{{ $video['caption'] }}" placeholder="Keterangan (opsional)"
                            class="w-full rounded-lg border border-line-200 px-3 py-2 text-sm focus:border-steel-700 focus:outline-none">
                        <button type="button"
                            class="remove-video shrink-0 rounded-lg border border-red-200 px-3 text-xs font-medium text-red-500 hover:bg-red-50">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="font-display text-lg font-bold text-steel-900">Galeri Gambar</h2>
        <p class="mt-1 text-xs text-graphite-500">Gambar pertama yang diunggah otomatis menjadi gambar utama bila belum
            ada.</p>

        <label
            class="mt-5 block cursor-pointer rounded border-2 border-dashed border-line-200 bg-paper-100 p-6 text-center transition hover:border-steel-700">
            <span class="text-sm font-medium text-graphite-500">Klik untuk memilih gambar (bisa banyak, maks 2MB per
                gambar)</span>
            <input type="file" name="images[]" multiple accept="image/*" class="hidden">
        </label>

        @if (isset($product) && $product->images->isNotEmpty())
            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($product->images as $image)
                    <div class="rounded border border-line-200 p-2">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar produk"
                            class="aspect-[4/3] w-full rounded-lg object-cover">
                        <label class="mt-2 flex items-center gap-2 text-xs text-graphite-900">
                            <input type="radio" name="primary_image" value="{{ $image->id }}"
                                @checked($image->is_primary)
                                class="rounded-full border-line-200 text-steel-700 focus:ring-steel-700">
                            Gambar utama
                        </label>
                        <label class="mt-1 flex items-center gap-2 text-xs text-red-500">
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                class="rounded border-red-300 text-red-500 focus:ring-red-500">
                            Hapus
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <button type="submit"
            class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            {{ isset($product) ? 'Simpan Perubahan' : 'Simpan Produk' }}
        </button>
        <a href="{{ route('admin.products.index') }}"
            class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
    </div>
</form>

<script>
    const videosContainer = document.getElementById('videos-container');

    // ─── Spec Matrix ───
    const specModels = document.getElementById('spec-matrix-thead');
    const specBody = document.getElementById('spec-matrix-tbody');
    const specEmpty = document.getElementById('spec-matrix-empty');

    @php
        $specData = collect(old('specifications', $product->specifications ?? []))
            ->map(function ($s) {
                return is_object($s) ? ['model_name' => $s->model_name ?? null, 'spec_key' => $s->spec_key, 'spec_value' => $s->spec_value] : $s;
            })
            ->values()
            ->all();
    @endphp

    function initSpecMatrix() {
        const data = @json($specData);
        return parseSpecData(data);
    }

    function parseSpecData(rows) {
        const models = [];
        const attrs = [];
        const values = {};
        rows.forEach(function(r) {
            const m = (r.model_name || '').trim();
            const k = (r.spec_key || '').trim();
            const v = r.spec_value || '';
            if (!k) return;
            if (m && models.indexOf(m) === -1) models.push(m);
            if (attrs.indexOf(k) === -1) attrs.push(k);
            if (m) {
                if (!values[m]) values[m] = {};
                values[m][k] = v;
            }
        });
        return {
            models: models,
            attrs: attrs,
            values: values
        };
    }

    function specMatrixHtml() {
        const st = specState;
        const mLen = st.models.length;
        specEmpty.classList.toggle('hidden', mLen > 0 || st.attrs.length > 0);

        // thead
        let thead =
            '<tr><th class="border border-line-200 bg-paper-100 px-3 py-2 text-left font-mono text-xs uppercase tracking-widest text-graphite-500">Model</th>';
        st.models.forEach(function(m, mi) {
            thead +=
                '<th class="border border-line-200 bg-paper-100 px-3 py-2 text-left"><div class="flex items-center gap-1">';
            thead += '<input type="text" data-col="' + mi + '" value="' + esc(m) +
                '" placeholder="Nama model" class="spec-model-input w-full rounded border border-line-200 px-2 py-1 text-xs font-semibold text-graphite-900 focus:border-steel-700 focus:outline-none">';
            thead += '<button type="button" data-col="' + mi +
                '" class="spec-remove-col shrink-0 rounded border border-red-200 px-1.5 text-[10px] font-medium text-red-500 hover:bg-red-50">&times;</button>';
            thead += '</div></th>';
        });
        thead += '<th class="w-10 border border-line-200 bg-paper-100"></th></tr>';
        specModels.innerHTML = thead;

        // tbody
        let tbody = '';
        st.attrs.forEach(function(a, ai) {
            tbody += '<tr>';
            tbody += '<td class="border border-line-200 px-3 py-2"><div class="flex items-center gap-1">';
            tbody += '<input type="text" data-row="' + ai + '" value="' + esc(a) +
                '" placeholder="Nama Model" class="spec-attr-input w-full rounded border border-line-200 px-2 py-1 text-xs font-semibold text-graphite-900 focus:border-steel-700 focus:outline-none">';
            tbody += '<button type="button" data-row="' + ai +
                '" class="spec-remove-row shrink-0 rounded border border-red-200 px-1.5 text-[10px] font-medium text-red-500 hover:bg-red-50">&times;</button>';
            tbody += '</div></td>';
            st.models.forEach(function(m, mi) {
                var val = (st.values[m] && st.values[m][a]) || '';
                tbody +=
                    '<td class="border border-line-200 px-2 py-1"><input type="text" data-model="' +
                    mi + '" data-attr="' + ai + '" value="' + esc(val) +
                    '" placeholder="—" class="spec-cell-input w-full rounded border border-line-200 px-2 py-1 text-sm text-graphite-900 focus:border-steel-700 focus:outline-none"></td>';
            });
            tbody += '<td class="border border-line-200"></td></tr>';
        });
        specBody.innerHTML = tbody;
    }

    function esc(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    var specState = initSpecMatrix();
    specMatrixHtml();

    // Add model column
    document.getElementById('add-model').addEventListener('click', function() {
        promptModal('Nama model/varian (contoh: BY-800):', 'Ketik nama model...').then(function(name) {
            if (!name) return;
            if (specState.models.indexOf(name) !== -1) return;
            specState.models.push(name);
            specState.values[name] = {};
            specMatrixHtml();
        });
    });

    // Add attribute row
    document.getElementById('add-attr').addEventListener('click', function() {
        promptModal('Nama atribut (contoh: Daya, Berat, Kapasitas):', 'Ketik nama atribut...').then(function(name) {
            if (!name) return;
            if (specState.attrs.indexOf(name) !== -1) return;
            specState.attrs.push(name);
            specMatrixHtml();
        });
    });

    // Delegate: remove col / remove row / sync inputs
    document.getElementById('spec-matrix-wrap').addEventListener('input', function(e) {
        var t = e.target;
        if (t.classList.contains('spec-model-input')) {
            var mi = parseInt(t.dataset.col, 10);
            var oldName = specState.models[mi];
            var newName = t.value.trim();
            if (oldName === newName) return;
            if (specState.models.indexOf(newName) !== -1 && newName !== '') {
                t.value = oldName;
                return;
            }
            specState.models[mi] = newName;
            if (oldName && newName) {
                specState.values[newName] = specState.values[oldName];
                delete specState.values[oldName];
            } else if (!newName && oldName) {
                delete specState.values[oldName];
            }
        } else if (t.classList.contains('spec-attr-input')) {
            var ai = parseInt(t.dataset.row, 10);
            var oldA = specState.attrs[ai];
            var newA = t.value.trim();
            if (oldA === newA) return;
            if (specState.attrs.indexOf(newA) !== -1 && newA !== '') {
                t.value = oldA;
                return;
            }
            specState.attrs[ai] = newA;
            if (oldA && newA) {
                specState.models.forEach(function(m) {
                    if (specState.values[m] && specState.values[m][oldA] !== undefined) {
                        specState.values[m][newA] = specState.values[m][oldA];
                        delete specState.values[m][oldA];
                    }
                });
            } else if (!newA && oldA) {
                specState.models.forEach(function(m) {
                    if (specState.values[m]) delete specState.values[m][oldA];
                });
            }
        } else if (t.classList.contains('spec-cell-input')) {
            var mi2 = parseInt(t.dataset.model, 10);
            var ai2 = parseInt(t.dataset.attr, 10);
            var m = specState.models[mi2];
            var a = specState.attrs[ai2];
            if (m && a) {
                if (!specState.values[m]) specState.values[m] = {};
                specState.values[m][a] = t.value;
            }
        }
    });

    document.getElementById('spec-matrix-wrap').addEventListener('click', function(e) {
        if (e.target.classList.contains('spec-remove-col')) {
            var ci = parseInt(e.target.dataset.col, 10);
            var removed = specState.models.splice(ci, 1)[0];
            if (removed) delete specState.values[removed];
            specMatrixHtml();
        } else if (e.target.classList.contains('spec-remove-row')) {
            var ri = parseInt(e.target.dataset.row, 10);
            var removedA = specState.attrs.splice(ri, 1)[0];
            specState.models.forEach(function(m) {
                if (specState.values[m]) delete specState.values[m][removedA];
            });
            specMatrixHtml();
        }
    });

    // Serialize to hidden inputs before submit
    document.getElementById('product-form').addEventListener('submit', function() {
        document.querySelectorAll('.spec-hidden-input').forEach(function(el) {
            el.remove();
        });
        var idx = 0;
        var modelsToSerialize = specState.models.length ? specState.models : [''];
        modelsToSerialize.forEach(function(m) {
            specState.attrs.forEach(function(a) {
                var v = (specState.values[m] && specState.values[m][a]) || '';
                addHidden('specifications[' + idx + '][model_name]', m);
                addHidden('specifications[' + idx + '][spec_key]', a);
                addHidden('specifications[' + idx + '][spec_value]', v);
                idx++;
            });
        });
    });

    function addHidden(name, value) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = name;
        inp.value = value;
        inp.className = 'spec-hidden-input';
        document.getElementById('product-form').appendChild(inp);
    }

    // ─── Videos (unchanged) ───
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
        return Math.max(0, ...Array.from(document.querySelectorAll('[name^="videos["]')).map((el) => parseInt(el.name
            .match(/\[(\d+)\]/)[1], 10))) + 1;
    }

    document.getElementById('add-video').addEventListener('click', () => {
        videosContainer.insertAdjacentHTML('beforeend', videoRowHtml(nextIndex()));
    });

    videosContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-video')) e.target.closest('.video-row').remove();
    });
</script>
