<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollRequest;
use App\Models\Attendance;
use App\Models\Cashbook;
use App\Models\Payroll;
use App\Models\Worker;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->input('period', now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'));

        try {
            $periodStart = Carbon::parse($period)->startOfWeek(Carbon::MONDAY);
        } catch (\Throwable) {
            $period = now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
            $periodStart = now()->startOfWeek(Carbon::MONDAY);
        }

        $periodEnd = $periodStart->copy()->addDays(5);
        $currentMonday = now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');

        $payrolls = Payroll::with('worker', 'approver')
            ->where('period', $period)
            ->orderBy('status')
            ->orderBy('worker_id')
            ->get();

        $periods = Payroll::distinct()->pluck('period')
            ->merge(Attendance::query()->pluck('date')->map(fn ($date) => Carbon::parse($date)->startOfWeek(Carbon::MONDAY)->format('Y-m-d')))
            ->unique()
            ->sortDesc()
            ->values();

        $approvalCount = $payrolls->where('status', 'approved')->count();
        $approvedAmount = $payrolls->where('status', 'approved')->sum(fn ($p) => $p->net_salary);
        $currentWeekHasPayroll = Payroll::where('period', $currentMonday)->exists();

        return view('admin.payrolls.index', [
            'payrolls' => $payrolls,
            'period' => $period,
            'periodLabel' => $periodStart->translatedFormat('d M').' – '.$periodEnd->translatedFormat('d M Y'),
            'periods' => $periods,
            'approvalCount' => $approvalCount,
            'approvedAmount' => $approvedAmount,
            'workerCount' => Worker::whereNotNull('salary')->count(),
            'currentMonday' => $currentMonday,
            'currentWeekHasPayroll' => $currentWeekHasPayroll,
        ]);
    }

    public function generate(PayrollRequest $request): RedirectResponse
    {
        $period = $request->period;
        $start = Carbon::parse($period)->startOfWeek(Carbon::MONDAY)->toDateString();
        $end = Carbon::parse($period)->startOfWeek(Carbon::MONDAY)->addDays(5)->toDateString();

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

        $periodStart = Carbon::parse($period);
        $periodLabel = $periodStart->translatedFormat('d M').' – '.$periodStart->copy()->addDays(5)->translatedFormat('d M Y');
        $message = $created > 0
            ? "Penggajian periode $periodLabel dibuat untuk $created pekerja (draft)."
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

    public function slip(Payroll $payroll): View
    {
        $payroll->load(['worker', 'approver']);

        return view('admin.payrolls.slip', [
            'payroll' => $payroll,
        ]);
    }

    public function downloadSlip(Payroll $payroll)
    {
        $payroll->load(['worker', 'approver']);

        $pdf = Pdf::loadView('admin.payrolls._slip-print', ['payroll' => $payroll])
            ->setPaper('a4', 'portrait');

        $workerName = str_replace(' ', '-', strtolower($payroll->worker->name));

        return $pdf->stream("slip-gaji-{$workerName}-{$payroll->period}.pdf");
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
