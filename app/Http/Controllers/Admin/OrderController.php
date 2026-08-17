<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cashbook;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        ]);
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['admin_user_id'] = Auth::id();
        $validated['status'] = 'pending';

        if ($request->hasFile('payment_proof')) {
            $validated['payment_proof'] = $request->file('payment_proof')->store('payments', 'public');
        } else {
            unset($validated['payment_proof']);
        }

        $order = Order::create($validated);

        $this->syncPayment($order, $request);

        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan'])
            ->with('success', 'Pemesanan berhasil ditambahkan.');
    }

    public function edit(Order $pemesanan): View
    {
        $pemesanan->load('customer');

        return view('admin.orders.edit', [
            'order' => $pemesanan,
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function update(OrderRequest $request, Order $pemesanan): RedirectResponse
    {
        $oldProof = $pemesanan->payment_proof;
        $validated = $request->validated();

        $proofPath = $oldProof;
        if ($request->hasFile('payment_proof')) {
            if ($oldProof && Storage::disk('public')->exists($oldProof)) {
                Storage::disk('public')->delete($oldProof);
            }
            $proofPath = $request->file('payment_proof')->store('payments', 'public');
        }

        $validated['payment_proof'] = $proofPath;

        if (in_array($validated['payment_status'] ?? 'belum', ['belum'], true)) {
            $validated['payment_amount'] = null;
            $validated['payment_type'] = null;
            $validated['payment_date'] = null;
            if ($proofPath && Storage::disk('public')->exists($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }
            $validated['payment_proof'] = null;
        }

        $pemesanan->update($validated);
        $this->syncPayment($pemesanan, $request);

        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan'])
            ->with('success', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(Order $pemesanan): RedirectResponse
    {
        if ($pemesanan->payment_proof && Storage::disk('public')->exists($pemesanan->payment_proof)) {
            Storage::disk('public')->delete($pemesanan->payment_proof);
        }

        Cashbook::where('transaction_id', $pemesanan->transactions()->pluck('id'))->delete();
        $pemesanan->transactions()->delete();
        $pemesanan->delete();

        return redirect()->route('admin.sales.index', ['tab' => 'pemesanan'])
            ->with('success', 'Pemesanan berhasil dihapus.');
    }

    private function syncPayment(Order $order, OrderRequest $request): void
    {
        $status = $request->input('payment_status', 'belum');

        if ($status !== 'belum' && filled($request->input('payment_amount'))) {
            $transaction = $order->transactions()->first();
            $attrs = [
                'amount' => $request->input('payment_amount'),
                'payment_type' => $request->input('payment_type', 'transfer'),
                'transaction_date' => $request->input('payment_date', now()->toDateString()),
                'status' => $status === 'lunas' ? TransactionStatus::Lunas : TransactionStatus::BelumLunas,
                'staff_user_id' => Auth::id(),
            ];

            if ($transaction) {
                $transaction->update($attrs);
            } else {
                $transaction = $order->transactions()->create($attrs);
            }

            $this->syncCashbook($order, $transaction, Auth::id());
        } else {
            $existingIds = $order->transactions()->pluck('id')->toArray();
            if ($existingIds) {
                Cashbook::whereIn('transaction_id', $existingIds)->delete();
                $order->transactions()->delete();
            }
        }
    }

    private function syncCashbook(Order $order, Transaction $transaction, int $userId): void
    {
        $description = 'Pembayaran #'.$order->id.' — '.($order->customer?->name ?? 'Pesanan').' ('.$transaction->status->label().')';

        $existing = Cashbook::where('transaction_id', $transaction->id)->first();

        if ($existing) {
            $existing->update([
                'amount' => $transaction->amount,
                'description' => $description,
                'transaction_date' => $transaction->transaction_date,
            ]);
        } else {
            Cashbook::create([
                'type' => 'pemasukan',
                'amount' => $transaction->amount,
                'description' => $description,
                'transaction_date' => $transaction->transaction_date,
                'user_id' => $userId,
                'transaction_id' => $transaction->id,
            ]);
        }
    }
}
