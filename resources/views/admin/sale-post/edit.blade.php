@extends('admin.layout')

@section('content')
@php
    // Đồng bộ hóa dữ liệu từ mọi nguồn Controller
    $item = $post ?? $salePost ?? $rentPost ?? $item ?? null;
@endphp

<style>
    /* Nâng cấp hiệu ứng Input */
    .form-control-custom {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s;
        background-color: #f8fafc;
    }
    .form-control-custom:focus {
        border-color: #4f46e5;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    
    /* Quản lý ảnh hiện đại */
    .image-container-edit {
        position: relative;
        width: 100%;
        padding-top: 75%; /* Ratio 4:3 */
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f1f5f9;
    }
    .image-container-edit img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
    }
    
    /* Card nổi bật */
    .edit-card {
        border: none;
        border-radius: 24px;
        background: #ffffff;
    }
    
    .section-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        margin-bottom: 1rem;
        display: block;
    }

    .sticky-sidebar {
        position: sticky;
        top: 2rem;
    }

    .empty-image-placeholder {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
    }
</style>

<div class="container-fluid py-4">
    @if(!$item)
        <div class="text-center py-5">
            <h4 class="text-muted">Không tìm thấy dữ liệu để chỉnh sửa</h4>
            <a href="{{ url()->previous() }}" class="btn btn-primary rounded-pill px-4">Quay lại</a>
        </div>
    @else
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-800 text-dark m-0">Chỉnh sửa tin đăng</h2>
                <p class="text-muted mb-0 small">ID: #{{ $item->id }} • Loại: {{ isset($salePost) ? 'Tin bán' : 'Tin thuê' }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary border-0 fw-bold">
                <i class="fas fa-times me-2"></i> Hủy thay đổi
            </a>
        </div>

        <form action="{{ route('update-sale-post-admin', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                {{-- Cột Trái: Nội dung --}}
                <div class="col-lg-8">
                    <div class="card edit-card shadow-sm p-4 p-lg-5">
                        <span class="section-label">Thông tin cơ bản</span>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Tiêu đề bài viết <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-custom fw-bold text-dark" 
                                   value="{{ old('title', $item->title) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control form-control-custom" 
                                      rows="8" required>{{ old('description', $item->description) }}</textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Giá niêm yết (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control form-control-custom" 
                                           value="{{ old('price', $item->price) }}">
                                    <span class="input-group-text bg-light border-0 fw-bold">₫</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Diện tích (m²)</label>
                                <div class="input-group">
                                    <input type="number" name="area" class="form-control form-control-custom" 
                                           value="{{ old('area', $item->area) }}">
                                    <span class="input-group-text bg-light border-0 fw-bold">m²</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold small">Địa chỉ chi tiết</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <input type="text" name="address" class="form-control form-control-custom" 
                                       value="{{ old('address', $item->address) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cột Phải: Thông số & Ảnh --}}
                <div class="col-lg-4">
                    <div class="sticky-sidebar">
                        <div class="card edit-card shadow-sm p-4 mb-4">
                            <span class="section-label">Thông số kỹ thuật</span>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="small fw-bold mb-1">Phòng ngủ</label>
                                    <input type="number" name="bedrooms" class="form-control form-control-custom py-2" 
                                           value="{{ old('bedrooms', $item->bedrooms) }}">
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold mb-1">Phòng tắm</label>
                                    <input type="number" name="bathrooms" class="form-control form-control-custom py-2" 
                                           value="{{ old('bathrooms', $item->bathrooms) }}">
                                </div>
                            </div>
                            <div class="form-check form-switch py-2">
                                <input class="form-check-input" type="checkbox" name="is_furnished" id="furnished" 
                                       value="1" {{ $item->is_furnished ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold small text-dark" for="furnished">Đã có nội thất</label>
                            </div>
                        </div>

                        <div class="card edit-card shadow-sm p-4 mb-4">
                            <span class="section-label">Thư viện ảnh ({{ $item->images->count() }})</span>
                            
                            @if($item->images->count() > 0)
                                <div class="row g-2 mb-3">
                                    @foreach($item->images as $img)
                                        <div class="col-4">
                                            <div class="image-container-edit shadow-sm">
                                                @php $src = str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . ltrim($img->image_url, '/')); @endphp
                                                <img src="{{ $src }}" onerror="this.src='https://placehold.co/400x300?text=Lỗi+ảnh'">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-image-placeholder mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted opacity-50 mb-2"></i>
                                    <p class="mb-0 small fw-bold text-muted">Chưa có hình ảnh</p>
                                    <p class="mb-0 text-muted" style="font-size: 10px;">Chọn ảnh mới để cập nhật cho bài Seed</p>
                                </div>
                            @endif

                            <div class="p-3 bg-light rounded-3 mb-3">
                                <label class="small fw-bold mb-2 d-block">Tải ảnh mới</label>
                                <input type="file" name="image_url[]" class="form-control form-control-sm border-0 bg-white" 
                                       multiple accept="image/*">
                                <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded-3">
                                    <p class="mb-0 text-dark fw-bold" style="font-size: 9px; line-height: 1.2;">
                                        <i class="fas fa-info-circle me-1 text-warning"></i> Lưu ý: Ảnh mới sẽ thay thế toàn bộ ảnh cũ của tin này.
                                    </p>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-800 shadow-lg border-0" 
                                    style="border-radius: 16px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                                <i class="fas fa-save me-2"></i> LƯU THAY ĐỔI
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection