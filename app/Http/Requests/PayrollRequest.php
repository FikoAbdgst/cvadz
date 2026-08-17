<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
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
            'period' => ['required', 'date_format:Y-m'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'lemburan' => ['nullable', 'numeric', 'min:0'],
            'uang_luar_kota' => ['nullable', 'numeric', 'min:0'],
            'kasbon' => ['nullable', 'numeric', 'min:0'],
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
            'period.required' => 'Periode wajib dipilih.',
            'period.date_format' => 'Format periode tidak valid.',
            'bonus.numeric' => 'Bonus harus berupa angka.',
            'lemburan.numeric' => 'Lemburan harus berupa angka.',
            'uang_luar_kota.numeric' => 'Uang luar kota harus berupa angka.',
            'kasbon.numeric' => 'Kasbon harus berupa angka.',
        ];
    }
}
