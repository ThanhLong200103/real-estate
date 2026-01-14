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

    /* Sidebar logic tương tự index */
    .sidebar { background-color: var(--sidebar-bg); }
    .sidebar .nav-link { color: #94a3b8 !important; border-radius: 8px; transition: 0.2s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: var(--sidebar-hover); color: #fff !important; }
    .sidebar .nav-link.active { background-color: var(--primary-color) !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

    /* Form Styling */
    .card-form { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .form-label { font-weight: 600; color: #475569; }
    .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25 red rgba(79, 70, 229, 0.1); }
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
                <h2 class="fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Tạo Bài Viết Mới</h2>
                <a href="{{ route('index-news-admin') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-form bg-white p-4">
                <form action="{{ route('store-news-admin') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            {{-- Title --}}
                            <div class="mb-4">
                                <label for="title" class="form-label">Tiêu đề bài viết</label>
                                <input type="text" name="title" id="title" class="form-control form-control-lg" 
                                       placeholder="Nhập tiêu đề hấp dẫn..." value="{{ old('title') }}" required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="description" class="form-label">Nội dung mô tả</label>
                                <textarea name="description" id="description" class="form-control" rows="10" 
                                          placeholder="Viết nội dung tin tức tại đây..." required>{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            {{-- Status & Publish --}}
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <label class="form-label d-block mb-3">Thiết lập xuất bản</label>
                                    <div class="form-check form-switch mb-2">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" id="status" value="1" 
                                               class="form-check-input" {{ old('status', 1) ? 'checked' : '' }}>
                                        <label for="status" class="form-check-label fw-medium">Công khai ngay</label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Gạt sang phải để người dùng có thể thấy bài viết này ngay lập tức.
                                    </small>
                                </div>
                            </div>

                            {{-- Upload Images --}}
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <label for="image_url" class="form-label">Hình ảnh đính kèm</label>
                                    <input type="file" name="image_url[]" id="image_url" class="form-control" 
                                           multiple accept=".jpg,.jpeg,.png,.webp">
                                    <div class="mt-2 small text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Có thể chọn nhiều ảnh cùng lúc.
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow">
                                    <i class="fas fa-save me-2"></i>Lưu bài viết
                                </button>
                                <button type="reset" class="btn btn-light">Xóa trắng form</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>