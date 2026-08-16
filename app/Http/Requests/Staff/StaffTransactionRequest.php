<?php

namespace App\Http\Requests\Staff;

use App\Enums\TransactionStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_type' => ['required', 'in:tunai,transfer,lainnya'],
            'transaction_date' => ['required', 'date'],
            'status' => ['required', Rule::enum(TransactionStatus::class)],
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'order_id.required' => 'Pemesanan wajib dipilih.',
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.min' => 'Jumlah pembayaran minimal 1.',
            'payment_type.required' => 'Metode pembayaran wajib dipilih.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'status.required' => 'Status pembayaran wajib dipilih.',
        ];
    }
}
