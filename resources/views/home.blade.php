@extends('layouts.app')

@section('title', 'EstateHub - Tìm Kiếm Không Gian Sống Lý Tưởng')

@section('content')
    @php
        $convertImage = function ($path) {
            if (!$path) {
                return 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80';
            }
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            return asset('storage/' . $path);
        };
    @endphp

    <header class="hero-banner">
        <div class="container">
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
                            <input type="text" name="keyword" class="form-control" placeholder="Dự án, địa chỉ..."
                                value="{{ request('keyword') }}">
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
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 filter-zone">
                        <span class="search-label">Mức giá</span>
                        <select name="price_range" class="form-select">
                            <option value="">Tất cả giá</option>
                            <option value="0-2000000000" {{ request('price_range') == '0-2000000000' ? 'selected' : '' }}>
                                Dưới 2 tỷ</option>
                            <option value="2000000000-5000000000"
                                {{ request('price_range') == '2000000000-5000000000' ? 'selected' : '' }}>2 - 5 tỷ</option>
                            <option value="5000000000-10000000000"
                                {{ request('price_range') == '5000000000-10000000000' ? 'selected' : '' }}>5 - 10 tỷ
                            </option>
                            <option value="10000000000+" {{ request('price_range') == '10000000000+' ? 'selected' : '' }}>
                                Trên 10 tỷ</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="d-flex align-items-center px-3 gap-2">
                            <button type="button" class="btn-filter-toggle" data-bs-toggle="collapse"
                                data-bs-target="#advancedFilter">
                                <i class="fas fa-sliders-h"></i>
                            </button>
                            <button type="submit" class="btn btn-primary-custom flex-grow-1 py-3 rounded-4">Tìm
                                kiếm</button>
                        </div>
                    </div>
                </div>

                <div class="collapse {{ request('area_range') || request('bedrooms') ? 'show' : '' }}"
                    id="advancedFilter">
                    <div class="pt-4 border-top mt-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="search-label mb-2">Diện tích (m²)</label>
                                <select name="area_range" class="form-select border rounded-3 px-2 py-2">
                                    <option value="">Tất cả diện tích</option>
                                    <option value="0-50" {{ request('area_range') == '0-50' ? 'selected' : '' }}>Dưới 50
                                        m²</option>
                                    <option value="50-100" {{ request('area_range') == '50-100' ? 'selected' : '' }}>50 -
                                        100 m²</option>
                                    <option value="100-200" {{ request('area_range') == '100-200' ? 'selected' : '' }}>100
                                        - 200 m²</option>
                                    <option value="200+" {{ request('area_range') == '200+' ? 'selected' : '' }}>Trên 200
                                        m²</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="search-label mb-2">Số phòng ngủ ít nhất</label>
                                <div class="d-flex gap-2">
                                    @for ($i = 1; $i <= 4; $i++)
                                        <input type="radio" class="btn-check" name="bedrooms" id="bed{{ $i }}"
                                            value="{{ $i }}" {{ request('bedrooms') == $i ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary rounded-3 flex-grow-1"
                                            for="bed{{ $i }}">{{ $i }}+</label>
                                    @endfor
                                    <a href="{{ route('home') }}" class="btn btn-light rounded-3 text-muted"><i
                                            class="fas fa-undo"></i></a>
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
                                    <i
                                        class="{{ Auth::user()->favoritePosts->contains($post->id) ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
                                </button>
                            </form>
                        @endauth

                        @php
                            $imgPath =
                                $post->images && $post->images->isNotEmpty() ? $post->images->first()->image_url : null;
                        @endphp

                        <a href="{{ route('create-sale-show', $post->id) }}">
                            <img src="{{ $convertImage($imgPath) }}" alt="{{ $post->title }}" loading="lazy">
                        </a>

                        <div class="price-overlay">
                            @if ($post->price >= 1000000000)
                                {{ number_format($post->price / 1000000000, 1) }} Tỷ
                            @elseif($post->price > 0)
                                {{ number_format($post->price / 1000000, 0) }} Tr
                            @else
                                Thỏa thuận
                            @endif
                            @if ($post->type == 'rent')
                                <small style="font-size: 11px; color: #636e72;">/tháng</small>
                            @endif
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
                            <div class="amenity-item" title="Phòng ngủ"><i class="fas fa-bed"></i>
                                {{ $post->bedrooms ?? 0 }}</div>
                            <div class="amenity-item" title="Phòng tắm"><i class="fas fa-bath"></i>
                                {{ $post->bathrooms ?? 0 }}</div>
                            <div class="amenity-item" title="Diện tích"><i class="fas fa-vector-square"></i>
                                {{ $post->area ?? 0 }} m²</div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-3">
                            <small class="text-muted fw-500">
                                <i class="far fa-clock me-1"></i> {{ $post->created_at->diffForHumans() }}
                            </small>
                            <a href="{{ route('create-sale-show', $post->id) }}"
                                class="text-primary fw-bold text-decoration-none small">
                                Chi tiết <i class="fas fa-chevron-right ms-1" style="font-size: 10px;"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-5 bg-white rounded-5 shadow-sm" style="grid-column: 1/-1">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100"
                        class="mb-4 opacity-25" alt="Empty">
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
@endsection
