<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $rentPosts->title }} - Chi tiết tin đăng</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { 
            --primary-color: #6c5ce7; 
            --bg-light: #f8faff; 
            --text-dark: #2d3436; 
            --text-muted: #636e72;
        }
        
        body { background-color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-dark); margin: 0; padding: 0; }
        .property-container { max-width: 1140px; margin: 20px auto; padding: 0 15px; }

        /* --- Thanh thông báo chờ duyệt --- */
        .pending-banner {
            background: linear-gradient(90deg, #ff9f43, #ffb142);
            color: white;
            padding: 12px 0;
            font-weight: 700;
            font-size: 14px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(255, 159, 67, 0.2);
        }

        /* --- Navigation Section --- */
        .nav-top-wrapper { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            background: white;
            border: 1px solid #edf2f7;
            border-radius: 50px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            background: var(--bg-light);
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateX(-4px);
        }

        /* --- Header & Gallery --- */
        .prop-title { font-size: 32px; font-weight: 800; color: #1a1a1a; margin-bottom: 8px; }
        .prop-meta { display: flex; align-items: center; gap: 15px; font-size: 14px; color: var(--text-muted); }

        .gallery-grid { 
            display: grid; 
            grid-template-columns: 2fr 1fr; 
            grid-template-rows: 220px 220px; 
            gap: 12px; 
            border-radius: 24px; 
            overflow: hidden; 
            margin-bottom: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            background-color: #f1f1f1;
        }
        .gallery-item-main { grid-row: span 2; }
        .gallery-img { width: 100%; height: 100%; object-fit: cover; transition: 0.4s ease; cursor: pointer; }
        .gallery-img:hover { transform: scale(1.02); filter: brightness(0.95); }

        /* --- Info Sections --- */
        .price-tag { font-size: 32px; font-weight: 800; color: #e74c3c; margin-bottom: 20px; display: block; }
        
        .spec-card {
            background: var(--bg-light);
            border: 1px solid #edf2f7;
            border-radius: 20px;
            padding: 24px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 35px;
        }
        .spec-item { text-align: center; border-right: 1px solid #e2e8f0; }
        .spec-item:last-child { border-right: none; }
        .spec-item i { display: block; font-size: 24px; color: var(--primary-color); margin-bottom: 8px; }
        .spec-item strong { font-size: 16px; color: var(--text-dark); display: block; }

        /* --- Sidebar Card --- */
        .sidebar-card {
            border: 1px solid #edf2f7;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.06);
            position: sticky;
            top: 80px;
            background: white;
        }
        .btn-contact { background: var(--primary-color); color: white; border: none; padding: 15px; border-radius: 14px; font-weight: 700; width: 100%; transition: 0.3s; margin-bottom: 12px; }
        .btn-contact:hover { background: #5a4bcf; color: white; transform: translateY(-3px); }
        
        .btn-phone { background: #00b894; color: white; border: none; padding: 15px; border-radius: 14px; font-weight: 700; width: 100%; display: inline-block; text-align: center; text-decoration: none; transition: 0.3s; }
        .btn-phone:hover { background: #00a383; color: white; transform: translateY(-3px); }

        .pulse { animation: pulse-animation 2s infinite; }
        @keyframes pulse-animation {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

@php
    /**
     * LOGIC XỬ LÝ ẢNH THÔNG MINH
     */
    $convertImage = function($path) {
        if (!$path) return 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=80';
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        if (str_starts_with($path, 'images/')) return asset($path);
        return asset('storage/' . $path);
    };
    $imgs = $rentPosts->images;
@endphp

{{-- THÔNG BÁO TRẠNG THÁI --}}
@if(!$rentPosts->status)
    <div class="pending-banner">
        <div class="container d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-clock me-2 pulse"></i> 
                TIN ĐANG CHỜ DUYỆT: Nội dung này hiện chỉ có bạn và Quản trị viên nhìn thấy.
            </span>
            @if(auth()->check() && strcasecmp(auth()->user()->role, 'admin') === 0)
                <form action="{{ route('approve-sale-post-admin', $rentPosts->id) }}" method="POST" class="m-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-light btn-sm fw-bold px-4 rounded-pill">
                        Duyệt bài ngay
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif

<div class="property-container">
    {{-- NAVIGATION --}}
    <div class="nav-top-wrapper">
        <a href="javascript:history.back()" class="back-link">
            <i class="fas fa-chevron-left me-2"></i> Quay lại
        </a>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết tin đăng</li>
            </ol>
        </nav>
    </div>

    {{-- HEADER --}}
    <div class="prop-header mb-4">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <h1 class="prop-title">{{ $rentPosts->title }}</h1>
            <div class="text-end">
                <span class="badge {{ $rentPosts->status ? 'bg-success' : 'bg-warning' }} px-3 py-2 rounded-pill shadow-sm mb-2">
                    {{ $rentPosts->status ? 'Đã xác thực' : 'Đang chờ duyệt' }}
                </span>
                @if(auth()->id() === (int)$rentPosts->user_id)
                    <br>
                    <a href="{{ route('user-edit-sale-post', $rentPosts->id) }}" class="small fw-bold text-primary text-decoration-none">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa tin của bạn
                    </a>
                @endif
            </div>
        </div>
        <div class="prop-meta mt-2">
            <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $rentPosts->address }}</span>
            <span>•</span>
            <span><i class="far fa-calendar-alt me-1"></i> {{ $rentPosts->created_at->format('d/m/Y') }}</span>
        </div>
    </div>

    {{-- GALLERY --}}
    <div class="gallery-grid">
        <div class="gallery-item-main">
            <img src="{{ $convertImage($imgs[0]->image_url ?? null) }}" 
                 class="gallery-img" 
                 onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1200&q=80'">
        </div>
        <div>
            <img src="{{ $convertImage($imgs[1]->image_url ?? ($imgs[0]->image_url ?? null)) }}" 
                 class="gallery-img"
                 onerror="this.src='https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'">
        </div>
        <div class="position-relative">
            <img src="{{ $convertImage($imgs[2]->image_url ?? ($imgs[0]->image_url ?? null)) }}" 
                 class="gallery-img"
                 onerror="this.src='https://images.unsplash.com/photo-1448630360428-6542e085c95e?auto=format&fit=crop&w=800&q=80'">
            @if($imgs->count() > 3)
                <div class="position-absolute bottom-0 end-0 m-3 px-3 py-2 bg-dark text-white rounded-4 opacity-75 small fw-bold">
                    <i class="fas fa-images me-1"></i> +{{ $imgs->count() - 3 }} ảnh
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- NỘI DUNG CHI TIẾT --}}
        <div class="col-lg-8 pe-lg-5">
            <div class="price-tag">{{ number_format($rentPosts->price) }} VNĐ</div>

            <div class="spec-card shadow-sm">
                <div class="spec-item">
                    <i class="fas fa-expand-arrows-alt"></i>
                    <small class="text-muted d-block text-uppercase" style="font-size: 10px; font-weight: 700;">Diện tích</small>
                    <strong>{{ $rentPosts->area }} m²</strong>
                </div>
                <div class="spec-item">
                    <i class="fas fa-bed"></i>
                    <small class="text-muted d-block text-uppercase" style="font-size: 10px; font-weight: 700;">Phòng ngủ</small>
                    <strong>{{ $rentPosts->bedrooms }} PN</strong>
                </div>
                <div class="spec-item">
                    <i class="fas fa-bath"></i>
                    <small class="text-muted d-block text-uppercase" style="font-size: 10px; font-weight: 700;">Phòng tắm</small>
                    <strong>{{ $rentPosts->bathrooms }} WC</strong>
                </div>
                <div class="spec-item">
                    <i class="fas fa-couch"></i>
                    <small class="text-muted d-block text-uppercase" style="font-size: 10px; font-weight: 700;">Nội thất</small>
                    <strong>{{ $rentPosts->is_furnished ? 'Đầy đủ' : 'Cơ bản' }}</strong>
                </div>
            </div>

            <h4 class="fw-bold mb-3 border-start border-4 border-primary ps-3">Mô tả chi tiết</h4>
            <div class="desc-box mb-5 text-secondary" style="line-height: 1.8; white-space: pre-line;">
                {{ $rentPosts->description }}
            </div>
            
            <div class="p-4 rounded-4 bg-light border border-secondary border-opacity-10 mb-5">
                <h6 class="fw-bold"><i class="fas fa-shield-alt me-2 text-primary"></i>Lời khuyên an toàn</h6>
                <p class="text-muted small mb-0">Không chuyển khoản đặt cọc khi chưa xem nhà trực tiếp và xác thực giấy tờ pháp lý của chủ sở hữu.</p>
            </div>
        </div>

        {{-- SIDEBAR LIÊN HỆ --}}
        <div class="col-lg-4">
            <div class="sidebar-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-user-check text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">Chủ tin đăng</div>
                        <div class="text-muted small">Mã: #RE-{{ $rentPosts->user_id }}</div>
                    </div>
                </div>

                @auth
                    @if(auth()->id() !== (int)$rentPosts->user_id)
                        <form action="{{ route('contacts.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_two_id" value="{{ $rentPosts->user_id }}">
                            <input type="hidden" name="sale_post_id" value="{{ $rentPosts->id }}">
                            <button type="submit" class="btn-contact shadow-sm">
                                <i class="fas fa-comment-dots me-2"></i> Nhắn tin trao đổi
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info border-0 rounded-4 small fw-bold text-center py-3">
                            Đây là bài đăng của chính bạn
                        </div>
                    @endif
                @else
                    <a href="{{ route('login-form') }}" class="btn-contact d-block text-center text-decoration-none">
                        Đăng nhập để liên hệ
                    </a>
                @endauth

                <a href="tel:0123456789" class="btn-phone shadow-sm">
                    <i class="fas fa-phone-alt me-2"></i> Gọi 0123.456.789
                </a>

                <div class="mt-4 text-center">
                    <a href="#" class="text-decoration-none text-muted small hover-danger">
                        <i class="fas fa-flag me-1"></i> Báo cáo tin không chính xác
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>