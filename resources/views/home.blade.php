<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateHub - Tìm Kiếm Không Gian Sống Lý Tưởng</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5a4bcf;
            --secondary: #00cec9;
            --dark: #2d3436;
            --light-bg: #f8faff;
            --white: #ffffff;
            --rent-color: #00b894;
            --sale-color: #6c5ce7;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--dark);
        }

        /* Hero & Header */
        .hero-banner {
            background: var(--dark);
            background-image: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1373&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
            margin-bottom: -50px;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-glass { background: rgba(255,255,255,0.2); color: white; }
        .btn-glass:hover { background: white; color: var(--primary); }
        .btn-primary-custom { background: var(--primary); color: white; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3); }
        .btn-primary-custom:hover { background: var(--primary-dark); transform: translateY(-2px); }

        /* Search Box */
        .search-box {
            background: white;
            padding: 15px;
            border-radius: 24px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.08);
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .search-box .filter-zone {
            padding: 10px 15px;
            transition: background 0.2s;
        }

        .search-box .filter-zone:not(:last-child) {
            border-right: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .search-box .filter-zone { border-right: none; border-bottom: 1px solid #eee; }
        }

        .search-box .filter-zone:hover { background: #fcfcfd; border-radius: 12px; }

        .search-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #b2bec3;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            display: block;
        }

        .search-box .form-control, .search-box .form-select {
            border: none;
            padding: 0;
            font-weight: 600;
            color: var(--dark);
            box-shadow: none;
            background-color: transparent;
        }

        .btn-filter-toggle {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: #f1f2f6;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            border: none;
        }

        .btn-filter-toggle:hover { background: #e2e3e9; transform: rotate(15deg); }

        /* Property Grid */
        .property-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 30px; 
            margin-top: 80px; 
        }

        /* Post Card */
        .post-card { 
            background: var(--white); 
            border-radius: 24px; 
            overflow: hidden; 
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            border: 1px solid rgba(0,0,0,0.03);
            height: 100%;
            position: relative;
        }
        .post-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.12); }

        .image-container { position: relative; height: 240px; overflow: hidden; background-color: #eee; }
        .image-container img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s ease; }
        .post-card:hover .image-container img { transform: scale(1.08); }

        .btn-heart-wishlist {
            position: absolute; top: 20px; right: 20px; z-index: 5;
            background: white; border: none; width: 40px; height: 40px;
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15); transition: 0.3s;
        }
        .btn-heart-wishlist i { font-size: 18px; }

        /* Badge Styles */
        .type-badge {
            position: absolute; top: 20px; left: 20px; z-index: 5;
            padding: 6px 14px; border-radius: 10px; font-size: 11px;
            font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .badge-sale { background: var(--sale-color); color: white; }
        .badge-rent { background: var(--rent-color); color: white; }

        .category-badge {
            position: absolute; top: 20px; left: 115px; z-index: 5;
            padding: 6px 12px; border-radius: 10px; font-size: 10px;
            font-weight: 700; text-transform: uppercase;
            background: rgba(255,255,255,0.9); color: var(--dark);
            backdrop-filter: blur(4px); box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .price-overlay {
            position: absolute; bottom: 15px; right: 15px;
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(4px);
            color: var(--dark); padding: 8px 16px; border-radius: 12px;
            font-weight: 800; font-size: 17px; z-index: 2;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .post-content { padding: 25px; }
        .post-title { font-weight: 700; font-size: 18px; color: var(--dark); text-decoration: none; display: block; margin-bottom: 10px; line-height: 1.4; transition: 0.2s; }
        .post-title:hover { color: var(--primary); }
        
        .location { color: #636e72; font-size: 13.5px; display: flex; align-items: center; gap: 6px; margin-bottom: 15px; }
        
        .amenities { 
            display: flex; justify-content: space-between; 
            padding-top: 15px; border-top: 1px solid #f1f1f1; margin-top: 15px; 
        }
        .amenity-item { font-size: 13px; color: #636e72; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .amenity-item i { color: var(--primary); opacity: 0.8; }

        /* Custom Pagination Styling */
        .pagination { justify-content: center; margin-top: 50px; gap: 10px; }
        .page-item .page-link { border-radius: 12px; padding: 12px 20px; color: var(--dark); font-weight: 600; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .page-item.active .page-link { background-color: var(--primary); color: white; }
    </style>
</head>
<body>

@php
    $convertImage = function($path) {
        if (!$path) return 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80';
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        return asset('storage/' . $path);
    };
@endphp

<header class="hero-banner">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <h2 class="fw-800 m-0 text-white">ESTATE<span class="text-info">HUB</span></h2>
            
            <div class="nav-actions">
                <a href="{{ route('news.index') }}" class="btn-custom btn-glass"><i class="fas fa-newspaper"></i> Tin tức</a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('index-news-admin') }}" class="btn-custom btn-glass text-warning"><i class="fas fa-user-shield"></i> Admin</a>
                    @endif
                    
                    {{-- Nút Liên hệ --}}
                    <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass" title="Tin nhắn"><i class="fas fa-comment-dots"></i></a>
                    
                    {{-- Nút Yêu thích --}}
                    <a href="{{ route('favorite.index') }}" class="btn-custom btn-glass" title="Tin đã lưu"><i class="fas fa-heart text-danger"></i></a>
                    
                    {{-- NÚT LỊCH SỬ BÁO CÁO CỦA USER --}}
                    <a href="{{ route('user.report.index') }}" class="btn-custom btn-glass" title="Lịch sử báo cáo"><i class="fas fa-flag text-warning"></i></a>
                    
                    <a href="{{ route('user-sale-post-index') }}" class="btn-custom btn-glass">Tin của tôi</a>
                    <a href="{{ route('create-sale-post') }}" class="btn-custom btn-primary-custom">Đăng tin ngay</a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-custom btn-glass border-0"><i class="fas fa-power-off"></i></button>
                    </form>
                @else
                    <a href="{{ route('login-form') }}" class="btn-custom btn-glass">Đăng nhập</a>
                    <a href="{{ route('register.form') }}" class="btn-custom btn-primary-custom">Tham gia ngay</a>
                @endauth
            </div>
        </div>

        <h1 class="display-4 fw-800 mb-3">Tìm Kiếm Không Gian Sống Lý Tưởng</h1>
        <p class="lead opacity-75 mb-5 fw-500">Hàng ngàn bất động sản mới mỗi ngày - Uy tín, Minh bạch, Nhanh chóng</p>
    </div>
</header>

<main class="container">
    <div class="search-box">
        <form action="{{ route('home') }}" method="GET"> 
            <div class="row g-0 align-items-center">
                <div class="col-md-3 filter-zone">
                    <span class="search-label">Địa điểm</span>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-search text-muted me-2"></i>
                        <input type="text" name="keyword" class="form-control" placeholder="Dự án, địa chỉ..." value="{{ request('keyword') }}">
                    </div>
                </div>

                <div class="col-md-2 filter-zone">
                    <span class="search-label">Hình thức</span>
                    <select name="type" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Đang bán</option>
                        <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Cho thuê</option>
                    </select>
                </div>

                <div class="col-md-2 filter-zone">
                    <span class="search-label">Loại hình</span>
                    <select name="category_id" class="form-select">
                        <option value="">Loại hình</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 filter-zone">
                    <span class="search-label">Mức giá</span>
                    <select name="price_range" class="form-select">
                        <option value="">Tất cả giá</option>
                        <option value="0-2000000000" {{ request('price_range') == '0-2000000000' ? 'selected' : '' }}>Dưới 2 tỷ</option>
                        <option value="2000000000-5000000000" {{ request('price_range') == '2000000000-5000000000' ? 'selected' : '' }}>2 - 5 tỷ</option>
                        <option value="5000000000-10000000000" {{ request('price_range') == '5000000000-10000000000' ? 'selected' : '' }}>5 - 10 tỷ</option>
                        <option value="10000000000+" {{ request('price_range') == '10000000000+' ? 'selected' : '' }}>Trên 10 tỷ</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="d-flex align-items-center px-3 gap-2">
                        <button type="button" class="btn-filter-toggle" data-bs-toggle="collapse" data-bs-target="#advancedFilter">
                            <i class="fas fa-sliders-h"></i>
                        </button>
                        <button type="submit" class="btn btn-primary-custom flex-grow-1 py-3 rounded-4">Tìm kiếm</button>
                    </div>
                </div>
            </div>

            <div class="collapse {{ (request('area_range') || request('bedrooms')) ? 'show' : '' }}" id="advancedFilter">
                <div class="pt-4 border-top mt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="search-label mb-2">Diện tích (m²)</label>
                            <select name="area_range" class="form-select border rounded-3 px-2 py-2">
                                <option value="">Tất cả diện tích</option>
                                <option value="0-50" {{ request('area_range') == '0-50' ? 'selected' : '' }}>Dưới 50 m²</option>
                                <option value="50-100" {{ request('area_range') == '50-100' ? 'selected' : '' }}>50 - 100 m²</option>
                                <option value="100-200" {{ request('area_range') == '100-200' ? 'selected' : '' }}>100 - 200 m²</option>
                                <option value="200+" {{ request('area_range') == '200+' ? 'selected' : '' }}>Trên 200 m²</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="search-label mb-2">Số phòng ngủ ít nhất</label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 4; $i++)
                                    <input type="radio" class="btn-check" name="bedrooms" id="bed{{$i}}" value="{{$i}}" {{ request('bedrooms') == $i ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary rounded-3 flex-grow-1" for="bed{{$i}}">{{$i}}+</label>
                                @endfor
                                <a href="{{ route('home') }}" class="btn btn-light rounded-3 text-muted"><i class="fas fa-undo"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="property-grid">
        @forelse($rentPosts as $post)
        <article class="post-card shadow-sm">
            <div class="image-container">
                <span class="type-badge {{ $post->type == 'rent' ? 'badge-rent' : 'badge-sale' }}">
                    {{ $post->type == 'rent' ? 'Cho Thuê' : 'Đang Bán' }}
                </span>

                <span class="category-badge">
                    {{ $post->category->name ?? 'BĐS' }}
                </span>

                @auth
                    <form action="{{ route('favorite.toggle', $post->id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-heart-wishlist">
                            <i class="{{ Auth::user()->favoritePosts->contains($post->id) ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
                        </button>
                    </form>
                @endauth

                @php
                    $imgPath = ($post->images && $post->images->isNotEmpty()) 
                               ? $post->images->first()->image_url 
                               : null;
                @endphp
                
                <a href="{{ route('create-sale-show', $post->id) }}">
                    <img src="{{ $convertImage($imgPath) }}" alt="{{ $post->title }}" loading="lazy">
                </a>

                <div class="price-overlay">
                    @if($post->price >= 1000000000)
                        {{ number_format($post->price / 1000000000, 1) }} Tỷ
                    @elseif($post->price > 0)
                        {{ number_format($post->price / 1000000, 0) }} Tr
                    @else
                        Thỏa thuận
                    @endif
                    @if($post->type == 'rent') <small style="font-size: 11px; color: #636e72;">/tháng</small> @endif
                </div>
            </div>

            <div class="post-content">
                <a href="{{ route('create-sale-show', $post->id) }}" class="post-title text-truncate">
                    {{ $post->title }}
                </a>
                
                <div class="location text-truncate">
                    <i class="fas fa-map-marker-alt text-danger"></i> {{ $post->address }}
                </div>
                
                <div class="amenities">
                    <div class="amenity-item" title="Phòng ngủ"><i class="fas fa-bed"></i> {{ $post->bedrooms ?? 0 }}</div>
                    <div class="amenity-item" title="Phòng tắm"><i class="fas fa-bath"></i> {{ $post->bathrooms ?? 0 }}</div>
                    <div class="amenity-item" title="Diện tích"><i class="fas fa-vector-square"></i> {{ $post->area ?? 0 }} m²</div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-3">
                    <small class="text-muted fw-500">
                        <i class="far fa-clock me-1"></i> {{ $post->created_at->diffForHumans() }}
                    </small>
                    <a href="{{ route('create-sale-show', $post->id) }}" class="text-primary fw-bold text-decoration-none small">
                        Chi tiết <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i>
                    </a>
                </div>
            </div>
        </article>
        @empty
            <div class="text-center py-5 bg-white rounded-5 shadow-sm" style="grid-column: 1/-1">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-4 opacity-25" alt="Empty">
                <h4 class="text-muted fw-bold">Rất tiếc, không tìm thấy kết quả nào!</h4>
                <p class="text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm của bạn.</p>
                <a href="{{ route('home') }}" class="btn btn-outline-primary mt-2 px-4 py-2">Xóa bộ lọc</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mb-5">
        {{ $rentPosts->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>