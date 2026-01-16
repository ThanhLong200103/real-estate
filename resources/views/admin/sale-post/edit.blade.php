@extends('admin.layout')

@section('content')
@php
    // Tự động nhận diện biến từ Controller gửi qua
    $item = $salePost ?? $post ?? $item ?? null;
@endphp

<style>
    .edit-card { border: none; border-radius: 24px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .form-label { font-weight: 700; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
    .form-control, .form-select { border-radius: 12px; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; font-weight: 500; }
    .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .input-group-text { border-radius: 12px 0 0 12px; background-color: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; }
    .input-group .form-control { border-radius: 0 12px 12px 0; }
    .img-edit-preview { width: 100%; height: 120px; object-fit: cover; border-radius: 12px; border: 1px solid #e2e8f0; }
    .section-title { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; }
    .section-title i { width: 32px; height: 32px; background: #e0e7ff; color: #4f46e5; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 0.9rem; }
</style>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('index-false-sale-post-admin') }}" class="text-muted text-decoration-none">Quản lý</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold">Chỉnh sửa bài đăng</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-dark m-0">Cập nhật nội dung #{{ $item->id }}</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('update-sale-post-admin', $item->id) }}" method="POST" enctype="multipart/form-data" up-submit up-target=".main-content">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Cột trái: Thông tin chính --}}
            <div class="col-lg-8">
                <div class="card edit-card p-4 p-lg-5 mb-4">
                    <div class="section-title"><i class="fas fa-info-circle"></i> Thông tin cơ bản</div>
                    
                    <div class="mb-4">
                        <label class="form-label">Tiêu đề bài đăng</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Loại hình</label>
                            <select name="type" class="form-select">
                                <option value="sale" {{ old('type', $item->type) == 'sale' ? 'selected' : '' }}>Bán bất động sản</option>
                                <option value="rent" {{ old('type', $item->type) == 'rent' ? 'selected' : '' }}>Cho thuê</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Địa chỉ chi tiết</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $item->address) }}" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Mô tả nội dung</label>
                        <textarea name="description" id="description" class="form-control" required>{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>

                <div class="card edit-card p-4 p-lg-5">
                    <div class="section-title"><i class="fas fa-ruler-combined"></i> Thông số kỹ thuật</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Giá niêm yết</label>
                            <div class="input-group">
                                <span class="input-group-text">VNĐ</span>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $item->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Diện tích (m²)</label>
                            <input type="number" name="area" class="form-control" value="{{ old('area', $item->area) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phòng ngủ</label>
                            <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $item->bedrooms) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phòng tắm</label>
                            <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $item->bathrooms) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nội thất</label>
                            <select name="is_furnished" class="form-select">
                                <option value="1" {{ old('is_furnished', $item->is_furnished) == 1 ? 'selected' : '' }}>Đầy đủ</option>
                                <option value="0" {{ old('is_furnished', $item->is_furnished) == 0 ? 'selected' : '' }}>Cơ bản / Trống</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải: Hình ảnh & Trạng thái --}}
            <div class="col-lg-4">
                <div class="card edit-card p-4 mb-4">
                    <div class="section-title"><i class="fas fa-images"></i> Hình ảnh hiện tại</div>
                    <div class="row g-2 mb-3">
                        @forelse($item->images as $img)
                            <div class="col-4">
                                <img src="{{ str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . $img->image_url) }}" class="img-edit-preview">
                            </div>
                        @empty
                            <p class="text-muted small ps-2">Chưa có hình ảnh nào</p>
                        @endforelse
                    </div>
                    <label class="form-label">Thêm ảnh mới</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted mt-2 d-block italic">Chọn nhiều ảnh để tải lên cùng lúc</small>
                </div>

                <div class="card edit-card p-4 mb-4">
                    <div class="section-title"><i class="fas fa-toggle-on"></i> Trạng thái hiển thị</div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" {{ old('status', $item->status) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="statusSwitch">Công khai bài đăng</label>
                    </div>
                    <p class="text-muted small">Tắt công khai nếu bài đăng vi phạm chính sách hoặc cần chờ bổ sung thông tin.</p>
                </div>

                <div class="sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card edit-card p-3 border-primary border-opacity-25 bg-primary-subtle bg-opacity-10">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-3 mb-2 shadow">
                            <i class="fas fa-save me-2"></i> LƯU THAY ĐỔI
                        </button>
                        <a href="{{ route('show-sale-post-admin', $item->id) }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2">
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection