
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
    /* CSS Riêng cho trang News - Đồng bộ style với Home */
    body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
    .news-container { max-width: 1200px; margin: 0 auto; padding: 50px 15px; }

    /* Header */
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 40px; border-bottom: 2px solid #e9ecef; padding-bottom: 20px;
    }
    .page-title h2 { font-weight: 800; color: #2d3436; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }
    .page-title p { margin: 5px 0 0; color: #636e72; font-weight: 500; }

    .btn-back {
        text-decoration: none; color: #636e72; font-weight: 600;
        border: 1px solid #dfe6e9; padding: 8px 16px; border-radius: 8px;
        transition: 0.3s; background: #fff;
    }
    .btn-back:hover { border-color: #6c5ce7; color: #6c5ce7; }

    /* Grid System (Thay thế row/col của Bootstrap để đều hơn) */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    /* Card Tin tức */
    .news-card {
        background: #fff; border-radius: 16px; overflow: hidden;
        border: 1px solid #eee; display: flex; flex-direction: column;
        transition: all 0.3s ease; height: 100%;
    }
    .news-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border-color: #a29bfe;
    }

    /* Ảnh thumbnail */
    .news-thumb {
        height: 220px; width: 100%; position: relative; overflow: hidden;
        background: #f1f2f6; display: flex; align-items: center; justify-content: center;
    }
    .news-thumb img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.5s ease;
    }
    .news-card:hover .news-thumb img { transform: scale(1.05); }
    
    .date-badge {
        position: absolute; top: 15px; left: 15px;
        background: rgba(255, 255, 255, 0.95); padding: 5px 12px;
        border-radius: 6px; color: #2d3436; font-size: 12px; font-weight: 700;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; align-items: center;
    }
    .date-badge i { color: #6c5ce7; margin-right: 5px; }

    /* Nội dung Card */
    .news-body { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    
    .news-title {
        font-size: 18px; font-weight: 700; color: #2d3436;
        margin-bottom: 15px; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    
    .news-excerpt {
        font-size: 14px; color: #636e72; line-height: 1.6; margin-bottom: 20px;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }

    /* Nút Đọc thêm */
    .btn-read-more {
        margin-top: auto; /* Đẩy nút xuống đáy */
        display: inline-flex; align-items: center; justify-content: center;
        width: 100%; padding: 10px; border-radius: 8px;
        background: #f8f9fa; color: #2d3436; font-weight: 600;
        text-decoration: none; transition: 0.3s;
    }
    .btn-read-more:hover {
        background: #6c5ce7; color: white;
    }

    /* Trạng thái trống */
    .empty-state {
        grid-column: 1 / -1; text-align: center; padding: 60px;
        background: #fff; border-radius: 16px; border: 2px dashed #b2bec3;
    }

    /* Pagination Styling */
    .pagination-wrapper { margin-top: 40px; display: flex; justify-content: center; }
    /* CSS Override cho Laravel Pagination mặc định để đẹp hơn */
    .pagination { gap: 5px; }
    .page-item .page-link {
        border-radius: 8px; border: none; color: #2d3436;
        padding: 10px 16px; font-weight: 600;
    }
    .page-item.active .page-link {
        background-color: #6c5ce7; color: white;
    }

    /* Responsive */
    @media (max-width: 992px) { .news-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { 
        .news-grid { grid-template-columns: 1fr; } 
        .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    }
</style>

<div class="news-container">
    <div class="page-header">
        <div class="page-title">
            <h2>Tin Tức & Thị Trường</h2>
            <p>Cập nhật xu hướng bất động sản mới nhất</p>
        </div>
        <a href="{{ url('/home') }}" class="btn-back">
            <i class="fas fa-arrow-left me-1"></i> Quay lại Trang chủ
        </a>
    </div>

    <div class="news-grid">
        @forelse($newsList as $post)
        <div class="news-card">
            <div class="news-thumb">
                <div class="date-badge">
                    <i class="far fa-calendar-alt"></i> {{ $post->created_at->format('d/m/Y') }}
                </div>
                
                @if($post->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $post->images->first()->path) }}" alt="{{ $post->title }}">
                @else
                    <img src="https://via.placeholder.com/400x250?text=No+Image" alt="Default">
                @endif
            </div>

            <div class="news-body">
                <h3 class="news-title">{{ $post->title }}</h3>
                <p class="news-excerpt">
                    {{ Str::limit(strip_tags($post->content), 120) }}
                </p>
                
                <a href="{{ route('news.show', $post->id) }}" class="btn-read-more">
                    Đọc chi tiết <i class="fas fa-long-arrow-alt-right ms-2"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="far fa-newspaper fa-3x text-muted mb-3"></i>
            <p class="text-muted fw-bold">Hiện chưa có tin tức nào được đăng tải.</p>
        </div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $newsList->links() }} 
        {{-- Lưu ý: Nếu giao diện phân trang bị vỡ, hãy thêm 'pagination::bootstrap-5' vào trong links() --}}
    </div>
</div>
