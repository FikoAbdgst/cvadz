<?php

namespace App\Http\Controllers\Staff;

use App\Enums\OrderStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffTransactionRequest;
use App\Models\Cashbook;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with('order.customer', 'order.product', 'order.service', 'staffUser');

        if ($orderId = $request->integer('order_id')) {
            $query->where('order_id', $orderId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15)->withQueryString();

        return view('staff.transactions.index', [
            'transactions' => $transactions,
            'statuses' => TransactionStatus::cases(),
            'orderId' => $orderId,
            'statusFilter' => $request->input('status'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('staff.transactions.create', [
            'orders' => Order::with(['customer', 'product', 'service'])
                ->orderByDesc('created_at')
                ->get(),
            'statuses' => TransactionStatus::cases(),
            'preselectedOrder' => $request->integer('order_id'),
        ]);
    }

    public function store(StaffTransactionRequest $request): RedirectResponse
    {
        $transaction = Transaction::create($request->validated() + ['staff_user_id' => auth()->id()]);

        $this->completeOrderIfLunas($transaction, $request->status);
        $this->recordPaymentCashbook($transaction);

        return redirect()->route('staff.transactions.invoice', $transaction)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function edit(Transaction $transaksi): View
    {
        $transaksi->load(['order.customer', 'order.product', 'order.service']);

        return view('staff.transactions.edit', [
            'transaction' => $transaksi,
            'statuses' => TransactionStatus::cases(),
        ]);
    }

    public function update(StaffTransactionRequest $request, Transaction $transaksi): RedirectResponse
    {
        $transaksi->update($request->validated());

        $this->completeOrderIfLunas($transaksi, $request->status);
        $this->syncPaymentCashbook($transaksi);

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaksi #'.$transaksi->id.' berhasil diperbarui.');
    }

    public function destroy(Transaction $transaksi): RedirectResponse
    {
        Cashbook::where('transaction_id', $transaksi->id)->delete();
        $transaksi->delete();

        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function invoice(Transaction $transaksi): View
    {
        $transaksi->load(['order.customer', 'order.product', 'order.service', 'staffUser']);

        return view('staff.transactions.invoice', ['transaction' => $transaksi]);
    }

    /**
     * Mark the order as selesai when a full payment is recorded.
     */
    private function completeOrderIfLunas(Transaction $transaction, string $status): void
    {
        $order = $transaction->order;

        if ($status === TransactionStatus::Lunas->value && $order && $order->status !== OrderStatus::Batal) {
            $order->update(['status' => OrderStatus::Selesai]);
        }
    }

    /**
     * Insert the matching pemasukan row for a new payment.
     */
    private function recordPaymentCashbook(Transaction $transaction): void
    {
        Cashbook::create([
            'type' => 'pemasukan',
            'amount' => $transaction->amount,
            'description' => $this->paymentDescription($transaction),
            'transaction_date' => $transaction->transaction_date,
            'user_id' => auth()->id(),
            'transaction_id' => $transaction->id,
        ]);
    }

    /**
     * Keep the linked pemasukan row in sync when a payment is corrected.
     */
    private function syncPaymentCashbook(Transaction $transaction): void
    {
        Cashbook::where('transaction_id', $transaction->id)->update([
            'amount' => $transaction->amount,
            'description' => $this->paymentDescription($transaction),
            'transaction_date' => $transaction->transaction_date,
        ]);
    }

    private function paymentDescription(Transaction $transaction): string
    {
        $order = $transaction->order;

        return 'Pembayaran #'.$order?->id.' — '.($order?->customer?->name ?? 'Pesanan').' ('.$transaction->status->label().')';
    }
}
