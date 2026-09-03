<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:255'],
            'description' => ['string'],
            'type' => ['in:1,2'],
            'regular_price' => ['numeric', 'min:0'],
            'special_price' => ['nullable', 'numeric', 'min:0'],
            'size' => ['array'],
            'size.*' => ['string', 'max:255'],
            'v_regular_price' => ['array'],
            'v_regular_price.*' => ['numeric', 'min:0'],
            'v_special_price' => ['nullable', 'array'],
            'v_special_price.*' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['exists:categories,id'],
            'tags' => ['nullable', 'string'],
            'featured' => ['boolean'],
            'availability' => ['boolean'],
            'review' => ['boolean'],
            'status' => ['boolean'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'image' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'v_image' => ['nullable', 'array'],
            'v_image.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'pi_id' => ['nullable', 'array'],
            'pi_id.*' => ['exists:images,id'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'delete_gallery' => ['nullable', 'array'],
            'delete_gallery.*' => ['exists:images,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Product name cannot be longer than 255 characters.',
            'code.max' => 'Product code cannot be longer than 255 characters.',
            'short_desc.max' => 'Short description cannot be longer than 255 characters.',
            'type.in' => 'Product type must be Simple or Variable.',
            'regular_price.numeric' => 'Regular price must be a number.',
            'regular_price.min' => 'Regular price cannot be negative.',
            'special_price.numeric' => 'Special price must be a number.',
            'special_price.min' => 'Special price cannot be negative.',
            'size.*.string' => 'Each size must be a valid text.',
            'v_regular_price.*.numeric' => 'Each regular price must be a number.',
            'v_regular_price.*.min' => 'Regular prices cannot be negative.',
            'v_special_price.*.numeric' => 'Each special price must be a number.',
            'v_special_price.*.min' => 'Special prices cannot be negative.',
            'category_id.exists' => 'The selected category does not exist.',
            'meta_description.max' => 'Meta description cannot be longer than 255 characters.',
            'meta_keywords.max' => 'Meta keywords cannot be longer than 255 characters.',
            'image.image' => 'The main image must be a valid image file.',
            'image.mimes' => 'Main image must be a JPG, JPEG, or PNG file.',
            'image.max' => 'Main image cannot be larger than 2MB.',
            'v_image.*.image' => 'Variable product images must be valid image files.',
            'v_image.*.mimes' => 'Variable product images must be JPG, JPEG, or PNG files.',
            'v_image.*.max' => 'Variable product images cannot be larger than 2MB.',
            'gallery.*.image' => 'Gallery images must be valid image files.',
            'gallery.*.mimes' => 'Gallery images must be JPG, JPEG, or PNG files.',
            'gallery.*.max' => 'Gallery images cannot be larger than 2MB.',
            'pi_id.*.exists' => 'One or more selected image IDs do not exist.',
            'delete_gallery.*.exists' => 'One or more gallery image IDs to delete do not exist.',
        ];
    }
}
