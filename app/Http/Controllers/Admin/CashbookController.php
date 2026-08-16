<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashbook;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CashbookController extends Controller
{
    public function index(Request $request): View
    {
        $type = in_array($request->input('type'), ['pemasukan', 'pengeluaran'], true) ? $request->input('type') : null;
        $from = $request->input('from') ? Carbon::parse($request->input('from')) : now()->startOfMonth();
        $to = $request->input('to') ? Carbon::parse($request->input('to')) : now();

        $query = Cashbook::with('user')
            ->whereDate('transaction_date', '>=', $from->toDateString())
            ->whereDate('transaction_date', '<=', $to->toDateString())
            ->when($type, fn ($q) => $q->where('type', $type));

        $income = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $expense = (clone $query)->where('type', 'pengeluaran')->sum('amount');

        $entries = $query->orderByDesc('transaction_date')->latest('id')->paginate(15)->withQueryString();

        return view('admin.cashbooks.index', [
            'type' => $type,
            'from' => $from,
            'to' => $to,
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'entries' => $entries,
        ]);
    }
}
