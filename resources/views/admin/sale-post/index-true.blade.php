@extends('admin.layout')

@section('content')
<style>
    /* Trạng thái chờ: Màu vàng cảnh báo */
    .status-pending { width: 8px; height: 8px; background: #f59e0b; border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2); }
    .price-text-pending { color: #f59e0b; font-weight: 800; font-size: 1.05rem; }
    .badge-info-custom { background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a; padding: 5px 10px; border-radius: 8px; font-weight: 600; }
    
    .btn-action { background: #fff; border: 1px solid #e2e8f0; color: #64748b; transition: 0.2s; }
    .btn-action:hover { background: #f8fafc; color: #4f46e5; border-color: #4f46e5; }
    
    /* Nút duyệt bài nổi bật */
    .btn-approve { background: #10b981; color: white; border: none; font-weight: 700; transition: 0.3s; }
    .btn-approve:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); color: white; }

    .table thead th { border-top: none; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; background: #fffbeb; padding: 1.25rem 1rem; color: #92400e; }
    
    .post-thumb-container { width: 70px; height: 50px; border-radius: 10px; overflow: hidden; background: #f1f5f9; display: inline-block; border: 1px solid #e2e8f0; position: relative; }
    .post-thumb { width: 100%; height: 100%; object-fit: cover; }

    @keyframes pulse-warn {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    .animate-pulse-warn { animation: pulse-warn 2s infinite; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-800 mb-0 text-dark" style="letter-spacing: -1px;">Yêu Cầu Chờ Duyệt</h2>
            <p class="text-muted small mb-0"><i class="fas fa-clock text-warning me-1"></i> Có <strong>{{ $rentPosts->total() }}</strong> tin đăng cần bạn kiểm tra và phê duyệt</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('index-true-sale-post-admin') }}" class="btn btn-light border px-4 fw-bold" style="border-radius: 12px; height: 48px; display: flex; align-items: center;">
                Xem tin đã đăng
            </a>
        </div>
    </div>

    {{-- Hiển thị thông báo --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="d-flex">
                <i class="fas fa-exclamation-circle mt-1 me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Rất tiếc! Có lỗi xảy ra:</h6>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center px-4">ID</th>
                        <th>Thông tin BĐS</th>
                        <th>Giá & Diện tích</th>
                        <th>Người đăng</th>
                        <th class="text-center">Hình ảnh</th>
                        <th class="text-center pe-4">Phê duyệt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentPosts as $post)
                        <tr>
                            <td class="text-center text-muted fw-bold px-4">#{{ $post->id }}</td>
                            <td style="max-width: 300px;">
                                <div class="fw-bold text-dark mb-1 text-truncate">{{ $post->title }}</div>
                                
                                <div class="d-flex align-items-center small text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> 
                                    <span class="text-truncate">{{ Str::limit($post->address, 35) }}</span>
                                </div>

                                {{-- PHẦN CẬP NHẬT: Badge Loại hình theo yêu cầu --}}
                                <div class="mb-2">
                                    @php
                                        $categoryName = $post->category->name ?? 'Khác';
                                        $icon = match($categoryName) {
                                            'Căn hộ'  => 'fa-building',
                                            'Nhà phố' => 'fa-home',
                                            'Đất nền' => 'fa-map-marked-alt',
                                            default   => 'fa-tag',
                                        };
                                    @endphp
                                    <span class="badge bg-white text-dark border fw-bold shadow-sm" style="font-size: 0.65rem; padding: 4px 8px;">
                                        <i class="fas {{ $icon }} me-1 text-primary"></i>{{ $categoryName }}
                                    </span>
                                </div>

                                <div class="d-flex align-items-center small text-warning fw-bold">
                                    <span class="status-pending animate-pulse-warn"></span> Đang chờ duyệt
                                </div>
                            </td>
                            <td>
                                <div class="price-text-pending mb-1">{{ number_format($post->price) }} đ</div>
                                <div class="badge bg-light text-secondary border fw-semibold px-2 py-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-vector-square me-1"></i>{{ $post->area }} m²
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info-subtle rounded-circle text-center me-2" style="width: 32px; height: 32px; line-height: 32px; background: #e0f2fe; color: #0369a1; font-weight: bold; font-size: 12px;">
                                        {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="small fw-bold text-dark">{{ $post->user->name ?? 'Khách hàng' }}</div>
                                        <div class="text-muted" style="font-size: 10px;">{{ $post->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="post-thumb-container shadow-sm">
                                    @php 
                                        $firstImage = $post->images->first()?->image_url;
                                        $src = $firstImage 
                                               ? (str_starts_with($firstImage, 'http') ? $firstImage : asset('storage/' . ltrim($firstImage, '/')))
                                               : 'https://placehold.co/70x50?text=No+Img';
                                    @endphp
                                    <img src="{{ $src }}" class="post-thumb" onerror="this.src='https://placehold.co/70x50?text=Error'">
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('approve-sale-post-admin', $post->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        
                                    </form>
                                    
                                    <div class="btn-group border rounded-3 overflow-hidden">
                                        <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn btn-sm btn-action"><i class="fas fa-eye text-primary"></i></a>
                                        <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-sm btn-action"><i class="fas fa-pen text-warning"></i></a>
                                        <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Từ chối và xóa tin này?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-action"><i class="fas fa-trash-alt text-danger"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3"><i class="fas fa-check-circle fa-4x text-success opacity-20"></i></div>
                                <h5 class="fw-bold text-dark">Sạch bóng tin chờ!</h5>
                                <p class="text-muted small">Hiện tại không có yêu cầu phê duyệt nào mới.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($rentPosts, 'links'))
        <div class="mt-4 d-flex justify-content-center">
            {{ $rentPosts->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection