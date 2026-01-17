@extends('admin.layout')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb & Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Bất động sản</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">Tạo bài đăng mới</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-dark mb-0">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Thêm bài đăng mới
                </h2>

                {{-- giữ type khi quay lại --}}
                <a href="{{ route('index-true-sale-post-admin', ['type' => old('type','sale')]) }}"
                   class="btn btn-outline-secondary px-3 shadow-sm"
                   up-follow up-target=".main-content">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    {{-- 1. Hiển thị lỗi hệ thống --}}
    @if (session('error'))
        <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="d-flex">
                <i class="fas fa-exclamation-triangle me-3 mt-1 fa-2x text-warning"></i>
                <div>
                    <strong class="d-block mb-1">Lỗi hệ thống (Exception):</strong>
                    <span class="text-dark">{{ session('error') }}</span>
                    <p class="small mb-0 mt-2 text-muted italic">
                        <i class="fas fa-info-circle me-1"></i> Gợi ý: Vui lòng kiểm tra lại cấu hình Database hoặc file ảnh.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- 2. Hiển thị lỗi Validation --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Vui lòng kiểm tra lại các thông tin sau:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('store-sale-post-admin') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
        @csrf

        <div class="row g-4">
            {{-- Cột Trái: Nội dung chính --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0 text-dark">Thông tin cơ bản</h5>
                    </div>

                    <div class="card-body p-4 pt-0">
                        {{-- Loại giao dịch --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">
                                Loại giao dịch <span class="text-danger">*</span>
                            </label>

                            <div class="d-flex gap-3">
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="type" id="type_sale" value="sale"
                                           {{ old('type', 'sale') == 'sale' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-primary w-100 py-2 fw-bold" for="type_sale">
                                        <i class="fas fa-tags me-2"></i>CẦN BÁN
                                    </label>
                                </div>

                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="type" id="type_rent" value="rent"
                                           {{ old('type') == 'rent' ? 'checked' : '' }} autocomplete="off">
                                    <label class="btn btn-outline-info w-100 py-2 fw-bold" for="type_rent">
                                        <i class="fas fa-key me-2"></i>CHO THUÊ
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Tiêu đề tin đăng --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">
                                Tiêu đề tin đăng <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="title"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="Ví dụ: Căn hộ chung cư cao cấp 2PN tại Quận 1..."
                                   required
                                   style="border-radius: 10px; border: 2px solid #edf2f7;">
                            @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- FIX: Loại hình bất động sản (category_id) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">
                                Loại hình bất động sản <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-building text-primary"></i>
                                </span>

                                <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required
                                        style="border: 2px solid #edf2f7; border-radius: 0 10px 10px 0;">
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>
                                        -- Chọn loại hình --
                                    </option>

                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                                {{ (string)old('category_id') === (string)$cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('category_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ chi tiết --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">
                                Địa chỉ chi tiết <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </span>

                                <input type="text"
                                       name="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}"
                                       placeholder="Số nhà, tên đường, phường, quận..."
                                       required
                                       style="border: 2px solid #edf2f7; border-radius: 0 10px 10px 0;">
                            </div>

                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-uppercase text-muted">
                                Mô tả nội dung <span class="text-danger">*</span>
                            </label>

                            <textarea name="description"
                                      id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Cung cấp thông tin chi tiết về căn nhà..."
                                      required
                                      style="border-radius: 10px; border: 2px solid #edf2f7;">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Thư viện hình ảnh --}}
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0 text-dark">Thư viện hình ảnh</h5>
                    </div>

                    <div class="card-body p-4 pt-0">
                        <div class="upload-zone text-center p-5 border-dashed"
                             style="border: 2px dashed #cbd5e0; border-radius: 15px; background-color: #f8fafc;">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h6 class="fw-bold">Chọn ảnh sản phẩm</h6>
                            <p class="text-muted small">Hỗ trợ JPG, PNG, WEBP. Có thể chọn nhiều ảnh cùng lúc.</p>

                            <input type="file"
                                   name="images[]"
                                   multiple
                                   class="form-control mt-3 @error('images.*') is-invalid @enderror"
                                   accept="image/*"
                                   style="border-radius: 8px;">

                            @error('images')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột Phải --}}
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Giá niêm yết (VNĐ)</label>
                            <div class="input-group">
                                <input type="number"
                                       name="price"
                                       class="form-control form-control-lg fw-bold text-primary @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}"
                                       required
                                       style="border-radius: 10px 0 0 10px; border: 2px solid #edf2f7;">
                                <span class="input-group-text bg-light border-2" style="border: 2px solid #edf2f7; border-left: 0;">VNĐ</span>
                            </div>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Diện tích (m²)</label>
                            <div class="input-group">
                                <input type="number"
                                       name="area"
                                       class="form-control fw-bold @error('area') is-invalid @enderror"
                                       value="{{ old('area') }}"
                                       required
                                       style="border-radius: 10px 0 0 10px; border: 2px solid #edf2f7;">
                                <span class="input-group-text bg-light border-2" style="border: 2px solid #edf2f7; border-left: 0;">m²</span>
                            </div>
                            @error('area')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">
                                    <i class="fas fa-bed me-1"></i> Phòng ngủ
                                </label>
                                <input type="number"
                                       name="bedrooms"
                                       class="form-control @error('bedrooms') is-invalid @enderror"
                                       value="{{ old('bedrooms', 0) }}"
                                       style="border-radius: 8px; border: 2px solid #edf2f7;">
                                @error('bedrooms')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">
                                    <i class="fas fa-bath me-1"></i> Phòng tắm
                                </label>
                                <input type="number"
                                       name="bathrooms"
                                       class="form-control @error('bathrooms') is-invalid @enderror"
                                       value="{{ old('bathrooms', 0) }}"
                                       style="border-radius: 8px; border: 2px solid #edf2f7;">
                                @error('bathrooms')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4" style="border-top: 2px dashed #edf2f7;">

                        {{-- FIX: is_furnished gửi 0/1 --}}
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="is_furnished" value="0">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_furnished"
                                   id="is_furnished"
                                   value="1"
                                   {{ old('is_furnished') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_furnished">Nội thất đầy đủ</label>
                        </div>
                        @error('is_furnished')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        {{-- FIX: status gửi 0/1 --}}
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="status"
                                   id="status"
                                   value="1"
                                   {{ old('status') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-success" for="status">Duyệt hiển thị ngay</label>
                        </div>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 text-center"
                     style="border-radius: 15px; background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                    <p class="text-white opacity-75 small mb-3">
                        <i class="fas fa-info-circle me-1"></i> Tin đăng sẽ được gửi trực tiếp lên máy chủ.
                    </p>

                    <button type="submit"
                            class="btn btn-light w-100 py-3 fw-bold text-primary shadow-sm mb-2"
                            style="border-radius: 12px;">
                        <i class="fas fa-paper-plane me-2"></i>XÁC NHẬN ĐĂNG BÀI
                    </button>

                    <a href="{{ route('index-true-sale-post-admin', ['type' => old('type','sale')]) }}"
                       class="btn btn-link text-white text-decoration-none fw-semibold small opacity-75"
                       up-follow up-target=".main-content">
                        Hủy bỏ
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .btn-check:checked + .btn-outline-primary { background-color: #4f46e5; color: white; border-color: #4f46e5; }
    .btn-check:checked + .btn-outline-info { background-color: #0dcaf0; color: white; border-color: #0dcaf0; }
    .border-dashed { transition: all 0.3s ease; }
    .border-dashed:hover { border-color: #4f46e5 !important; background-color: #eff6ff !important; }
    .form-control:focus { border-color: #4f46e5 !important; box-shadow: none; }
    .btn-light:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }
</style>
@endsection
