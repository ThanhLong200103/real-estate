@extends('admin.layout')

@section('content')
<style>
    /* Style bổ sung cho phần hiển thị ảnh hiện tại */
    .current-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        background: #f1f5f9;
        padding: 15px;
        border-radius: 12px;
    }
    .img-wrapper {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .img-wrapper img {
        width: 100%;
        height: 90px;
        object-fit: cover;
    }
    .img-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0,0,0,0.5);
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>

<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ $rentPost->status ? route('index-true-sale-post-admin') : route('index-false-sale-post-admin') }}" up-follow up-target=".main-wrapper">
                    Quản lý bài đăng
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark"><i class="fas fa-edit text-primary me-2"></i> Chỉnh Sửa Bất Động Sản</h2>
    <p class="text-muted small">Cập nhật thông tin chi tiết cho bài đăng: <strong>{{ $rentPost->title }}</strong></p>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2"></i> Có lỗi xảy ra:</div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-form shadow-sm border-0" style="border-radius: 16px;">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('update-sale-post-admin', $rentPost->id) }}" method="POST" enctype="multipart/form-data" up-submit up-target=".main-wrapper">
            @csrf
            @method('PUT')

            <div class="form-section-title">1. Thông tin cơ bản</div>
            <div class="mb-4">
                <label class="form-label">Tiêu đề bài đăng <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $rentPost->title) }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5" required>{{ old('description', $rentPost->description) }}</textarea>
            </div>

            <div class="form-section-title mt-5">2. Giá trị & Vị trí</div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Giá bán/thuê (VNĐ)</label>
                    <div class="input-group">
                        <input type="number" name="price" class="form-control" value="{{ old('price', $rentPost->price) }}" required>
                        <span class="input-group-text">VNĐ</span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label">Diện tích (m²)</label>
                    <div class="input-group">
                        <input type="number" step="0.1" name="area" class="form-control" value="{{ old('area', $rentPost->area) }}" required>
                        <span class="input-group-text">m²</span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Địa chỉ chính xác <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-map-marker-alt text-danger"></i></span>
                    <input type="text" name="address" class="form-control border-start-0" value="{{ old('address', $rentPost->address) }}" required>
                </div>
            </div>

            <div class="form-section-title mt-5">3. Thông số chi tiết</div>
            <div class="row align-items-center">
                <div class="col-md-4 mb-4">
                    <label class="form-label">Số phòng ngủ</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-bed text-primary"></i></span>
                        <input type="number" name="bedrooms" class="form-control border-start-0" value="{{ old('bedrooms', $rentPost->bedrooms) }}">
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <label class="form-label">Số phòng tắm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-bath text-primary"></i></span>
                        <input type="number" name="bathrooms" class="form-control border-start-0" value="{{ old('bathrooms', $rentPost->bathrooms) }}">
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="form-check form-switch pt-2">
                        <input type="hidden" name="is_furnished" value="0">
                        <input type="checkbox" name="is_furnished" class="form-check-input" id="is_furnished" value="1" {{ old('is_furnished', $rentPost->is_furnished) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold ms-2" for="is_furnished">Đã có nội thất</label>
                    </div>
                </div>
            </div>

            <div class="form-section-title mt-5">4. Hình ảnh</div>
            <div class="mb-4">
                <label class="form-label d-block mb-3">Ảnh hiện tại trên hệ thống:</label>
                <div class="current-images-grid">
                    @foreach($rentPost->images as $image)
                        <div class="img-wrapper">
                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="image">
                            <span class="img-badge">Ảnh {{ $loop->iteration }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label">Cập nhật ảnh mới (Nếu chọn sẽ thay thế ảnh cũ)</label>
                <div class="border-dashed p-4 text-center rounded-4 mb-2" style="border: 2px dashed #cbd5e1; background-color: #f8fafc;">
                    <i class="fas fa-images fa-2x text-muted mb-3"></i>
                    <input type="file" name="image_url[]" class="form-control shadow-none border-0 bg-transparent" multiple accept=".jpg,.jpeg,.png,.webp" style="margin: 0 auto; max-width: 300px;">
                    <p class="small text-muted mt-3 mb-0">Chọn nhiều ảnh để tải lên cùng lúc</p>
                </div>
            </div>

            <hr class="my-5">

            <div class="d-flex justify-content-end gap-3">
                @php
                    $backRoute = $rentPost->status ? route('index-true-sale-post-admin') : route('index-false-sale-post-admin');
                @endphp
                <a href="{{ $backRoute }}" class="btn btn-light px-4 py-2 fw-semibold text-secondary" up-follow up-target=".main-wrapper">
                    Hủy bỏ
                </a>
                <button type="submit" class="btn btn-primary btn-save px-5">
                    <i class="fas fa-save me-2"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection