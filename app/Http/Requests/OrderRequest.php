<?php

namespace App\Http\Requests;

use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'total' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'warranty_end_date' => ['nullable', 'date'],
            'payment_status' => ['required', Rule::enum(PaymentStatus::class)],
            'payment_amount' => ['nullable', 'numeric', 'min:1'],
            'payment_type' => ['nullable', 'in:tunai,transfer,lainnya'],
            'payment_date' => ['nullable', 'date'],
            'payment_proof' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('product_id') && ! $this->filled('service_id')) {
                $validator->errors()->add('item', 'Pilih produk atau layanan.');
            }

            if ($this->filled('service_id') && ! $this->filled('quantity')) {
                $this->merge(['quantity' => 1]);
            }

            if (in_array($this->input('payment_status'), ['dp', 'lunas'], true)) {
                if (! $this->filled('payment_amount') || (float) $this->input('payment_amount', 0) < 1) {
                    $validator->errors()->add('payment_amount', 'Jumlah pembayaran wajib diisi minimal Rp 1.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'warranty_end_date.date' => 'Tanggal selesai garansi tidak valid.',
            'payment_status.required' => 'Status pembayaran wajib dipilih.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format gambar: JPG, PNG, atau WebP.',
            'payment_proof.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
