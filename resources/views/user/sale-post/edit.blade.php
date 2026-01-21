<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa tin đăng - {{ $rentPost->title }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f4f7f6; font-family: 'Plus Jakarta Sans', sans-serif; color: #2d3436; }
        .form-container { max-width: 900px; margin: 50px auto; padding: 0 15px; }
        .form-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: none; }
        .card-header-gradient { background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); padding: 40px; color: white; text-align: center; }
        .form-body { padding: 40px; }
        .section-title { font-size: 14px; font-weight: 800; color: #6c5ce7; text-transform: uppercase; letter-spacing: 1px; margin: 30px 0 20px; display: flex; align-items: center; gap: 10px; }
        .section-title:first-child { margin-top: 0; }
        .section-title::after { content: ""; flex: 1; height: 1px; background: #eee; }
        .form-label { font-weight: 600; color: #2d3436; font-size: 14px; margin-bottom: 8px; }
        .form-control, .form-select { padding: 12px 15px; border-radius: 12px; border: 1px solid #dfe6e9; background-color: #f8f9fa; transition: 0.3s; }
        .form-control:focus { background-color: white; border-color: #6c5ce7; box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); }
        .current-images { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 15px; }
        .img-preview-wrapper { position: relative; width: 110px; height: 90px; border-radius: 12px; overflow: hidden; border: 2px solid #eee; background-color: #f1f1f1; }
        .img-preview-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        .upload-zone { border: 2px dashed #6c5ce7; border-radius: 15px; padding: 30px; text-align: center; background: #f8faff; cursor: pointer; transition: 0.3s; }
        .upload-zone:hover { background: #eff0fe; }
        .btn-update { background: #6c5ce7; color: white; padding: 15px 40px; border-radius: 12px; border: none; font-weight: 700; transition: 0.3s; }
        .btn-update:hover { background: #5a4bcf; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3); }
        .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; background: rgba(255,255,255,0.2); color: white; margin-top: 10px; }
        .input-group-text { background: #f8f9fa; border-radius: 12px 0 0 12px; border: 1px solid #dfe6e9; border-right: none; }
        .has-icon .form-control, .has-icon .form-select { border-left: none !important; border-radius: 0 12px 12px 0; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-card">
        <div class="card-header-gradient">
            <h2 class="fw-800 m-0">Chỉnh sửa tin đăng</h2>
            <div class="status-badge">
                <i class="fas fa-info-circle me-1"></i>
                @if($rentPost->status)
                    Tin đang hiển thị (Sửa tin sẽ cần duyệt lại)
                @else
                    Tin đang chờ duyệt
                @endif
            </div>
        </div>

        <div class="form-body">
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user-update-sale-post', $rentPost->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="section-title">1. Hình thức & Danh mục</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại giao dịch <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="sale" {{ old('type', $rentPost->type) == 'sale' ? 'selected' : '' }}>Cần bán</option>
                            <option value="rent" {{ old('type', $rentPost->type) == 'rent' ? 'selected' : '' }}>Cho thuê</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $rentPost->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="section-title">2. Vị trí địa lý</div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-city text-muted"></i></span>
                            <select name="province_id" id="province" class="form-select" required>
                                <option value="">-- Chọn Tỉnh/Thành --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id', $rentPost->province_id) == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quận / Huyện <span class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-map-marked-alt text-muted"></i></span>
                            <select name="district_id" id="district" class="form-select" required>
                                <option value="">-- Chọn Quận/Huyện --</option>
                                {{-- Load sẵn danh sách quận của tỉnh hiện tại --}}
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $rentPost->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phường / Xã <span class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-map text-muted"></i></span>
                            <select name="ward_id" id="ward" class="form-select" required>
                                <option value="">-- Chọn Phường/Xã --</option>
                                {{-- Load sẵn danh sách phường của quận hiện tại --}}
                                @if(isset($wards))
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->id }}" {{ old('ward_id', $rentPost->ward_id) == $ward->id ? 'selected' : '' }}>
                                            {{ $ward->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Địa chỉ chi tiết (Số nhà, tên đường) <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $rentPost->address) }}" required>
                </div>

                <div class="section-title">3. Thông tin tiêu đề & Giá</div>
                <div class="mb-4">
                    <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $rentPost->title) }}" required>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', (int)$rentPost->price) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                        <input type="number" name="area" class="form-control" value="{{ old('area', $rentPost->area) }}" required>
                    </div>
                </div>

                <div class="section-title">4. Đặc điểm & Mô tả</div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phòng ngủ</label>
                        <select name="bedrooms" class="form-select">
                            @for($i=0; $i<=10; $i++)
                                <option value="{{ $i }}" {{ old('bedrooms', $rentPost->bedrooms) == $i ? 'selected' : '' }}>{{ $i }} phòng</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phòng tắm</label>
                        <select name="bathrooms" class="form-select">
                            @for($i=0; $i<=10; $i++)
                                <option value="{{ $i }}" {{ old('bathrooms', $rentPost->bathrooms) == $i ? 'selected' : '' }}>{{ $i }} phòng</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nội thất</label>
                        <select name="is_furnished" class="form-select">
                            <option value="0" {{ old('is_furnished', $rentPost->is_furnished) == 0 ? 'selected' : '' }}>Cơ bản</option>
                            <option value="1" {{ old('is_furnished', $rentPost->is_furnished) == 1 ? 'selected' : '' }}>Đầy đủ</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Mô tả nội dung <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $rentPost->description) }}</textarea>
                </div>

                <div class="section-title">5. Hình ảnh sản phẩm</div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Ảnh hiện có:</label>
                    <div class="current-images">
                        @foreach($rentPost->images as $img)
                            @php $src = filter_var($img->image_url, FILTER_VALIDATE_URL) ? $img->image_url : asset('storage/' . $img->image_url); @endphp
                            <div class="img-preview-wrapper shadow-sm">
                                <img src="{{ $src }}" onerror="this.src='https://placehold.co/300x200?text=No+Image'">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-images fa-2x text-primary mb-2"></i>
                    <p class="mb-0 fw-bold">Thay thế bộ ảnh mới</p>
                    <small class="text-muted">Chọn ảnh mới sẽ xóa bộ ảnh cũ sau khi lưu</small>
                    <input type="file" id="fileInput" name="images[]" class="d-none" multiple accept="image/*" onchange="previewImages(event)">
                </div>
                <div id="newPreview" class="current-images mt-3"></div>

                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                    <a href="{{ route('user-sale-post-index') }}" class="text-decoration-none text-muted fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn-update shadow-sm">
                        <i class="fas fa-check-circle me-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    // AJAX load Quận/Huyện
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

        if (provinceId) {
            fetch(`/api/get-districts/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                    data.forEach(item => {
                        districtSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => districtSelect.innerHTML = '<option value="">-- Lỗi dữ liệu --</option>');
        }
    });

    // AJAX load Phường/Xã
    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';

        if (districtId) {
            fetch(`/api/get-wards/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                    data.forEach(item => {
                        wardSelect.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(() => wardSelect.innerHTML = '<option value="">-- Lỗi dữ liệu --</option>');
        }
    });

    // Preview ảnh mới
    function previewImages(event) {
        const preview = document.getElementById('newPreview');
        preview.innerHTML = '';
        const files = event.target.files;
        if (files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'img-preview-wrapper shadow-sm';
                    div.innerHTML = `<img src="${e.target.result}">`;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>

</body>
</html>