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
            'status' => 'required|boolean',
            'description' => 'required|string|min:10',
            'image_array_new'   => 'nullable|array',
            'image_array_new.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả không được để trống.',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự.',
            'status.required' => 'Trạng thái tin tức là bắt buộc.',
            'status.boolean' => 'Trạng thái tin tức không hợp lệ.',
            'image_array_new.array' => 'Hình ảnh phải được gửi dưới dạng mảng.',
            'image_array_new.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'image_array_new.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'image_array_new.*.max' => 'Dung lượng mỗi hình ảnh không được vượt quá 2MB.',
        ];
    }
}