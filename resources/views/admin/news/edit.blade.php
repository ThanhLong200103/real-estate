@extends('admin.layout')

@section('content')
<style>
    .current-img { width: 100%; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0"><i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa bài viết</h2>
        <small class="text-muted">ID: #{{ $rentPost->id }} - Cập nhật lần cuối: {{ $rentPost->updated_at->format('d/m/Y H:i') }}</small>
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
    <form action="{{ route('update-news-admin', $rentPost->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                {{-- Tiêu đề --}}
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">Tiêu đề</label>
                    <input type="text" name="title" id="title" class="form-control form-control-lg border-2" 
                           value="{{ old('title', $rentPost->title) }}" required>
                </div>

                {{-- Mô tả --}}
                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Nội dung chi tiết</label>
                    <textarea name="description" id="description" class="form-control border-2" rows="12" required>{{ old('description', $rentPost->description) }}</textarea>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Trạng thái --}}
                <div class="card bg-light border-0 mb-4 shadow-sm">
                    <div class="card-body">
                        <label class="form-label d-block mb-3 fw-bold">Trạng thái hiển thị</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" id="status" value="1" 
                                   class="form-check-input" {{ old('status', $rentPost->status) ? 'checked' : '' }}>
                            <label for="status" class="form-check-label fw-medium">Đã xuất bản</label>
                        </div>
                    </div>
                </div>

                {{-- Ảnh hiện tại --}}
                <div class="card bg-light border-0 mb-4 shadow-sm">
                    <div class="card-body">
                        <label class="form-label mb-2 fw-bold">Ảnh hiện tại</label>
                        @if($rentPost->images && $rentPost->images->isNotEmpty())
                            <div class="row g-2 mb-3">
                                @foreach($rentPost->images as $img)
                                    <div class="col-4">
                                        <img src="{{ str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . $img->image_url) }}" 
                                             class="current-img shadow-sm border">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small italic">Chưa có ảnh nào.</p>
                        @endif

                        <label for="image_array_new" class="form-label mt-2 fw-bold">Thay đổi/Thêm ảnh mới</label>
                        <input type="file" name="image_array_new[]" id="image_array_new" class="form-control border-2" 
                               multiple accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted d-block mt-2 italic">* Tải ảnh mới sẽ cập nhật bộ sưu tập hình ảnh bài viết.</small>
                    </div>
                </div>

                {{-- Nút bấm --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-save me-2"></i> Cập nhật thay đổi
                    </button>
                    <a href="{{ route('show-news-admin', $rentPost->id) }}" class="btn btn-light text-muted" up-follow up-target=".main-content">Hủy bỏ</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection