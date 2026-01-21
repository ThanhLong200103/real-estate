<?php

namespace App\Http\Requests\SalePost;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalePostRequest extends FormRequest
{
    /**
     * Xác thực người dùng có quyền thực hiện request này hay không.
     */
    

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:sale,rent', // Chỉ chấp nhận 2 giá trị này
            'category_id'  => 'required|integer',
            'province_id'  => 'required|integer',
            'district_id'  => 'required|integer',
            'ward_id'      => 'required|integer',
            'price'        => 'required|numeric|min:0',
            'area'         => 'required|numeric|min:0',
            'address'      => 'required|string',
            'description'  => 'required|string',
            'images'       => 'required|array|min:1', // Bắt buộc là mảng và có ít nhất 1 ảnh
            'images.*'     => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'bedrooms'     => 'nullable|integer',
            'bathrooms'    => 'nullable|integer',
            'is_furnished' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Vui lòng nhập tiêu đề bài đăng.',
            'images.required'      => 'Bạn phải tải lên ít nhất một hình ảnh.',
            'images.array'         => 'Định dạng hình ảnh không hợp lệ.',
            'description.required' => 'Vui lòng nhập mô tả chi tiết.',
            'price.required'       => 'Vui lòng nhập giá.',
            'price.numeric'        => 'Giá phải là chữ số.',
            'area.required'        => 'Vui lòng nhập diện tích.',
            'province_id.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district_id.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward_id.required'     => 'Vui lòng chọn Phường/Xã.',
        ];
    }
}