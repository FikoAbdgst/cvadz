@extends('layouts.admin')

@section('title', 'Tambah Pemesanan — CV Adzra Engineering')
@section('page', 'Tambah Pemesanan')

@section('content')
    <div class="mx-auto max-w-2xl rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="customer_id" class="block text-sm font-medium text-graphite-900">Pelanggan</label>
                <select id="customer_id" name="customer_id" required
                        class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    <option value="">Pilih pelanggan...</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-graphite-500">Belum ada pelanggan? <a href="{{ route('admin.customers.create') }}" class="text-steel-700 hover:underline">Tambahkan dulu</a></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-graphite-900">Produk / Layanan</label>
                <p class="mt-1 text-xs text-graphite-500">Isi salah satu — pilih produk <span class="font-semibold">atau</span> layanan. Memilih yang satu otomatis mengosongkan yang lain.</p>

                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="product_id" class="text-xs font-medium text-graphite-500">Produk</label>
                        <select id="product_id" name="product_id"
                                class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                            <option value="">Pilih produk...</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0 }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="service_id" class="text-xs font-medium text-graphite-500">Layanan</label>
                        <select id="service_id" name="service_id"
                                class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                            <option value="">Pilih layanan...</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price ?? 0 }}" @selected(old('service_id') == $service->id)>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @error('item')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-graphite-900">Jumlah</label>
                    <input type="number" min="1" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>

                <div>
                    <label for="total" class="block text-sm font-medium text-graphite-900">Total Harga (Rp) <span class="text-graphite-500">(opsional)</span></label>
                    <input type="text" inputmode="numeric" id="total" name="total" value="{{ old('total') }}" data-rupiah readonly
                           class="mt-1 block w-full rounded-lg border border-line-200 bg-gray-50 px-3 py-2.5 text-sm text-graphite-500" placeholder="Otomatis">
                </div>

                <div>
                    <label for="warranty_end_date" class="block text-sm font-medium text-graphite-900">Selesai Garansi <span class="text-graphite-500">(opsional)</span></label>
                    <input type="date" id="warranty_end_date" name="warranty_end_date" value="{{ old('warranty_end_date') }}"
                           class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                </div>
            </div>

            <div class="border-t border-line-200 pt-5">
                <h3 class="font-display text-sm font-bold text-steel-900">Pembayaran</h3>
                <p class="mt-1 text-xs text-graphite-500">Jika sudah tf dari pelanggan, isi status pembayaran dan upload bukti pembayaran dari WhatsApp.</p>

                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-graphite-900">Status Pembayaran</label>
                        <select id="payment_status" name="payment_status" required
                                class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                            <option value="belum" @selected(old('payment_status', 'belum') === 'belum')>Belum Bayar</option>
                            <option value="dp" @selected(old('payment_status') === 'dp')>DP</option>
                            <option value="lunas" @selected(old('payment_status') === 'lunas')>Lunas</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-graphite-900">Nominal Bayar (Rp) <span class="text-graphite-500">(wajib jika DP/Lunas)</span></label>
                        <input type="text" inputmode="numeric" id="payment_amount" name="payment_amount" value="{{ old('payment_amount') }}" data-rupiah
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                        @error('payment_amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-graphite-900">Metode <span class="text-graphite-500">(opsional)</span></label>
                        <select id="payment_type" name="payment_type"
                                class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                            <option value="transfer" @selected(old('payment_type', 'transfer') === 'transfer')>Transfer</option>
                            <option value="tunai" @selected(old('payment_type') === 'tunai')>Tunai</option>
                            <option value="lainnya" @selected(old('payment_type') === 'lainnya')>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-graphite-900">Tanggal Bayar <span class="text-graphite-500">(opsional)</span></label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}"
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="payment_proof" class="block text-sm font-medium text-graphite-900">Foto Bukti Pembayaran <span class="text-graphite-500">(opsional)</span></label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/jpeg,image/png,image/webp"
                               class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20 file:mr-3 file:rounded-lg file:border-0 file:bg-steel-700 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white file:hover:bg-steel-900">
                        <p class="mt-1 text-xs text-graphite-500">Upload screenshot bukti transfer dari WhatsApp. Maks 2MB (JPG/PNG/WebP).</p>
                        @error('payment_proof')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-graphite-900">Catatan <span class="text-graphite-500">(opsional)</span></label>
                <textarea id="notes" name="notes" rows="3"
                          class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan</button>
                <a href="{{ route('admin.sales.index', ['tab' => 'pemesanan']) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const productSelect = document.getElementById('product_id');
        const serviceSelect = document.getElementById('service_id');
        const quantityInput = document.getElementById('quantity');
        const totalInput = document.getElementById('total');
        const quantityWrap = quantityInput.closest('div');

        productSelect.addEventListener('change', () => {
            if (productSelect.value) serviceSelect.value = '';
            toggleQuantity();
            autoCalcTotal();
        });
        serviceSelect.addEventListener('change', () => {
            if (serviceSelect.value) productSelect.value = '';
            toggleQuantity();
            autoCalcTotal();
        });
        quantityInput.addEventListener('input', autoCalcTotal);

        function toggleQuantity() {
            if (serviceSelect.value) {
                quantityWrap.style.display = 'none';
                quantityInput.value = '';
                quantityInput.removeAttribute('required');
            } else {
                quantityWrap.style.display = '';
                quantityInput.setAttribute('required', '');
                if (!quantityInput.value) quantityInput.value = 1;
            }
        }

        function autoCalcTotal() {
            const hasProduct = !!productSelect.value;
            const selected = hasProduct ? productSelect.selectedOptions[0] : null;
            const price = parseFloat(selected?.dataset?.price || 0);
            const qty = parseInt(quantityInput.value, 10) || 0;

            if (hasProduct) {
                totalInput.readOnly = true;
                totalInput.classList.add('bg-gray-50', 'text-graphite-500');
                totalInput.classList.remove('focus:border-steel-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-steel-700/20');
                totalInput.placeholder = 'Otomatis';
                totalInput.value = (price > 0 && qty > 0) ? (price * qty).toLocaleString('id-ID') : '';
            } else {
                totalInput.readOnly = false;
                totalInput.classList.remove('bg-gray-50', 'text-graphite-500');
                totalInput.classList.add('focus:border-steel-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-steel-700/20');
                totalInput.placeholder = 'Isi manual jika layanan';
                if (serviceSelect.value) totalInput.value = '';
            }
        }

        toggleQuantity();
    </script>
@endsection
