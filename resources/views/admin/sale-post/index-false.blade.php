@extends('admin.layout')

@section('content')
    @php
        $title = request()->routeIs('*false*') ? 'Danh sách chờ duyệt' : 'Danh sách đã đăng';
    @endphp

    <style>
        /* Nâng cấp bảng và dòng */
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .price-text-pending {
            color: #4f46e5;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
        }

        /* Thumbnail phong cách mới */
        .thumb-wrapper {
            width: 90px;
            height: 65px;
            overflow: hidden;
            border-radius: 14px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s ease;
        }

        .badge-type {
            position: absolute;
            top: 4px;
            left: 4px;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: 800;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Thông tin địa chỉ phân cấp */
        .loc-preview {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .loc-tag-mini {
            background: #f1f5f9;
            color: #64748b;
            padding: 1px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        /* Button Group hiện đại */
        .action-group {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 2px;
            display: inline-flex;
            overflow: hidden;
        }

        .btn-action-item {
            border: none;
            background: transparent;
            padding: 6px 10px;
            color: #64748b;
            transition: 0.2s;
            border-radius: 8px;
        }

        .btn-action-item:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }

        .btn-action-item.delete:hover {
            background: #fef2f2;
            color: #ef4444;
        }

        /* Modal Style */
        .confirm-box {
            border-radius: 18px;
            padding: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .confirm-box.success {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .confirm-box.danger {
            background: #fef2f2;
            border-color: #fecaca;
        }
    </style>

    {{-- PHẦN THÔNG BÁO MỚI THÊM --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
            style="border-radius: 16px; background: #ecfdf5; color: #065f46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div><strong>Thành công!</strong> {{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-900 mb-1 text-dark" style="letter-spacing: -1.5px;">{{ $title }}</h2>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-bold"
                    style="font-size: 0.7rem;">
                    <i class="fas fa-layer-group me-1"></i>
                    {{ $type === 'sale' ? 'BẤT ĐỘNG SẢN BÁN' : 'BẤT ĐỘNG SẢN CHO THUÊ' }}
                </span>
                <span class="text-muted small">
                    <i class="fas fa-clock text-warning me-1"></i>
                    Có <strong>{{ method_exists($items, 'total') ? $items->total() : $items->count() }}</strong> bài đang
                    chờ
                </span>
            </div>
        </div>
        <div class="d-flex gap-3">
            <div class="btn-group p-1 bg-white border shadow-sm" style="border-radius: 14px;">
                <a href="{{ route(Route::currentRouteName(), ['type' => 'sale']) }}"
                    class="btn btn-sm {{ $type == 'sale' ? 'btn-dark' : 'btn-light border-0' }} fw-bold px-3"
                    style="border-radius: 10px;" up-follow up-target=".main-content">Bán</a>
                <a href="{{ route(Route::currentRouteName(), ['type' => 'rent']) }}"
                    class="btn btn-sm {{ $type == 'rent' ? 'btn-dark' : 'btn-light border-0' }} fw-bold px-3"
                    style="border-radius: 10px;" up-follow up-target=".main-content">Thuê</a>
            </div>
            <a href="{{ route('create-sale-post-admin') }}"
                class="btn btn-primary px-4 shadow-sm fw-800 d-flex align-items-center"
                style="border-radius: 14px; background: #4f46e5; border: none;">
                <i class="fas fa-plus-circle me-2"></i>Tạo mới
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 28px; overflow: hidden; background: white;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light-subtle">
                            <th class="ps-4 py-4 text-muted fw-800"
                                style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; width: 120px;">
                                Hình ảnh</th>
                            <th class="py-4 text-muted fw-800"
                                style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px;">Thông tin nội
                                dung</th>
                            <th class="py-4 text-muted fw-800"
                                style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px;">Giá trị & Diện
                                tích</th>
                            <th class="py-4 text-muted fw-800"
                                style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px;">Đối tác</th>
                            <th class="py-4 text-muted fw-800 text-center"
                                style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px;">Quản lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $post)
                            <tr>
                                <td class="ps-4">
                                    <div class="thumb-wrapper">
                                        <span
                                            class="badge badge-type {{ $post->type == 'sale' ? 'bg-primary' : 'bg-info' }}">
                                            {{ $post->type == 'sale' ? 'Bán' : 'Thuê' }}
                                        </span>
                                        @php
                                            $firstImage = $post->images->first()?->image_url;
                                            $src = $firstImage
                                                ? (str_starts_with($firstImage, 'http')
                                                    ? $firstImage
                                                    : asset('storage/' . ltrim($firstImage, '/')))
                                                : 'https://placehold.co/600x400?text=No+Image';
                                        @endphp
                                        <img src="{{ $src }}" class="thumb-img" loading="lazy">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-800 text-dark mb-1" style="font-size: 0.95rem;">
                                        {{ Str::limit($post->title, 55) }}</div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-light text-dark border-0 fw-bold"
                                            style="font-size: 0.6rem; padding: 4px 8px;">
                                            <i
                                                class="fas fa-tag me-1 text-primary"></i>{{ $post->category->name ?? 'Loại khác' }}
                                        </span>
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
                                    <small class="text-muted small"><i
                                            class="fas fa-map-marker-alt me-1 text-danger"></i>{{ Str::limit($post->address, 45) }}</small>
                                </td>
                                <td>
                                    <div class="price-text-pending">
                                        @if ($post->type == 'sale')
                                            {{ $post->price >= 1000000000 ? number_format($post->price / 1000000000, 2) . ' tỷ' : number_format($post->price / 1000000) . ' triệu' }}
                                        @else
                                            {{ number_format($post->price / 1000000, 1) }} tr/tháng
                                        @endif
                                    </div>
                                    <div class="text-muted fw-bold mt-1" style="font-size: 0.75rem;">
                                        <i class="fas fa-ruler-combined me-1"></i>{{ $post->area }} m²
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm"
                                            style="width: 38px; height: 38px; background: #eef2ff; color: #4f46e5; border: 2px solid white;">
                                            {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                                {{ $post->user->name ?? 'Admin' }}</div>
                                            <div class="text-muted" style="font-size: 11px;">
                                                {{ $post->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="pe-4 text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-3">
                                        @if ($post->status == 0)
                                            <form action="{{ route('approve-sale-post-admin', $post->id) }}" method="POST"
                                                class="m-0 approve-form" up-submit up-target=".main-content">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success px-3 fw-900 shadow-sm"
                                                    style="border-radius: 10px;">
                                                    DUYỆT
                                                </button>
                                            </form>
                                        @endif
                                        <div class="action-group shadow-sm">
                                            <a href="{{ route('show-sale-post-admin', $post->id) }}"
                                                class="btn-action-item" title="Xem chi tiết" up-follow><i
                                                    class="fas fa-eye"></i></a>
                                            <a href="{{ route('edit-sale-post-admin', $post->id) }}"
                                                class="btn-action-item" title="Chỉnh sửa" up-follow><i
                                                    class="fas fa-pen"></i></a>
                                            <form action="{{ route('destroy-sale-post-admin', $post->id) }}"
                                                method="POST" class="m-0 delete-form" up-submit
                                                up-target=".main-content">
                                                @csrf @method('DELETE')
                                                <button type="button"
                                                    class="btn-action-item delete btn-open-delete-modal"
                                                    data-title="{{ e($post->title) }}"
                                                    data-type="{{ $post->type == 'sale' ? 'Bán' : 'Thuê' }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="fas fa-folder-open fa-4x text-muted"></i></div>
                                    <h5 class="text-muted fw-800">Mọi thứ đã được xử lý xong!</h5>
                                    <p class="text-muted small">Hiện tại không có tin nào đang chờ phê duyệt.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (method_exists($items, 'links'))
        <div class="mt-4" up-nav>
            {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif

    {{-- PHẦN SCRIPT: GIỮ NGUYÊN VÀ THÊM SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Toast thông báo thành công
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

            // 2. Xử lý nút XÓA (Sửa lỗi không xóa ở đây)
            document.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.btn-open-delete-modal');
                if (deleteBtn) {
                    e.preventDefault();
                    const form = deleteBtn.closest('form');
                    const title = deleteBtn.getAttribute('data-title');

                    Swal.fire({
                        title: 'Xác nhận xóa?',
                        text: `Bạn có chắc muốn xóa bài: "${title}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Đồng ý xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // QUAN TRỌNG: Dùng up.submit để Unpoly thực hiện gửi dữ liệu
                            if (window.up) {
                                up.submit(form);
                            } else {
                                form.submit();
                            }
                        }
                    });
                }
            });

            // 3. Xử lý nút DUYỆT (Dùng chung style cho đẹp)
            document.addEventListener('submit', function(e) {
                if (e.target.classList.contains('approve-form')) {
                    // Nếu form này chưa được xác nhận thì dừng lại để hiện Modal
                    if (!e.target.dataset.confirmed) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Duyệt bài này?',
                            text: "Bài viết sẽ được đăng công khai.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            confirmButtonText: 'Duyệt ngay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                e.target.dataset.confirmed = "true";
                                if (window.up) {
                                    up.submit(e.target);
                                } else {
                                    e.target.submit();
                                }
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
