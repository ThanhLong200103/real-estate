<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #f4f7f6; font-family: 'Plus Jakarta Sans', sans-serif; }
    .form-container { max-width: 850px; margin: 50px auto; }
    .form-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: none; }
    .card-header-gradient { background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); padding: 40px; color: white; text-align: center; }
    .form-body { padding: 40px; }
    .section-title { font-size: 14px; font-weight: 800; color: #6c5ce7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .section-title::after { content: ""; flex: 1; height: 1px; background: #eee; }
    .form-label { font-weight: 600; color: #2d3436; font-size: 14px; }
    .form-control, .form-select { padding: 12px 15px; border-radius: 12px; border: 1px solid #dfe6e9; background-color: #f8f9fa; }
    .form-control:focus { background-color: white; border-color: #6c5ce7; box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); }
    .btn-submit { background: #6c5ce7; color: white; padding: 15px 30px; border-radius: 12px; border: none; font-weight: 700; transition: 0.3s; }
    .btn-submit:hover { background: #5a4bcf; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3); }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="card-header-gradient">
            <h2 class="fw-800 m-0">Đăng Tin Bất Động Sản</h2>
            <p class="opacity-75 mb-0 mt-2">Cung cấp thông tin chính xác để thu hút người mua</p>
        </div>
        
        <div class="form-body">
            <form action="{{ route('store-sale-post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="section-title">1. Thông tin cơ bản</div>
                <div class="mb-3">
                    <label class="form-label">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Ví dụ: Căn hộ cao cấp 2PN trung tâm Quận 1" required>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Diện tích (m²) <span class="text-danger">*</span></label>
                        <input type="number" name="area" class="form-control" required>
                    </div>
                </div>

                <div class="section-title">2. Chi tiết bất động sản</div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                    <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường, Phường/Xã..." required>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Phòng ngủ</label>
                        <input type="number" name="bedrooms" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phòng tắm</label>
                        <input type="number" name="bathrooms" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nội thất</label>
                        <select name="is_furnished" class="form-select">
                            <option value="0">Chưa có</option>
                            <option value="1">Đầy đủ</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Mô tả ưu điểm, tiện ích xung quanh..."></textarea>
                </div>

                <div class="section-title">3. Hình ảnh</div>
                <div class="mb-4">
                    <div class="p-4 border-2 border-dashed rounded-4 text-center bg-light">
                        <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                        <input type="file" name="image_url[]" class="form-control" multiple>
                        <small class="text-muted d-block mt-2">Nên chọn ít nhất 3 ảnh rõ nét.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted fw-bold">Hủy bỏ</a>
                    <button type="submit" class="btn-submit px-5">Gửi duyệt tin ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>