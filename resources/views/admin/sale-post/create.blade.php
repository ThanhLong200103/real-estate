@extends('admin.layout')

@section('content')
    {{-- CSS Tùy chỉnh --}}
    <style>
        .custom-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
        }

        .input-group-custom:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .input-group-custom .input-group-text {
            background: #f8fafc;
            border: none;
            color: #94a3b8;
            padding: 0 15px;
            height: 100%;
        }

        .input-group-custom .form-control,
        .input-group-custom .form-select {
            border: none;
            padding: 0.6rem 1rem;
            flex: 1;
        }

        .input-group-custom .form-control:focus,
        .input-group-custom .form-select:focus {
            box-shadow: none;
            outline: none;
        }

        .btn-submit-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border: none;
            color: white;
            font-weight: 700;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-submit-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3);
            color: white;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-2">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Bất động sản</a>
                        </li>
                        <li class="breadcrumb-item active fw-bold" aria-current="page">Tạo bài đăng mới</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>Thêm bài đăng mới
                    </h2>
                    <a href="{{ route('index-true-sale-post-admin') }}" class="btn btn-outline-secondary px-3 shadow-sm"
                        style="border-radius: 10px;">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>

        {{-- Hiển thị tất cả lỗi validation để dễ debug --}}
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('store-sale-post-admin') }}" method="POST" enctype="multipart/form-data"
            id="createPostForm">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                {{-- Cột Trái: Nội dung chính --}}
                <div class="col-xl-8 col-lg-7">
                    <div class="card custom-card">
                        <div class="card-header py-3 bg-white">
                            <h5 class="fw-bold mb-0 text-dark">Thông tin cơ bản</h5>
                        </div>

                        <div class="card-body p-4">
                            {{-- HÀNG 1: Loại giao dịch & Loại hình BĐS --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-custom">Loại giao dịch <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <input type="radio" class="btn-check" name="type" id="type_sale" value="sale"
                                            {{ old('type', 'sale') == 'sale' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary flex-fill py-2 fw-bold" for="type_sale"
                                            style="border-radius: 10px;">CẦN BÁN</label>

                                        <input type="radio" class="btn-check" name="type" id="type_rent" value="rent"
                                            {{ old('type') == 'rent' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info flex-fill py-2 fw-bold" for="type_rent"
                                            style="border-radius: 10px;">CHO THUÊ</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">Loại hình bất động sản <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <select name="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror" required>
                                            <option value="">-- Chọn loại hình --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Tiêu đề --}}
                            <div class="mb-4">
                                <label class="form-label-custom">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Ví dụ: Căn hộ chung cư cao cấp..."
                                        required>
                                </div>
                            </div>

                            {{-- Địa chỉ 3 cấp --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Tỉnh / Thành phố <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                        <select name="province_id" id="province_id" class="form-select" required>
                                            <option value="">-- Chọn Tỉnh --</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                    {{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label-custom">Quận / Huyện <span class="text-danger">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text"><i class="fas fa-map-signs"></i></span>
                                        <select name="district_id" id="district_id" class="form-select" required>
                                            <option value="">-- Chọn Quận --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label-custom">Phường / Xã <span class="text-danger">*</span></label>
                                    <div class="input-group-custom">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <select name="ward_id" id="ward_id" class="form-select" required>
                                            <option value="">-- Chọn Xã --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Số nhà, tên đường <span
                                        class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ old('address') }}" placeholder="Số nhà, tên ngõ, tên đường..."
                                        required>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label-custom">Mô tả chi tiết <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="6"
                                    style="border: 2px solid #f1f5f9; border-radius: 12px;" required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Thư viện ảnh --}}
                    <div class="card custom-card">
                        <div class="card-header py-3 bg-white d-flex justify-content-between">
                            <h5 class="fw-bold mb-0 text-dark">Thư viện hình ảnh <span class="text-danger">*</span></h5>
                            <small class="text-muted">Tối thiểu 1 ảnh</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="p-4 text-center"
                                style="border: 2px dashed #cbd5e0; border-radius: 15px; background-color: #f8fafc;">
                                <i class="fas fa-images fa-3x text-primary mb-3"></i>
                                <input type="file" name="images[]" multiple class="form-control" accept="image/*"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cột Phải --}}
                <div class="col-xl-4 col-lg-5">
                    <div class="card custom-card">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label-custom">Giá niêm yết (VNĐ) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <input type="number" name="price" class="form-control fw-bold text-primary"
                                        value="{{ old('price') }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Diện tích (m²) <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <input type="number" name="area" class="form-control fw-bold"
                                        value="{{ old('area') }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label-custom">Phòng ngủ</label>
                                    <div class="input-group-custom">
                                        <input type="number" name="bedrooms" class="form-control"
                                            value="{{ old('bedrooms', 0) }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Phòng tắm</label>
                                    <div class="input-group-custom">
                                        <input type="number" name="bathrooms" class="form-control"
                                            value="{{ old('bathrooms', 0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_furnished" id="is_furnished"
                                    value="1" {{ old('is_furnished') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_furnished">Nội thất đầy đủ</label>
                            </div>

                            <button type="submit"
                                class="btn btn-submit-gradient w-100 py-3 mt-3 shadow-sm text-uppercase">
                                <i class="fas fa-check-circle me-2"></i> Xác nhận đăng bài
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- AJAX Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const districtSelect = document.getElementById('district_id');
            const wardSelect = document.getElementById('ward_id');

            // Hàm load Quận/Huyện
            async function loadDistricts(provinceId, selectedDistrictId = null) {
                if (!provinceId) return;
                districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                try {
                    const res = await fetch(`/api/get-districts/${provinceId}`);
                    const data = await res.json();
                    districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                    data.forEach(d => {
                        const selected = d.id == selectedDistrictId ? 'selected' : '';
                        districtSelect.innerHTML +=
                            `<option value="${d.id}" ${selected}>${d.name}</option>`;
                    });
                    if (selectedDistrictId) {
                        loadWards(selectedDistrictId, "{{ old('ward_id') }}");
                    }
                } catch (error) {
                    console.error('Lỗi load Quận:', error);
                }
            }

            // Hàm load Phường/Xã
            async function loadWards(districtId, selectedWardId = null) {
                if (!districtId) return;
                wardSelect.innerHTML = '<option value="">Đang tải...</option>';
                try {
                    const res = await fetch(`/api/get-wards/${districtId}`);
                    const data = await res.json();
                    wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                    data.forEach(w => {
                        const selected = w.id == selectedWardId ? 'selected' : '';
                        wardSelect.innerHTML +=
                        `<option value="${w.id}" ${selected}>${w.name}</option>`;
                    });
                } catch (error) {
                    console.error('Lỗi load Xã:', error);
                }
            }

            // Xử lý khi người dùng thay đổi Tỉnh
            provinceSelect.addEventListener('change', function() {
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                loadDistricts(this.value);
            });

            // Xử lý khi người dùng thay đổi Quận
            districtSelect.addEventListener('change', function() {
                loadWards(this.value);
            });

            // QUAN TRỌNG: Tự động load lại dữ liệu cũ khi Validation fail
            const oldProvinceId = "{{ old('province_id') }}";
            const oldDistrictId = "{{ old('district_id') }}";

            if (oldProvinceId) {
                loadDistricts(oldProvinceId, oldDistrictId);
            }
        });
    </script>

@endsection
