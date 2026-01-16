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
     */
    public function rules(): array
    {
        return [
            'type'         => 'required|in:sale,rent',
            'category_id'  => 'required|exists:categories,id', // Đã đổi tên và kiểu kiểm tra
            'title'        => 'required|string|max:200',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0',
            'area'         => 'required|numeric|min:0',
            'address'      => 'required|string|max:255',
            'bedrooms'     => 'nullable|numeric|min:0',
            'bathrooms'    => 'nullable|numeric|min:0',
            'is_furnished' => 'nullable|boolean',
            'images'       => 'required|array|min:1', // Bắt buộc phải có ít nhất 1 ảnh
            'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'        => 'Vui lòng chọn hình thức giao dịch (Bán hoặc Cho thuê).',
            'category_id.required' => 'Vui lòng chọn loại hình bất động sản.',
            'category_id.exists'   => 'Danh mục đã chọn không tồn tại.',
            'title.required'       => 'Tiêu đề không được để trống.',
            'price.required'       => 'Giá không được để trống.',
            'area.required'        => 'Diện tích không được để trống.',
            'address.required'     => 'Địa chỉ không được để trống.',
            'description.required' => 'Mô tả chi tiết không được để trống.',
            'images.required'      => 'Bạn phải tải lên ít nhất một hình ảnh thực tế.',
            'images.*.image'       => 'Tệp tải lên phải là hình ảnh.',
            'images.*.max'         => 'Dung lượng mỗi ảnh không được quá 5MB.',
        ];
    }
}