@extends('layouts.app')

@section('title', 'Tin Tức & Thị Trường - EstateHub')

@section('content')
@php
    $convertImage = function($path) {
        if (!$path) return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80';
        if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
        return asset('storage/' . $path);
    };
    
    $getFirstImage = function($post) {
        if ($post->images && $post->images->isNotEmpty()) {
            $img = $post->images->first();
            return $img->image_url ?? $img->image_path ?? null;
        }
        return null;
    };
@endphp

<style>
    .news-hero-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin-bottom: 50px;
    }

    .news-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px 50px;
    }

    /* Layout 2 cột */
    .news-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 50px;
    }

    /* Bài viết nổi bật bên trái */
    .featured-article {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
        cursor: pointer;
        height: 100%;
        min-height: 500px;
    }

    .featured-article:hover {
        transform: translateY(-5px);
    }

    .featured-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
    }

    .featured-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
        padding: 40px;
        z-index: 2;
        color: white;
    }

    .featured-tag {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .featured-title {
        font-size: 32px;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .featured-byline {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        opacity: 0.9;
    }

    .featured-byline .author {
        font-weight: 700;
    }

    .featured-byline .date {
        opacity: 0.8;
    }

    /* Cột phải - 3 bài viết nhỏ */
    .sidebar-articles {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar-article {
        display: flex;
        gap: 15px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .sidebar-article:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .sidebar-image {
        width: 140px;
        min-width: 140px;
        height: 140px;
        object-fit: cover;
        background: #f1f2f6;
    }

    .sidebar-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .sidebar-tag {
        display: inline-block;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 12px;
        margin-bottom: 10px;
    }

    .sidebar-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
        color: #2d3436;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Tag colors */
    .tag-business { background: #3b82f6; color: white; }
    .tag-culture { background: #8b5cf6; color: white; }
    .tag-lifestyle { background: #ec4899; color: white; }
    .tag-sport { background: #10b981; color: white; }
    .tag-default { background: #6c5ce7; color: white; }

    /* Grid các bài viết còn lại */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        margin-top: 50px;
    }

    .news-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .news-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f1f2f6;
    }

    .news-card-body {
        padding: 20px;
        flex: 1;
    }

    .news-card-tag {
        display: inline-block;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .news-card-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
        color: #2d3436;
        margin: 0 0 10px 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-card-date {
        font-size: 12px;
        color: #636e72;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .pagination-wrapper {
        margin-top: 50px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        gap: 8px;
    }

    .page-item .page-link {
        border-radius: 10px;
        border: none;
        color: #2d3436;
        padding: 10px 16px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .page-item.active .page-link {
        background-color: #6c5ce7;
        color: white;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .news-layout {
            grid-template-columns: 1fr;
        }

        .news-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .featured-article {
            min-height: 400px;
        }
    }

    @media (max-width: 600px) {
        .news-grid {
            grid-template-columns: 1fr;
        }

        .sidebar-article {
            flex-direction: column;
        }

        .sidebar-image {
            width: 100%;
            height: 200px;
        }
    }
</style>

<div class="news-hero-section">
    <div class="container text-center">
        <h1 class="display-4 fw-800 mb-3">Tin Tức & Thị Trường</h1>
        <p class="lead opacity-90">Cập nhật xu hướng bất động sản mới nhất</p>
    </div>
</div>

<div class="news-container">
    @if($newsList->count() > 0)
        @php
            $featuredPost = $newsList->first();
            $sidebarPosts = $newsList->skip(1)->take(3);
            $remainingPosts = $newsList->skip(4);
        @endphp

        <div class="news-layout">
            {{-- Bài viết nổi bật bên trái --}}
            <a href="{{ route('news.show', $featuredPost->id) }}" class="featured-article">
                <img src="{{ $convertImage($getFirstImage($featuredPost)) }}" alt="{{ $featuredPost->title }}" class="featured-image">
                <div class="featured-overlay">
                    <span class="featured-tag tag-default">Tin tức</span>
                    <h2 class="featured-title">{{ $featuredPost->title }}</h2>
                    <div class="featured-byline">
                        <span class="author">Admin</span>
                        <span class="date">- {{ $featuredPost->created_at->format('M d') }}</span>
    </div>
                </div>
            </a>

            {{-- 3 bài viết nhỏ bên phải --}}
            <div class="sidebar-articles">
                @foreach($sidebarPosts as $index => $post)
                    @php
                        $tags = ['tag-business', 'tag-culture', 'tag-lifestyle', 'tag-sport'];
                        $tagClass = $tags[$index % count($tags)] ?? 'tag-default';
                    @endphp
                    <a href="{{ route('news.show', $post->id) }}" class="sidebar-article">
                        <img src="{{ $convertImage($getFirstImage($post)) }}" alt="{{ $post->title }}" class="sidebar-image">
                        <div class="sidebar-content">
                            <div>
                                <span class="sidebar-tag {{ $tagClass }}">Tin tức</span>
                                <h3 class="sidebar-title">{{ $post->title }}</h3>
                            </div>
            </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Grid các bài viết còn lại --}}
        @if($remainingPosts->count() > 0)
            <div class="news-grid">
                @foreach($remainingPosts as $index => $post)
                    @php
                        $tags = ['tag-business', 'tag-culture', 'tag-lifestyle', 'tag-sport'];
                        $tagClass = $tags[$index % count($tags)] ?? 'tag-default';
                    @endphp
                    <a href="{{ route('news.show', $post->id) }}" class="news-card">
                        <img src="{{ $convertImage($getFirstImage($post)) }}" alt="{{ $post->title }}" class="news-card-image">
                        <div class="news-card-body">
                            <span class="news-card-tag {{ $tagClass }}">Tin tức</span>
                            <h3 class="news-card-title">{{ $post->title }}</h3>
                            <div class="news-card-date">{{ $post->created_at->format('d/m/Y') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="far fa-newspaper fa-4x text-muted mb-4"></i>
            <h3 class="fw-800 text-muted">Chưa có tin tức nào</h3>
            <p class="text-muted">Hãy quay lại sau để xem các tin tức mới nhất.</p>
        </div>
    @endif

    <div class="pagination-wrapper">
        {{ $newsList->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
