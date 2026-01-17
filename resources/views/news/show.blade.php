@extends('layouts.app')

@section('title', $newsPost->title)

@section('content')
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    /**
     * Chuẩn hóa đường dẫn ảnh từ DB để render <img src="">
     * - null/empty -> placeholder
     * - URL (http/https) -> dùng luôn
     * - path kiểu: 'news/a.jpg', 'public/news/a.jpg', '/storage/news/a.jpg', 'storage/news/a.jpg'
     *   -> đưa về đúng URL public: asset('storage/...') hoặc Storage::url(...)
     * - nếu file không tồn tại trong disk public -> placeholder (đỡ ảnh vỡ)
     */
    $resolveImage = function (?string $rawPath, string $size = '800x450') {
        $placeholder = "https://via.placeholder.com/{$size}?text=No+Image";

        if (!$rawPath) return $placeholder;

        $path = trim($rawPath);

        // Full URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Chuẩn hóa slash
        $path = ltrim($path, '/');

        // Nếu seed kiểu "public/news/abc.jpg" => bỏ "public/"
        if (Str::startsWith($path, 'public/')) {
            $path = Str::after($path, 'public/');
        }

        // Nếu seed kiểu "storage/news/abc.jpg" => bỏ "storage/"
        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        // Nếu seed kiểu "/storage/news/abc.jpg" hoặc "storage/news/abc.jpg" sau normalize sẽ còn "news/abc.jpg"
        // Bây giờ $path là đường dẫn trong disk public: "news/abc.jpg"
        // Check tồn tại file thật
        try {
            if (Storage::disk('public')->exists($path)) {
                // URL đúng nhất theo config filesystem
                return Storage::url($path); // -> /storage/news/abc.jpg
            }
        } catch (\Throwable $e) {
            // nếu disk lỗi/config lỗi thì fallback asset
            return asset('storage/' . $path);
        }

        // File không tồn tại => placeholder để không bị ảnh vỡ
        return $placeholder;
    };

    $firstImagePath = optional($newsPost->images->first())->image_url;
@endphp

<style>
    .news-wrap { padding: 32px 0; }
    .news-article { background:#fff; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); overflow:hidden; }
    .news-cover { width:100%; max-height: 430px; object-fit: cover; display:block; background:#f1f2f6; }
    .news-body { padding: 26px; }
    .news-meta { color:#6c757d; font-size: 13px; display:flex; gap:12px; flex-wrap:wrap; margin-bottom: 14px; }
    .news-content { line-height: 1.9; }
    .news-content img { max-width: 100%; height: auto; border-radius: 12px; }

    .gallery { display:grid; grid-template-columns: repeat(4, 1fr); gap:10px; padding: 0 26px 26px; }
    .gallery img { width:100%; height: 90px; object-fit: cover; border-radius: 12px; background:#f1f2f6; }

    @media (max-width: 992px) { .gallery { grid-template-columns: repeat(3, 1fr);} }
    @media (max-width: 576px) { .gallery { grid-template-columns: repeat(2, 1fr);} }

    .sidebar-card { background:#fff; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 18px; }
    .sidebar-item { display:flex; gap:12px; text-decoration:none; color:inherit; padding:10px; border-radius:14px; transition: .2s; }
    .sidebar-item:hover { background:#f8f9fa; transform: translateY(-1px); }
    .sidebar-thumb { width: 96px; height: 70px; object-fit:cover; border-radius: 12px; background:#f1f2f6; flex-shrink:0; }
    .sidebar-title { font-weight:700; font-size: 14px; margin:0 0 6px; line-height:1.35; }
    .sidebar-time { font-size: 12px; color:#6c757d; }
</style>

<div class="container news-wrap">
    <div class="row g-4">
        {{-- MAIN --}}
        <div class="col-lg-8">
            <article class="news-article">
                {{-- COVER --}}
                <img
                    class="news-cover"
                    src="{{ $resolveImage($firstImagePath, '1200x600') }}"
                    alt="{{ $newsPost->title }}"
                >

                <div class="news-body">
                    <h1 class="fw-bold mb-2">{{ $newsPost->title }}</h1>

                    <div class="news-meta">
                        <span>
                            <i class="far fa-user me-1"></i>
                            {{ $newsPost->author->name ?? 'Admin' }}
                        </span>
                        <span>
                            <i class="far fa-clock me-1"></i>
                            {{ $newsPost->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    {{-- DESCRIPTION (HIỂN THỊ HTML) --}}
                    <div class="news-content">
                        {!! $newsPost->description !!}
                    </div>
                </div>

                {{-- GALLERY: các ảnh còn lại (nếu có > 1 ảnh) --}}
                @if($newsPost->images && $newsPost->images->count() > 1)
                    <div class="gallery">
                        @foreach($newsPost->images->take(8) as $img)
                            <img
                                src="{{ $resolveImage($img->image_url, '400x300') }}"
                                alt="news-image"
                                loading="lazy"
                            >
                        @endforeach
                    </div>
                @endif
            </article>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">
            <div class="sidebar-card">
                <h5 class="fw-bold mb-3">🔥 Tin nổi bật</h5>

                @forelse($popularPosts as $post)
                    @php
                        $pImg = optional($post->images->first())->image_url;
                    @endphp

                    <a href="{{ route('news.show', $post->id) }}" class="sidebar-item">
                        <img
                            class="sidebar-thumb"
                            src="{{ $resolveImage($pImg, '300x220') }}"
                            alt="{{ $post->title }}"
                            loading="lazy"
                        >
                        <div>
                            <p class="sidebar-title">{{ Str::limit($post->title, 70) }}</p>
                            <div class="sidebar-time">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-muted mb-0">Chưa có tin nổi bật.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
