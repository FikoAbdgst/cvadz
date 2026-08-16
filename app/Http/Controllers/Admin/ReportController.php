<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashbook;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), ['penjualan', 'stok', 'kas', 'penggajian'], true)
            ? $request->query('tab')
            : 'penjualan';

        $from = $request->input('from') ? Carbon::parse($request->input('from')) : now()->startOfMonth();
        $to = $request->input('to') ? Carbon::parse($request->input('to')) : now();

        $data = match ($tab) {
            'stok' => $this->stockReport(),
            'kas' => $this->cashReport($from, $to),
            'penggajian' => $this->payrollReport(),
            default => $this->salesReport($from, $to),
        };

        return view('admin.reports.index', [
            'tab' => $tab,
            'from' => $from,
            'to' => $to,
        ] + $data);
    }

    private function salesReport(Carbon $from, Carbon $to): array
    {
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

        $byItem = (clone $query)
            ->with('order.product', 'order.service')
            ->get()
            ->filter(fn (Transaction $transaction) => $transaction->order)
            ->groupBy(fn (Transaction $transaction) => $transaction->order->service_id ? "service:{$transaction->order->service_id}" : "product:{$transaction->order->product_id}")
            ->map(function ($transactions) {
                $order = $transactions->first()->order;

                return [
                    'label' => $order->itemLabel(),
                    'is_service' => $order->service_id !== null,
                    'quantity' => $transactions->sum(fn ($t) => $t->order->quantity),
                    'total' => $transactions->where('status', 'lunas')->sum('amount'),
                ];
            })
            ->values();

        return [
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'unpaidAmount' => $unpaidAmount,
            'daily' => $daily,
            'byItem' => $byItem,
        ];
    }

    private function stockReport(): array
    {
        $products = Product::with('category')->orderBy('name')->get();

        return [
            'totalStock' => $products->sum('stock'),
            'criticalStock' => $products->filter(fn (Product $product) => $product->isLowStock())->count(),
            'stockValue' => $products->sum(fn (Product $product) => $product->price * $product->stock),
            'stockItems' => $products,
        ];
    }

    private function cashReport(Carbon $from, Carbon $to): array
    {
        $query = Cashbook::with('user')
            ->whereDate('transaction_date', '>=', $from->toDateString())
            ->whereDate('transaction_date', '<=', $to->toDateString());

        $income = (clone $query)->where('type', 'pemasukan')->sum('amount');
        $expense = (clone $query)->where('type', 'pengeluaran')->sum('amount');

        return [
            'cashIncome' => $income,
            'cashExpense' => $expense,
            'cashBalance' => $income - $expense,
            'cashEntries' => $query->orderByDesc('transaction_date')->latest('id')->get(),
        ];
    }

    private function payrollReport(): array
    {
        $payrolls = Payroll::with('worker')->orderByDesc('period')->get();
        $approved = $payrolls->where('status', 'approved');

        return [
            'payrollTotal' => $payrolls->count(),
            'payrollApprovedTotal' => $approved->count(),
            'payrollApprovedAmount' => $approved->sum('salary_amount'),
            'payrollsByPeriod' => $payrolls->groupBy('period'),
        ];
    }
}
