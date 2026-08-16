<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pendingOrders = Order::with(['customer', 'product', 'service'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $inProgressOrders = Order::where('status', 'diproses')->count();
        $todayPresent = Attendance::with('worker')
            ->whereDate('date', now()->toDateString())
            ->get();
        $criticalStock = Product::where('stock', '<=', Product::LOW_STOCK_THRESHOLD)->count();
        $recentTransactions = Transaction::with('order.customer')
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('staff.dashboard.index', [
            'pendingOrders' => $pendingOrders,
            'pendingCount' => $pendingOrders->count(),
            'inProgressCount' => $inProgressOrders,
            'todayPresent' => $todayPresent,
            'presentCount' => $todayPresent->count(),
            'criticalStock' => $criticalStock,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
