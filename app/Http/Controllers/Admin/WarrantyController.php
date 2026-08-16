<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $query = Order::with(['customer', 'product', 'service'])
            ->whereNotNull('warranty_end_date');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($customer) use ($search) {
                    $customer->where('name', 'like', "%{$search}%");
                })->orWhere('id', $search);
            });
        }

        $orders = $query->orderByDesc('warranty_end_date')->paginate(15)->withQueryString();

        return view('admin.warranty.index', [
            'search' => $search,
            'orders' => $orders,
        ]);
    }
}
