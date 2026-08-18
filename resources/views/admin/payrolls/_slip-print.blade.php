<div class="mx-auto max-w-2xl border border-line-200 bg-white shadow-sm print:max-w-none print:border-0 print:shadow-none">
    <div class="border-b border-dashed border-line-200 px-8 py-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                <div>
                    <p class="font-display text-lg font-bold leading-tight text-steel-900">CV Adzra Engineering</p>
                    <p class="text-xs text-graphite-500">Fabrikasi & Jasa Mesin Industri<br>Bandung, Indonesia</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-display text-sm font-bold uppercase tracking-widest text-steel-700">Slip Gaji</p>
                <p class="mt-1 font-mono text-sm text-graphite-900">Periode {{ \Carbon\Carbon::parse($payroll->period)->translatedFormat('d M').' – '.\Carbon\Carbon::parse($payroll->period)->addDays(5)->translatedFormat('d M Y') }}</p>
                <p class="text-xs text-graphite-500">{{ now()->translatedFormat('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 px-8 py-6 sm:grid-cols-2">
        <div>
            <p class="label-mono text-graphite-500">Data Pekerja</p>
            <p class="mt-1 font-medium text-graphite-900">{{ $payroll->worker->name }}</p>
            <p class="mt-1 text-sm text-graphite-500">{{ $payroll->worker->position }}</p>
        </div>
        <div class="sm:text-right">
            <p class="label-mono text-graphite-500">Periode</p>
            <p class="mt-1 font-medium text-graphite-900">{{ \Carbon\Carbon::parse($payroll->period)->translatedFormat('d M').' – '.\Carbon\Carbon::parse($payroll->period)->addDays(5)->translatedFormat('d M Y') }}</p>
            <p class="mt-2 label-mono text-graphite-500">Hari Kerja</p>
            <p class="mt-1 font-mono text-sm text-steel-700">{{ $payroll->total_days }} hari</p>
        </div>
    </div>

    <div class="px-8 pb-6">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b-2 border-graphite-900 text-xs uppercase tracking-wider text-graphite-500">
                    <th class="py-2">Keterangan</th>
                    <th class="py-2 text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-line-200">
                    <td class="py-3 text-graphite-900">Gaji Pokok (Rp {{ number_format((float) $payroll->worker->salary, 0, ',', '.') }} × {{ $payroll->total_days }} hari)</td>
                    <td class="py-3 text-right font-mono text-graphite-900">{{ number_format((float) $payroll->salary_amount, 0, ',', '.') }}</td>
                </tr>
                @if ((float) $payroll->bonus > 0)
                    <tr class="border-b border-line-200">
                        <td class="py-3 text-graphite-900">Bonus</td>
                        <td class="py-3 text-right font-mono text-green-600">+{{ number_format((float) $payroll->bonus, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ((float) $payroll->lemburan > 0)
                    <tr class="border-b border-line-200">
                        <td class="py-3 text-graphite-900">Lemburan</td>
                        <td class="py-3 text-right font-mono text-green-600">+{{ number_format((float) $payroll->lemburan, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ((float) $payroll->uang_luar_kota > 0)
                    <tr class="border-b border-line-200">
                        <td class="py-3 text-graphite-900">Uang Luar Kota</td>
                        <td class="py-3 text-right font-mono text-green-600">+{{ number_format((float) $payroll->uang_luar_kota, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ((float) $payroll->kasbon > 0)
                    <tr class="border-b border-line-200">
                        <td class="py-3 text-graphite-900">Kasbon</td>
                        <td class="py-3 text-right font-mono text-red-600">-{{ number_format((float) $payroll->kasbon, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="px-8 pb-8">
        <div class="ml-auto max-w-xs space-y-2">
            <div class="flex items-center justify-between border-t-2 border-graphite-900 pt-3">
                <span class="label-mono text-graphite-900">Gaji Bersih</span>
                <span class="font-mono text-xl font-bold text-steel-900">Rp {{ number_format((float) $payroll->net_salary, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-10 flex items-end justify-between">
            <p class="text-sm text-graphite-500">Dokumen ini dicetak otomatis oleh sistem.<br>Untuk pertanyaan: wa.me/{{ config('services.whatsapp.number') }}</p>
            <div class="text-center">
                <p class="text-xs text-graphite-500">Bandung, {{ now()->translatedFormat('d M Y') }}</p>
                <div class="mt-14">
                    <p class="font-medium text-graphite-900">{{ $payroll->approver?->name ?? '—' }}</p>
                    <p class="text-xs text-graphite-500">Pengelola</p>
                </div>
            </div>
        </div>
    </div>
</div>
