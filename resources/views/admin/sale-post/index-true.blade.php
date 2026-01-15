@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title mb-0"><i class="fas fa-list-alt header-icon"></i>Tin Đang Hiển Thị</h2>
        <p class="text-muted small mb-0">Danh sách bất động sản đang hoạt động trên hệ thống</p>
    </div>
    <a href="{{ route('create-sale-post-admin') }}" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 10px;">
        <i class="fas fa-plus me-2"></i>Tạo bài mới
    </a>
</div>

@if($rentPosts->count() > 0)
    <div class="card card-table shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Thông tin BĐS</th>
                        <th>Giá niêm yết</th>
                        <th>Diện tích</th>
                        <th>Cơ sở vật chất</th>
                        <th>Khu vực</th>
                        <th>Ảnh minh họa</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentPosts as $post)
                        <tr>
                            <td class="text-center text-muted fw-bold">#{{ $post->id }}</td>
                            <td style="max-width: 250px;">
                                <div class="fw-bold text-dark mb-1">{{ $post->title }}</div>
                                <div class="d-flex align-items-center small text-success fw-medium">
                                    <span class="status-online"></span> Đang hiển thị
                                </div>
                            </td>
                            <td><div class="price-text">{{ number_format($post->price) }} đ</div></td>
                            <td><span class="fw-semibold">{{ $post->area }} m²</span></td>
                            <td>
                                <span class="badge badge-info-custom me-1">{{ $post->bedrooms }} PN</span>
                                <span class="badge bg-light text-dark border">{{ $post->bathrooms }} PT</span>
                            </td>
                            <td>
                                <small class="text-muted" title="{{ $post->address }}">
                                    <i class="fas fa-map-marker-alt text-secondary me-1"></i>{{ Str::limit($post->address, 25) }}
                                </small>
                            </td>
                            <td>
                                @php $firstImage = $post->images->first(); @endphp
                                @if($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image_url) }}" 
                                         class="rounded shadow-sm border" style="width:60px; height:42px; object-fit:cover;">
                                @else
                                    <div class="bg-light rounded border text-center text-muted small" style="width:60px; height:42px; line-height:42px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                    <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn btn-sm btn-action" title="Xem bài đăng"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-sm btn-action" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn gỡ bài viết này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-action btn-delete" title="Gỡ bài"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <p class="text-muted small">Hiển thị {{ $rentPosts->count() }} trên tổng số {{ $rentPosts->total() }} bài đăng</p>
        {{ $rentPosts->links() }}
    </div>
@else
    <div class="text-center py-5 bg-white rounded shadow-sm border">
        <i class="fas fa-layer-group fa-3x text-light mb-3"></i>
        <h5 class="text-muted">Chưa có bất động sản nào công khai</h5>
    </div>
@endif
@endsection