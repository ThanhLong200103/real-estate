@extends('admin.layout')

@section('content')
    @php
        // Tự động nhận diện biến từ Controller truyền xuống (ưu tiên $items)
        $items = $items ?? ($salePosts ?? ($rentPosts ?? collect()));
        $title = 'Tin đăng đã duyệt';
    @endphp

    <style>
        /* Trạng thái hoạt động: Màu xanh Success */
        .status-online {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .price-text-active {
            color: #4f46e5;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
        }

        /* Cấu trúc Table Header sang trọng */
        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 1.25rem 1rem;
            border-top: none;
        }

        /* Thumbnail & Hover */
        .post-thumb-wrapper {
            width: 85px;
            height: 60px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .post-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s ease;
        }

        tr:hover .post-thumb {
            transform: scale(1.1);
        }

        /* Location Tags mini */
        .loc-preview {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .loc-tag-mini {
            background: #f1f5f9;
            color: #475569;
            padding: 1px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            border: 1px solid #e2e8f0;
        }

        /* Button Action Group */
        .action-stack {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 2px;
            display: inline-flex;
        }

        .btn-action-tool {
            border: none;
            background: transparent;
            padding: 6px 10px;
            color: #64748b;
            transition: 0.2s;
            border-radius: 8px;
        }

        .btn-action-tool:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }

        .btn-action-tool.del:hover {
            background: #fef2f2;
            color: #ef4444;
        }

        /* Badge phân loại */
        .cat-badge {
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 8px;
            font-weight: 800;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-900 mb-0 text-dark" style="letter-spacing: -1.5px;">{{ $title }}</h2>
                <div class="d-flex align-items-center gap-3 mt-1">
                    <span class="text-muted small">
                        <i class="fas fa-check-double text-success me-1"></i>
                        Có <strong>{{ method_exists($items, 'total') ? $items->total() : $items->count() }}</strong> bài
                        đang hiển thị
                    </span>
                    <span
                        class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ (request('type') ?? ($type ?? 'sale')) === 'sale' ? 'Bán' : 'Cho thuê' }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <div class="btn-group p-1 bg-white border shadow-sm" style="border-radius: 14px;">
                    <a href="{{ route(Route::currentRouteName(), ['type' => 'sale']) }}"
                        class="btn btn-sm {{ (request('type') ?? ($type ?? 'sale')) == 'sale' ? 'btn-dark' : 'btn-light border-0' }} fw-bold px-3"
                        style="border-radius: 10px;">Bán</a>
                    <a href="{{ route(Route::currentRouteName(), ['type' => 'rent']) }}"
                        class="btn btn-sm {{ (request('type') ?? ($type ?? 'sale')) == 'rent' ? 'btn-dark' : 'btn-light border-0' }} fw-bold px-3"
                        style="border-radius: 10px;">Thuê</a>
                </div>
                <a href="{{ route('index-false-sale-post-admin', ['type' => request('type') ?? ($type ?? 'sale')]) }}"
                    class="btn btn-outline-warning border-warning shadow-sm px-4 fw-800" style="border-radius: 14px;">
                    <i class="fas fa-clock me-2"></i>Chờ duyệt
                </a>
            </div>
        </div>

        {{-- THÊM KHỐI THÔNG BÁO FLASH (Bootstrap Alert) --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between"
                style="border-radius: 15px; background: #ecfdf5; color: #065f46;">
                <div><i class="fas fa-check-circle me-2 text-success"></i> {{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 24px; overflow: hidden; background: white;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Hình ảnh</th>
                            <th>Chi tiết tin đăng</th>
                            <th>Giá & Diện tích</th>
                            <th>Người phụ trách</th>
                            <th class="text-center pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $post)
                            <tr>
                                <td class="ps-4">
                                    <div class="post-thumb-wrapper shadow-sm">
                                        @php
                                            $firstImage = $post->images->first()?->image_url;
                                            $src = $firstImage
                                                ? (str_starts_with($firstImage, 'http')
                                                    ? $firstImage
                                                    : asset('storage/' . ltrim($firstImage, '/')))
                                                : 'https://placehold.co/600x400?text=No+Image';
                                        @endphp
                                        <img src="{{ $src }}" class="post-thumb" loading="lazy">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-800 text-dark mb-1" style="font-size: 0.95rem;">
                                        {{ Str::limit($post->title, 55) }}</div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="cat-badge"><i
                                                class="fas fa-tag me-1"></i>{{ $post->category->name ?? 'BĐS' }}</span>
                                        <div class="loc-preview">
                                            <span class="loc-tag-mini">{{ $post->province?->name }}</span>
                                            <i class="fas fa-chevron-right text-muted" style="font-size: 8px;"></i>
                                            <span class="loc-tag-mini">{{ $post->district?->name }}</span>
                                            {{-- Thêm phần Phường/Xã ở đây --}}
                                            @if ($post->ward)
                                                <i class="fas fa-chevron-right text-muted" style="font-size: 8px;"></i>
                                                <span class="loc-tag-mini">{{ $post->ward->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <small class="text-muted small"><i
                                                class="fas fa-map-marker-alt me-1 text-danger"></i>{{ Str::limit($post->address, 40) }}</small>
                                        <small class="text-success fw-bold small"><span class="status-online"></span>Đang
                                            Online</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-text-active">
                                        @if ($post->type == 'sale')
                                            {{ $post->price >= 1000000000 ? number_format($post->price / 1000000000, 2) . ' tỷ' : number_format($post->price / 1000000) . ' triệu' }}
                                        @else
                                            {{ number_format($post->price / 1000000, 1) }} tr/tháng
                                        @endif
                                    </div>
                                    <div class="text-muted fw-bold small mt-1">
                                        <i class="fas fa-ruler-combined me-1"></i>{{ $post->area }} m²
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px; background: #f0fdf4; color: #16a34a; font-weight: 800; font-size: 12px; border: 1px solid #bbf7d0;">
                                            {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="small fw-800 text-dark">{{ $post->user->name ?? 'Admin' }}</div>
                                            <div class="text-muted" style="font-size: 10px;">Duyệt:
                                                {{ $post->updated_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="action-stack shadow-sm">
                                        <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn-action-tool"
                                            title="Xem nhanh">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                        <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn-action-tool"
                                            title="Sửa nội dung">
                                            <i class="fas fa-pen-nib text-warning"></i>
                                        </a>
                                        {{-- FORM XÓA CẬP NHẬT UP-SUBMIT --}}
                                        <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST"
                                            class="d-inline delete-form m-0" up-submit up-target=".main-content">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-action-tool del btn-open-delete-modal"
                                                data-id="{{ $post->id }}" data-title="{{ e($post->title) }}">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="fas fa-layer-group fa-4x"></i></div>
                                    <h5 class="fw-bold text-muted">Chưa có dữ liệu bài đăng</h5>
                                    <a href="{{ route('create-sale-post-admin') }}"
                                        class="btn btn-primary rounded-pill px-4 mt-2">Đăng bài đầu tiên</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Phân trang --}}
        @if (method_exists($items, 'links'))
            <div class="mt-4 d-flex justify-content-center">
                {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- MODAL DELETE GIỮ NGUYÊN --}}
    <div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-900 text-danger">Xác nhận xóa bài</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="p-3 rounded-4 bg-danger-subtle border border-danger-subtle">
                        <p class="text-danger fw-800 mb-1">Cảnh báo hành động!</p>
                        <p class="small text-muted mb-0">Bạn đang xóa bài đăng: <br><strong id="delTitle"
                                class="text-dark"></strong></p>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy
                        bỏ</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-800" id="confirmDeleteBtn">Xác nhận
                        xóa</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- THÊM THƯ VIỆN SWEETALERT2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. XỬ LÝ HIỂN THỊ THÔNG BÁO THÀNH CÔNG TỪ SESSION
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                @endif

                // 2. LOGIC MODAL XÓA (GIỮ NGUYÊN CODE CỦA BẠN)
                const modalEl = document.getElementById('deletePostModal');
                const titleEl = document.getElementById('delTitle');
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                let targetForm = null;

                document.querySelectorAll('.btn-open-delete-modal').forEach(btn => {
                    btn.addEventListener('click', function() {
                        targetForm = this.closest('form.delete-form');
                        titleEl.textContent = this.dataset.title || 'Tin đăng';
                        new bootstrap.Modal(modalEl).show();
                    });
                });

                confirmBtn.addEventListener('click', function() {
                    if (targetForm) {
                        // Đóng modal trước khi submit
                        bootstrap.Modal.getInstance(modalEl).hide();
                        targetForm.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
