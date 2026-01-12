<?php

namespace App\Http\Requests\SalePost;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
           'status'=>'required|string',
        'description' => 'required|string|min:10',
     'image_array_new'   => 'nullable|array',
'image_array_new.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

        ];
    }
}
