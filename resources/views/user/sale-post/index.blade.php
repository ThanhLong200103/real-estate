<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tin đăng của tôi - EstateHub</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-light: #a29bfe;
            --dark: #2d3436;
            --light-bg: #f8faff;
            --success: #00b894;
            --warning: #fdcb6e;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--dark);
        }

        /* Header Đồng Bộ Hero-style */
        .hero-banner-mini {
            background: var(--dark);
            background-image: linear-gradient(rgba(45, 52, 54, 0.85), rgba(45, 52, 54, 0.85)), 
                              url('https://images.unsplash.com/photo-1460472178825-e5250fb13e8d?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 60px 0 100px 0;
            color: white;
            margin-bottom: -50px;
        }

        .nav-actions {
            display: flex; gap: 12px; background: rgba(255,255,255,0.1);
            padding: 8px; border-radius: 50px; backdrop-filter: blur(10px);
        }

        .btn-custom {
            padding: 10px 20px; border-radius: 50px; font-weight: 600; font-size: 14px;
            transition: 0.3s; border: none; display: flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-glass { background: rgba(255,255,255,0.2); color: white; }
        .btn-glass:hover { background: white; color: var(--primary); }
        .btn-primary-custom { background: var(--primary); color: white; }

        /* Stats Section */
        .stats-container { position: relative; z-index: 10; margin-bottom: 30px; }
        .stat-card {
            background: white; padding: 25px; border-radius: 24px;
            border: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px;
            transition: 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(108, 92, 231, 0.1); }
        .stat-icon {
            width: 60px; height: 60px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center; font-size: 24px;
        }

        /* Main Content Table */
        .main-card {
            background: white; border-radius: 30px; overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 20px 40px rgba(0,0,0,0.03);
        }
        .table thead th {
            background: #fcfcff; border: none; padding: 20px 25px;
            font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #a0aec0; font-weight: 800;
        }
        .table tbody td { padding: 20px 25px; vertical-align: middle; border-bottom: 1px solid #f8faff; }

        /* UI Elements */
        .post-inline-img { 
            width: 100px; height: 70px; border-radius: 15px; 
            object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.08); 
        }
        .status-badge {
            padding: 6px 14px; border-radius: 12px; font-size: 11px; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .bg-active { background: #e6fffa; color: #00b894; }
        .bg-pending { background: #fffaf0; color: #fdcb6e; }

        .price-text { color: var(--primary); font-weight: 800; font-size: 16px; }
        .btn-action {
            width: 40px; height: 40px; border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: 0.3s; text-decoration: none; border: none;
        }
        .btn-edit { background: #f0f1ff; color: var(--primary); }
        .btn-edit:hover { background: var(--primary); color: white; }
        .btn-delete { background: #fff5f5; color: #ff7675; }
        .btn-delete:hover { background: #ff7675; color: white; }
    </style>
</head>
<body>

@php
    $getThumbnail = function($post) {
        $firstImg = $post->images->first();
        if (!$firstImg) return 'https://placehold.co/600x400?text=EstateHub';
        $path = $firstImg->image_url;
        return filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $path);
    };

    $formatPrice = function($price) {
        if ($price >= 1000000000) return number_format($price / 1000000000, 1, ',', '.') . ' Tỷ';
        if ($price >= 1000000) return number_format($price / 1000000, 0, ',', '.') . ' Triệu';
        return number_format($price) . ' đ';
    };
@endphp

<header class="hero-banner-mini">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="fw-800 m-0 text-white" style="cursor: pointer; letter-spacing: -1px;" onclick="window.location='{{ route('home') }}'">
                ESTATE<span class="text-info">HUB</span>
            </h2>
            <div class="nav-actions">
                <a href="{{ route('home') }}" class="btn-custom btn-glass"><i class="fas fa-home"></i></a>
                <a href="{{ route('create-sale-post') }}" class="btn-custom btn-primary-custom px-4"><i class="fas fa-plus"></i> Đăng tin</a>
                <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass"><i class="fas fa-comment-dots"></i></a>
                <a href="{{ route('favorite.index') }}" class="btn-custom btn-glass"><i class="fas fa-heart"></i></a>
            </div>
        </div>
        <h1 class="fw-800 mb-2">Quản lý tin đăng</h1>
        <p class="opacity-75 fw-500">Theo dõi hiệu quả và trạng thái các bất động sản của bạn</p>
    </div>
</header>

<main class="container stats-container">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-lg mb-4 p-3 rounded-4 d-flex align-items-center animate__animated animate__fadeIn">
            <i class="fas fa-check-circle fs-4 me-3"></i> 
            <span class="fw-bold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: #f0f1ff; color: var(--primary);"><i class="fas fa-layer-group"></i></div>
                <div>
                    <h3 class="m-0 fw-800">{{ $myPosts->total() }}</h3>
                    <small class="text-muted fw-700">Tổng bài đăng</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e6fffa; color: var(--success);"><i class="fas fa-globe-asia"></i></div>
                <div>
                    <h3 class="m-0 fw-800">{{ $myPosts->where('status', 1)->count() }}</h3>
                    <small class="text-muted fw-700">Đang hiển thị</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fffaf0; color: var(--warning);"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h3 class="m-0 fw-800">{{ $myPosts->where('status', 0)->count() }}</h3>
                    <small class="text-muted fw-700">Đang đợi duyệt</small>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Bất động sản</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myPosts as $post)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-4">
                                <img src="{{ $getThumbnail($post) }}" class="post-inline-img">
                                <div>
                                    <div class="d-flex gap-2 mb-1">
                                        <span class="badge rounded-pill {{ $post->type == 'sale' ? 'bg-primary' : 'bg-info' }}" style="font-size: 9px;">
                                            {{ $post->type == 'sale' ? 'BÁN' : 'CHO THUÊ' }}
                                        </span>
                                        <span class="text-muted fw-700" style="font-size: 10px; text-transform: uppercase;">
                                            <i class="fas fa-tag me-1"></i> {{ $post->category->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('user-sale-post-show', $post->id) }}" class="text-dark fw-800 text-decoration-none d-block mb-1">
                                        {{ Str::limit($post->title, 50) }}
                                    </a>
                                    <div class="price-text">{{ $formatPrice($post->price) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-700 small">{{ $post->created_at->format('d/m/Y') }}</div>
                            <div class="text-muted small">{{ $post->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            @if($post->status)
                                <span class="status-badge bg-active"><i class="fas fa-check-circle"></i> Hiển thị</span>
                            @else
                                <span class="status-badge bg-pending"><i class="fas fa-clock"></i> Chờ duyệt</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('user-edit-sale-post', $post->id) }}" class="btn-action btn-edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('user-destroy-sale-post', $post->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="opacity-25 mb-3">
                            <h5 class="fw-800 text-muted">Chưa có bài đăng nào</h5>
                            <a href="{{ route('create-sale-post') }}" class="btn btn-primary-custom btn-custom mt-3 mx-auto">Đăng bài ngay</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $myPosts->links() }}
    </div>
</main>

@include('layouts.footer')