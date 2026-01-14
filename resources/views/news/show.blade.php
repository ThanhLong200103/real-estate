
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">

<style>
    body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
    
    .news-detail-container {
        max-width: 1140px;
        margin: 0 auto;
        padding: 40px 15px;
    }

    /* Layout: 70% Nội dung - 30% Sidebar */
    .layout-grid {
        display: grid;
        grid-template-columns: 2.5fr 1fr;
        gap: 40px;
    }

    /* --- CỘT TRÁI: BÀI VIẾT --- */
    .article-wrapper {
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    /* Header bài viết */
    .article-header { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
    
    .article-tag {
        display: inline-block; background: #eef2ff; color: #6c5ce7;
        padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 12px;
        margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .article-title {
        font-size: 30px; font-weight: 800; color: #2d3436;
        line-height: 1.35; margin-bottom: 15px;
    }

    .article-meta {
        color: #636e72; font-size: 14px; display: flex; align-items: center; gap: 20px;
    }
    .article-meta i { color: #6c5ce7; margin-right: 6px; }

    /* Ảnh đại diện (Hero Image) */
    .featured-image-wrapper {
        width: 100%; height: 400px; border-radius: 12px; overflow: hidden; margin-bottom: 35px;
        background-color: #f1f2f6; position: relative;
    }
    .featured-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }

    /* Nội dung bài viết (Typography chuẩn báo) */
    .article-content {
        font-family: 'Merriweather', serif; /* Font chữ có chân dễ đọc */
        font-size: 18px; line-height: 1.8; color: #2d3436; text-align: justify;
    }
    .article-content p { margin-bottom: 25px; }

    /* Gallery ảnh phụ (nếu có nhiều hơn 1 ảnh) */
    .mini-gallery {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 30px;
    }
    .mini-gallery img {
        width: 100%; height: 200px; object-fit: cover; border-radius: 8px; cursor: pointer;
        transition: 0.3s;
    }
    .mini-gallery img:hover { opacity: 0.9; transform: scale(1.02); }

    /* --- CỘT PHẢI: SIDEBAR --- */
    .sidebar { position: sticky; top: 20px; }

    .sidebar-card {
        background: #fff; padding: 25px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;
        border: 1px solid #f1f2f6;
    }

    .btn-action {
        display: flex; align-items: center; justify-content: center;
        width: 100%; padding: 12px; border-radius: 8px;
        text-decoration: none; font-weight: 600; margin-bottom: 10px;
        transition: 0.3s; font-size: 15px;
    }
    .btn-back { background: #fff; border: 1px solid #dfe6e9; color: #636e72; }
    .btn-back:hover { border-color: #6c5ce7; color: #6c5ce7; }
    
    .btn-home { background: #6c5ce7; color: white; border: none; }
    .btn-home:hover { background: #5a4bcf; transform: translateY(-2px); }

    /* Responsive cho Mobile */
    @media (max-width: 900px) {
        .layout-grid { grid-template-columns: 1fr; } /* 1 cột trên mobile */
        .featured-image-wrapper { height: 250px; }
        .article-title { font-size: 24px; }
        .sidebar { position: static; } /* Tắt sticky trên mobile */
    }
</style>

<div class="news-detail-container">
    <div class="layout-grid">
        
        <article class="article-wrapper">
            <div class="article-header">
                <span class="article-tag">Tin tức & Sự kiện</span>
                <h1 class="article-title">{{ $newsPost->title }}</h1>
                
                <div class="article-meta">
                    <span><i class="far fa-calendar-alt"></i> {{ $newsPost->created_at->format('d/m/Y H:i') }}</span>
                    <span><i class="fas fa-eye"></i> Lượt đọc: N/A</span> 
                </div>
            </div>

            @if($newsPost->images->isNotEmpty())
                <div class="featured-image-wrapper">
                    <img src="{{ asset('storage/' . $newsPost->images->first()->path) }}" alt="{{ $newsPost->title }}">
                </div>
            @endif

            <div class="article-content">
                {{-- Giữ nguyên logic an toàn của bạn --}}
                {!! nl2br(e($newsPost->content)) !!}
            </div>

            @if($newsPost->images->count() > 1)
                <h4 class="mt-5 mb-3 fw-bold">Hình ảnh khác</h4>
                <div class="mini-gallery">
                    @foreach($newsPost->images->skip(1) as $image)
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Ảnh phụ">
                    @endforeach
                </div>
            @endif

            <div class="mt-5 pt-4 border-top">
                <p class="text-muted small fst-italic">Cảm ơn bạn đã theo dõi tin tức từ hệ thống.</p>
            </div>
        </article>

        <aside class="sidebar">
            <div class="sidebar-card">
                <h5 class="fw-bold mb-3">Điều hướng</h5>
                <a href="{{ route('news.index') }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
                </a>
                <a href="{{ url('/home') }}" class="btn-action btn-home">
                    <i class="fas fa-home me-2"></i> Trang chủ
                </a>
            </div>

            <div class="sidebar-card text-center" style="background: #eef2ff; border: none;">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h6 class="fw-bold">Bạn cần hỗ trợ?</h6>
                <p class="text-muted small mb-3">Liên hệ đội ngũ CSKH để được tư vấn ngay.</p>
                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">0912.xxx.xxx</a>
            </div>
        </aside>

    </div>
</div>
