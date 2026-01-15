<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng tin mới - Bất động sản</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f4f7f6; font-family: 'Plus Jakarta Sans', sans-serif; color: #2d3436; }
        .form-container { max-width: 850px; margin: 50px auto; padding: 0 15px; }
        .form-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: none; }
        .card-header-gradient { background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); padding: 40px; color: white; text-align: center; }
        .form-body { padding: 40px; }
        
        .section-title { font-size: 14px; font-weight: 800; color: #6c5ce7; text-transform: uppercase; letter-spacing: 1px; margin: 30px 0 20px; display: flex; align-items: center; gap: 10px; }
        .section-title:first-child { margin-top: 0; }
        .section-title::after { content: ""; flex: 1; height: 1px; background: #eee; }
        
        .form-label { font-weight: 600; color: #2d3436; font-size: 14px; margin-bottom: 8px; }
        .form-control, .form-select { padding: 12px 15px; border-radius: 12px; border: 1px solid #dfe6e9; background-color: #f8f9fa; transition: 0.3s; }
        .form-control:focus { background-color: white; border-color: #6c5ce7; box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); outline: none; }
        
        .upload-zone { border: 2px dashed #6c5ce7; border-radius: 20px; padding: 40px; text-align: center; background: #f8faff; cursor: pointer; transition: 0.3s; }
        .upload-zone:hover { background: #eff0fe; border-style: solid; }
        
        #imagePreview { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px; margin-top: 20px; }
        .preview-item { position: relative; height: 100px; border-radius: 12px; overflow: hidden; border: 1px solid #ddd; }
        .preview-item img { width: 100%; height: 100%; object-fit: cover; }

        .btn-submit { background: #6c5ce7; color: white; padding: 15px 30px; border-radius: 12px; border: none; font-weight: 700; transition: 0.3s; width: 100%; max-width: 250px; }
        .btn-submit:hover { background: #5a4bcf; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3); }
        
        .input-group-text { background: #f8f9fa; border-radius: 12px; border-right: none; }
        .has-icon .form-control { border-left: none; }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-card">
        <div class="card-header-gradient">
            <h2 class="fw-800 m-0"><i class="fas fa-edit me-2"></i>Đăng Tin Bất Động Sản</h2>
            <p class="opacity-75 mb-0 mt-2">Cung cấp thông tin chính xác để thu hút người mua</p>
        </div>
        
        <div class="form-body">
            {{-- Hiển thị lỗi nếu có --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('store-sale-post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="section-title">1. Thông tin cơ bản</div>
                <div class="mb-4">
                    <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Ví dụ: Căn hộ cao cấp 2PN trung tâm Quận 1" value="{{ old('title') }}" required>
                    <small class="text-muted">Tiêu đề nên có ít nhất 30 ký tự để SEO tốt hơn.</small>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-tag text-muted"></i></span>
                            <input type="number" name="price" class="form-control" placeholder="Ví dụ: 2500000000" value="{{ old('price') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-ruler-combined text-muted"></i></span>
                            <input type="number" name="area" class="form-control" placeholder="Ví dụ: 75" value="{{ old('area') }}" required>
                        </div>
                    </div>
                </div>

                <div class="section-title">2. Chi tiết bất động sản</div>
                <div class="mb-4">
                    <label class="form-label">Địa chỉ chính xác <span class="text-danger">*</span></label>
                    <div class="input-group has-icon">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt text-muted"></i></span>
                        <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường, Phường/Xã..." value="{{ old('address') }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Phòng ngủ</label>
                        <select name="bedrooms" class="form-select">
                            @for($i=0; $i<=10; $i++)
                                <option value="{{ $i }}" {{ old('bedrooms') == $i ? 'selected' : '' }}>{{ $i }} PN</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phòng tắm</label>
                        <select name="bathrooms" class="form-select">
                            @for($i=0; $i<=10; $i++)
                                <option value="{{ $i }}" {{ old('bathrooms') == $i ? 'selected' : '' }}>{{ $i }} WC</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nội thất</label>
                        <select name="is_furnished" class="form-select">
                            <option value="0" {{ old('is_furnished') == 0 ? 'selected' : '' }}>Nội thất cơ bản</option>
                            <option value="1" {{ old('is_furnished') == 1 ? 'selected' : '' }}>Full nội thất</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="6" placeholder="Mô tả ưu điểm, tiện ích xung quanh, pháp lý..." required>{{ old('description') }}</textarea>
                </div>

                <div class="section-title">3. Hình ảnh (Tối thiểu 3 ảnh)</div>
                <div class="mb-4">
                    <div class="upload-zone" onclick="document.getElementById('imageInput').click()">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Bấm để tải ảnh lên</h5>
                        <p class="text-muted mb-0">Hỗ trợ định dạng: JPG, PNG, WEBP</p>
                        <input type="file" name="image_url[]" id="imageInput" class="d-none" multiple accept="image/*" required onchange="previewImages()">
                    </div>
                    <div id="imagePreview"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted fw-bold hover-danger">
                        <i class="fas fa-times me-1"></i> Hủy bỏ
                    </a>
                    <button type="submit" class="btn-submit shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i>Gửi duyệt tin ngay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImages() {
        var preview = document.querySelector('#imagePreview');
        preview