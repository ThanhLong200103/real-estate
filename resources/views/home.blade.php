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
        --grad: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
    }

    body { 
        background-color: var(--light-bg); 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        color: var(--dark);
    }

    /* --- HERO SECTION --- */
    .hero-banner {
        background: var(--dark);
        background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1373&q=80');
        background-size: cover;
        background-position: center;
        padding: 100px 0;
        color: white;
        text-align: center;
        margin-bottom: -50px;
    }

    .search-box {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    /* --- NAV BUTTONS --- */
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

    /* --- PROPERTY CARDS --- */
    .property-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; margin-top: 80px; }

    .post-card { 
        background: var(--white); 
        border-radius: 24px; 
        overflow: hidden; 
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
    }

    .post-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.1); }

    .image-container { position: relative; height: 240px; overflow: hidden; }
    .image-container img { width: 100%; height: 100%; object-fit: cover; }
    
    .status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(0, 206, 201, 0.9);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .price-overlay {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: white;
        color: var(--primary);
        padding: 8px 18px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 18px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .post-content { padding: 25px; }
    .post-title { font-weight: 700; font-size: 19px; color: var(--dark); text-decoration: none; display: block; margin-bottom: 12px; line-height: 1.4; }
    .post-title:hover { color: var(--primary); }

    .location { color: #636e72; font-size: 14px; display: flex; align-items: center; gap: 6px; margin-bottom: 15px; }
    
    .amenities {
        display: flex;
        gap: 15px;
        padding-top: 15px;
        border-top: 1px solid #f1f1f1;
        margin-top: 15px;
    }
    
    .amenity-item { font-size: 13px; color: #636e72; display: flex; align-items: center; gap: 5px; }
    .amenity-item i { color: var(--primary); opacity: 0.7; }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .property-grid { grid-template-columns: 1fr; }
        .hero-banner { padding: 60px 0; }
        .nav-actions { overflow-x: auto; white-space: nowrap; border-radius: 15px; }
    }
</style>

<header class="hero-banner">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <h2 class="fw-800 m-0">ESTATE<span class="text-info">HUB</span></h2>
            
            <div class="nav-actions">
                <a href="{{ route('news.index') }}" class="btn-custom btn-glass"><i class="fas fa-newspaper"></i> Tin tức</a>
                
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('index-news-admin') }}" class="btn-custom btn-glass text-warning"><i class="fas fa-user-shield"></i> Admin</a>
                    @endif
                    <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass"><i class="fas fa-comment-dots"></i></a>
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

        <h1 class="display-4 fw-bold mb-3">Tìm Kiếm Không Gian Sống Lý Tưởng</h1>
        <p class="lead opacity-75 mb-5">Hàng ngàn bất động sản mới mỗi ngày - Uy tín, Minh bạch, Nhanh chóng</p>
    </div>
</header>

<main class="container">
    <div class="search-box">
        <form class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Nhập địa điểm, dự án...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select border-0 shadow-none fw-600">
                    <option selected>Loại hình</option>
                    <option>Căn hộ</option>
                    <option>Nhà phố</option>
                    <option>Biệt thự</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select border-0 shadow-none fw-600">
                    <option selected>Mức giá</option>
                    <option>Dưới 2 tỷ</option>
                    <option>2 - 5 tỷ</option>
                    <option>Trên 5 tỷ</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-primary-custom w-100 py-3 rounded-4">Tìm kiếm</button>
            </div>
        </form>
    </div>

    <div class="property-grid mb-5">
        @forelse($rentPosts as $post)
        <article class="post-card shadow-sm">
            <div class="image-container">
                <span class="status-badge">Mới đăng</span>
                @if($post->images && $post->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $post->images->first()->image_url) }}" alt="Property">
                @else
                    <img src="https://images.unsplash.com/photo-1570129477492-45c003edd2be?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Default">
                @endif
                <div class="price-overlay">
                    {{ number_format($post->price / 1000000, 1) }} Tỷ
                </div>
            </div>
            
            <div class="post-content">
                <a href="{{ route('create-sale-show', $post->id) }}" class="post-title">{{ Str::limit($post->title, 60) }}</a>
                <div class="location">
                    <i class="fas fa-map-marker-alt text-danger"></i> {{ $post->address ?? 'Hồ Chí Minh' }}
                </div>
                
                <div class="amenities">
                    <div class="amenity-item">
                        <i class="fas fa-bed"></i> {{ $post->bedrooms ?? 2 }} PN
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-bath"></i> {{ $post->bathrooms ?? 1 }} PT
                    </div>
                    <div class="amenity-item">
                        <i class="fas fa-ruler-combined"></i> {{ $post->area ?? 0 }} m²
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="far fa-clock"></i> {{ $post->created_at->diffForHumans() }}</small>
                    <a href="{{ route('create-sale-show', $post->id) }}" class="text-primary fw-bold text-decoration-none">
                        Chi tiết <i class="fas fa-arrow-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </article>
        @empty
        <div class="text-center py-5 bg-white rounded-5 shadow-sm" style="grid-column: 1/-1">
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="120" class="mb-4 opacity-50">
            <h3 class="text-muted fw-bold">Rất tiếc, chưa có bài đăng nào!</h3>
            <p class="text-muted">Hãy quay lại sau hoặc thử đăng tin của chính bạn.</p>
            <a href="{{ route('create-sale-post') }}" class="btn btn-primary-custom mt-2 px-5 py-3">Bắt đầu đăng tin</a>
        </div>
        @endforelse
    </div>
</main>