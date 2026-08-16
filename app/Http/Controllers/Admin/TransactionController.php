<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function create(Request $request): View
    {
        return view('admin.transactions.create', [
            'orders' => Order::with('customer', 'product')->orderByDesc('created_at')->get(),
            'statuses' => TransactionStatus::cases(),
            'preselectedOrder' => $request->integer('order_id'),
        ]);
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        Transaction::create($request->validated());

        return redirect()->route('admin.sales.index', ['tab' => 'transaksi'])
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaksi): View
    {
        return view('admin.transactions.edit', [
            'transaction' => $transaksi,
            'orders' => Order::with('customer', 'product')->orderByDesc('created_at')->get(),
            'statuses' => TransactionStatus::cases(),
        ]);
    }

    public function update(TransactionRequest $request, Transaction $transaksi): RedirectResponse
    {
        $transaksi->update($request->validated());

        return redirect()->route('admin.sales.index', ['tab' => 'transaksi'])
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaksi): RedirectResponse
    {
        $transaksi->delete();

        return redirect()->route('admin.sales.index', ['tab' => 'transaksi'])
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
