<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:2048'],
            'position' => ['required', 'integer', 'min:0'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'boolean'],
            'is_customized' => ['nullable', 'boolean'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255']
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'][] = Rule::unique('categories')->ignore($this->category);
        } else {
            $rules['name'][] = 'unique:categories';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'This category name is already taken.',
            'parent_id.exists' => 'The selected parent category is invalid.',
            'banner.image' => 'The file must be an image.',
            'banner.max' => 'The image size must not exceed 2MB.'
        ];
    }
}
