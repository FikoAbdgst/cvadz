<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class OrderRequest extends FormRequest
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
            'customer_id' => ['required', 'exists:customers,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'total' => ['nullable', 'numeric', 'min:0'],
            'warranty_end_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('product_id') && ! $this->filled('service_id')) {
                $validator->errors()->add('item', 'Pilih produk atau layanan.');
            }
        });
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'warranty_end_date.date' => 'Tanggal selesai garansi tidak valid.',
        ];
    }
}
