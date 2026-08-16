<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffCashbookRequest;
use App\Models\Cashbook;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashbookController extends Controller
{
    public function create(): View
    {
        $recent = Cashbook::with('user')
            ->where('type', 'pengeluaran')
            ->latest('transaction_date')
            ->latest('id')
            ->take(10)
            ->get();

        return view('staff.cashbooks.create', ['recent' => $recent]);
    }

    public function store(StaffCashbookRequest $request): RedirectResponse
    {
        Cashbook::create($request->validated() + [
            'type' => 'pengeluaran',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('staff.cashbooks.create')
            ->with('success', 'Pengeluaran operasional berhasil dicatat.');
    }
}
