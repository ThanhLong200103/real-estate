<?php

namespace App\Http\Requests\SalePost;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalePostRequest extends FormRequest
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
            'type'         => 'required|in:sale,rent',
            'category_id'  => 'required|exists:categories,id',

            'title'        => 'required|string|max:200',
            'description'  => 'required|string',

           
            'price'        => 'required|integer|min:0|max:1000000000000',

            'area'         => 'required|numeric|min:0|max:1000000',
            'address'      => 'required|string|max:255',


            'bedrooms'     => 'nullable|integer|min:0|max:100',
            'bathrooms'    => 'nullable|integer|min:0|max:100',

            
            'is_furnished' => 'nullable|boolean',
            'status'       => 'nullable|boolean',

            // Update: không bắt buộc ảnh, chỉ validate nếu có upload
            'images'       => 'nullable|array',
            'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'Vui lòng chọn hình thức giao dịch (Bán hoặc Cho thuê).',
            'type.in'              => 'Hình thức giao dịch không hợp lệ.',

            'category_id.required' => 'Vui lòng chọn loại hình bất động sản.',
            'category_id.exists'   => 'Danh mục đã chọn không tồn tại.',

            'title.required'       => 'Tiêu đề không được để trống.',
            'title.max'            => 'Tiêu đề không được vượt quá 200 ký tự.',

            'price.required'       => 'Giá không được để trống.',
            'price.integer'        => 'Giá phải là số nguyên.',
            'price.min'            => 'Giá không được nhỏ hơn 0.',
            'price.max'            => 'Giá không được vượt quá 1.000.000.000.000.',

            'area.required'        => 'Diện tích không được để trống.',
            'area.numeric'         => 'Diện tích phải là số hợp lệ.',
            'area.min'             => 'Diện tích không được nhỏ hơn 0.',
            'area.max'             => 'Diện tích không được vượt quá 1.000.000.',

            'address.required'     => 'Địa chỉ không được để trống.',
            'address.max'          => 'Địa chỉ không được vượt quá 255 ký tự.',

            'description.required' => 'Mô tả chi tiết không được để trống.',

            'bedrooms.integer'     => 'Số phòng ngủ phải là số nguyên.',
            'bedrooms.min'         => 'Số phòng ngủ không được nhỏ hơn 0.',
            'bedrooms.max'         => 'Số phòng ngủ không được vượt quá 100.',
            'bathrooms.integer'    => 'Số phòng tắm phải là số nguyên.',
            'bathrooms.min'        => 'Số phòng tắm không được nhỏ hơn 0.',
            'bathrooms.max'        => 'Số phòng tắm không được vượt quá 100.',

            'images.array'         => 'Danh sách ảnh không hợp lệ.',
            'images.*.image'       => 'Tệp tải lên phải là hình ảnh.',
            'images.*.mimes'       => 'Ảnh chỉ hỗ trợ định dạng JPG, JPEG, PNG, WEBP.',
            'images.*.max'         => 'Dung lượng mỗi ảnh không được quá 5MB.',
        ];
    }
}
