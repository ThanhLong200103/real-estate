<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tin đăng - Bất động sản</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8faff; font-family: 'Plus Jakarta Sans', sans-serif; color: #2d3436; }
        .manage-wrapper { max-width: 1100px; margin: 40px auto; padding: 0 15px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h3 { font-weight: 800; color: #6c5ce7; margin: 0; }

        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #edf2f7; display: flex; align-items: center; gap: 15px; }
        .stat-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; }

        .main-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #edf2f7; overflow: hidden; }
        .table { margin-bottom: 0; }
        .table thead { background: #f8faff; }
        .table thead th { border: none; padding: 15px 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #a0aec0; }
        .table tbody td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid #f1f2f6; }

        .post-inline-img { width: 80px; height: 55px; border-radius: 8px; object-fit: cover; }
        .status-badge { padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .bg-pending { background: #fffaf0; color: #dd6b20; }
        .bg-active { background: #e6fffa; color: #319795; }

        .btn-action { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; text-decoration: none; border: none; }
        .btn-edit { background: #edf2ff; color: #4c51bf; }
        .btn-edit:hover { background: #4c51bf; color: white; }
        .btn-delete { background: #fff5f5; color: #e53e3e; }
        .btn-delete:hover { background: #e53e3e; color: white; }
        
        /* Pagination custom */
        .pagination { justify-content: center; gap: 5px; }
        .page-link { border: none; border-radius: 8px !important; color: #6c5ce7; font-weight: 600; }
        .page-item.active .page-link { background-color: #6c5ce7; }
    </style>
</head>
<body>

@php
    /** Logic xử lý ảnh dự phòng */
    $convertImage = function($path) {
        if (!$path) return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80';
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        return asset('storage/' . $path);
    };
@endphp

<div class="manage-wrapper">
    <div class="page-header">
        <div>
            <a href="{{ route('home') }}" class="text-decoration-none text-muted small fw-bold text-uppercase">
                <i class="fas fa-arrow-left me-1"></i> Trang chủ
            </a>
            <h3 class="mt-1">Quản lý tin đăng</h3>
        </div>
        <a href="{{ route('create-sale-post') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm" style="background: #6c5ce7; border: none;">
            <i class="fas fa-plus me-2"></i> Đăng tin mới
        </a>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background: #eef2ff; color: #6c5ce7;"><i class="fas fa-layer-group"></i></div>
            <div>
                <h4 class="m-0 fw-bold">{{ $myPosts->total() }}</h4>
                <small class="text-muted">Tổng bài đăng</small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #e6fffa; color: #319795;"><i class="fas fa-check-circle"></i></div>
            <div>
                <h4 class="m-0 fw-bold">{{ $myPosts->where('status', 1)->count() }}</h4>
                <small class="text-muted">Đã duyệt</small>
            </div>
        </div>
    </div>

    <div class="main-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Thông tin bất động sản</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myPosts as $post)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @php $firstImg = $post->images->first(); @endphp
                                <img src="{{ $convertImage($firstImg->image_url ?? null) }}" 
                                     class="post-inline-img" 
                                     onerror="this.src='https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-1.2.1&auto=format&fit=crop&w=300&q=80'">
                                <div>
                                    <a href="{{ route('user-sale-post-show', $post->id) }}" class="text-dark fw-bold text-decoration-none d-block mb-1">
                                        {{ Str::limit($post->title, 50) }}
                                    </a>
                                    <span class="text-primary fw-bold small">{{ number_format($post->price) }} VNĐ</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">
                            {{ $post->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($post->status)
                                <span class="status-badge bg-active">Đã duyệt</span>
                            @else
                                <span class="status-badge bg-pending">Đang đợi</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('user-edit-sale-post', $post->id) }}" class="btn-action btn-edit me-1" title="Chỉnh sửa">
                                <i class="fas fa-pen-alt small"></i>
                            </a>
                            <form action="{{ route('user-destroy-sale-post', $post->id) }}" method="POST" class="d-inline">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tin đăng này?')">
                                    <i class="fas fa-trash-alt small"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-folder-open text-muted opacity-25" style="font-size: 4rem;"></i>
                            </div>
                            <p class="text-muted fw-bold">Bạn chưa có bài đăng nào.</p>
                            <a href="{{ route('create-sale-post') }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">Tạo tin ngay</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $myPosts->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>