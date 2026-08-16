<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), ['pelanggan', 'pemesanan', 'transaksi'], true)
            ? $request->query('tab')
            : 'pelanggan';

        $counts = [
            'customers' => Customer::count(),
            'orders' => Order::count(),
            'transactions' => Transaction::count(),
        ];

        $search = $request->query('q');
        $status = $request->query('status');
        $customerId = $request->integer('customer');
        $orderId = $request->integer('order');

        $customers = $orders = $transactions = null;
        $activeCustomer = $activeOrder = null;

        if ($tab === 'pelanggan') {
            $query = Customer::query();

            if ($search) {
                $query->where(fn ($q) => $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            }

            $customers = $query->withCount('orders')->orderBy('name')->paginate(15)->withQueryString();
        } elseif ($tab === 'pemesanan') {
            $query = Order::with(['customer', 'product'])
                ->withCount('transactions')
                ->withSum('transactions', 'amount');

            if ($customerId) {
                $query->where('customer_id', $customerId);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $orders = $query->latest()->paginate(15)->withQueryString();
            $activeCustomer = $customerId ? Customer::find($customerId) : null;
        } else {
            $query = Transaction::with('order.customer');

            if ($orderId) {
                $query->where('order_id', $orderId);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $transactions = $query->latest('transaction_date')->paginate(15)->withQueryString();
            $activeOrder = $orderId ? Order::with('customer', 'product')->find($orderId) : null;
        }

        return view('admin.sales.index', [
            'tab' => $tab,
            'counts' => $counts,
            'search' => $search,
            'status' => $status,
            'customerId' => $customerId,
            'orderId' => $orderId,
            'customers' => $customers,
            'orders' => $orders,
            'transactions' => $transactions,
            'activeCustomer' => $activeCustomer,
            'activeOrder' => $activeOrder,
            'statuses' => OrderStatus::cases(),
            'transactionStatuses' => TransactionStatus::cases(),
        ]);
    }
}
