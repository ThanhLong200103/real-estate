<?php

namespace App\Http\Requests\SalePost;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'area' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',

            // Thay đổi tại đây
            'is_furnished' => 'nullable',
            'status'       => 'nullable',
            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images'       => 'nullable|array',
        ];
    }
}