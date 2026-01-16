<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $salePost->title }} - EstateHub</title>

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
            --sale-color: #6c5ce7;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--dark);
        }

        /* Header Image Banner */
        .sub-header {
            background: var(--dark);
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            padding: 50px 0;
            color: white;
            margin-bottom: 30px;
        }

        .btn-custom {
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            border: none;
        }
        .btn-glass { background: rgba(255,255,255,0.15); color: white; backdrop-filter: blur(10px); }
        .btn-glass:hover { background: white; color: var(--primary); }
        
        .btn-primary-custom { 
            background: var(--primary); color: white; 
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3); 
        }
        .btn-primary-custom:hover { background: var(--primary-dark); transform: translateY(-2px); color: white; }

        .fw-800 { font-weight: 800; }

        /* Gallery */
        .main-gallery {
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            background: white;
            margin-bottom: 35px;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .carousel-item { height: 550px; background: #1a1a1a; }
        .carousel-item img { width: 100%; height: 100%; object-fit: contain; }

        /* Content Cards */
        .detail-card {
            background: white;
            border-radius: 28px;
            padding: 35px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            margin-bottom: 30px;
        }

        .price-tag {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -1px;
        }

        /* Specs Grid - Cấu trúc 5 cột linh hoạt */
        .spec-pill {
            background: #f1f3ff;
            border-radius: 20px;
            padding: 15px 10px;
            text-align: center;
            transition: 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .spec-pill:hover { transform: translateY(-5px); background: white; box-shadow: 0 10px 20px rgba(108, 92, 231, 0.1); }
        .spec-pill i { color: var(--primary); font-size: 20px; margin-bottom: 8px; }
        .spec-pill span { font-size: 10px; color: #a0aec0; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .spec-pill strong { display: block; font-size: 13px; color: var(--dark); margin-top: 5px; line-height: 1.2; }

        /* Sidebar Sticky */
        .sticky-contact { position: sticky; top: 30px; }
        .user-info-box {
            background: white;
            border-radius: 28px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            border: 1px solid #f1f2f6;
        }
        .avatar-circle {
            width: 85px; height: 85px;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px; color: white;
            border: 5px solid white;
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.2);
        }

        .comment-box {
            background: #f8faff;
            padding: 18px 22px;
            border-radius: 22px;
            border: 1px solid #edf2f7;
        }
    </style>
</head>
<body>

@php
    $convertImage = function($path) {
        if (!$path) return 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=1000&q=80';
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        return asset('storage/' . $path);
    };

    $formatPrice = function($price) {
        if ($price >= 1000000000) return number_format($price / 1000000000, 1, ',', '.') . ' Tỷ';
        if ($price >= 1000000) return number_format($price / 1000000, 0, ',', '.') . ' Triệu';
        return number_format($price) . ' đ';
    };
@endphp

<header class="sub-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('home') }}" class="text-white text-decoration-none h3 fw-800 m-0">ESTATE<span class="text-info">HUB</span></a>
                <p class="text-white-50 small m-0 mt-1">Nơi kết nối giá trị thực của bất động sản</p>
            </div>
            <div class="d-flex gap-3">
                {{-- ĐÃ SỬA: user-manage-sale-post -> user-sale-post-index --}}
                <a href="{{ route('user-sale-post-index') }}" class="btn-custom btn-glass"><i class="fas fa-th-list"></i> Quản lý tin</a>
                
                @if(auth()->check() && (int)auth()->id() === (int)$salePost->user_id)
                    <a href="{{ route('user-edit-sale-post', $salePost->id) }}" class="btn-custom btn-primary-custom"><i class="fas fa-magic"></i> Chỉnh sửa ngay</a>
                @endif
            </div>
        </div>
    </div>
</header>

<main class="container mb-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="main-gallery">
                <div id="saleCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @forelse($salePost->images as $key => $img)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ $convertImage($img->image_url) }}" alt="{{ $salePost->title }}">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="{{ $convertImage(null) }}" alt="Placeholder">
                            </div>
                        @endforelse
                    </div>
                    @if($salePost->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#saleCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" style="width: 50px; height: 50px;"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#saleCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" style="width: 50px; height: 50px;"></span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="detail-card">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary rounded-pill px-3 py-1 text-uppercase fw-800" style="font-size: 10px;">{{ $salePost->type == 'sale' ? 'Cần bán' : 'Cho thuê' }}</span>
                        <span class="text-muted small"><i class="far fa-clock me-1"></i> Đăng ngày {{ $salePost->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <h1 class="fw-800 h2 mb-3">{{ $salePost->title }}</h1>
                    
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <span class="text-muted small">
                            <i class="fas fa-layer-group me-1 text-primary"></i> 
                            Danh mục: <strong class="text-dark">{{ $salePost->category->name ?? 'Chưa phân loại' }}</strong>
                        </span>
                        <span class="text-muted small">
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i> 
                            {{ $salePost->address }}
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-baseline gap-3 mb-5 pb-4 border-bottom">
                    <span class="price-tag">{{ $formatPrice($salePost->price) }}</span>
                    <span class="text-muted fw-600">~ {{ number_format($salePost->price / ($salePost->area > 0 ? $salePost->area : 1), 0, ',', '.') }} đ/m²</span>
                </div>

                <div class="row g-2 g-md-3 mt-2">
                    <div class="col-6 col-md-2" style="flex: 0 0 auto; width: 20%;">
                        <div class="spec-pill">
                            <i class="fas fa-home"></i> 
                            <span>Loại hình</span>
                            <strong>{{ $salePost->category->name ?? 'Nhà đất' }}</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-2" style="flex: 0 0 auto; width: 20%;">
                        <div class="spec-pill">
                            <i class="fas fa-ruler-combined"></i>
                            <span>Diện tích</span>
                            <strong>{{ $salePost->area }} m²</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-2" style="flex: 0 0 auto; width: 20%;">
                        <div class="spec-pill">
                            <i class="fas fa-bed"></i>
                            <span>Phòng ngủ</span>
                            <strong>{{ $salePost->bedrooms }} PN</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-2" style="flex: 0 0 auto; width: 20%;">
                        <div class="spec-pill">
                            <i class="fas fa-bath"></i>
                            <span>Phòng tắm</span>
                            <strong>{{ $salePost->bathrooms }} PT</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-2" style="flex: 0 0 auto; width: 20%;">
                        <div class="spec-pill">
                            <i class="fas fa-file-contract"></i>
                            <span>Pháp lý</span>
                            <strong>{{ $salePost->legal_status ?? 'Sổ hồng' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h5 class="fw-800 mb-4 position-relative d-inline-block">
                        Mô tả chi tiết
                        <span class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px; border-radius: 2px; margin-bottom: -8px;"></span>
                    </h5>
                    <div class="text-secondary leading-relaxed fs-6" style="white-space: pre-line; text-align: justify; line-height: 1.8;">
                        {{ $salePost->description }}
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h5 class="fw-800 mb-4">Hỏi đáp & Thảo luận ({{ $salePost->comments->count() }})</h5>
                <div class="comment-list mb-4">
                    @forelse($salePost->comments as $comment)
                        <div class="d-flex gap-3 mb-4">
                            <div class="avatar-circle m-0" style="width: 50px; height: 50px; font-size: 20px; flex-shrink: 0;">
                                {{ substr($comment->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="comment-box flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark">{{ $comment->user->name ?? 'Người dùng' }}</strong>
                                    <span class="text-muted" style="font-size: 11px;">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="m-0 text-secondary small">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 border rounded-4 bg-light">
                            <i class="far fa-comments text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted small m-0">Hãy là người đầu tiên đặt câu hỏi về tin đăng này.</p>
                        </div>
                    @endforelse
                </div>

                @auth
                    <form action="{{ route('comments.store', $salePost->id) }}" method="POST">
                        @csrf
                        <div class="input-group bg-light p-2 rounded-pill shadow-sm">
                            <input type="text" name="content" class="form-control border-0 bg-transparent px-4" placeholder="Nhập thắc mắc của bạn..." required>
                            <button class="btn btn-primary-custom rounded-pill px-4" type="submit">Gửi tin</button>
                        </div>
                    </form>
                @endauth
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-contact">
                <div class="user-info-box mb-4">
                    <div class="avatar-circle">
                        {{ substr($salePost->user->name ?? 'U', 0, 1) }}
                    </div>
                    <h5 class="fw-800 mb-1">{{ $salePost->user->name ?? 'Ẩn danh' }}</h5>
                    <p class="text-muted small mb-4">Người đăng tin chuyên nghiệp</p>

                    <div class="d-grid gap-3">
                        @auth
                            @if(auth()->id() !== (int)$salePost->user_id)
                                <form action="{{ route('contacts.start') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="user_two_id" value="{{ $salePost->user_id }}">
                                    <input type="hidden" name="sale_post_id" value="{{ $salePost->id }}">
                                    <button type="submit" class="btn-custom btn-primary-custom w-100 justify-content-center py-3">
                                        <i class="fas fa-comment-dots"></i> Gửi lời nhắn
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-primary rounded-4 py-3 small fw-bold">
                                    <i class="fas fa-info-circle me-2"></i> Đây là bài đăng của bạn
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login-form') }}" class="btn-custom btn-primary-custom justify-content-center py-3">Đăng nhập để liên hệ</a>
                        @endauth

                        <a href="tel:0123456789" class="btn-custom border w-100 justify-content-center py-3 text-dark fw-bold">
                            <i class="fas fa-phone-alt text-success"></i> 0123.456.789
                        </a>
                    </div>

                    <hr class="my-4">
                    <button type="button" class="btn text-danger small fw-bold p-0" data-bs-toggle="modal" data-bs-target="#reportModal">
                        <i class="fas fa-exclamation-triangle me-1"></i> Báo cáo tin đăng ảo
                    </button>
                </div>

                <div class="detail-card bg-primary text-white p-4">
                    <h6 class="fw-800 mb-3"><i class="fas fa-shield-check me-2"></i>EstateHub Cam Kết</h6>
                    <ul class="list-unstyled small mb-0 opacity-90">
                        <li class="mb-2 d-flex gap-2"><i class="fas fa-check-circle mt-1"></i> <span>Mọi thông tin pháp lý được kiểm tra kỹ lưỡng.</span></li>
                        <li class="mb-2 d-flex gap-2"><i class="fas fa-check-circle mt-1"></i> <span>Kết nối trực tiếp chủ nhà, không qua trung gian ảo.</span></li>
                        <li class="d-flex gap-2"><i class="fas fa-check-circle mt-1"></i> <span>Hỗ trợ tư vấn hợp đồng miễn phí.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- MODAL BÁO CÁO --}}
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 30px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-800">Báo cáo vi phạm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.report.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <input type="hidden" name="sale_post_id" value="{{ $salePost->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Lý do báo cáo</label>
                        <select name="reason" class="form-select border-0 bg-light rounded-3 py-2" required>
                            <option value="">Chọn lý do...</option>
                            <option value="Tin đã bán">Tin đã bán</option>
                            <option value="Giá sai thực tế">Giá sai thực tế</option>
                            <option value="Ảnh giả mạo">Ảnh giả mạo</option>
                            <option value="Số điện thoại không liên lạc được">SĐT không liên lạc được</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Ghi chú</label>
                        <textarea name="description" class="form-control border-0 bg-light rounded-3" rows="4" placeholder="Nhập thêm thông tin..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-800">Gửi phản hồi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>