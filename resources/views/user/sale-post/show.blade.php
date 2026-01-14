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
    
    body { background-color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-dark); }
    .property-container { max-width: 1140px; margin: 20px auto; padding: 0 15px; }

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
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .back-link:hover {
        background: var(--bg-light);
        color: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateX(-4px);
    }

    .breadcrumb { margin-bottom: 0; background: transparent; padding: 0; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 18px; vertical-align: middle; color: #cbd5e0; }
    .breadcrumb-item a { color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 500; }
    .breadcrumb-item.active { color: var(--primary-color); font-weight: 600; font-size: 13px; }

    /* --- Header & Gallery --- */
    .prop-header { margin-bottom: 25px; }
    .prop-title { font-size: 32px; font-weight: 800; color: #1a1a1a; margin-bottom: 8px; }
    .prop-meta { display: flex; align-items: center; gap: 15px; font-size: 14px; color: var(--text-muted); }

    .gallery-grid { 
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        grid-template-rows: 220px 220px; 
        gap: 12px; 
        border-radius: 20px; 
        overflow: hidden; 
        margin-bottom: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .gallery-item-main { grid-row: span 2; }
    .gallery-img { width: 100%; height: 100%; object-fit: cover; transition: 0.4s ease; cursor: pointer; }
    .gallery-img:hover { transform: scale(1.02); filter: brightness(0.95); }

    /* --- Info Sections --- */
    .price-tag { font-size: 28px; font-weight: 800; color: #e74c3c; margin-bottom: 20px; display: block; }
    
    .spec-card {
        background: var(--bg-light);
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 24px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 35px;
    }
    .spec-item { text-align: center; border-right: 1px solid #e2e8f0; }
    .spec-item:last-child { border-right: none; }
    .spec-item i { display: block; font-size: 22px; color: var(--primary-color); margin-bottom: 10px; }
    .spec-item span { font-size: 12px; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px; }
    .spec-item strong { font-size: 16px; color: var(--text-dark); }

    .section-label { font-weight: 800; font-size: 20px; margin-bottom: 20px; color: #1a1a1a; display: flex; align-items: center; gap: 10px; }
    .section-label::after { content: ""; flex: 1; height: 1px; background: #f1f2f6; }
    .desc-box { line-height: 1.8; color: #4a5568; font-size: 16px; white-space: pre-line; }

    /* --- Sidebar Card --- */
    .sidebar-card {
        border: 1px solid #edf2f7;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.06);
        position: sticky;
        top: 25px;
        background: white;
    }
    .owner-avatar {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: var(--bg-light);
        border: 2px solid var(--primary-color);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: var(--primary-color);
    }

    .btn-contact { background: var(--primary-color); color: white; border: none; padding: 15px; border-radius: 14px; font-weight: 700; width: 100%; transition: 0.3s; margin-bottom: 12px; }
    .btn-contact:hover { background: #5a4bcf; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(108,92,231,0.25); color: white; }
    
    .btn-phone { background: #00b894; color: white; border: none; padding: 15px; border-radius: 14px; font-weight: 700; width: 100%; display: inline-block; text-align: center; text-decoration: none; transition: 0.3s; }
    .btn-phone:hover { background: #00a383; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,184,148,0.2); color: white; }

    .report-link { font-size: 13px; color: var(--text-muted); text-decoration: none; transition: 0.2s; }
    .report-link:hover { color: #e74c3c; }
</style>

<div class="property-container">
    <div class="nav-top-wrapper">
        <a href="javascript:history.back()" class="back-link">
            <i class="fas fa-chevron-left me-2"></i> Quay lại
        </a>
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="fas fa-home me-1"></i> Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">Bất động sản</a></li>
                <li class="breadcrumb-item active">Chi tiết tin đăng</li>
            </ol>
        </nav>
    </div>

    <div class="prop-header">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <h1 class="prop-title">{{ $rentPosts->title }}</h1>
            <span class="badge {{ $rentPosts->status ? 'bg-success' : 'bg-warning' }} px-3 py-2 rounded-pill shadow-sm">
                {{ $rentPosts->status ? 'Đã xác thực' : 'Đang chờ duyệt' }}
            </span>
        </div>
        <div class="prop-meta">
            <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $rentPosts->address }}</span>
            <span>•</span>
            <span><i class="far fa-calendar-alt me-1"></i> Đăng ngày {{ $rentPosts->created_at->format('d/m/Y') }}</span>
        </div>
    </div>

    <div class="gallery-grid">
        @php $imgs = $rentPosts->images; @endphp
        <div class="gallery-item-main">
            <img src="{{ asset('storage/' . ($imgs[0]->image_url ?? 'default.jpg')) }}" class="gallery-img">
        </div>
        <div>
            <img src="{{ asset('storage/' . ($imgs[1]->image_url ?? ($imgs[0]->image_url ?? 'default.jpg'))) }}" class="gallery-img">
        </div>
        <div class="position-relative">
            <img src="{{ asset('storage/' . ($imgs[2]->image_url ?? ($imgs[0]->image_url ?? 'default.jpg'))) }}" class="gallery-img">
            @if($imgs->count() > 3)
                <div class="position-absolute bottom-0 end-0 m-3 px-3 py-2 bg-dark text-white rounded-4 opacity-75 small fw-bold">
                    <i class="fas fa-images me-1"></i> +{{ $imgs->count() - 3 }} ảnh khác
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 pe-lg-5">
            <div class="price-tag">{{ number_format($rentPosts->price) }} VNĐ <span class="text-muted fw-normal fs-6">/ tháng</span></div>

            <div class="spec-card shadow-sm">
                <div class="spec-item">
                    <i class="fas fa-expand-arrows-alt"></i>
                    <span>Diện tích</span>
                    <strong>{{ $rentPosts->area }} m²</strong>
                </div>
                <div class="spec-item">
                    <i class="fas fa-bed"></i>
                    <span>Phòng ngủ</span>
                    <strong>{{ $rentPosts->bedrooms }} PN</strong>
                </div>
                <div class="spec-item">
                    <i class="fas fa-bath"></i>
                    <span>Phòng tắm</span>
                    <strong>{{ $rentPosts->bathrooms }} WC</strong>
                </div>
                <div class="spec-item">
                    <i class="fas fa-couch"></i>
                    <span>Nội thất</span>
                    <strong>{{ $rentPosts->is_furnished ? 'Đầy đủ' : 'Cơ bản' }}</strong>
                </div>
            </div>

            <h3 class="section-label">Mô tả chi tiết</h3>
            <div class="desc-box mb-5">
                {{ $rentPosts->description }}
            </div>
            
            <div class="p-4 rounded-4 bg-light border-start border-4 border-primary">
                <h6 class="fw-bold mb-2">Lời khuyên an toàn</h6>
                <p class="text-muted small mb-0">Hẹn gặp chủ nhà tại địa điểm công cộng, yêu cầu kiểm tra giấy tờ pháp lý (Sổ đỏ/hợp đồng) và tuyệt đối không chuyển khoản đặt cọc khi chưa xác thực thông tin.</p>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="sidebar-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="owner-avatar">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="fw-800 fs-5 text-dark">Chủ tin đăng</div>
                        <div class="text-muted small">Thành viên từ 2024</div>
                    </div>
                </div>

                <div class="mb-4">
                    <small class="text-muted d-block mb-1">Mã tin đăng:</small>
                    <span class="fw-bold text-dark">#RE-{{ $rentPosts->id }}</span>
                </div>

                @auth
                    @if(auth()->id() !== (int)$rentPosts->user_id)
                        {{-- FORM LIÊN HỆ CHUẨN --}}
                        <form action="{{ route('contacts.start') }}" method="POST">
                            @csrf
                            {{-- Gửi sale_post_id để lưu thông tin bài đăng trong cuộc hội thoại --}}
                            <input type="hidden" name="user_two_id" value="{{ $rentPosts->user_id }}">
                            <input type="hidden" name="sale_post_id" value="{{ $rentPosts->id }}">
                            
                            <button type="submit" class="btn-contact">
                                <i class="fas fa-envelope me-2"></i> Nhắn tin trao đổi
                            </button>
                        </form>
                    @else
                        <a href="{{ route('user-edit-sale-post', $rentPosts->id) }}" class="btn btn-outline-dark w-100 py-3 rounded-4 fw-bold mb-3 border-2">
                            <i class="fas fa-magic me-2"></i> Chỉnh sửa tin của bạn
                        </a>
                    @endif
                @else
                    <a href="{{ route('login-form') }}" class="btn-contact d-inline-block text-center text-decoration-none">
                        Đăng nhập để liên hệ
                    </a>
                @endauth

                <a href="tel:0123456789" class="btn-phone shadow-sm">
                    <i class="fas fa-phone-alt me-2"></i> Gọi 0123.456.789
                </a>

                <hr class="my-4 opacity-50">
                <div class="text-center">
                    <a href="#" class="report-link">
                        <i class="fas fa-flag me-1"></i> Báo cáo tin đăng không chính xác
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>