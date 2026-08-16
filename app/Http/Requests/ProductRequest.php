<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('products', 'slug')->ignore($this->route('produk')),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'warranty_months' => ['nullable', 'integer', 'min:0', 'max:240'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['boolean'],

            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:2048'],

            'specifications' => ['nullable', 'array'],
            'specifications.*.spec_key' => ['nullable', 'string', 'max:100'],
            'specifications.*.spec_value' => ['nullable', 'string', 'max:255'],

            'videos' => ['nullable', 'array'],
            'videos.*.video_url' => ['nullable', 'url', 'max:255'],
            'videos.*.caption' => ['nullable', 'string', 'max:150'],

            'primary_image' => ['nullable', 'integer', 'exists:product_images,id'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
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
            'category_id.required' => 'Kategori wajib dipilih.',
            'name.required' => 'Nama produk wajib diisi.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda strip.',
            'slug.unique' => 'Slug sudah digunakan.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
            'videos.*.video_url.url' => 'URL video tidak valid.',
        ];
    }
}
