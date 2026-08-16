<?php

namespace App\Http\Requests\Staff;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AttendanceRequest extends FormRequest
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
            'worker_id' => ['required', 'exists:workers,id'],
            'date' => ['required', 'date'],
            'check_in' => ['required', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('check_in') && $this->filled('check_out') && $this->input('check_out') < $this->input('check_in')) {
                $validator->errors()->add('check_out', 'Jam keluar tidak boleh lebih awal dari jam masuk.');
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
            'worker_id.required' => 'Pekerja wajib dipilih.',
            'date.required' => 'Tanggal wajib diisi.',
            'check_in.required' => 'Jam masuk wajib diisi.',
            'check_in.date_format' => 'Format jam masuk tidak valid (HH:MM).',
            'check_out.date_format' => 'Format jam keluar tidak valid (HH:MM).',
        ];
    }
}
