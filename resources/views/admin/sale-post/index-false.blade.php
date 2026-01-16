@extends('admin.layout')

@section('content')
@php
    // Tự động nhận diện danh sách bài đăng từ Controller (salePosts hoặc rentPosts)
    $items = $salePosts ?? $rentPosts ?? collect();
    $title = (request()->routeIs('*false*')) ? 'Danh sách chờ duyệt' : 'Danh sách đã đăng';
@endphp

<style>
    .price-text-pending { color: #4f46e5; font-weight: 800; font-size: 1.05rem; }
    .user-avatar { width: 32px; height: 32px; font-size: 11px; font-weight: bold; background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .thumb-wrapper { width: 80px; height: 60px; overflow: hidden; border-radius: 12px; background: #f1f5f9; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; position: relative; }
    .thumb-img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
    .thumb-wrapper:hover .thumb-img { transform: scale(1.1); }
    .badge-type { position: absolute; top: 2px; right: 2px; font-size: 8px; padding: 2px 5px; border-radius: 4px; z-index: 10; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-800 mb-1 text-dark" style="letter-spacing: -1px;">{{ $title }}</h2>
        <p class="text-muted small mb-0">
            <i class="fas fa-filter me-1 text-primary"></i> 
            Đang xem: <span class="fw-bold text-dark text-uppercase">{{ $type === 'sale' ? 'Bất động sản Bán' : 'Bất động sản Cho Thuê' }}</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <div class="btn-group shadow-sm bg-white p-1" style="border-radius: 12px;">
            <a href="{{ route(Route::currentRouteName(), ['type' => 'sale']) }}" class="btn btn-sm {{ $type == 'sale' ? 'btn-dark' : 'btn-light' }} fw-bold" up-follow up-target=".main-content" style="border-radius: 8px;">Bán</a>
            <a href="{{ route(Route::currentRouteName(), ['type' => 'rent']) }}" class="btn btn-sm {{ $type == 'rent' ? 'btn-dark' : 'btn-light' }} fw-bold" up-follow up-target=".main-content" style="border-radius: 8px;">Thuê</a>
        </div>
        <a href="{{ route('create-sale-post-admin') }}" class="btn btn-primary px-4 shadow-sm fw-bold d-flex align-items-center" style="border-radius: 12px;">
            <i class="fas fa-plus me-2"></i>Tạo mới
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 24px; overflow: hidden;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-4 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Hình ảnh</th>
                        <th class="py-4 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Thông tin chi tiết</th>
                        <th class="py-4 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Giá & Diện tích</th>
                        <th class="py-4 text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Người đăng</th>
                        <th class="py-4 text-muted fw-bold text-center" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $post)
                    <tr>
                        <td class="ps-4">
                            <div class="thumb-wrapper shadow-sm">
                                <span class="badge badge-type {{ $post->type == 'sale' ? 'bg-primary' : 'bg-info' }}">
                                    {{ $post->type == 'sale' ? 'Bán' : 'Thuê' }}
                                </span>
                                @php 
                                    $firstImage = $post->images->first()?->image_url;
                                    $src = $firstImage 
                                           ? (str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . ltrim($firstImage, '/')))
                                           : 'https://placehold.co/600x400?text=No+Image';
                                @endphp
                                <img src="{{ $src }}" class="thumb-img" onerror="this.src='https://placehold.co/600x400?text=Error+Image'">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-1">{{ Str::limit($post->title, 50) }}</div>
                            
                            {{-- PHẦN CẬP NHẬT: Thêm Badge Loại hình tại đây --}}
                            <div class="mb-2 d-flex gap-1">
                                @php
                                    $catLabel = match($post->category) {
                                        'apartment' => ['text' => 'Căn hộ', 'class' => 'bg-primary-subtle text-primary'],
                                        'house'     => ['text' => 'Nhà phố', 'class' => 'bg-info-subtle text-info'],
                                        'land'      => ['text' => 'Đất nền', 'class' => 'bg-success-subtle text-success'],
                                        default     => ['text' => 'Khác', 'class' => 'bg-secondary-subtle text-secondary'],
                                    };
                                @endphp
                                <span class="badge {{ $catLabel['class'] }} border-0 fw-bold" style="font-size: 0.6rem; padding: 3px 8px;">
                                    <i class="fas fa-tag me-1"></i>{{ $catLabel['text'] }}
                                </span>
                            </div>

                            <small class="text-muted d-flex align-items-center">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                {{ Str::limit($post->address, 40) }}
                            </small>
                        </td>
                        <td>
                            <div class="price-text-pending">
                                @if($post->type == 'sale')
                                    {{ $post->price >= 1000000000 ? number_format($post->price/1000000000, 2).' tỷ' : number_format($post->price/1000000).' triệu' }}
                                @else
                                    {{ number_format($post->price/1000000, 1) }} tr/tháng
                                @endif
                            </div>
                            <div class="badge bg-light text-muted fw-bold border-0 mt-1" style="font-size: 0.65rem;">
                                {{ $post->area }} m²
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center me-2">
                                    {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="small fw-bold text-dark">{{ $post->user->name ?? 'Admin' }}</div>
                                    <div class="text-muted" style="font-size: 10px;">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="pe-4 text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @if($post->status == 0)
                                <form action="{{ route('approve-sale-post-admin', $post->id) }}" method="POST" up-submit up-target=".main-content, #admin-sidebar-nav">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success px-3 fw-800 shadow-sm" style="border-radius: 8px;" onclick="return confirm('Phê duyệt tin đăng này?')">
                                        <i class="fas fa-check-circle me-1"></i> DUYỆT
                                    </button>
                                </form>
                                @endif

                                <div class="btn-group shadow-sm border rounded-3 overflow-hidden">
                                    <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn btn-sm btn-white bg-white" up-follow up-target=".main-content"><i class="fas fa-eye text-primary"></i></a>
                                    <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-sm btn-white bg-white" up-follow up-target=".main-content"><i class="fas fa-pen-nib text-warning"></i></a>
                                    <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn tin đăng này?')" up-submit up-target=".main-content, #admin-sidebar-nav">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-white bg-white"><i class="fas fa-trash-alt text-danger"></i></button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-light mb-3"></i>
                            <h5 class="text-muted fw-bold">Trống trải quá...</h5>
                            <p class="text-muted small">Không tìm thấy bài đăng nào trong mục này.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center" up-nav>
    {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endsection