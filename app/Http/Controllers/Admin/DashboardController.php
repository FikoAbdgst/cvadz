<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashbook;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $monthlySales = Transaction::whereDate('transaction_date', '>=', now()->startOfMonth()->toDateString())
            ->whereDate('transaction_date', '<=', now()->toDateString())
            ->where('status', 'lunas')
            ->sum('amount');
        $monthlyTransactions = Transaction::whereDate('transaction_date', '>=', now()->startOfMonth()->toDateString())
            ->whereDate('transaction_date', '<=', now()->toDateString())
            ->where('status', 'lunas')
            ->count();

        $totalSales = Transaction::where('status', 'lunas')->sum('amount');
        $criticalStock = Product::where('stock', '<=', Product::LOW_STOCK_THRESHOLD)->count();
        $cashBalance = Cashbook::where('type', 'pemasukan')->sum('amount')
            - Cashbook::where('type', 'pengeluaran')->sum('amount');
        $lastCashEntry = Cashbook::latest('transaction_date')->first();

        $recentOrders = Order::with(['customer', 'product', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalCustomers' => $totalCustomers,
            'totalOrders' => $totalOrders,
            'monthlySales' => $monthlySales,
            'monthlyTransactions' => $monthlyTransactions,
            'totalSales' => $totalSales,
            'criticalStock' => $criticalStock,
            'cashBalance' => $cashBalance,
            'lastCashEntry' => $lastCashEntry,
            'recentOrders' => $recentOrders,
            'pendingOrders' => $pendingOrders,
        ]);
    }
}
