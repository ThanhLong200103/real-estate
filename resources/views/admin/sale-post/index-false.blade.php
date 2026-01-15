@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title mb-0"><i class="fas fa-user-check header-icon"></i>Duyệt Bất Động Sản</h2>
        <p class="text-muted small mb-0">Yêu cầu đăng tin đang chờ xử lý từ người dùng</p>
    </div>
    <a href="{{ route('create-sale-post-admin') }}" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 10px;">
        <i class="fas fa-plus me-2"></i>Tạo bài viết mới
    </a>
</div>

@if($rentPosts->count() > 0)
    <div class="card card-table shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Thông tin bài đăng</th>
                        <th>Giá & Diện tích</th>
                        <th>Thông số</th>
                        <th>Địa chỉ</th>
                        <th>Ảnh</th>
                        <th class="text-center">Quyết định</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentPosts as $post)
                        <tr>
                            <td class="text-center text-muted fw-bold">#{{ $post->id }}</td>
                            <td style="max-width: 250px;">
                                <div class="fw-bold text-dark mb-1">{{ $post->title }}</div>
                                <div class="small text-muted">
                                    <i class="fas fa-user-circle me-1"></i>{{ $post->user->name ?? 'Khách' }}
                                </div>
                            </td>
                            <td>
                                <div class="price-text">{{ number_format($post->price) }} đ</div>
                                <span class="area-badge">{{ $post->area }} m²</span>
                            </td>
                            <td class="property-spec">
                                <div class="mb-1"><i class="fas fa-bed"></i> {{ $post->bedrooms }} PN</div>
                                <div><i class="fas fa-bath"></i> {{ $post->bathrooms }} PT</div>
                            </td>
                            <td>
                                <span class="small text-muted" title="{{ $post->address }}">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ Str::limit($post->address, 25) }}
                                </span>
                            </td>
                            <td>
                                @php $firstImage = $post->images->first(); @endphp
                                @if($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image_url) }}" 
                                         class="rounded shadow-sm border" style="width:60px; height:45px; object-fit:cover;">
                                @else
                                    <div class="bg-light rounded border text-center" style="width:60px; height:45px; line-height:45px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Nút Duyệt --}}
                                    <form action="{{ route('approve-sale-post-admin', $post->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-approve px-3 py-2" onclick="return confirm('Duyệt bài đăng này?');">
                                            <i class="fas fa-check me-1"></i> Duyệt
                                        </button>
                                    </form>
                                    
                                    {{-- Nhóm hành động phụ --}}
                                    <div class="btn-group btn-action-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                        <a href="{{ route('show-sale-post-admin', $post->id) }}" class="btn btn-sm text-info" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('edit-sale-post-admin', $post->id) }}" class="btn btn-sm text-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('destroy-sale-post-admin', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa vĩnh viễn bài này?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $rentPosts->links() }}
        </div>
    </div>
@else
    <div class="empty-state text-center shadow-sm">
        <div class="mb-4">
            <i class="fas fa-check-double fa-4x" style="color: #10b981; opacity: 0.5;"></i>
        </div>
        <h4 class="fw-bold">Hệ thống đã sạch bài chờ!</h4>
        <p class="text-muted">Hiện tại không có yêu cầu phê duyệt bất động sản nào.</p>
        <a href="{{ route('index-true-sale-post-admin') }}" class="btn btn-outline-primary btn-sm mt-2">
            Xem bài đã duyệt
        </a>
    </div>
@endif
@endsection