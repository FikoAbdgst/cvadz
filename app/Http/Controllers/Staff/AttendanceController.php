<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Worker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : now();

        $attendances = Attendance::with('worker')
            ->whereDate('date', $date->toDateString())
            ->orderBy('check_in')
            ->get();

        $availableDates = Attendance::query()
            ->selectRaw('DISTINCT date')
            ->orderByDesc('date')
            ->limit(14)
            ->pluck('date');

        return view('staff.attendances.index', [
            'date' => $date,
            'attendances' => $attendances,
            'workers' => Worker::orderBy('name')->get(),
            'availableDates' => $availableDates,
            'presentCount' => $attendances->whereNotNull('check_in')->count(),
        ]);
    }

    public function store(AttendanceRequest $request): RedirectResponse
    {
        $exists = Attendance::where('worker_id', $request->worker_id)
            ->whereDate('date', $request->input('date'))
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['worker_id' => 'Absensi untuk pekerja dan tanggal ini sudah tercatat.'])
                ->withInput();
        }

        Attendance::create([
            'worker_id' => $request->worker_id,
            'date' => $request->input('date'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->filled('check_out') ? $request->input('check_out') : null,
        ]);

        return redirect()->route('staff.attendances.index', ['date' => $request->input('date')])
            ->with('success', 'Absensi berhasil dicatat.');
    }
}
