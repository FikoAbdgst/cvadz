<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->input('month', now()->format('Y-m'));

        try {
            $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        } catch (\Throwable) {
            $period = now()->startOfMonth();
            $month = $period->format('Y-m');
        }

        $attendances = Attendance::with('worker')
            ->whereDate('date', '>=', $period->startOfMonth()->toDateString())
            ->whereDate('date', '<=', $period->copy()->endOfMonth()->toDateString())
            ->orderBy('date')
            ->orderBy('worker_id')
            ->get()
            ->groupBy(fn (Attendance $attendance) => $attendance->date->format('d M Y'));

        $summary = $attendances->flatten()
            ->groupBy(fn (Attendance $attendance) => $attendance->worker->name)
            ->map(function ($rows) {
                $present = $rows->whereNotNull('check_in')->count();

                return [
                    'worker' => $rows->first()->worker,
                    'present' => $present,
                    'incomplete' => $rows->whereNull('check_out')->count(),
                ];
            })
            ->sortBy('worker.name');

        $availableMonths = Attendance::query()
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('admin.attendances.index', [
            'attendances' => $attendances,
            'summary' => $summary,
            'month' => $month,
            'availableMonths' => $availableMonths,
        ]);
    }
}
