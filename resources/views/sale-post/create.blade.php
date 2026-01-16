<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tạo bài đăng mới</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8faff; color: #2d3436; }
        .container { max-width: 800px; margin-top: 50px; margin-bottom: 50px; }
        .form-card { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05); }
        .form-label { font-weight: 700; font-size: 14px; color: #4b5563; margin-bottom: 8px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px 16px; border: 1px solid #e5e7eb; transition: all 0.3s; }
        .form-control:focus, .form-select:focus { border-color: #6c5ce7; box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); }
        .btn-primary { background: #6c5ce7; border: none; border-radius: 12px; padding: 14px 30px; font-weight: 700; transition: all 0.3s; }
        .btn-primary:hover { background: #5a4bcf; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3); }
        .alert-danger { border-radius: 16px; border: none; background-color: #fff5f5; color: #c53030; }
        .image-preview-container { border: 2px dashed #e5e7eb; border-radius: 16px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .image-preview-container:hover { border-color: #6c5ce7; background: #f3f0ff; }
        
        /* Style cho nút chọn loại hình */
        .type-selector { display: flex; gap: 10px; margin-bottom: 25px; background: #f1f3f5; padding: 5px; border-radius: 16px; }
        .type-option { flex: 1; text-align: center; }
        .type-option input { display: none; }
        .type-option label { display: block; padding: 12px; border-radius: 12px; cursor: pointer; font-weight: 700; transition: all 0.3s; color: #64748b; }
        .type-option input:checked + label.label-sale { background: white; color: #6c5ce7; shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .type-option input:checked + label.label-rent { background: white; color: #0dcaf0; shadow: 0 4px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <h2 class="mb-4 fw-800 text-center">Tạo bài đăng mới</h2>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('store-sale-post') }}" method="POST" novalidate enctype="multipart/form-data">
            @csrf

            <label class="form-label d-block text-center mb-3">Bạn muốn đăng tin loại nào?</label>
            <div class="type-selector">
                <div class="type-option">
                    <input type="radio" name="type" id="type_sale" value="sale" {{ old('type', 'sale') == 'sale' ? 'checked' : '' }}>
                    <label for="type_sale" class="label-sale"><i class="fas fa-hand-holding-usd me-2"></i>Cần bán</label>
                </div>
                <div class="type-option">
                    <input type="radio" name="type" id="type_rent" value="rent" {{ old('type') == 'rent' ? 'checked' : '' }}>
                    <label for="type_rent" class="label-rent"><i class="fas fa-key me-2"></i>Cho thuê</label>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Tiêu đề</label>
                <input type="text" name="title" class="form-control" placeholder="Ví dụ: Căn hộ cao cấp tại Quận 1" value="{{ old('title') }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Mô tả chi tiết về bất động sản của bạn...">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Giá (VND)</label>
                    <input type="number" name="price" class="form-control" placeholder="Nhập giá" value="{{ old('price') }}" required>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label">Diện tích (m²)</label>
                    <input type="number" name="area" class="form-control" placeholder="Ví dụ: 80" value="{{ old('area') }}" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường, Phường/Xã, Quận/Huyện" value="{{ old('address') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Phòng ngủ</label>
                    <input type="number" name="bedrooms" class="form-control" placeholder="Số lượng" value="{{ old('bedrooms', 0) }}">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label">Phòng tắm</label>
                    <input type="number" name="bathrooms" class="form-control" placeholder="Số lượng" value="{{ old('bathrooms', 0) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Nội thất</label>
                <select name="is_furnished" class="form-select">
                    <option value="1" {{ old('is_furnished') == 1 ? 'selected' : '' }}>Đã có nội thất</option>
                    <option value="0" {{ old('is_furnished') == 0 ? 'selected' : '' }}>Chưa có nội thất</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="form-label">Hình ảnh bất động sản (Chọn nhiều ảnh)</label>
                <div class="image-preview-container" onclick="document.getElementById('image_input').click()">
                    <i class="fas fa-cloud-upload-alt text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0 text-muted">Nhấn để tải lên hoặc kéo thả ảnh vào đây</p>
                    <input type="file" name="images[]" id="image_input" accept="image/*" multiple hidden onchange="updateFileName(this)">
                    <div id="file-count" class="mt-2 fw-bold text-primary"></div>
                </div>
                <small class="text-muted mt-2 d-block">* Hỗ trợ định dạng: JPG, PNG. Dung lượng tối đa 2MB/ảnh.</small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-3">
                    <i class="fas fa-paper-plane me-2"></i>Đăng bài ngay
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateFileName(input) {
        const count = input.files.length;
        const display = document.getElementById('file-count');
        if (count > 0) {
            display.innerText = `Đã chọn ${count} ảnh`;
        } else {
            display.innerText = '';
        }
    }
</script>

</body>
</html>