@extends('layouts.app')

@section('title', 'EstateHub - Tìm Kiếm Không Gian Sống Lý Tưởng')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
@endsection

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
        <div class="container text-center">
            <h1 class="display-4 fw-800 mb-3">Tìm Kiếm Không Gian Sống Lý Tưởng</h1>
            <p class="lead opacity-75 mb-5 fw-500">Hàng ngàn bất động sản mới mỗi ngày - Uy tín, Minh bạch, Nhanh chóng</p>
        </div>
    </header>

    <main class="container">
        <div class="search-box p-3 shadow-lg rounded-4 bg-white"
            style="margin-top: -50px; position: relative; z-index: 10;">
            <form action="{{ route('home') }}" method="GET">
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
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light rounded-3" data-bs-toggle="collapse"
                                data-bs-target="#advancedFilter" title="Bộ lọc nâng cao">
                                <i class="fas fa-sliders-h text-primary"></i>
                            </button>

                            {{-- Nút Xóa bộ lọc chỉ hiện khi có bất kỳ tham số query nào --}}

                            <button type="submit" class="btn btn-primary-custom w-100 rounded-3 py-2 fw-bold">
                                <i class="fas fa-search me-1"></i> Tìm
                            </button>
                        </div>
                    </div>
                </div>

                <div class="collapse {{ request('province_id') || request('area_range') || request('bedrooms') ? 'show' : '' }}"
                    id="advancedFilter">
                    @if (request()->anyFilled([
                            'keyword',
                            'type',
                            'category_id',
                            'price_range',
                            'province_id',
                            'district_id',
                            'ward_id',
                            'area_range',
                        ]))
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-3"
                            title="Xóa tất cả bộ lọc">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    @endif
                    <div class="pt-4 border-top mt-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="search-label mb-2 fw-bold small">Tỉnh thành</label>
                                <select name="province_id" id="province_search" class="form-select rounded-3">
                                    <option value="">Tất cả Tỉnh thành</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="search-label mb-2 fw-bold small">Quận huyện</label>
                                <select name="district_id" id="district_search" class="form-select rounded-3">
                                    <option value="">Quận/Huyện</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="search-label mb-2 fw-bold small">Phường xã</label>
                                <select name="ward_id" id="ward_search" class="form-select rounded-3">
                                    <option value="">Phường/Xã</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="search-label mb-2 fw-bold small">Diện tích (m²)</label>
                                <select name="area_range" class="form-select rounded-3">
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
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-3">
                            <label class="search-label mb-2 fw-bold small">Phòng ngủ</label>
                            <select name="bedrooms" class="form-select rounded-3">
                                <option value="">Tất cả phòng ngủ</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}"
                                        {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+ phòng
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="search-label mb-2 fw-bold small">Phòng tắm</label>
                            <select name="bathrooms" class="form-select rounded-3">
                                <option value="">Tất cả phòng tắm</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}"
                                        {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}+ phòng
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            @if (request()->anyFilled([
                                    'keyword',
                                    'type',
                                    'category_id',
                                    'price_range',
                                    'province_id',
                                    'district_id',
                                    'ward_id',
                                    'area_range',
                                    'bedrooms',
                                    'bathrooms',
                                ]))
                                <a href="{{ route('home') }}" class="btn btn-outline-danger btn-sm rounded-3 mb-1"
                                    title="Xóa tất cả bộ lọc">
                                    <i class="fas fa-sync-alt me-1"></i> Xóa lọc
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================= AI FORECAST WIDGET ================= --}}
        <div id="ai-forecast-container" class="card border-0 shadow-sm rounded-4 mb-4 d-none">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Biểu đồ Xu hướng & Dự báo AI
                </h5>

                {{-- Trạng thái AI --}}
                <p id="ai-status" class="text-muted mb-3">
                    Vui lòng chọn Quận/Huyện để xem dự báo
                </p>

                {{-- Chỉ số nhanh --}}
                <div class="row text-center mb-3">
                    <div class="col">
                        <small class="text-muted">1 Tháng</small>
                        <p id="forecast-month" class="fw-bold mb-0">--</p>
                    </div>
                    <div class="col">
                        <small class="text-muted">3 Tháng</small>
                        <p id="forecast-quarter" class="fw-bold mb-0">--</p>
                    </div>
                    <div class="col">
                        <small class="text-muted">1 Năm</small>
                        <p id="forecast-year" class="fw-bold mb-0">--</p>
                    </div>
                </div>

                {{-- Biểu đồ --}}
                <div style="height: 300px;">
                    <canvas id="forecastChart"></canvas>
                </div>
            </div>
        </div>
        {{-- ================= END AI FORECAST WIDGET ================= --}}



        <section class="newest-posts mt-5">
            <h4 class="fw-bold mb-4"><i class="fas fa-home text-primary me-2"></i>Tin đăng mới nhất</h4>
            <div class="property-grid">
                @forelse($rentPosts as $post)
                    <article class="post-card shadow-sm border-0">
                        <div class="image-container">
                            @if ($post->created_at->diffInDays(now()) < 2)
                                <span class="badge bg-warning text-dark position-absolute start-0 mt-5 ms-2 shadow-sm"
                                    style="z-index: 5; font-size: 10px; font-weight: 800;">
                                    <i class="fas fa-fire me-1"></i>MỚI
                                </span>
                            @endif
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
                                <div class="amenity-item small" title="Phòng ngủ"><i
                                        class="fas fa-bed me-1 text-primary"></i> {{ $post->bedrooms ?? 0 }}</div>
                                <div class="amenity-item small" title="Phòng tắm"><i
                                        class="fas fa-bath me-1 text-primary"></i> {{ $post->bathrooms ?? 0 }}</div>
                                <div class="amenity-item small" title="Diện tích"><i
                                        class="fas fa-vector-square me-1 text-primary"></i> {{ $post->area ?? 0 }} m²
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                                <small class="text-muted"><i class="far fa-clock me-1"></i>
                                    {{ $post->created_at->diffForHumans() }}</small>
                                <a href="{{ route('create-sale-show', $post->id) }}"
                                    class="btn btn-sm btn-link p-0 fw-bold text-decoration-none">
                                    Chi tiết <i class="fas fa-chevron-right ms-1 small"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5 bg-white rounded-5 shadow-sm" style="grid-column: 1/-1">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100"
                            class="mb-4 opacity-25" alt="Empty">
                        <h4 class="text-muted fw-bold">Rất tiếc, không tìm thấy kết quả nào!</h4>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary mt-2 px-4 py-2">Xóa bộ lọc</a>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center my-5">
                {{ $rentPosts->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </section>

        <hr class="my-5 opacity-25">

        <section class="popular-locations mb-5">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-map-marked-alt text-primary me-2"></i>Khám phá bất động sản theo địa điểm
            </h4>

            <div class="location-grid">
                @foreach ($popularLocations as $key => $loc)
                    @php
                        // Mảng ảnh minh họa cho các tỉnh thành phổ biến (nếu DB chưa có ảnh)
                        $defaultImages = [
                            'TP Hồ Chí Minh' =>
                                'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=800&q=80',
                            'Hà Nội' =>
                                'https://images.unsplash.com/photo-1509030464150-1b9216307ee8?auto=format&fit=crop&w=800&q=80',
                            'Đà Nẵng' =>
                                'https://images.unsplash.com/photo-1559592443-7f87a79f6ca8?auto=format&fit=crop&w=800&q=80',
                            'Bình Dương' =>
                                'https://images.unsplash.com/photo-1596436889106-be35e843f974?auto=format&fit=crop&w=800&q=80',
                        ];
                        $locImage =
                            $loc->image_url ??
                            ($defaultImages[$loc->name] ??
                                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80');
                    @endphp

                    <a href="{{ route('posts.province', ['province_slug' => $loc->slug]) }}"
                        class="location-item item-{{ $key }}"
                        style="background-image: url('{{ $locImage }}');">
                        <div class="location-info">
                            <h5 class="fw-bold mb-1">{{ $loc->name }}</h5>
                            <span class="small">{{ number_format($loc->sale_posts_count) }} tin đăng</span>
                        </div>
                    </a>
                @endforeach
            </div>

        </section>
    </main>

    <style>
        /* Giữ nguyên CSS của bạn */
        .location-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 180px);
            gap: 15px;
        }

        .item-0 {
            grid-column: span 2;
            grid-row: span 2;
        }

        .location-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            display: block;
            background: #eee;
        }

        .location-info {
            position: absolute;
            bottom: 0;
            left: 0;
            padding: 20px;
            color: white;
            z-index: 2;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            width: 100%;
        }

        @media (max-width: 768px) {
            .location-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: repeat(3, 150px);
            }

            .item-0 {
                grid-column: span 2;
                grid-row: span 1;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let forecastChart = null;
        /* ================== QUẢN LÝ BIỂU ĐỒ (CHART.JS) ================== */
        function renderChart(history, future) {

            const canvas = document.getElementById('forecastChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');

            if (forecastChart) {
                forecastChart.destroy();
            }

            const historyLabels = history.map(i => i.ds);
            const historyPrices = history.map(i => i.y);

            const futureLabels = future.map(i => i.ds);
            const futurePrices = future.map(i => i.y);


            forecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [...historyLabels, ...futureLabels],
                    datasets: [{
                            label: 'Giá lịch sử',
                            data: historyPrices,
                            borderColor: '#0d6efd',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: false
                        },
                        {
                            label: 'AI dự báo',
                            data: [
                                ...Array(historyPrices.length - 1).fill(null),
                                historyPrices.at(-1),
                                ...futurePrices
                            ],
                            borderColor: '#fd7e14',
                            borderDash: [6, 6],
                            borderWidth: 3,
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });
        }


        /* ================== AI FETCH + UI ================== */
        document.addEventListener('DOMContentLoaded', function() {
            const districtSelect = document.getElementById('district_search');
            const container = document.getElementById('ai-forecast-container');

            if (districtSelect) {
                districtSelect.addEventListener('change', function() {
                    const districtId = this.value;

                    if (!districtId) {
                        if (container) container.classList.add('d-none');
                        return;
                    }

                    // Hiển thị trạng thái đang tải
                    if (container) {
                        container.classList.remove('d-none');
                        document.getElementById('ai-status').innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2"></span>AI đang phân tích dữ liệu thị trường...';
                    }

                    // Gọi API AI (AI thật + fallback)
                    fetch(`/api/forecast?district_id=${districtId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('API 404 hoặc lỗi server');
                            return response.json();
                        })
                        .then(data => {
                            console.log('FORECAST API:', data);
                            if (data.error) {
                                document.getElementById('ai-status').innerText = data.error;
                                return;
                            }

                            // Cập nhật các chỉ số tăng trưởng (Khớp ID với HTML của bạn)
                            updateGrowthUI('forecast-month', data.month);
                            updateGrowthUI('forecast-quarter', data.quarter);
                            updateGrowthUI('forecast-year', data.year);


                            // Vẽ biểu đồ với history và forecast_value nhận được
                            if (data.history && data.history.length > 0) {
                                renderChart(data.history, data.future);
                                document.getElementById('ai-status').innerText =
                                    "Dự báo hoàn tất dựa trên dữ liệu lịch sử thị trường.";
                            } else {
                                document.getElementById('ai-status').innerText =
                                    "Không đủ dữ liệu lịch sử để dự báo.";
                            }
                        })
                        .catch(err => {
                            console.error("Lỗi:", err);
                            document.getElementById('ai-status').innerText =
                                "Chưa có dữ liệu dự báo cho khu vực này.";
                        });
                });
            }

            function updateGrowthUI(id, value) {
                const el = document.getElementById(id);
                if (!el) return;

                const numValue = parseFloat(value) || 0;
                const colorClass = numValue >= 0 ? 'text-success' : 'text-danger';
                const icon = numValue >= 0 ? '↑' : '↓';
                const sign = numValue > 0 ? '+' : '';

                el.className = `fw-bold mb-0 ${colorClass}`;
                el.innerText = `${sign}${numValue}% ${icon}`;
            }
        });

        /* ================== FILTER TỈNH / HUYỆN / XÃ ================== */
        $(document).ready(function() {
            function loadDistricts(provinceId, selectedDistrictId = null) {
                var districtSelect = $('#district_search');
                districtSelect.html('<option value="">Đang tải...</option>');
                $('#ward_search').html('<option value="">Phường/Xã</option>');

                if (provinceId) {
                    $.ajax({
                        url: '/api/get-districts/' + provinceId,
                        type: 'GET',
                        success: function(data) {
                            districtSelect.html('<option value="">Quận/Huyện</option>');
                            $.each(data, function(key, value) {
                                var selected = (value.id == selectedDistrictId) ? 'selected' :
                                    '';
                                districtSelect.append(
                                    `<option value="${value.id}" ${selected}>${value.name}</option>`
                                );
                            });
                            if (selectedDistrictId) districtSelect.trigger('change');
                        }
                    });
                } else {
                    districtSelect.html('<option value="">Quận/Huyện</option>');
                }
            }

            function loadWards(districtId, selectedWardId = null) {
                var wardSelect = $('#ward_search');
                if (districtId) {
                    $.ajax({
                        url: '/api/get-wards/' + districtId,
                        type: 'GET',
                        success: function(data) {
                            wardSelect.html('<option value="">Phường/Xã</option>');
                            $.each(data, function(key, value) {
                                var selected = (value.id == selectedWardId) ? 'selected' : '';
                                wardSelect.append(
                                    `<option value="${value.id}" ${selected}>${value.name}</option>`
                                );
                            });
                        }
                    });
                }
            }

            $('#province_search').on('change', function() {
                loadDistricts($(this).val());
            });

            $('#district_search').on('change', function() {
                loadWards($(this).val());
            });

            // Khởi tạo nếu có dữ liệu cũ từ request
            var oldProvince = $('#province_search').val();
            var oldDistrict = "{{ request('district_id') }}";
            if (oldProvince) loadDistricts(oldProvince, oldDistrict);
        });
    </script>

@endsection
