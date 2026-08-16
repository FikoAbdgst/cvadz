<?php

namespace App\Http\Controllers\Staff;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['customer', 'product', 'service']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->input('q'))) {
            $query->where(fn ($q) => $q
                ->whereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$search}%"))
                ->orWhere('id', $search));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('staff.orders.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
            'statusFilter' => $request->input('status'),
            'search' => trim((string) $request->input('q')),
        ]);
    }

    public function edit(Order $pemesanan): View
    {
        $pemesanan->load(['customer', 'product', 'service']);

        return view('staff.orders.edit', [
            'order' => $pemesanan,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function update(StaffOrderRequest $request, Order $pemesanan): RedirectResponse
    {
        $pemesanan->update($request->validated());

        return redirect()->route('staff.orders.edit', $pemesanan)
            ->with('success', 'Progress pemesanan berhasil diperbarui.');
    }
}
