<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->input('from') ? Carbon::parse($request->input('from')) : now()->startOfMonth();
        $to = $request->input('to') ? Carbon::parse($request->input('to')) : now();

        $query = Transaction::query()
            ->whereDate('transaction_date', '>=', $from->toDateString())
            ->whereDate('transaction_date', '<=', $to->toDateString());

        $totalRevenue = (clone $query)->where('status', 'lunas')->sum('amount');
        $totalTransactions = (clone $query)->where('status', 'lunas')->count();
        $unpaidAmount = (clone $query)->where('status', 'belum_lunas')->sum('amount');

        $daily = (clone $query)
            ->selectRaw('transaction_date, COUNT(*) as total_count, SUM(CASE WHEN status = "lunas" THEN amount ELSE 0 END) as total_amount')
            ->groupBy('transaction_date')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $byProduct = (clone $query)
            ->with('order.product')
            ->get()
            ->filter(fn (Transaction $transaction) => $transaction->order?->product)
            ->groupBy('order.product.id')
            ->map(function ($transactions) {
                return [
                    'product' => $transactions->first()->order->product,
                    'quantity' => $transactions->sum(fn ($t) => $t->order->quantity),
                    'total' => $transactions->where('status', 'lunas')->sum('amount'),
                ];
            })
            ->values();

        return view('admin.reports.index', [
            'from' => $from,
            'to' => $to,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'unpaidAmount' => $unpaidAmount,
            'daily' => $daily,
            'byProduct' => $byProduct,
        ]);
    }
}
