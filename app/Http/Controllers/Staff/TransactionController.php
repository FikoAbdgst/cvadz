<?php

namespace App\Http\Controllers\Staff;

use App\Enums\PaymentStatus;
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
        $tab = $request->query('tab') === 'transaksi' ? 'transaksi' : 'pesanan';

        $paidOrders = Order::with(['customer', 'product', 'service', 'transactions'])
            ->whereIn('payment_status', [PaymentStatus::DP, PaymentStatus::Lunas])
            ->where('status', '!=', 'batal')
            ->latest()
            ->get();

        $query = Transaction::with('order.customer', 'order.product', 'order.service', 'staffUser');

        if ($orderId = $request->integer('order_id')) {
            $query->where('order_id', $orderId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15)->withQueryString();

        return view('staff.transactions.index', [
            'tab' => $tab,
            'paidOrders' => $paidOrders,
            'transactions' => $transactions,
            'statuses' => TransactionStatus::cases(),
            'orderId' => $orderId,
            'statusFilter' => $request->input('status'),
        ]);
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
        $this->syncPaymentCashbook($transaksi);
        $this->syncOrderFromTransaction($transaksi);

        return redirect()->route('staff.transactions.index', ['tab' => 'transaksi'])
            ->with('success', 'Transaksi #'.$transaksi->id.' berhasil diperbarui.');
    }

    public function destroy(Transaction $transaksi): RedirectResponse
    {
        Cashbook::where('transaction_id', $transaksi->id)->delete();
        $order = $transaksi->order;
        $transaksi->delete();

        if ($order) {
            $order->update([
                'payment_status' => PaymentStatus::BelumBayar,
                'payment_amount' => null,
                'payment_type' => null,
                'payment_date' => null,
            ]);
        }

        return redirect()->route('staff.transactions.index', ['tab' => 'transaksi'])
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function verify(Transaction $transaksi): RedirectResponse
    {
        $transaksi->update(['status' => TransactionStatus::Lunas]);
        $this->syncPaymentCashbook($transaksi);
        $this->syncOrderFromTransaction($transaksi);

        return redirect()->route('staff.transactions.index', ['tab' => 'pesanan'])
            ->with('success', 'Pembayaran #'.$transaksi->order_id.' terverifikasi sebagai Lunas.');
    }

    public function invoice(Transaction $transaksi): View
    {
        $transaksi->load(['order.customer', 'order.product', 'order.service', 'staffUser']);

        return view('staff.transactions.invoice', ['transaction' => $transaksi]);
    }

    private function syncPaymentCashbook(Transaction $transaction): void
    {
        $order = $transaction->order;
        $description = 'Pembayaran #'.$order?->id.' — '.($order?->customer?->name ?? 'Pesanan').' ('.$transaction->status->label().')';

        Cashbook::where('transaction_id', $transaction->id)->update([
            'amount' => $transaction->amount,
            'description' => $description,
            'transaction_date' => $transaction->transaction_date,
        ]);
    }

    private function syncOrderFromTransaction(Transaction $transaction): void
    {
        $order = $transaction->order;

        if (! $order) {
            return;
        }

        $paymentStatus = $transaction->status === TransactionStatus::Lunas
            ? PaymentStatus::Lunas
            : PaymentStatus::DP;

        $order->update([
            'payment_status' => $paymentStatus,
            'payment_amount' => $transaction->amount,
            'payment_type' => $transaction->payment_type,
            'payment_date' => $transaction->transaction_date,
        ]);
    }
}
