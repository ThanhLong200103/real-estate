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

    /* --- SIDEBAR --- */
    .sidebar { background-color: var(--sidebar-bg); box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
    .sidebar h4 { letter-spacing: 2px; font-weight: 700; color: #f1f5f9; border-bottom: 1px solid #334155; padding-bottom: 20px; }
    .sidebar .nav-link { border-radius: 8px; margin-bottom: 5px; padding: 12px 15px; transition: 0.2s; font-weight: 500; color: #94a3b8 !important; display: flex; align-items: center; }
    .sidebar .nav-link:hover { background-color: var(--sidebar-hover); color: #fff !important; }
    .sidebar .nav-link.active { background-color: var(--primary-color) !important; color: #fff !important; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }

    /* --- FORM STYLING --- */
    .card-form { border: none; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .form-section-title { font-size: 0.9rem; font-weight: 700; text-transform: uppercase; color: var(--primary-color); letter-spacing: 1px; border-bottom: 2px solid #eef2ff; padding-bottom: 8px; margin-bottom: 20px; }
    
    .form-label { font-size: 0.85rem; font-weight: 600; color: #475569; }
    .form-control, .form-select { border-radius: 10px; padding: 10px 15px; border: 1px solid #e2e8f0; transition: 0.3s; }
    .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
    
    .input-group-text { background-color: #f8fafc; border-radius: 0 10px 10px 0; color: #64748b; font-weight: 600; }
    
    /* --- CUSTOM SWITCH --- */
    .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }

    /* --- BUTTONS --- */
    .btn-save { background-color: var(--primary-color); border: none; border-radius: 10px; padding: 12px 25px; font-weight: 600; transition: 0.3s; }
    .btn-save:hover { background-color: #4338ca; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4); }
    .btn-cancel { border-radius: 10px; padding: 12px 25px; font-weight: 600; color: #64748b; }

    /* --- BREADCRUMB --- */
    .breadcrumb-item a { color: var(--primary-color); text-decoration: none; font-weight: 500; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar min-vh-100 p-3 text-white">
            <h4 class="text-center mt-3 mb-4">REAL ESTATE</h4>
            <div class="nav flex-column nav-pills">
                <a href="{{ route('index-news-admin') }}" class="nav-link">
                    <i class="fas fa-newspaper me-3"></i> Quản lý Tin tức
                </a>
                <a href="{{ route('index-false-sale-post-admin') }}" class="nav-link">
                    <i class="fas fa-clock me-3"></i> Duyệt bài đăng 
                </a>
                <a href="{{ route('index-true-sale-post-admin') }}" class="nav-link active">
                    <i class="fas fa-check-circle me-3"></i> Bài đăng đã duyệt
                </a>
                <div class="mt-auto pt-4">
                    <hr class="opacity-20">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm w-100 py-2 border-0">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-10 p-4 main-content">
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index-true-sale-post-admin') }}">Quản lý bài đăng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tạo mới</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark"><i class="fas fa-plus-circle text-primary me-2"></i> Tạo Bất Động Sản Mới</h2>
                <p class="text-muted small">Điền đầy đủ thông tin để đăng tải sản phẩm lên hệ thống.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2"></i> Có lỗi xảy ra:</div>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-form shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('store-sale-post-admin') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-section-title">1. Thông tin cơ bản</div>
                        <div class="mb-4">
                            <label class="form-label">Tiêu đề bài đăng <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Ví dụ: Căn hộ cao cấp Landmark 81 với view sông Sài Gòn" value="{{ old('title') }}" maxlength="200" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Mô tả ưu điểm, tiện ích xung quanh, hướng nhà..." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-section-title mt-5">2. Giá trị & Vị trí</div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Giá bán/thuê (VNĐ)</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control" placeholder="0" value="{{ old('price') }}" min="0" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Diện tích (m²)</label>
                                <div class="input-group">
                                    <input type="number" name="area" class="form-control" placeholder="0" value="{{ old('area') }}" min="0" required>
                                    <span class="input-group-text">m²</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Địa chỉ chính xác <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-map-marker-alt text-danger"></i></span>
                                <input type="text" name="address" class="form-control border-start-0" placeholder="Số nhà, tên đường, phường, quận..." value="{{ old('address') }}" maxlength="255" required>
                            </div>
                        </div>

                        <div class="form-section-title mt-5">3. Thông số chi tiết</div>
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Số phòng ngủ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-bed text-primary"></i></span>
                                    <input type="number" name="bedrooms" class="form-control border-start-0" value="{{ old('bedrooms', 0) }}" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Số phòng tắm</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-bath text-primary"></i></span>
                                    <input type="number" name="bathrooms" class="form-control border-start-0" value="{{ old('bathrooms', 0) }}" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="form-check form-switch pt-2">
                                    <input type="hidden" name="is_furnished" value="0">
                                    <input type="checkbox" name="is_furnished" class="form-check-input" id="is_furnished" value="1" {{ old('is_furnished') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold ms-2" for="is_furnished">Đã có nội thất</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-title mt-5">4. Hình ảnh thực tế</div>
                        <div class="mb-5">
                            <div class="border-dashed p-4 text-center rounded-4 mb-2" style="border: 2px dashed #cbd5e1; background-color: #f8fafc;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <input type="file" name="image_url[]" class="form-control shadow-none border-0 bg-transparent" multiple accept=".jpg,.jpeg,.png,.webp" style="margin: 0 auto; max-width: 300px;">
                                <p class="small text-muted mt-3 mb-0">Hỗ trợ định dạng: JPG, PNG, WEBP (Tối đa 2MB/ảnh)</p>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('index-true-sale-post-admin') }}" class="btn btn-cancel">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary btn-save px-5">
                                <i class="fas fa-paper-plane me-2"></i> Đăng bài ngay
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>