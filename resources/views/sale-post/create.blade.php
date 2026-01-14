<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   <div class="container">
    <h2 class="mb-4">Tạo bài đăng bán</h2>

    {{-- Thông báo lỗi validate --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('store-sale-post') }}" method="POST" novalidate enctype="multipart/form-data">
        @csrf

       <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Giá</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Diện tích (m²)</label>
                <input type="number" name="area" class="form-control" value="{{ old('area') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Phòng ngủ</label>
                <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Phòng tắm</label>
                <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Nội thất</label>
            <select name="is_furnished" class="form-select">
                <option value="1" {{ old('is_furnished') == 1 ? 'selected' : '' }}>Có</option>
                <option value="0" {{ old('is_furnished') == 0 ? 'selected' : '' }}>Không</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh</label>
            <input type="file" name="image_url[]" accept="image/*">
            {{-- @error('image_url')
                <div class="text-danger">{{$message}}</div>
            @enderror --}}
        </div>

        <button type="submit" class="btn btn-primary">
            Đăng bài
        </button>
    </form>
</div>
</body>
</html>
