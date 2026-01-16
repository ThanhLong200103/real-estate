<?php

namespace App\Http\Requests\SalePost; // SỬA Ở ĐÂY CHO ĐÚNG THƯ MỤC

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // app/Http/Requests/SalePost/StoreReportRequest.php

    public function rules(): array
    {
        return [
            'sale_post_id' => 'required|exists:sale_posts,id',
            'reason'       => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000', // Đổi từ content -> description
        ];
    }

    public function messages(): array
    {
        return [
            'sale_post_id.exists' => 'Bài viết không tồn tại hoặc đã bị xóa.',
            'reason.required'     => 'Vui lòng chọn lý do báo cáo.',
            'description.max'      => 'Nội dung chi tiết không được quá 1000 ký tự.', // Đổi content -> description
        ];
    }
}