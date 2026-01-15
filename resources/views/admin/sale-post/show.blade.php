@extends('admin.layout')

@section('content')
@php
    // Tự động bắt biến từ Controller bất kể tên gì
    $item = $post ?? $salePost ?? $rentPost ?? $item ?? null;
@endphp

@if(!$item)
    <div class="container-fluid py-5 text-center">
        <div class="display-1 text-muted opacity-25 mb-4"><i class="fas fa-search"></i></div>
        <h4 class="fw-bold">Không tìm thấy dữ liệu bài đăng</h4>
        <a href="{{ url()->previous() }}" class="btn btn-primary rounded-pill px-4">Quay lại danh sách</a>
    </div>
@else
<style>
    .detail-card { border: none; border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .table-detail th { background-color: #f8fafc; color: #64748b; font-weight: 600; width: 35%; border-left: 4px solid #4f46e5; padding: 15px 20px; }
    .table-detail td { font-weight: 500; color: #1e293b; padding: 15px 20px; }
    .price-large { font-size: 2rem; font-weight: 800; color: #ef4444; letter-spacing: -1px; }
    .description-box { background-color: #f8fafc; border-radius: 20px; padding: 30px; line-height: 1.8; color: #334155; border: 1px solid #e2e8f0; min-height: 150px; }
    .img-main-container { border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; height: 400px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
    .img-grid-item { border-radius: 12px; overflow: hidden; height: 100px; border: 1px solid #e2e8f0; cursor: pointer; transition: 0.3s; background: #f8fafc; }
    .img-grid-item:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #4f46e5; }
    .img-full { width: 100%; height: 100%; object-fit: cover; }
    .badge-status { padding: 10px 20px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; }
    .section-title { font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #4f46e5; letter-spacing: 1.5px; margin-bottom: 20px; display: block; }
    .bg-primary-soft { background-color: rgba(79, 70, 229, 0.1); }
    .border-dashed-custom { border: 2px dashed #cbd5e1 !important; }
</style>

<div class="container-fluid py-4">
    {{-- Top Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Hệ thống</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold">Chi tiết tin #{{ $item->id }}</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-dark m-0">Xem trước & Thẩm định</h2>
        </div>
        <div class="d-flex gap-2">
            @if($item->status)
                <span class="badge bg-success text-white badge-status shadow-sm d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i> Đang hiển thị
                </span>
            @else
                <span class="badge bg-warning text-dark badge-status shadow-sm d-flex align-items-center">
                    <i class="fas fa-clock me-2"></i> Chờ phê duyệt
                </span>
            @endif
        </div>
    </div>

    <div class="card detail-card shadow-sm mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-5">
                {{-- Trái: Nội dung chi tiết --}}
                <div class="col-lg-7">
                    <span class="section-title">Thông tin bất động sản</span>
                    <h3 class="fw-800 text-dark mb-4" style="line-height: 1.3;">{{ $item->title }}</h3>
                    
                    <div class="table-responsive rounded-4 border overflow-hidden mb-5">
                        <table class="table table-detail align-middle mb-0">
                            <tr>
                                <th><i class="fas fa-tag me-2"></i> Giá niêm yết</th>
                                <td><span class="price-large">{{ number_format($item->price) }} ₫</span></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-vector-square me-2"></i> Diện tích</th>
                                <td><span class="fs-5 fw-bold">{{ $item->area }} m²</span></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-bed me-2"></i> Tiện nghi</th>
                                <td>
                                    <span class="me-3 fw-bold"><i class="fas fa-door-open text-primary me-1"></i> {{ $item->bedrooms }} PN</span>
                                    <span class="fw-bold"><i class="fas fa-shower text-info me-1"></i> {{ $item->bathrooms }} PT</span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-couch me-2"></i> Tình trạng nội thất</th>
                                <td>
                                    @if($item->is_furnished)
                                        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill border border-primary border-opacity-25">Đầy đủ nội thất</span>
                                    @else
                                        <span class="text-muted italic">Cơ bản / Chưa có nội thất</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-map-marked-alt me-2"></i> Tọa lạc tại</th>
                                <td class="text-dark fw-bold">{{ $item->address }}</td>
                            </tr>
                        </table>
                    </div>

                    <span class="section-title">Mô tả từ người đăng</span>
                    <div class="description-box shadow-sm">
                        {!! nl2br(e($item->description)) !!}
                    </div>
                </div>

                {{-- Phải: Gallery Hình ảnh --}}
                <div class="col-lg-5">
                    <span class="section-title">Hình ảnh thực tế ({{ $item->images->count() }})</span>
                    
                    @if($item->images->isNotEmpty())
                        {{-- Ảnh chính nổi bật --}}
                        <div class="img-main-container mb-3 shadow-sm">
                            @php 
                                $first = $item->images->first()->image_url;
                                $firstSrc = str_starts_with($first, 'http') ? $first : asset('storage/' . ltrim($first, '/'));
                            @endphp
                            <img src="{{ $firstSrc }}" class="img-full" id="mainImage" onerror="this.src='https://placehold.co/800x600?text=Hình+Ảnh+Đang+Cập+Nhật'">
                        </div>

                        {{-- Thumbnails --}}
                        <div class="row g-2">
                            @foreach($item->images as $img)
                                <div class="col-3">
                                    <div class="img-grid-item shadow-sm">
                                        @php $s = str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . ltrim($img->image_url, '/')); @endphp
                                        <img src="{{ $s }}" class="img-full" onclick="document.getElementById('mainImage').src = this.src" onerror="this.src='https://placehold.co/200x200?text=Lỗi'">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State cho ảnh Seed bị thiếu --}}
                        <div class="text-center py-5 bg-light rounded-4 border-dashed-custom" style="min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div class="bg-white p-4 rounded-circle shadow-sm mb-3">
                                <i class="fas fa-camera-retro fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-dark fw-bold">Chưa tải lên hình ảnh</h5>
                            <p class="text-muted small px-5">Bài đăng này chưa có ảnh. Hãy chỉnh sửa để bổ sung hình ảnh minh họa.</p>
                            <a href="{{ route('edit-sale-post-admin', $item->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">
                                <i class="fas fa-upload me-2"></i> Tải ảnh ngay
                            </a>
                        </div>
                    @endif

                    {{-- Thông tin tài khoản đăng tin --}}
                    <div class="mt-4 p-4 rounded-4 bg-white border shadow-sm">
                        <span class="section-title mb-3" style="font-size: 0.6rem;">Chủ tin đăng</span>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 54px; height: 54px; font-size: 1.3rem;">
                                    {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-800 text-dark mb-0">{{ $item->user->name ?? 'Người dùng hệ thống' }}</h6>
                                <small class="text-muted">{{ $item->user->email ?? 'no-email@system.com' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thanh tác vụ dưới cùng --}}
        <div class="card-footer p-4 bg-light border-top">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary border-0 fw-bold px-3">
                    <i class="fas fa-chevron-left me-2"></i> Quay lại
                </a>
                
                <div class="d-flex gap-2">
                    <form action="{{ route('destroy-sale-post-admin', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tin đăng này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger fw-bold text-decoration-none px-3">
                            <i class="fas fa-trash-alt me-2"></i> Xóa tin
                        </button>
                    </form>

                    <a href="{{ route('edit-sale-post-admin', $item->id) }}" class="btn btn-outline-warning fw-bold px-4 rounded-3 bg-white">
                        <i class="fas fa-pen me-2"></i> Chỉnh sửa
                    </a>
                    
                    @if(!$item->status)
                        <form action="{{ route('approve-sale-post-admin', $item->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-primary fw-bold px-5 rounded-3 shadow-lg" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none;">
                                <i class="fas fa-check-double me-2"></i> PHÊ DUYỆT BÀI ĐĂNG
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection