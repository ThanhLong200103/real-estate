<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
        --primary-color: #4f46e5;
        --bg-body: #f8fafc;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #1e293b;
    }

    /* --- SIDEBAR --- */
    .sidebar { background-color: var(--sidebar-bg); box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
    .sidebar h4 { letter-spacing: 2px; font-weight: 700; color: #f1f5f9; border-bottom: 1px solid #334155; padding-bottom: 20px; }
    .sidebar .nav-link { border-radius: 8px; margin-bottom: 5px; padding: 12px 15px; transition: 0.2s; font-weight: 500; color: #94a3b8 !important; display: flex; align-items: center; }
    .sidebar .nav-link:hover { background-color: var(--sidebar-hover); color: #fff !important; }
    .sidebar .nav-link.active { background-color: var(--primary-color) !important; color: #fff !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

    /* --- DETAIL STYLING --- */
    .detail-card { border: none; border-radius: 16px; overflow: hidden; background: white; }
    .table-detail th { background-color: #f8fafc; color: #64748b; font-weight: 600; width: 35%; border-left: 4px solid #e2e8f0; }
    .table-detail td { font-weight: 500; color: #1e293b; }
    
    .price-large { font-size: 1.5rem; font-weight: 800; color: #ef4444; letter-spacing: -0.5px; }
    
    .description-box { background-color: #f1f5f9; border-radius: 12px; padding: 20px; line-height: 1.7; color: #334155; border-left: 5px solid var(--primary-color); }
    
    .image-preview { position: relative; border-radius: 12px; overflow: hidden; transition: 0.3s; height: 180px; }
    .image-preview img { width: 100%; height: 100%; object-fit: cover; }
    .image-preview:hover { transform: scale(1.02); }
    
    .badge-status { padding: 8px 16px; border-radius: 50px; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }

    .action-bar { background-color: #fff; border-top: 1px solid #f1f5f9; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link">
                    <i class="fas fa-newspaper me-3"></i> Quản lý Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link {{ !$post->status ? 'active' : '' }}">
                    <i class="fas fa-clock me-3"></i> Duyệt bài đăng 
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link {{ $post->status ? 'active' : '' }}">
                    <i class="fas fa-check-circle me-3"></i> Bài đăng đã duyệt
                </a>
                <div class="mt-auto pt-4">
                    <hr class="opacity-20">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100 py-2 border-0">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <div class="mb-4 d-flex justify-content-between align-items-end">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Bất động sản</a></li>
                            <li class="breadcrumb-item active">Chi tiết tin đăng</li>
                        </ol>
                    </nav>
                    <h2 class="fw-bold text-dark m-0"><i class="fas fa-file-alt text-primary me-2"></i> Thẩm định nội dung</h2>
                </div>
                <div>
                    @if($post->status)
                        <span class="badge bg-success badge-status shadow-sm"><i class="fas fa-globe me-1"></i> Đang hiển thị</span>
                    @else
                        <span class="badge bg-warning text-dark badge-status shadow-sm"><i class="fas fa-shield-alt me-1"></i> Đang chờ duyệt</span>
                    @endif
                </div>
            </div>

            <div class="card detail-card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row g-5">
                        <div class="col-md-7">
                            <h3 class="fw-bold mb-4" style="color: #0f172a;">{{ $post->title }}</h3>
                            
                            <table class="table table-detail align-middle">
                                <tr>
                                    <th><i class="fas fa-money-bill-wave me-2"></i> Giá niêm yết</th>
                                    <td><span class="price-large">{{ number_format($post->price) }} đ</span></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-expand-arrows-alt me-2"></i> Diện tích</th>
                                    <td><span class="fw-bold">{{ $post->area }} m²</span></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-door-open me-2"></i> Cấu trúc</th>
                                    <td>{{ $post->bedrooms }} Phòng ngủ / {{ $post->bathrooms }} Phòng tắm</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-couch me-2"></i> Tình trạng nội thất</th>
                                    <td>
                                        @if($post->is_furnished)
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i> Đầy đủ nội thất</span>
                                        @else
                                            <span class="text-muted"><i class="fas fa-times-circle me-1"></i> Nhà trống</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-map-marked-alt me-2"></i> Vị trí</th>
                                    <td>{{ $post->address }}</td>
                                </tr>
                            </table>

                            <div class="mt-5">
                                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-align-left text-primary me-2"></i>Mô tả chi tiết bài viết</h5>
                                <div class="description-box shadow-sm">
                                    {!! nl2br(e($post->description)) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-dark m-0"><i class="fas fa-camera text-primary me-2"></i>Thư viện ảnh</h5>
                                <span class="badge bg-secondary rounded-pill">{{ $post->images->count() }} ảnh</span>
                            </div>

                            @if($post->images->isNotEmpty())
                                <div class="row g-3">
                                    @foreach($post->images as $image)
                                        <div class="col-6">
                                            <div class="image-preview shadow-sm border">
                                                <a href="{{ asset('storage/' . $image->image_url) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $image->image_url) }}" alt="BĐS Image">
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5 rounded-4 bg-light border border-dashed" style="border: 2px dashed #cbd5e1 !important;">
                                    <i class="fas fa-image fa-4x text-muted opacity-20 mb-3"></i>
                                    <p class="text-muted small fw-bold">Không có hình ảnh đính kèm</p>
                                </div>
                            @endif
                            
                            <div class="alert alert-info border-0 mt-4 small shadow-sm">
                                <i class="fas fa-lightbulb me-2"></i> <strong>Mẹo:</strong> Click vào hình ảnh để xem kích thước đầy đủ trong tab mới.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer action-bar p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <a href="{{ $post->status ? route('index-true-sale-post-admin') : route('index-false-sale-post-admin') }}" class="btn btn-light px-4 py-2 fw-semibold me-2 border">
                                <i class="fas fa-chevron-left me-2"></i>Quay lại danh sách
                            </a>
                            <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-warning px-4 py-2 fw-semibold shadow-sm">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                            </a>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            @if(!$post->status && strcasecmp(Auth::user()->role, 'admin') === 0)
                                <form action="{{ route('approve-sale-post-admin', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow" onclick="return confirm('Phê duyệt tin đăng này lên website công khai?')">
                                        <i class="fas fa-check-double me-2"></i>PHÊ DUYỆT BÀI ĐĂNG
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>