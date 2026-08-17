<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollRequest;
use App\Models\Attendance;
use App\Models\Cashbook;
use App\Models\Payroll;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->input('period', now()->format('Y-m'));

        try {
            $periodStart = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        } catch (\Throwable) {
            $period = now()->format('Y-m');
            $periodStart = now()->startOfMonth();
        }

        $payrolls = Payroll::with('worker', 'approver')
            ->where('period', $period)
            ->orderBy('status')
            ->orderBy('worker_id')
            ->get();

        $periods = Payroll::distinct()->pluck('period')
            ->merge(Attendance::query()->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('Y-m')))
            ->unique()
            ->sortDesc()
            ->values();

        $approvalCount = $payrolls->where('status', 'approved')->count();
        $approvedAmount = $payrolls->where('status', 'approved')->sum(fn ($p) => $p->net_salary);

        return view('admin.payrolls.index', [
            'payrolls' => $payrolls,
            'period' => $period,
            'periodLabel' => $periodStart->translatedFormat('F Y'),
            'periods' => $periods,
            'approvalCount' => $approvalCount,
            'approvedAmount' => $approvedAmount,
            'workerCount' => Worker::whereNotNull('salary')->count(),
        ]);
    }

    public function generate(PayrollRequest $request): RedirectResponse
    {
        $period = $request->period;
        $start = Carbon::createFromFormat('Y-m', $period)->startOfMonth()->toDateString();
        $end = Carbon::createFromFormat('Y-m', $period)->endOfMonth()->toDateString();

        $workers = Worker::withCount([
            'attendances as days_present' => function ($query) use ($start, $end) {
                $query->whereNotNull('check_in')
                    ->whereDate('date', '>=', $start)
                    ->whereDate('date', '<=', $end);
            },
        ])->whereNotNull('salary')->get();

        $created = 0;

        DB::transaction(function () use ($workers, $period, &$created) {
            foreach ($workers as $worker) {
                $exists = Payroll::where('worker_id', $worker->id)->where('period', $period)->exists();

                if ($exists) {
                    continue;
                }

                Payroll::create([
                    'worker_id' => $worker->id,
                    'period' => $period,
                    'total_days' => $worker->days_present,
                    'salary_amount' => (float) $worker->salary * $worker->days_present,
                    'status' => 'draft',
                ]);

                $created++;
            }
        });

        $message = $created > 0
            ? "Penggajian periode $period dibuat untuk $created pekerja (draft)."
            : 'Tidak ada data baru — semua pekerja sudah dibuatkan atau belum punya upah harian.';

        return redirect()->route('admin.payrolls.index', ['period' => $period])
            ->with('success', $message);
    }

    public function edit(Payroll $payroll): View
    {
        $payroll->load('worker');

        return view('admin.payrolls.edit', [
            'payroll' => $payroll,
        ]);
    }

    public function update(PayrollRequest $request, Payroll $payroll): RedirectResponse
    {
        $payroll->update([
            'bonus' => $request->filled('bonus') ? $request->bonus : 0,
            'lemburan' => $request->filled('lemburan') ? $request->lemburan : 0,
            'uang_luar_kota' => $request->filled('uang_luar_kota') ? $request->uang_luar_kota : 0,
            'kasbon' => $request->filled('kasbon') ? $request->kasbon : 0,
        ]);

        return redirect()->route('admin.payrolls.index', ['period' => $payroll->period])
            ->with('success', 'Komponen gaji berhasil diperbarui.');
    }

    public function approve(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'Penggajian sudah di-approve sebelumnya.');
        }

        DB::transaction(function () use ($payroll) {
            $payroll->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            Cashbook::create([
                'type' => 'pengeluaran',
                'amount' => $payroll->net_salary,
                'description' => 'Pembayaran gaji '.$payroll->worker->name.' — periode '.$payroll->period,
                'transaction_date' => now()->toDateString(),
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->route('admin.payrolls.index', ['period' => $payroll->period])
            ->with('success', 'Penggajian di-approve dan dicatat sebagai pengeluaran kas.');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status === 'approved') {
            return back()->with('error', 'Penggajian yang sudah di-approve tidak dapat dihapus.');
        }

        $period = $payroll->period;
        $payroll->delete();

        return redirect()->route('admin.payrolls.index', ['period' => $period])
            ->with('success', 'Draft penggajian dihapus.');
    }
}
