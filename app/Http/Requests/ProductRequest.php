<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['required', 'string', 'max:50'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured' => ['boolean'],
            'status' => ['boolean'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->isMethod('POST')) {
            $rules['images'] = ['required', 'array', 'min:1'];
            $rules['images.*'] = ['required', 'image', 'max:2048'];
        } else {
            $rules['images'] = ['nullable', 'array'];
            $rules['images.*'] = ['nullable', 'image', 'max:2048'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.max' => 'The product name cannot exceed 255 characters.',
            'description.required' => 'The product description is required.',
            'price.required' => 'The product price is required.',
            'price.numeric' => 'The product price must be a number.',
            'price.min' => 'The product price must be greater than or equal to 0.',
            'stock.required' => 'The product stock is required.',
            'stock.integer' => 'The product stock must be an integer.',
            'stock.min' => 'The product stock must be greater than or equal to 0.',
            'sku.required' => 'The product SKU is required.',
            'sku.max' => 'The product SKU cannot exceed 50 characters.',
            'category_id.required' => 'The product category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'images.required' => 'At least one product image is required.',
            'images.array' => 'The images must be an array.',
            'images.min' => 'At least one product image is required.',
            'images.*.required' => 'Each image is required.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image cannot exceed 2MB.',
        ];
    }
}
