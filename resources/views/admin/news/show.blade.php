@extends('admin.layout')

@section('content')
<style>
    .content-box { line-height: 1.8; color: #334155; white-space: pre-wrap; }
    .img-preview { object-fit: cover; height: 180px; width: 100%; border-radius: 8px; transition: 0.3s; }
    .img-preview:hover { transform: scale(1.02); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('index-news-admin') }}">Tin tức</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
        <h2 class="fw-bold m-0">Xem bài viết</h2>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('index-news-admin') }}" class="btn btn-light shadow-sm" up-follow up-target=".main-content"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
        <a href="{{ route('edit-news-admin', $post->id) }}" class="btn btn-warning shadow-sm" up-follow up-target=".main-content"><i class="fas fa-edit me-1"></i> Chỉnh sửa</a>
    </div>
</div>

<div class="card card-detail bg-white p-4 mb-4">
    <div class="row">
        <div class="col-lg-8 border-end">
            <div class="mb-3">
                <span class="badge {{ $post->status ? 'bg-success' : 'bg-warning text-dark' }}">
                    <i class="fas {{ $post->status ? 'fa-check-circle' : 'fa-file-alt' }} me-1"></i> {{ $post->status ? 'Đã xuất bản' : 'Bản nháp' }}
                </span>
                <span class="text-muted ms-3 small"><i class="far fa-clock me-1"></i> {{ $post->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <h1 class="fw-bold text-dark mb-4">{{ $post->title }}</h1>
            <h6 class="fw-bold text-uppercase small text-muted mb-3">Mô tả bài viết:</h6>
            <div class="content-box">{!! nl2br(e($post->description)) !!}</div>
        </div>

        <div class="col-lg-4 ps-lg-4 mt-4 mt-lg-0">
            <h6 class="fw-bold text-uppercase small text-muted mb-3">Hình ảnh đính kèm:</h6>
            @if($post->images->isNotEmpty())
                <div class="row g-3">
                    @foreach($post->images as $image)
                        <div class="col-6 col-lg-12">
                            <a href="{{ str_starts_with($image->image_url, 'http') ? $image->image_url : asset('storage/' . $image->image_url) }}" target="_blank">
                                <img src="{{ str_starts_with($image->image_url, 'http') ? $image->image_url : asset('storage/' . $image->image_url) }}" 
                                     class="img-preview shadow-sm border" alt="News Image">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 bg-light rounded"><i class="fas fa-image fa-3x text-muted d-block"></i><span class="text-muted small">Không có hình ảnh</span></div>
            @endif
        </div>
    </div>
</div>
@endsection