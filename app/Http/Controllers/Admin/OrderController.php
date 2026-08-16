<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan']);
    }

    public function create(): View
    {
        return view('admin.orders.create', [
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        Order::create($request->validated() + ['admin_user_id' => auth()->id()]);

        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan'])
            ->with('success', 'Pemesanan berhasil ditambahkan.');
    }

    public function edit(Order $pemesanan): View
    {
        return view('admin.orders.edit', [
            'order' => $pemesanan,
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function update(OrderRequest $request, Order $pemesanan): RedirectResponse
    {
        $pemesanan->update($request->validated());

        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan'])
            ->with('success', 'Status pemesanan berhasil diperbarui.');
    }

    public function destroy(Order $pemesanan): RedirectResponse
    {
        $pemesanan->delete();

        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan'])
            ->with('success', 'Pemesanan berhasil dihapus.');
    }
}
