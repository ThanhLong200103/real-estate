@extends('admin.layout')

@section('content')
@php
    $item = $salePost ?? $post ?? $item ?? null;
@endphp

<style>
    /* Nâng cấp giao diện đồng bộ với trang Create */
    .edit-card { border: none; border-radius: 20px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .form-label { font-weight: 700; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
    
    .input-group-custom {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        background: #fff;
        display: flex;
        align-items: center;
    }
    .input-group-custom:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .input-group-custom .input-group-text { background: #f8fafc; border: none; color: #94a3b8; padding: 0 15px; }
    .input-group-custom .form-control, .input-group-custom .form-select { border: none; padding: 0.75rem 1rem; flex: 1; font-weight: 500; }
    .input-group-custom .form-control:focus, .input-group-custom .form-select:focus { box-shadow: none; outline: none; }

    .img-edit-preview { width: 100%; height: 100px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; transition: transform 0.2s; }
    .img-edit-preview:hover { transform: scale(1.05); }
    
    .section-title { font-size: 1rem; font-weight: 800; color: #1e293b; margin-bottom: 1.25rem; display: flex; align-items: center; }
    .section-title i { width: 30px; height: 30px; background: #e0e7ff; color: #4f46e5; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 0.85rem; }
    
    .btn-save { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); border: none; }
    .btn-save:hover { opacity: 0.9; transform: translateY(-1px); }
</style>

<div class="container-fluid py-4">
    {{-- Breadcrumb & Header --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route($item->status ? 'index-true-sale-post-admin' : 'index-false-sale-post-admin', ['type' => $item->type]) }}"
                           class="text-muted text-decoration-none">Quản lý bài đăng</a>
                    </li>
                    <li class="breadcrumb-item active text-primary fw-bold">Chỉnh sửa</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark m-0">Cập nhật nội dung #{{ $item->id }}</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <ul class="mb-0 small fw-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('update-sale-post-admin', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- Cột trái: Nội dung chính --}}
            <div class="col-lg-8">
                <div class="card edit-card p-4 mb-4">
                    <div class="section-title"><i class="fas fa-edit"></i> Thông tin bài đăng</div>

                    <div class="mb-4">
                        <label class="form-label">Tiêu đề bài đăng <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Loại hình giao dịch</label>
                            <div class="input-group-custom">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <select name="type" class="form-select">
                                    <option value="sale" {{ old('type', $item->type) == 'sale' ? 'selected' : '' }}>Bán bất động sản</option>
                                    <option value="rent" {{ old('type', $item->type) == 'rent' ? 'selected' : '' }}>Cho thuê</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Danh mục BĐS</label>
                            <div class="input-group-custom">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <select name="category_id" class="form-select">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- VỊ TRÍ 3 CẤP MỚI --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tỉnh / Thành phố</label>
                            <div class="input-group-custom">
                                <span class="input-group-text"><i class="fas fa-map"></i></span>
                                <select name="province_id" id="province_id" class="form-select" required>
                                    <option value="">-- Chọn Tỉnh/Thành --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ old('province_id', $item->province_id) == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quận / Huyện</label>
                            <div class="input-group-custom">
                                <span class="input-group-text"><i class="fas fa-map-signs"></i></span>
                                <select name="district_id" id="district_id" class="form-select" required>
                                    <option value="">-- Chọn Quận/Huyện --</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ old('district_id', $item->district_id) == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phường / Xã</label>
                            <div class="input-group-custom">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <select name="ward_id" id="ward_id" class="form-select" required>
                                    <option value="">-- Chọn Phường/Xã --</option>
                                    @if(isset($wards))
                                        @foreach($wards as $ward)
                                            <option value="{{ $ward->id }}" {{ old('ward_id', $item->ward_id) == $ward->id ? 'selected' : '' }}>
                                                {{ $ward->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Địa chỉ chi tiết (Số nhà, đường...)</label>
                        <div class="input-group-custom">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $item->address) }}" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Mô tả nội dung</label>
                        <textarea name="description" class="form-control" rows="6" style="border: 2px solid #f1f5f9; border-radius: 12px; padding: 1rem;" required>{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>

                <div class="card edit-card p-4">
                    <div class="section-title"><i class="fas fa-ruler-combined"></i> Thông số kỹ thuật</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Giá niêm yết</label>
                            <div class="input-group-custom">
                                <input type="number" name="price" class="form-control fw-bold text-primary" value="{{ old('price', $item->price) }}" required>
                                <span class="input-group-text fw-bold">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Diện tích</label>
                            <div class="input-group-custom">
                                <input type="number" name="area" class="form-control fw-bold" value="{{ old('area', $item->area) }}" required>
                                <span class="input-group-text fw-bold">m²</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phòng ngủ</label>
                            <div class="input-group-custom"><input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $item->bedrooms) }}"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phòng tắm</label>
                            <div class="input-group-custom"><input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $item->bathrooms) }}"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nội thất</label>
                            <div class="input-group-custom">
                                <select name="is_furnished" class="form-select">
                                    <option value="1" {{ (string)old('is_furnished', $item->is_furnished) === '1' ? 'selected' : '' }}>Đầy đủ</option>
                                    <option value="0" {{ (string)old('is_furnished', $item->is_furnished) === '0' ? 'selected' : '' }}>Cơ bản / Trống</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột phải: Ảnh & Trạng thái --}}
            <div class="col-lg-4">
                <div class="card edit-card p-4 mb-4">
                    <div class="section-title"><i class="fas fa-images"></i> Album ảnh</div>
                    <div class="row g-2 mb-4">
                        @forelse($item->images as $img)
                            <div class="col-4">
                                <img src="{{ asset('storage/' . $img->image_url) }}" class="img-edit-preview shadow-sm">
                            </div>
                        @empty
                            <div class="col-12 text-center py-3 bg-light rounded-3">
                                <p class="text-muted small mb-0 italic text-center">Chưa có hình ảnh</p>
                            </div>
                        @endforelse
                    </div>
                    <label class="form-label">Thay đổi album ảnh</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" style="border-radius: 10px;">
                    <p class="text-muted x-small mt-2 italic">* Tải ảnh mới sẽ thay thế toàn bộ ảnh hiện tại.</p>
                </div>

                <div class="card edit-card p-4 mb-4">
                    <div class="section-title"><i class="fas fa-toggle-on"></i> Hiển thị</div>
                    <input type="hidden" name="status" value="0">
                    <div class="form-check form-switch p-0 d-flex justify-content-between align-items-center">
                        <label class="form-check-label fw-bold text-dark" for="statusSwitch">Công khai bài đăng</label>
                        <input class="form-check-input ms-0" type="checkbox" name="status" id="statusSwitch" value="1" 
                               {{ old('status', $item->status) ? 'checked' : '' }} style="width: 2.5em; height: 1.25em;">
                    </div>
                </div>

                <div class="sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card edit-card p-3 shadow-lg border-primary border-opacity-10">
                        <button type="submit" class="btn btn-primary btn-save w-100 rounded-pill fw-bold py-3 mb-2 shadow">
                            <i class="fas fa-save me-2"></i> CẬP NHẬT NGAY
                        </button>
                        <a href="{{ route('index-true-sale-post-admin', ['type' => $item->type]) }}" 
                           class="btn btn-outline-secondary w-100 rounded-pill fw-bold py-2">
                            Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Script jQuery xử lý 3 cấp địa chỉ --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // 1. Khi thay đổi Tỉnh/Thành
        $('#province_id').on('change', function () {
            var provinceId = $(this).val();
            $('#district_id').html('<option value="">Đang tải...</option>');
            $('#ward_id').html('<option value="">-- Chọn Phường/Xã --</option>');

            if (provinceId) {
                $.ajax({
                    url: '/api/get-districts/' + provinceId,
                    type: 'GET',
                    success: function (data) {
                        var html = '<option value="">-- Chọn Quận/Huyện --</option>';
                        $.each(data, function (key, district) {
                            html += '<option value="' + district.id + '">' + district.name + '</option>';
                        });
                        $('#district_id').html(html);
                    }
                });
            } else {
                $('#district_id').html('<option value="">-- Chọn Quận/Huyện --</option>');
            }
        });

        // 2. Khi thay đổi Quận/Huyện
        $('#district_id').on('change', function () {
            var districtId = $(this).val();
            $('#ward_id').html('<option value="">Đang tải...</option>');

            if (districtId) {
                $.ajax({
                    url: '/api/get-wards/' + districtId,
                    type: 'GET',
                    success: function (data) {
                        var html = '<option value="">-- Chọn Phường/Xã --</option>';
                        $.each(data, function (key, ward) {
                            html += '<option value="' + ward.id + '">' + ward.name + '</option>';
                        });
                        $('#ward_id').html(html);
                    }
                });
            } else {
                $('#ward_id').html('<option value="">-- Chọn Phường/Xã --</option>');
            }
        });
    });
</script>
@endsection