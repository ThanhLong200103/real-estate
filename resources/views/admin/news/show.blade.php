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

    /* Sidebar logic */
    .sidebar { background-color: var(--sidebar-bg); }
    .sidebar .nav-link { color: #94a3b8 !important; border-radius: 8px; margin-bottom: 5px; padding: 12px 15px; transition: 0.2s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: var(--sidebar-hover); color: #fff !important; }
    .sidebar .nav-link.active { background-color: var(--primary-color) !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

    /* Content Styling */
    .main-content { background-color: var(--bg-body); }
    .card-detail { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .content-box { line-height: 1.8; color: #334155; white-space: pre-wrap; }
    .img-preview { object-fit: cover; height: 180px; width: 100%; border-radius: 8px; transition: 0.3s; }
    .img-preview:hover { transform: scale(1.02); }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4 fw-bold border-bottom pb-3 text-light">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link active">
                    <i class="fas fa-newspaper me-3"></i> Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link">
                    <i class="fas fa-clock me-3"></i> Chờ duyệt
                    <span class="badge bg-danger ms-auto">{{ $pendingPostsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link">
                    <i class="fas fa-check-circle me-3"></i> Đã duyệt
                </a>
                <div class="mt-auto pt-4">
                    <hr class="opacity-20">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100 border-0">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-10 p-4 main-content">
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
                    <a href="{{ route('index-news-admin') }}" class="btn btn-light shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <a href="{{ route('edit-news-admin', $post->id) }}" class="btn btn-warning shadow-sm">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('create-news-admin') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tạo bài mới
                    </a>
                </div>
            </div>

            <div class="card card-detail bg-white p-4 mb-4">
                <div class="row">
                    <div class="col-lg-8 border-end">
                        <div class="mb-3">
                            @if($post->status)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Đã xuất bản</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-file-alt me-1"></i> Bản nháp</span>
                            @endif
                            <span class="text-muted ms-3 small"><i class="far fa-clock me-1"></i> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <h1 class="fw-bold text-dark mb-4">{{ $post->title }}</h1>
                        
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">Mô tả bài viết:</h6>
                        <div class="content-box">
                            {!! nl2br(e($post->description)) !!}
                        </div>
                    </div>

                    <div class="col-lg-4 ps-lg-4 mt-4 mt-lg-0">
                        <h6 class="fw-bold text-uppercase small text-muted mb-3">Hình ảnh đính kèm:</h6>
                        @if($post->images && $post->images->isNotEmpty())
                            <div class="row g-3">
                                @foreach($post->images as $image)
                                    <div class="col-6 col-lg-12">
                                        <div class="card border-0">
                                            <a href="{{ asset('storage/' . $image->image_url) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $image->image_url) }}" 
                                                     class="img-preview shadow-sm border" 
                                                     alt="News Image">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 bg-light rounded border-dashed">
                                <i class="fas fa-image fa-3x text-muted mb-3 d-block"></i>
                                <span class="text-muted small">Không có hình ảnh</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>