@extends('layouts.app')

@section('title', 'Mua bán nhà đất tại ' . $province->name)

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

        // SỬA TẠI ĐÂY: Đổi 'province-posts' thành 'posts.province'
        // và tham số slug thành 'province_slug' cho đúng với Route Context của bạn
        $formAction = isset($province) ? route('posts.province', ['province_slug' => $province->slug]) : route('home');
    @endphp

    <header class="hero-banner" style="padding: 40px 0; background: #f8f9fa; margin-bottom: 20px;">
        <div class="container text-center">
            <h1 class="fs-4 fw-bold mb-3">Mua Bán Nhà Đất tại {{ $province->name }} Giá Rẻ mới nhất {{ date('m/Y') }}</h1>
            <p class="text-secondary">Hiện có {{ number_format($rentPosts->total()) }} bất động sản.</p>
        </div>
    </header>

    <main class="container">
        <div class="search-box p-3 shadow-lg rounded-4 bg-white mb-5 border">
            <form action="{{ $formAction }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-lg-4 col-md-6 filter-zone border-end px-3">
                        <span class="search-label d-block mb-1 small text-muted">Từ khóa</span>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-search text-primary me-2"></i>
                            <input type="text" name="keyword" class="form-control border-0 shadow-none p-0 fw-bold"
                                placeholder="Tên đường, dự án, khu vực..." value="{{ request('keyword') }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-3 filter-zone border-end px-3">
                        <span class="search-label d-block mb-1 small text-muted">Hình thức</span>
                        <select name="type" class="form-select border-0 shadow-none p-0 fw-bold">
                            <option value="">Tất cả</option>
                            <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Đang bán</option>
                            <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Cho thuê</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3 filter-zone border-end px-3">
                        <span class="search-label d-block mb-1 small text-muted">Loại hình</span>
                        <select name="category_id" class="form-select border-0 shadow-none p-0 fw-bold">
                            <option value="">Tất cả</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6 filter-zone px-3 border-end-lg">
                        <span class="search-label d-block mb-1 small text-muted">Mức giá</span>
                        <select name="price_range" class="form-select border-0 shadow-none p-0 fw-bold">
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

                    <div class="col-lg-2 col-md-6">
                        <button type="submit" class="btn btn-primary-custom w-100 rounded-3 py-2 fw-bold">
                            <i class="fas fa-search me-1"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="property-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                    @forelse($rentPosts as $post)
                        <article class="post-card shadow-sm border-0">
                            <div class="image-container">
                                <span class="type-badge {{ $post->type == 'rent' ? 'badge-rent' : 'badge-sale' }}">
                                    {{ $post->type == 'rent' ? 'Cho Thuê' : 'Đang Bán' }}
                                </span>
                                <span class="category-badge">{{ $post->category->name ?? 'BĐS' }}</span>

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
                                        $post->images && $post->images->isNotEmpty()
                                            ? $post->images->first()->image_url
                                            : null;
                                @endphp

                                <a href="{{ route('create-sale-show', $post->id) }}">
                                    <img src="{{ $convertImage($imgPath) }}" alt="{{ $post->title }}" class="img-fluid"
                                        loading="lazy">
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
                                        <small style="font-size: 11px;">/tháng</small>
                                    @endif
                                </div>
                            </div>

                            <div class="post-content p-3">
                                <a href="{{ route('create-sale-show', $post->id) }}"
                                    class="post-title text-truncate d-block h6 fw-bold mb-2">
                                    {{ $post->title }}
                                </a>
                                <div class="location text-truncate small text-muted mb-3">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $post->address }}
                                </div>
                                <div class="amenities d-flex justify-content-between mb-3 bg-light p-2 rounded-3">
                                    <div class="amenity-item small"><i class="fas fa-bed me-1 text-primary"></i>
                                        {{ $post->bedrooms ?? 0 }}</div>
                                    <div class="amenity-item small"><i class="fas fa-bath me-1 text-primary"></i>
                                        {{ $post->bathrooms ?? 0 }}</div>
                                    <div class="amenity-item small"><i class="fas fa-vector-square me-1 text-primary"></i>
                                        {{ $post->area ?? 0 }} m²</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>
                                        {{ $post->created_at->diffForHumans() }}</small>
                                    <a href="{{ route('create-sale-show', $post->id) }}"
                                        class="btn btn-sm btn-link p-0 fw-bold text-decoration-none">Chi tiết <i
                                            class="fas fa-chevron-right ms-1 small"></i></a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-5 bg-white rounded-5 shadow-sm" style="grid-column: 1/-1">
                            <h4 class="text-muted fw-bold">Rất tiếc, không tìm thấy kết quả nào tại {{ $province->name }}!
                            </h4>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center my-5">
                    {{ $rentPosts->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <div class="col-md-3">
                <div class="sticky-sidebar">
                    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 pt-3 pb-0">
                            <h6 class="fw-bold mb-0"><i class="fas fa-hand-holding-usd me-2 text-primary"></i>Khoảng giá
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled filter-list">
                                <li><a href="{{ request()->fullUrlWithQuery(['price_range' => '0-500000000']) }}"
                                        class="small">Dưới 500 triệu</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['price_range' => '500000000-800000000']) }}"
                                        class="small">500 - 800 triệu</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['price_range' => '800000000-1000000000']) }}"
                                        class="small">800 triệu - 1 tỷ</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['price_range' => '1000000000-2000000000']) }}"
                                        class="small">1 - 2 tỷ</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['price_range' => '10000000000+']) }}"
                                        class="small">Trên 10 tỷ</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-0 pt-3 pb-0">
                            <h6 class="fw-bold mb-0"><i class="fas fa-ruler-combined me-2 text-primary"></i>Diện tích</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled filter-list">
                                <li><a href="{{ request()->fullUrlWithQuery(['area_range' => '0-30']) }}"
                                        class="small">Dưới 30 m²</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['area_range' => '30-50']) }}"
                                        class="small">30 - 50 m²</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['area_range' => '50-80']) }}"
                                        class="small">50 - 80 m²</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['area_range' => '80-100']) }}"
                                        class="small">80 - 100 m²</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['area_range' => '100-200']) }}"
                                        class="small">100 - 200 m²</a></li>
                                <li><a href="{{ request()->fullUrlWithQuery(['area_range' => '200+']) }}"
                                        class="small">Trên 200 m²</a></li>
                            </ul>
                        </div>
                    </div>

                    @if (request()->has('price_range') || request()->has('area_range'))
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger btn-sm w-100 mt-3 rounded-pill">
                            <i class="fas fa-times me-1"></i> Xóa tất cả bộ lọc
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <style>
        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 20px;
            /* Cách mép trên màn hình 20px khi cuộn */
            height: fit-content;
            z-index: 100;
        }

        /* Làm đẹp danh sách lọc */
        .filter-list li {
            border-bottom: 1px solid #f1f1f1;
            transition: all 0.2s ease;
        }

        .filter-list li:last-child {
            border-bottom: none;
        }

        .filter-list li a {
            display: block;
            padding: 8px 0;
            color: #444 !important;
            transition: all 0.3s;
        }

        .filter-list li a:hover {
            color: #007bff !important;
            /* Màu primary của bạn */
            padding-left: 8px;
            /* Hiệu ứng dịch chuyển nhẹ khi hover */
        }

        /* Thêm biểu tượng mũi tên nhỏ phía sau */
        .filter-list li a::after {
            content: '\f105';
            /* font-awesome chevron-right */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            float: right;
            opacity: 0;
            transition: 0.3s;
        }

        .filter-list li a:hover::after {
            opacity: 1;
        }
    </style>

    <script>
        $(document).ready(function() {
            let selectedProvinceId = "{{ $province->id }}";
            if (selectedProvinceId) {
                $('#province_search').val(selectedProvinceId).trigger('change');
            }
        });
    </script>
@endsection
