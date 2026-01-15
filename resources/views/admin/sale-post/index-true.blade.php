@extends('admin.layout')

@section('content')
<style>
    .status-online { width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .price-text { color: #4f46e5; font-weight: 800; font-size: 1.05rem; }
    .badge-info-custom { background-color: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; padding: 5px 10px; border-radius: 8px; font-weight: 600; }
    .btn-action { background: #fff; border: 1px solid #e2e8f0; color: #64748b; transition: 0.2s; }
    .btn-action:hover { background: #f8fafc; color: #4f46e5; border-color: #4f46e5; }
    .btn-delete:hover { color: #ef4444; border-color: #ef4444; background: #fef2f2; }
    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; background: #f8fafc; padding: 1.25rem 1rem; }
    
    /* Fix khung ảnh: Đảm bảo ảnh luôn đẹp, không méo */
    .post-thumb-container { width: 70px; height: 50px; border-radius: 10px; overflow: hidden; background: #f1f5f9; display: inline-block; border: 1px solid #e2e8f0; position: relative; }
    .post-thumb { width: 100%; height: 100%; object-fit: cover; transition: 0.4s ease; }
    .post-thumb-container:hover .post-thumb { transform: scale(1.15); }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .animate-pulse { animation: pulse 2s infinite; }
</style>

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-800 mb-0 text-dark" style="letter-spacing: -1px;">Tin Đang Hiển Thị</h2>
        <p class="text-muted small mb-0"><i class="fas fa-check-double text-success me-1"></i> Quản lý các bất động sản đã được phê duyệt và đang công khai</p>
    </div>
    <a href="{{ route('create-sale-post-admin') }}" 
       class="btn btn-primary px-4 shadow-sm fw-bold d-flex align-items-center" 
       style="border-radius: 12px; height: 48px;">
        <i class="fas fa-plus me-2"></i> Tạo bài mới
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center px-4">ID</th>
                    <th>Thông tin BĐS</th>
                    <th>Giá & Diện tích</th>
                    <th>Thông số</th>
                    <th class="text-center">Hình ảnh</th>
                    <th class="text-center pe-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rentPosts as $post)
                    <tr>
                        <td class="text-center text-muted fw-bold px-4">#{{ $post->id }}</td>
                        <td style="max-width: 320px;">
                            <div class="fw-bold text-dark mb-1 text-truncate" title="{{ $post->title }}">{{ $post->title }}</div>
                            <div class="d-flex align-items-center small text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1 text-primary"></i> 
                                <span class="text-truncate">{{ Str::limit($post->address, 40) }}</span>
                            </div>
                            <div class="d-flex align-items-center small text-success fw-bold">
                                <span class="status-online animate-pulse"></span> Công khai
                            </div>
                        </td>
                        <td>
                            <div class="price-text mb-1">{{ number_format($post->price) }} đ</div>
                            <div class="badge bg-light text-secondary border fw-semibold px-2 py-1" style="font-size: 0.75rem;">
                                <i class="fas fa-vector-square me-1"></i>{{ $post->area }} m²
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge badge-info-custom" title="Phòng ngủ"><i class="fas fa-bed me-1"></i>{{ $post->bedrooms }}</span>
                                <span class="badge badge-info-custom" title="Phòng tắm"><i class="fas fa-bath me-1"></i>{{ $post->bathrooms }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="post-thumb-container shadow-sm">
                                @php 
                                    $firstImage = $post->images->first()?->image_url;
                                    // Fallback logic cho cả Seeder (link http) và Upload (link storage)
                                    $src = $firstImage 
                                           ? (str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . ltrim($firstImage, '/')))
                                           : 'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg?auto=compress&cs=tinysrgb&w=150';
                                @endphp

                                <img src="{{ $src }}" 
                                     class="post-thumb" 
                                     referrerpolicy="no-referrer"
                                     onerror="this.src='https://placehold.co/70x50?text=No+Img'">
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group shadow-sm border rounded-3 overflow-hidden">
                                <a href="{{ route('show-sale-post-admin', $post->id) }}" 
                                   class="btn btn-sm btn-action px-3" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                                <a href="{{ route('edit-sale-post-admin', $post->id) }}" 
                                   class="btn btn-sm btn-action px-3" 
                                   title="Chỉnh sửa">
                                    <i class="fas fa-pen text-warning"></i>
                                </a>
                                <form action="{{ route('destroy-sale-post-admin', $post->id) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin đăng này?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action btn-delete px-3" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-light mb-3"></i>
                            <h5 class="text-muted">Chưa có bài viết nào được phê duyệt.</h5>
                            <p class="small text-muted">Vui lòng kiểm tra danh sách chờ duyệt hoặc tạo bài mới.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Thêm phân trang nếu cần --}}
@if(method_exists($rentPosts, 'links'))
    <div class="mt-4 d-flex justify-content-center">
        {{ $rentPosts->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection