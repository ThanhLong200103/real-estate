<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng tin mới - Bất động sản</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #2d3436;
        }

        .form-container {
            max-width: 850px;
            margin: 50px auto;
            padding: 0 15px;
        }

        .form-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            padding: 40px;
            color: white;
            text-align: center;
        }

        .form-body {
            padding: 40px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 800;
            color: #6c5ce7;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 30px 0 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .form-label {
            font-weight: 600;
            color: #2d3436;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #dfe6e9;
            background-color: #f8f9fa;
            transition: 0.3s;
        }

        .form-control:focus {
            background-color: white;
            border-color: #6c5ce7;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
            outline: none;
        }

        .transaction-type-wrapper {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .type-radio {
            display: none;
        }

        .type-label {
            flex: 1;
            padding: 15px;
            text-align: center;
            border: 2px solid #f0f0f0;
            border-radius: 16px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 700;
            color: #636e72;
            background: #fdfdfd;
        }

        .type-radio:checked+.label-sale {
            border-color: #6c5ce7;
            color: #6c5ce7;
            background: #f5f3ff;
        }

        .type-radio:checked+.label-rent {
            border-color: #00cec9;
            color: #00cec9;
            background: #e6fffe;
        }

        .upload-zone {
            border: 2px dashed #6c5ce7;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            background: #f8faff;
            cursor: pointer;
            transition: 0.3s;
        }

        .upload-zone:hover {
            background: #eff0fe;
            border-style: solid;
        }

        #imagePreview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .preview-item {
            position: relative;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #ddd;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-submit {
            background: #6c5ce7;
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            transition: 0.3s;
            width: 100%;
            max-width: 250px;
        }

        .btn-submit:hover {
            background: #5a4bcf;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        .input-group-text {
            background: #f8f9fa;
            border-radius: 12px 0 0 12px;
            border: 1px solid #dfe6e9;
            border-right: none;
        }

        .has-icon .form-control,
        .has-icon .form-select {
            border-left: none !important;
            border-radius: 0 12px 12px 0;
        }

        .is-invalid {
            border-color: #ff7675 !important;
        }

        .invalid-feedback {
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <div class="form-card">
            <div class="card-header-gradient">
                <h2 class="fw-800 m-0"><i class="fas fa-plus-circle me-2"></i>Đăng Tin Mới</h2>
                <p class="opacity-75 mb-0 mt-2">Tin đăng của bạn sẽ được hiển thị sau khi Admin phê duyệt</p>
            </div>


            <div class="form-body">
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

                    <div class="section-title">1. Hình thức & Danh mục</div>

                    <div class="transaction-type-wrapper">
                        <input type="radio" name="type" id="type_sale" value="sale" class="type-radio"
                            {{ old('type', 'sale') == 'sale' ? 'checked' : '' }}>
                        <label for="type_sale" class="type-label label-sale">
                            <i class="fas fa-hand-holding-usd me-2"></i>CẦN BÁN
                        </label>

                        <input type="radio" name="type" id="type_rent" value="rent" class="type-radio"
                            {{ old('type') == 'rent' ? 'checked' : '' }}>
                        <label for="type_rent" class="type-label label-rent">
                            <i class="fas fa-key me-2"></i>CHO THUÊ
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Danh mục bất động sản <span class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-th-large text-muted"></i></span>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="section-title">2. Vị trí địa lý</div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <div class="input-group has-icon">
                                <span class="input-group-text"><i class="fas fa-city text-muted"></i></span>
                                <select name="province_id" id="province"
                                    class="form-select @error('province_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn Tỉnh --</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}"
                                            {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Quận / Huyện <span class="text-danger">*</span></label>
                            <div class="input-group has-icon">
                                <span class="input-group-text"><i class="fas fa-map-marked-alt text-muted"></i></span>
                                <select name="district_id" id="district"
                                    class="form-select @error('district_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn Quận --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phường / Xã <span class="text-danger">*</span></label>
                            <div class="input-group has-icon">
                                <span class="input-group-text"><i class="fas fa-map text-muted"></i></span>
                                <select name="ward_id" id="ward"
                                    class="form-select @error('ward_id') is-invalid @enderror" required>
                                    <option value="">-- Chọn Phường --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Địa chỉ chi tiết (Số nhà, tên đường) <span
                                class="text-danger">*</span></label>
                        <div class="input-group has-icon">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt text-muted"></i></span>
                            <input type="text" name="address" class="form-control"
                                placeholder="Ví dụ: 123 Đường ABC..." value="{{ old('address') }}" required>
                        </div>
                    </div>

                    <div class="section-title">3. Thông tin chi tiết</div>
                    <div class="mb-4">
                        <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                            placeholder="Ví dụ: Bán căn hộ Sky Center 2PN đầy đủ nội thất..."
                            value="{{ old('title') }}" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group has-icon">
                                <span class="input-group-text"><i
                                        class="fas fa-money-bill-wave text-muted"></i></span>
                                <input type="number" name="price" class="form-control"
                                    placeholder="Ví dụ: 2500000000" value="{{ old('price') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                            <div class="input-group has-icon">
                                <span class="input-group-text"><i class="fas fa-vector-square text-muted"></i></span>
                                <input type="number" name="area" class="form-control" placeholder="Ví dụ: 75"
                                    value="{{ old('area') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Phòng ngủ</label>
                            <select name="bedrooms" class="form-select">
                                @for ($i = 0; $i <= 10; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('bedrooms') == $i ? 'selected' : '' }}>{{ $i }} phòng
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phòng tắm</label>
                            <select name="bathrooms" class="form-select">
                                @for ($i = 0; $i <= 10; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('bathrooms') == $i ? 'selected' : '' }}>{{ $i }} phòng
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nội thất</label>
                            <select name="is_furnished" class="form-select">
                                <option value="0" {{ old('is_furnished') == 0 ? 'selected' : '' }}>Cơ bản / Không
                                </option>
                                <option value="1" {{ old('is_furnished') == 1 ? 'selected' : '' }}>Đầy đủ nội
                                    thất</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="5"
                            placeholder="Mô tả ưu điểm, tiện ích xung quanh, pháp lý, hướng nhà..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="section-title">4. Hình ảnh thực tế</div>
                    <div class="mb-4">
                        <div class="upload-zone" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5 class="fw-bold">Chọn ảnh để tải lên</h5>
                            <p class="text-muted mb-0">Hỗ trợ JPG, PNG, WEBP (Tối đa 5MB/ảnh)</p>
                            <input type="file" name="images[]" id="imageInput" class="d-none" multiple
                                accept="image/*" required onchange="previewImages()">
                        </div>
                        <div id="imagePreview"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <a href="{{ route('user-sale-post-index') }}"
                            class="text-decoration-none text-muted fw-bold">
                            <i class="fas fa-arrow-left me-1"></i> Danh sách của tôi
                        </a>
                        <button type="submit" class="btn-submit shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i>Đăng bài ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            // 1. Khi thay đổi Tỉnh/Thành
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;

                // Reset và hiển thị trạng thái đang tải
                districtSelect.innerHTML = '<option value="">-- Đang tải Quận/Huyện... --</option>';
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

                if (!provinceId) {
                    districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                    return;
                }

                // Gọi đúng URL Route bạn đã đặt: /api/get-districts/{province_id}
                fetch(`/api/get-districts/${provinceId}`)
                    .then(res => res.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(err => {
                        console.error('Lỗi:', err);
                        districtSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
                    });
            });

            // 2. Khi thay đổi Quận/Huyện
            districtSelect.addEventListener('change', function() {
                const districtId = this.value;

                wardSelect.innerHTML = '<option value="">-- Đang tải Phường/Xã... --</option>';

                if (!districtId) {
                    wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                    return;
                }

                // Gọi đúng URL Route bạn đã đặt: /api/get-wards/{district_id}
                fetch(`/api/get-wards/${districtId}`)
                    .then(res => res.json())
                    .then(data => {
                        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            wardSelect.appendChild(option);
                        });
                    })
                    .catch(err => {
                        console.error('Lỗi:', err);
                        wardSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
                    });
            });
        });

        function previewImages() {
            const preview = document.querySelector('#imagePreview');
            const files = document.getElementById('imageInput').files;

            // Nếu muốn mỗi lần chọn ảnh mới sẽ xóa ảnh cũ đã hiện:
            preview.innerHTML = '';

            if (files) {
                [].forEach.call(files, readAndPreview);
            }

            function readAndPreview(file) {
                // Kiểm tra định dạng file
                if (!/\.(jpe?g|png|webp)$/i.test(file.name)) {
                    return alert(file.name + " không phải định dạng hình ảnh cho phép");
                }

                const reader = new FileReader();
                reader.addEventListener("load", function() {
                    const div = document.createElement('div');
                    div.className = 'preview-item';

                    // Tạo ảnh
                    const img = new Image();
                    img.src = this.result;

                    // Tạo nút xóa (tùy chọn nhưng nên có để tăng trải nghiệm)
                    const removeBtn = document.createElement('span');
                    removeBtn.innerHTML = '×';
                    removeBtn.style =
                        "position:absolute; top:5px; right:5px; background:rgba(255,0,0,0.7); color:white; border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; font-weight:bold;";
                    removeBtn.onclick = function() {
                        div.remove();
                        // Lưu ý: Việc xóa này chỉ xóa trên giao diện. 
                        // Để xóa thực tế trong Input File cần xử lý phức tạp hơn bằng DataTransfer.
                    };

                    div.appendChild(img);
                    div.appendChild(removeBtn);
                    preview.appendChild(div);
                });

                reader.readAsDataURL(file);
            }
        }
    </script>
    {{ dd($errors->all()) }}
</body>

</html>
