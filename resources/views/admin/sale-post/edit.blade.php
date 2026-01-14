<div class="container mt-4">
    <h2>Chỉnh sửa bài đăng: {{ $rentPost->title }}</h2>

    <form action="{{ route('update-sale-post-admin', $rentPost->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $rentPost->title) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $rentPost->description) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Giá (VNĐ)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $rentPost->price) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Diện tích (m²)</label>
                <input type="number" step="0.1" name="area" class="form-control" value="{{ old('area', $rentPost->area) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $rentPost->address) }}" required>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Phòng ngủ</label>
                <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $rentPost->bedrooms) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Phòng tắm</label>
                <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $rentPost->bathrooms) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nội thất</label>
                <select name="is_furnished" class="form-select">
                    <option value="1" {{ $rentPost->is_furnished ? 'selected' : '' }}>Có</option>
                    <option value="0" {{ !$rentPost->is_furnished ? 'selected' : '' }}>Không</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label>
            <div class="d-flex flex-wrap gap-2 mb-2">
                @foreach($rentPost->images as $image)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $image->image_url) }}" style="width: 100px; height: 75px; object-fit: cover; border-radius: 4px;">
                    </div>
                @endforeach
            </div>
            <label class="form-label">Thêm ảnh mới (nếu muốn thay đổi)</label>
            <input type="file" name="image_url[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>

        {{-- Logic Quay lại thông minh --}}
        @if($rentPost->status)
            <a href="{{ route('index-true-sale-post-admin') }}" class="btn btn-secondary text-white">Quay lại</a>
        @else
            <a href="{{ route('index-false-sale-post-admin') }}" class="btn btn-secondary text-white">Quay lại</a>
        @endif
    </form>
</div>