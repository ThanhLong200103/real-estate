<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin đã lưu - EstateHub</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5a4bcf;
            --secondary: #00cec9;
            --dark: #2d3436;
            --light-bg: #f8faff;
            --white: #ffffff;
            --danger: #ff4757;
        }

        /* Header Đồng Bộ Hero-style */
        .hero-banner-mini {
            background: var(--dark);
            background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=1370&q=80');
            background-size: cover;
            background-position: center;
            padding: 40px 0 80px 0;
            color: white;
            margin-bottom: -40px;
        }

        /* Card Tin Đăng Đồng Bộ */
        .post-card {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #fff;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .post-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(108, 92, 231, 0.12);
        }

        .image-wrapper {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .post-card:hover .image-wrapper img {
            transform: scale(1.1);
        }

        /* Nút Bỏ Lưu - Trái tim đỏ */
        .btn-remove-fav {
            position: absolute;
            top: 15px;
            right: 15px;
            background: white;
            color: var(--danger);
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            z-index: 10;
        }

        .btn-remove-fav:hover {
            background: var(--danger);
            color: white;
            transform: scale(1.1);
        }

        .type-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(108, 92, 231, 0.9);
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            z-index: 5;
        }

        .price-text {
            color: var(--primary);
            font-weight: 800;
            font-size: 1.15rem;
        }

        .stats-group {
            background: #f8faff;
            border-radius: 15px;
            padding: 12px;
            display: flex;
            justify-content: space-between;
        }

        .stats-item {
            font-size: 0.75rem;
            font-weight: 700;
            color: #636e72;
        }

        .stats-item i {
            color: var(--primary);
            margin-right: 4px;
        }

        .empty-state {
            background: white;
            border-radius: 30px;
            padding: 80px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }
    </style>
</head>

<body>

    @php
        $convertImage = function ($path) {
            if (!$path) {
                return 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1000&q=80';
            }
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            return asset('storage/' . $path);
        };
    @endphp

    <header class="hero-banner-mini">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h2 class="fw-800 m-0 text-white" style="cursor: pointer"
                    onclick="window.location='{{ route('home') }}'">
                    ESTATE<span class="text-info">HUB</span>
                </h2>

                <div class="nav-actions">
                    <a href="{{ route('home') }}" class="btn-custom btn-glass"><i class="fas fa-home"></i> Trang chủ</a>
                    <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass"><i
                            class="fas fa-comment-dots"></i></a>
                    <a href="{{ route('favorite.index') }}" class="btn-custom btn-primary-custom"><i
                            class="fas fa-heart"></i> Yêu thích</a>
                    <a href="{{ route('user.report.index') }}" class="btn-custom btn-glass"><i
                            class="fas fa-flag"></i></a>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-custom btn-glass border-0"><i
                                class="fas fa-power-off"></i></button>
                    </form>
                </div>
            </div>
            <h1 class="fw-800 mb-2">Bộ sưu tập của bạn</h1>
            <p class="opacity-75 fw-500">Bạn đã lưu <span class="text-info fw-700">{{ $favorites->total() }}</span> bất
                động sản</p>
        </div>
    </header>

    <main class="container mb-5" style="position: relative; z-index: 10;">
        <div class="row g-4">
            @forelse($favorites as $post)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="post-card">
                        <div class="image-wrapper">
                            <span class="type-badge">{{ $post->type == 'sale' ? 'Mua bán' : 'Cho thuê' }}</span>

                            <form action="{{ route('favorite.toggle', $post->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-remove-fav">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </form>

                            @php
                                $firstImage = $post->images->first();
                                $imagePath = $firstImage ? $firstImage->image_path ?? $firstImage->image_url : null;
                            @endphp

                            <a href="{{ route('create-sale-show', $post->id) }}">
                                <img src="{{ $convertImage($imagePath) }}" alt="{{ $post->title }}"
                                    onerror="this.src='https://via.placeholder.com/400x300?text=EstateHub'">
                            </a>
                        </div>

                        <div class="p-4">
                            <div class="price-text mb-2">
                                @if ($post->price >= 1000000000)
                                    {{ number_format($post->price / 1000000000, 1) }} Tỷ
                                @else
                                    {{ number_format($post->price / 1000000, 0) }} Triệu
                                @endif
                            </div>

                            <h6 class="fw-700 mb-3">
                                <a href="{{ route('create-sale-show', $post->id) }}"
                                    class="text-decoration-none text-dark text-truncate d-block">
                                    {{ $post->title }}
                                </a>
                            </h6>

                            <p class="text-muted small mb-3">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                {{ Str::limit($post->address, 35) }}
                            </p>

                            <div class="stats-group">
                                <span class="stats-item"><i class="fas fa-bed"></i> {{ $post->bedrooms ?? 0 }}
                                    PN</span>
                                <span class="stats-item"><i class="fas fa-bath"></i> {{ $post->bathrooms ?? 0 }}
                                    PT</span>
                                <span class="stats-item"><i class="fas fa-expand"></i> {{ $post->area ?? 0 }}m²</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="mb-4">
                            <i class="fas fa-heart-broken display-1 text-muted opacity-25"></i>
                        </div>
                        <h3 class="fw-800">Danh sách đang trống</h3>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 450px;">
                            Hãy lưu lại những căn nhà bạn ưng ý để dễ dàng so sánh và liên hệ chủ nhà bất cứ khi nào bạn
                            cần.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-primary-custom px-5 py-3 shadow-lg">
                            <i class="fas fa-search me-2"></i> Khám phá bất động sản ngay
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $favorites->links('pagination::bootstrap-5') }}
        </div>
    </main>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
