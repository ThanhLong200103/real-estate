@extends('admin.layout')

@section('content')
@php
    $item = $post ?? $salePost ?? $rentPost ?? $item ?? null;
    $type = $item?->type ?? request('type', 'sale');
    $backRoute = ($item && $item->status)
        ? route('index-true-sale-post-admin', ['type' => $type])
        : route('index-false-sale-post-admin', ['type' => $type]);
@endphp

@if(!$item)
    <div class="container-fluid py-5 text-center">
        <div class="display-1 text-muted opacity-25 mb-4"><i class="fas fa-search"></i></div>
        <h4 class="fw-bold">Không tìm thấy dữ liệu bài đăng</h4>
        <a href="{{ $backRoute }}" class="btn btn-primary rounded-pill px-4" up-follow>Quay lại danh sách</a>
    </div>
@else

<style>
    /* Nâng cấp Card giao diện */
    .detail-card { border: none; border-radius: 28px; background: white; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.03); }
    
    /* Bảng chi tiết sạch sẽ hơn */
    .table-detail { margin-bottom: 0; }
    .table-detail th { background-color: #fcfcfd; color: #94a3b8; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; width: 30%; padding: 18px 25px; border-bottom: 1px solid #f1f5f9; }
    .table-detail td { font-weight: 600; color: #1e293b; padding: 18px 25px; border-bottom: 1px solid #f1f5f9; }

    /* Highlights */
    .price-display { font-size: 2.4rem; font-weight: 900; color: #4f46e5; letter-spacing: -1.5px; line-height: 1; }
    .area-badge { background: #f0f9ff; color: #0369a1; padding: 6px 14px; border-radius: 10px; font-weight: 800; }
    
    /* Location Tags hiện đại */
    .loc-wrapper { display: flex; flex-wrap: wrap; gap: 8px; }
    .loc-item { background: #f8fafc; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 12px; font-size: 0.85rem; color: #475569; display: flex; align-items: center; }
    .loc-item i { color: #6366f1; margin-right: 6px; font-size: 0.75rem; }
    .loc-label { font-weight: 400; color: #94a3b8; margin-right: 4px; }

    /* Hình ảnh */
    .img-main-wrapper { border-radius: 24px; overflow: hidden; height: 480px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .img-grid-item { border-radius: 14px; height: 90px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); filter: grayscale(0.3); opacity: 0.8; }
    .img-grid-item.active { filter: grayscale(0); opacity: 1; transform: scale(1.05); border: 3px solid #4f46e5; }
    
    .section-divider { display: flex; align-items: center; margin: 30px 0 20px; }
    .section-divider span { font-size: 0.75rem; font-weight: 800; color: #6366f1; text-transform: uppercase; letter-spacing: 2px; white-space: nowrap; }
    .section-divider::after { content: ""; flex-grow: 1; height: 1px; background: #e2e8f0; margin-left: 15px; }

    /* Nút bấm */
    .btn-action { border-radius: 16px; padding: 12px 24px; font-weight: 700; transition: 0.3s; }
</style>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ $backRoute }}" class="text-muted text-decoration-none">Hệ thống</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold">Chi tiết BĐS #{{ $item->id }}</li>
                </ol>
            </nav>
            <h2 class="fw-900 text-dark m-0" style="letter-spacing: -1px;">Thông tin bài đăng</h2>
        </div>
        <div class="text-end">
            @if($item->status)
                <div class="badge bg-success-subtle text-success px-4 py-2 rounded-pill fw-800 border border-success-subtle shadow-sm">
                   <i class="fas fa-check-circle me-1"></i> ĐANG HIỂN THỊ
                </div>
            @else
                <div class="badge bg-warning-subtle text-warning-emphasis px-4 py-2 rounded-pill fw-800 border border-warning-subtle shadow-sm">
                   <i class="fas fa-clock me-1"></i> ĐANG CHỜ DUYỆT
                </div>
            @endif
        </div>
    </div>

    <div class="card detail-card">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-5">
                {{-- Trái: Nội dung --}}
                <div class="col-lg-7">
                    <div class="mb-4">
                        <span class="price-display">{{ number_format($item->price) }} <small style="font-size: 1.2rem;">VNĐ{{ $item->type == 'rent' ? '/tháng' : '' }}</small></span>
                    </div>
                    
                    <h3 class="fw-800 text-dark mb-4" style="line-height: 1.4; font-size: 1.75rem;">{{ $item->title }}</h3>

                    <div class="section-divider"><span>Thông tin chi tiết</span></div>
                    
                    <div class="table-responsive rounded-4 border overflow-hidden shadow-sm mb-4">
                        <table class="table table-detail align-middle">
                            <tr>
                                <th><i class="fas fa-map-marker-alt me-2 text-primary"></i> Vị trí khu vực</th>
                                <td>
                                    <div class="loc-wrapper">
                                        <div class="loc-item"><span class="loc-label">Tỉnh:</span>{{ $item->province?->name ?? '--' }}</div>
                                        <div class="loc-item"><span class="loc-label">Quận:</span>{{ $item->district?->name ?? '--' }}</div>
                                        <div class="loc-item"><span class="loc-label">Phường:</span>{{ $item->ward?->name ?? '--' }}</div>
                                    </div>
                                    <div class="mt-3 text-muted fw-normal small">
                                        <i class="fas fa-home me-1"></i> {{ $item->address ?: 'Không có địa chỉ chi tiết' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-ruler-combined me-2 text-primary"></i> Diện tích & Loại hình</th>
                                <td>
                                    <span class="area-badge">{{ $item->area }} m²</span>
                                    <span class="ms-2 text-muted fw-bold">|</span>
                                    <span class="ms-2 fw-bold text-dark">{{ $item->category?->name ?? 'Chưa rõ loại' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-th-large me-2 text-primary"></i> Kết cấu</th>
                                <td>
                                    <div class="d-flex gap-4">
                                        <span><i class="fas fa-bed text-muted me-2"></i>{{ $item->bedrooms ?? 0 }} PN</span>
                                        <span><i class="fas fa-bath text-muted me-2"></i>{{ $item->bathrooms ?? 0 }} WC</span>
                                        @if($item->is_furnished)
                                            <span class="text-success"><i class="fas fa-couch me-2"></i>Full nội thất</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="section-divider"><span>Mô tả bài đăng</span></div>
                    <div class="description-box">
                        {{ $item->description }}
                    </div>
                </div>

                {{-- Phải: Gallery --}}
                <div class="col-lg-5">
                    <div class="img-main-wrapper mb-3">
                        <div style="position: absolute; top: 15px; left: 15px; z-index: 5; background: #4f46e5; color: white; padding: 5px 15px; border-radius: 10px; font-weight: 800; font-size: 0.7rem;">
                            {{ $item->type == 'sale' ? 'RAO BÁN' : 'CHO THUÊ' }}
                        </div>
                        @php
                            $first = $item->images->first()?->image_url;
                            $firstSrc = str_starts_with($first, 'http') ? $first : asset('storage/' . ltrim($first, '/'));
                        @endphp
                        <img src="{{ $firstSrc }}" class="img-full w-100 h-100" id="mainImage" style="object-fit: cover;">
                    </div>

                    <div class="row g-2 mb-4">
                        @foreach($item->images as $index => $img)
                            @php
                                $src = str_starts_with($img->image_url, 'http') ? $img->image_url : asset('storage/' . ltrim($img->image_url, '/'));
                            @endphp
                            <div class="col-3">
                                <img src="{{ $src }}" class="img-grid-item w-100 {{ $index == 0 ? 'active' : '' }}" 
                                     style="object-fit: cover; cursor: pointer;" onclick="changeImage(this, '{{ $src }}')">
                            </div>
                        @endforeach
                    </div>

                    {{-- User Card --}}
                    <div class="p-4 rounded-4 border bg-white shadow-sm d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-900" style="width: 55px; height: 55px; font-size: 1.2rem;">
                                {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h6 class="fw-800 text-dark mb-0">{{ $item->user->name ?? 'Người đăng' }}</h6>
                            <div class="small text-muted"><i class="fas fa-clock me-1"></i>Ngày đăng: {{ $item->created_at?->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="card-footer p-4 bg-light-subtle border-top d-flex justify-content-between">
            <a href="{{ $backRoute }}" class="btn btn-action btn-light shadow-sm">
                <i class="fas fa-chevron-left me-2"></i> Quay lại
            </a>
            <div class="d-flex gap-2">
                <form action="{{ route('destroy-sale-post-admin', $item->id) }}" method="POST" class="m-0 delete-form">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-action btn-outline-danger btn-open-delete-modal" data-id="{{ $item->id }}" data-title="{{ e($item->title) }}">
                        Gỡ tin đăng
                    </button>
                </form>
                
                <a href="{{ route('edit-sale-post-admin', $item->id) }}" class="btn btn-action btn-outline-warning">
                    Sửa nội dung
                </a>

                @if(!$item->status)
                    <form action="{{ route('approve-sale-post-admin', $item->id) }}" method="POST" class="m-0">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-action btn-primary shadow-lg" style="background: #4f46e5; border: none; padding-left: 40px; padding-right: 40px;">
                            PHÊ DUYỆT NGAY
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Hàm đổi ảnh (giữ nguyên của bạn)
    function changeImage(element, src) {
        const mainImg = document.getElementById('mainImage');
        mainImg.style.opacity = '0.5';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 150);
        document.querySelectorAll('.img-grid-item').forEach(img => img.classList.remove('active'));
        element.classList.add('active');
    }

    // --- MÃ MỚI CHO NÚT XÓA ---
    document.addEventListener('click', function(e) {
        // Kiểm tra xem có bấm đúng vào nút "Gỡ tin đăng" không
        const deleteBtn = e.target.closest('.btn-open-delete-modal');
        
        if (deleteBtn) {
            e.preventDefault();
            const form = deleteBtn.closest('form');
            const title = deleteBtn.getAttribute('data-title') || 'bài đăng này';

            Swal.fire({
                title: 'Xác nhận gỡ tin?',
                text: `Bạn có chắc chắn muốn xóa: "${title}"? Hành động này không thể hoàn tác!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Đồng ý, gỡ tin!',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Nếu dùng Unpoly (giống trang index)
                    if (window.up) {
                        up.submit(form);
                    } else {
                        form.submit();
                    }
                }
            });
        }
    });
</script>
@endpush

@endif
@endsection