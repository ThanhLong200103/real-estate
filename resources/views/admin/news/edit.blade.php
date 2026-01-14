<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
        --primary-color: #4f46e5;
        --bg-body: #f8fafc;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #1e293b;
    }

    .sidebar { background-color: var(--sidebar-bg); }
    .sidebar .nav-link { color: #94a3b8 !important; border-radius: 8px; padding: 12px 15px; transition: 0.2s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: var(--sidebar-hover); color: #fff !important; }
    .sidebar .nav-link.active { background-color: var(--primary-color) !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

    .card-form { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .form-label { font-weight: 600; color: #475569; }
    
    .current-img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4 fw-bold border-bottom pb-3">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link active mb-2">
                    <i class="fas fa-newspaper me-3"></i> Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link mb-2">
                    <i class="fas fa-clock me-3"></i> Chờ duyệt
                    <span class="badge bg-danger ms-auto">{{ $pendingPostsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link mb-2">
                    <i class="fas fa-check-circle me-3"></i> Đã duyệt
                </a>
                <div class="mt-auto pt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100 border-0">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0"><i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa bài viết</h2>
                    <small class="text-muted">ID: #{{ $rentPost->id }} - Cập nhật lần cuối: {{ $rentPost->updated_at->format('d/m/Y H:i') }}</small>
                </div>
                <a href="{{ route('index-news-admin') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-form bg-white p-4">
                <form action="{{ route('update-news-admin', $rentPost->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            {{-- Title --}}
                            <div class="mb-4">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input type="text" name="title" id="title" class="form-control form-control-lg" 
                                       value="{{ old('title', $rentPost->title) }}" required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="description" class="form-label">Nội dung chi tiết</label>
                                <textarea name="description" id="description" class="form-control" rows="12" required>{{ old('description', $rentPost->description) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            {{-- Status --}}
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <label class="form-label d-block mb-3">Trạng thái hiển thị</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status" value="1" 
                                               class="form-check-input" {{ old('status', $rentPost->status) ? 'checked' : '' }}>
                                        <label for="status" class="form-check-label fw-medium">Đã xuất bản</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Current Images --}}
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <label class="form-label mb-2">Ảnh hiện tại</label>
                                    @if($rentPost->images && $rentPost->images->isNotEmpty())
                                        <div class="row g-2 mb-3">
                                            @foreach($rentPost->images as $img)
                                                <div class="col-4">
                                                    <img src="{{ asset('storage/' . $img->image_url) }}" class="current-img shadow-sm">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small italic">Chưa có ảnh nào.</p>
                                    @endif

                                    <label for="image_array_new" class="form-label mt-2">Thay đổi/Thêm ảnh mới</label>
                                    <input type="file" name="image_array_new[]" id="image_array_new" class="form-control" 
                                           multiple accept=".jpg,.jpeg,.png,.webp">
                                    <small class="text-muted d-block mt-2 font-italic">* Tải lên ảnh mới sẽ thay thế các ảnh cũ (tùy thuộc vào logic Controller của bạn).</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-save me-2"></i> Cập nhật thay đổi
                                </button>
                                <a href="{{ route('show-news-admin', $rentPost->id) }}" class="btn btn-light">Hủy bỏ</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>