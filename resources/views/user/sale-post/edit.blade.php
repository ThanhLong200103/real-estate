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
        
        .card-header-gradient { 
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); 
            padding: 40px; color: white; text-align: center; 
        }

        .form-body { padding: 40px; }
        .section-title { 
            font-size: 14px; font-weight: 800; color: #6c5ce7; 
            text-transform: uppercase; letter-spacing: 1px; 
            margin: 30px 0 20px; display: flex; align-items: center; gap: 10px; 
        }
        .section-title:first-child { margin-top: 0; }
        .section-title::after { content: ""; flex: 1; height: 1px; background: #eee; }

        .form-label { font-weight: 600; color: #2d3436; font-size: 14px; margin-bottom: 8px; }
        .form-control, .form-select { 
            padding: 12px 15px; border-radius: 12px; border: 1px solid #dfe6e9; 
            background-color: #f8f9fa; transition: 0.3s;
        }
        .form-control:focus { 
            background-color: white; border-color: #6c5ce7; 
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); 
        }

        /* Image Management Style */
        .current-images { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px; }
        .img-preview-wrapper { 
            position: relative; width: 100px; height: 80px; 
            border-radius: 10px; overflow: hidden; border: 1px solid #ddd;
            background-color: #f1f1f1;
        }
        .img-preview-wrapper img { width: 100%; height: 100%; object-fit: cover; }
        
        .upload-zone {
            border: 2px dashed #6c5ce7; border-radius: 15px; padding: 30px;
            text-align: center; background: #f8faff; cursor: pointer; transition: 0.3s;
        }
        .upload-zone:hover { background: #eff0fe; }

        .btn-update { 
            background: #6c5ce7; color: white; padding: 15px 40px; 
            border-radius: 12px; border: none; font-weight: 700; transition: 0.3s; 
        }
        .btn-update:hover { background: #5a4bcf; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3); }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-card">
        <div class="card-header-gradient">
            <h2 class="fw-800 m-0">Chỉnh sửa tin đăng</h2>
            <p class="opacity-75 mb-0 mt-2">Cập nhật thông tin chính xác để tối ưu hiệu quả bán hàng</p>
        </div>

        <div class="form-body">
            @if ($errors->any())
                <div class="alert alert-danger rounded-4 mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user-update-sale-post', $rentPost->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="section-title">1. Thông tin tiêu đề & Giá</div>
                <div class="mb-4">
                    <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $rentPost->title) }}" required>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Giá bán (VNĐ)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $rentPost->price) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diện tích (m²)</label>
                        <input type="number" name="area" class="form-control" value="{{ old('area', $rentPost->area) }}" required>
                    </div>
                </div>

                <div class="section-title">2. Vị trí & Mô tả</div>
                <div class="mb-4">
                    <label class="form-label">Địa chỉ chi tiết</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $rentPost->address) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mô tả bất động sản</label>
                    <textarea name="description" class="form-control" rows="6" required>{{ old('description', $rentPost->description) }}</textarea>
                </div>

                <div class="section-title">3. Chi tiết thêm</div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Phòng ngủ</label>
                        <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $rentPost->bedrooms) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phòng tắm</label>
                        <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $rentPost->bathrooms) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nội thất</label>
                        <select name="is_furnished" class="form-select">
                            <option value="0" {{ $rentPost->is_furnished == 0 ? 'selected' : '' }}>Cơ bản</option>
                            <option value="1" {{ $rentPost->is_furnished == 1 ? 'selected' : '' }}>Đầy đủ</option>
                        </select>
                    </div>
                </div>

                <div class="section-title">4. Hình ảnh sản phẩm</div>
                
                <label class="form-label">Ảnh hiện tại:</label>
                <div class="current-images">
                    @forelse($rentPost->images as $img)
                        @php
                            $path = $img->image_url;
                            // Kiểm tra nếu là URL tuyệt đối (Seeder/External)
                            if (filter_var($path, FILTER_VALIDATE_URL)) {
                                $src = $path;
                            } 
                            // Kiểm tra nếu nằm trong thư mục images/ (Public assets)
                            elseif (strpos($path, 'images/') === 0) {
                                $src = asset($path);
                            } 
                            // Còn lại là ảnh upload (Storage)
                            else {
                                $src = asset('storage/' . $path);
                            }
                        @endphp
                        <div class="img-preview-wrapper">
                            <img src="{{ $src }}" onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                        </div>
                    @empty
                        <p class="text-muted small">Chưa có ảnh nào được tải lên.</p>
                    @endforelse
                </div>

                <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-images fa-2x text-primary mb-2"></i>
                    <p class="mb-0 fw-bold">Chọn ảnh mới để thay thế</p>
                    <small class="text-muted">Lưu ý: Chọn ảnh mới sẽ thay thế toàn bộ ảnh cũ của tin đăng</small>
                    <input type="file" id="fileInput" name="image_url[]" class="d-none" multiple onchange="previewImages(event)">
                </div>
                <div id="newPreview" class="current-images mt-3"></div>

                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                    <a href="{{ route('user-sale-post-index') }}" class="text-decoration-none text-muted fw-bold">
                        <i class="fas fa-chevron-left me-1"></i> Quay lại quản lý
                    </a>
                    <button type="submit" class="btn-update">
                        Lưu thay đổi ngay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImages(event) {
        const preview = document.getElementById('newPreview');
        preview.innerHTML = '';
        const files = event.target.files;

        if (files) {
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'img-preview-wrapper';
                    div.innerHTML = `<img src="${e.target.result}">`;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(files[i]);
            }
        }
    }
</script>

</body>
</html>