@extends('admin.layout')

@section('content')
@php
    // Tự động bắt biến từ Controller bất kể tên gì
    $item = $post ?? $salePost ?? $rentPost ?? $item ?? null;
    
    // Logic xác định route quay lại (Fix lỗi Route [index-sale-post-admin] not defined)
    // Nếu bài đã duyệt thì quay về index_true, nếu chưa thì về index_false
    $backRoute = ($item && $item->status) 
                ? route('index-true-sale-post-admin') 
                : route('index-false-sale-post-admin');
@endphp

@if(!$item)
    <div class="container-fluid py-5 text-center" up-target=".main-content">
        <div class="display-1 text-muted opacity-25 mb-4"><i class="fas fa-search"></i></div>
        <h4 class="fw-bold">Không tìm thấy dữ liệu bài đăng</h4>
        <a href="{{ route('index-false-sale-post-admin') }}" class="btn btn-primary rounded-pill px-4" up-follow>Quay lại danh sách</a>
    </div>
@else
<style>
    .detail-card { border: none; border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .table-detail th { background-color: #f8fafc; color: #64748b; font-weight: 600; width: 35%; border-left: 4px solid #4f46e5; padding: 15px 20px; }
    .table-detail td { font-weight: 500; color: #1e293b; padding: 15px 20px; }
    .price-large { font-size: 2.2rem; font-weight: 800; color: #4f46e5; letter-spacing: -1px; }
    .description-box { background-color: #f8fafc; border-radius: 20px; padding: 30px; line-height: 1.8; color: #334155; border: 1px solid #e2e8f0; min-height: 150px; white-space: pre-line; }
    
    .img-main-container { border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; height: 450px; background: #f1f5f9; position: relative; }
    .img-full { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .img-grid-item { border-radius: 12px; overflow: hidden; height: 100px; border: 2px solid transparent; cursor: pointer; transition: 0.3s; background: #f8fafc; }
    .img-grid-item.active { border-color: #4f46e5; transform: translateY(-3px); }
    .img-grid-item:hover { border-color: #4f46e5; }

    .badge-status { padding: 10px 20px; border-radius: 50px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .section-title { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #4f46e5; letter-spacing: 1.5px; margin-bottom: 20px; display: block; }
    
    .type-label { position: absolute; top: 20px; left: 20px; z-index: 10; padding: 8px 16px; border-radius: 12px; font-weight: 800; color: white; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); }
    .bg-primary-soft { background-color: #e0e7ff; color: #4338ca; }
</style>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    {{-- Sửa lỗi: Quay lại trang danh sách phù hợp --}}
                    <li class="breadcrumb-item"><a href="{{ $backRoute }}" class="text-muted text-decoration-none" up-follow>Quản lý</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold">Chi tiết bài đăng #{{ $item->id }}</li>
                </ol>
            </nav>
            <h2 class="fw-800 text-dark m-0">Kiểm duyệt nội dung</h2>
        </div>
        <div class="d-flex gap-2">
            @if($item->status)
                <span class="badge bg-success text-white badge-status shadow-sm">
                    <i class="fas fa-check-circle me-2"></i> Đang hiển thị
                </span>
            @else
                <span class="badge bg-warning text-dark badge-status shadow-sm">
                    <i class="fas fa-clock me-2"></i> Chờ phê duyệt
                </span>
            @endif
        </div>
    </div>

    <div class="card detail-card shadow-sm mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-5">
                <div class="col-lg-7">
                    <span class="section-title">Thông số kỹ thuật</span>
                    <h3 class="fw-800 text-dark mb-4" style="line-height: 1.3;">{{ $item->title }}</h3>
                    
                    <div class="table-responsive rounded-4 border overflow-hidden mb-5">
                        <table class="table table-detail align-middle mb-0">
                            <tr>
                                <th><i class="fas fa-coins me-2"></i> Giá niêm yết</th>
                                <td>
                                    <span class="price-large">
                                        {{ number_format($item->price) }} đ
                                        <small class="text-muted fs-6 fw-normal">{{ $item->type == 'rent' ? '/ tháng' : '' }}</small>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-tags me-2"></i> Loại hình</th>
                                <td>
                                    {{-- Kiểm tra nếu category là Object (từ quan hệ) hay String --}}
                                    @php
                                        $catSlug = is_object($item->category) ? $item->category->slug : $item->category;
                                        $catName = is_object($item->category) ? $item->category->name : $item->category;
                                        
                                        $categoryClass = match($catSlug) {
                                            'apartment' => 'bg-primary',
                                            'house'     => 'bg-info text-dark',
                                            'land'      => 'bg-success',
                                            default     => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $categoryClass }} px-3 py-2 rounded-pill fw-bold">
                                        {{ $catName }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-ruler-combined me-2"></i> Diện tích</th>
                                <td><span class="fs-5 fw-bold">{{ $item->area }} m²</span> 
                                    @if($item->area > 0)
                                        <small class="text-muted">(Giá: {{ number_format($item->price / $item->area) }} đ/m²)</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-door-open me-2"></i> Bố cục</th>
                                <td>
                                    <span class="me-3 fw-bold"><i class="fas fa-bed text-primary me-1"></i> {{ $item->bedrooms ?? 0 }} PN</span>
                                    <span class="fw-bold"><i class="fas fa-bath text-info me-1"></i> {{ $item->bathrooms ?? 0 }} PT</span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-couch me-2"></i> Nội thất</th>
                                <td>
                                    @if($item->is_furnished)
                                        <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill border border-primary border-opacity-25">Đầy đủ nội thất</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-times-circle me-1"></i> Cơ bản / Trống</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ</th>
                                <td class="text-dark fw-bold">{{ $item->address }}</td>
                            </tr>
                        </table>
                    </div>

                    <span class="section-title">Nội dung mô tả</span>
                    <div class="description-box shadow-sm mb-4">
                        {{ $item->description }}
                    </div>
                </div>

                <div class="col-lg-5">
                    <span class="section-title">Thư viện ảnh ({{ $item->images->count() }})</span>
                    
                    @if($item->images->isNotEmpty())
                        <div class="img-main-container mb-3 shadow-sm">
                            <div class="type-label">{{ $item->type == 'sale' ? 'BÁN' : 'CHO THUÊ' }}</div>
                            @php 
                                $first = $item->images->first()->image_url;
                                $firstSrc = str_starts_with($first, 'http') ? $first : asset('storage/' . ltrim($first, '/'));
                            @endphp
                            <img src="{{ $firstSrc }}" class="img-full" id="mainImage">
                        </div>

                        <div class="row g-2">
                            @foreach($item->images as $index => $img)
                                <div class="col-3">
                                    <div class="img-grid-item shadow-sm {{ $index == 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . ltrim($img->image_url, '/')) }}')">
                                        <img src="{{ str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . ltrim($img->image_url, '/')) }}" class="img-full">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded-4 border-dashed-custom" style="min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px dashed #cbd5e1;">
                            <i class="fas fa-image fa-3x text-muted opacity-25 mb-3"></i>
                            <h5 class="text-dark fw-bold">Không có hình ảnh</h5>
                        </div>
                    @endif

                    <div class="mt-4 p-4 rounded-4 bg-light border-0 d-flex align-items-center justify-content-between shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px;">
                                {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-800 text-dark mb-0">{{ $item->user->name ?? 'Người đăng' }}</h6>
                                <small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $item->user->email ?? '' }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Ngày đăng</div>
                            <div class="fw-bold text-dark">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer p-4 bg-white border-top">
            <div class="d-flex justify-content-between align-items-center">
                {{-- Sửa lỗi: Route quay lại chuẩn --}}
                <a href="{{ $backRoute }}" class="btn btn-light px-4 fw-bold" up-follow>
                    <i class="fas fa-arrow-left me-2"></i> Quay lại
                </a>
                
                <div class="d-flex gap-3">
                    <form action="{{ route('destroy-sale-post-admin', $item->id) }}" method="POST" onsubmit="return confirm('Xóa bài đăng này vĩnh viễn?')" up-submit up-target=".main-content, #admin-sidebar-nav">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger border-0 fw-bold">
                            <i class="fas fa-trash-alt me-2"></i> Gỡ bỏ tin
                        </button>
                    </form>

                    <a href="{{ route('edit-sale-post-admin', $item->id) }}" class="btn btn-outline-warning fw-bold px-4" up-follow>
                        <i class="fas fa-edit me-2"></i> Chỉnh sửa
                    </a>
                    
                    @if(!$item->status)
                        <form action="{{ route('approve-sale-post-admin', $item->id) }}" method="POST" up-submit up-target=".main-content, #admin-sidebar-nav">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success fw-bold px-5 shadow-sm" style="background: #10b981; border: none;">
                                <i class="fas fa-check-double me-2"></i> PHÊ DUYỆT NGAY
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeImage(element, src) {
        const mainImg = document.getElementById('mainImage');
        if(!mainImg) return;
        mainImg.style.opacity = '0.5';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 150);

        document.querySelectorAll('.img-grid-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');
    }
</script>
@endif
@endsection