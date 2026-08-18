@extends('layouts.admin')

@section('title', 'Edit Komponen Gaji — CV Adzra Engineering')
@section('page', 'Edit Komponen Gaji')

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('admin.payrolls.index', ['period' => $payroll->period]) }}" class="text-sm text-steel-700 hover:underline">&larr; Kembali ke Penggajian</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <h2 class="font-display text-lg font-bold text-graphite-900">Data Pekerja</h2>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-graphite-500">Nama</span>
                    <span class="font-medium text-graphite-900">{{ $payroll->worker->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-graphite-500">Posisi</span>
                    <span class="text-graphite-900">{{ $payroll->worker->position }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-graphite-500">Periode</span>
                    <span class="text-graphite-900">{{ \Carbon\Carbon::parse($payroll->period)->translatedFormat('d M').' – '.\Carbon\Carbon::parse($payroll->period)->addDays(5)->translatedFormat('d M Y') }}</span>
                </div>
                <div class="border-t border-line-200 pt-3 flex justify-between">
                    <span class="text-graphite-500">Hari Kerja</span>
                    <span class="text-graphite-900">{{ $payroll->total_days }} hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-graphite-500">Upah Harian</span>
                    <span class="text-graphite-900">Rp {{ number_format((float) $payroll->worker->salary, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold">
                    <span class="text-graphite-500">Gaji Pokok</span>
                    <span class="text-steel-700">Rp {{ number_format((float) $payroll->salary_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="plate rounded bg-white p-6">
            <span class="plate-corner-bl"></span>
            <span class="plate-corner-br"></span>
            <h2 class="font-display text-lg font-bold text-graphite-900">Komponen Gaji</h2>
            <p class="mt-1 text-xs text-graphite-500">Isi kolom tambahan/perubahan. Kosongkan atau isi 0 jika tidak ada.</p>

            <form method="POST" action="{{ route('admin.payrolls.update', $payroll) }}">
                @csrf
                @method('PUT')

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="bonus" class="block text-sm font-medium text-graphite-900">Bonus (Penambah)</label>
                        <input type="text" inputmode="numeric" id="bonus" name="bonus"
                               value="{{ old('bonus', $payroll->bonus ? number_format((float) $payroll->bonus, 0, ',', '.') : '') }}"
                               data-rupiah
                               class="mt-1 block w-full rounded border border-line-200 px-3 py-2.5 text-sm text-graphite-900 focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20"
                               placeholder="0">
                    </div>

                    <div>
                        <label for="lemburan" class="block text-sm font-medium text-graphite-900">Lemburan (Penambah)</label>
                        <input type="text" inputmode="numeric" id="lemburan" name="lemburan"
                               value="{{ old('lemburan', $payroll->lemburan ? number_format((float) $payroll->lemburan, 0, ',', '.') : '') }}"
                               data-rupiah
                               class="mt-1 block w-full rounded border border-line-200 px-3 py-2.5 text-sm text-graphite-900 focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20"
                               placeholder="0">
                    </div>

                    <div>
                        <label for="uang_luar_kota" class="block text-sm font-medium text-graphite-900">Uang Luar Kota (Penambah)</label>
                        <input type="text" inputmode="numeric" id="uang_luar_kota" name="uang_luar_kota"
                               value="{{ old('uang_luar_kota', $payroll->uang_luar_kota ? number_format((float) $payroll->uang_luar_kota, 0, ',', '.') : '') }}"
                               data-rupiah
                               class="mt-1 block w-full rounded border border-line-200 px-3 py-2.5 text-sm text-graphite-900 focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20"
                               placeholder="0">
                    </div>

                    <div>
                        <label for="kasbon" class="block text-sm font-medium text-graphite-900">Kasbon (Pengurang)</label>
                        <input type="text" inputmode="numeric" id="kasbon" name="kasbon"
                               value="{{ old('kasbon', $payroll->kasbon ? number_format((float) $payroll->kasbon, 0, ',', '.') : '') }}"
                               data-rupiah
                               class="mt-1 block w-full rounded border border-line-200 px-3 py-2.5 text-sm text-graphite-900 focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20"
                               placeholder="0">
                    </div>
                </div>

                <div class="mt-6 border-t border-line-200 pt-4">
                    <div class="flex items-center justify-between text-sm font-semibold">
                        <span class="text-graphite-500">Gaji Bersih (Net)</span>
                        <span class="font-mono text-steel-700" id="net-preview">Rp {{ number_format((float) $payroll->net_salary, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.payrolls.index', ['period' => $payroll->period]) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            var fields = ['bonus', 'lemburan', 'uang_luar_kota', 'kasbon'];
            var base = {{ (float) $payroll->salary_amount }};
            var preview = document.getElementById('net-preview');

            function parseRupiah(str) {
                return parseFloat(str.replace(/\D/g, '')) || 0;
            }

            function updateNet() {
                var total = base;
                fields.forEach(function (f) {
                    var v = parseRupiah(document.getElementById(f).value);
                    if (f === 'kasbon') { total -= v; } else { total += v; }
                });
                preview.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            fields.forEach(function (f) {
                document.getElementById(f).addEventListener('input', updateNet);
            });
        })();
    </script>
@endsection
