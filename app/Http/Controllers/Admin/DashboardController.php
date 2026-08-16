<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $recentOrders = Order::with(['customer', 'product'])
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
            'recentOrders' => $recentOrders,
            'pendingOrders' => $pendingOrders,
        ]);
    }
}
