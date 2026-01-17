@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0"><i class="fas fa-plus-circle text-primary me-2"></i>Tạo Bài Viết Mới</h2>
        <small class="text-muted">Thêm tin tức mới vào hệ thống quản lý bài viết.</small>
    </div>
    <a href="{{ route('index-news-admin') }}" class="btn btn-outline-secondary" up-follow up-target=".main-content">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-form bg-white p-4">
    <form action="{{ route('store-news-admin') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                {{-- Tiêu đề --}}
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold text-dark">Tiêu đề bài viết</label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg border-2" 
                           placeholder="Nhập tiêu đề hấp dẫn..." value="{{ old('title') }}" required>
                </div>

                {{-- Nội dung --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold text-dark">Nội dung chi tiết</label>
                    <textarea name="description" id="description" class="form-control border-2" rows="12" 
                              placeholder="Viết nội dung tin tức tại đây..." required>{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Thiết lập xuất bản --}}
                <div class="card bg-light border-0 mb-4 shadow-sm">
                    <div class="card-body">
                        <label class="form-label d-block mb-3 fw-bold">Thiết lập xuất bản</label>
                        <div class="form-check form-switch mb-2">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" id="status" value="1" 
                                   class="form-check-input" {{ old('status', 1) ? 'checked' : '' }}>
                            <label for="status" class="form-check-label fw-medium">Công khai ngay</label>
                        </div>
                        <small class="text-muted d-block mt-2 italic">Gạt sang phải để người dùng có thể thấy bài viết ngay lập tức.</small>
                    </div>
                </div>

                {{-- Upload Hình ảnh --}}
                <div class="card bg-light border-0 mb-4 shadow-sm">
                    <div class="card-body">
                        <label for="image_url" class="form-label fw-bold">Hình ảnh đính kèm</label>
                        <div class="input-group">
                            <input type="file" name="image_array_new[]" id="image_url" class="form-control border-2" 
                                   multiple accept=".jpg,.jpeg,.png,.webp">
                        </div>
                        <div class="mt-2 small text-muted">
                            <i class="fas fa-info-circle me-1"></i> Có thể chọn nhiều ảnh cùng lúc.
                        </div>
                    </div>
                </div>

                {{-- Nút bấm --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-save me-2"></i> Lưu bài viết
                    </button>
                    <button type="reset" class="btn btn-light text-muted">Xóa trắng form</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection