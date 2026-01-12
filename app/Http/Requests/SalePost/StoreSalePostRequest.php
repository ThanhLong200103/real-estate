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
             'title' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'address' => 'required|string|max:255',
        'description' => 'required|string|min:10',
        'image_url.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'image_url'   => 'nullable|array',
        ];
    }
}
