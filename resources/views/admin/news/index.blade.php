@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">Bảng điều khiển</h2>
    <span class="text-muted"><i class="far fa-calendar-alt me-2"></i>{{ date('d/m/Y') }}</span>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-stats bg-white shadow-sm border-start border-primary border-5">
            <div class="card-body position-relative">
                <h6 class="text-muted text-uppercase small fw-bold">Tổng Tin Tức</h6>
                <h3 class="fw-bold mb-0">{{ $rentPosts->total() }}</h3>
                <i class="fas fa-newspaper stat-icon text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stats bg-white shadow-sm border-start border-warning border-5">
            <div class="card-body position-relative">
                <h6 class="text-muted text-uppercase small fw-bold">Chờ duyệt (BĐS)</h6>
                <h3 class="fw-bold mb-0">{{ $pendingPostsCount ?? 0 }}</h3>
                <i class="fas fa-hourglass-half stat-icon text-warning"></i>
            </div>
        </div>
    </div>
</div>

<div class="card card-table shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Danh sách bài viết tin tức</h5>
        <a href="{{ route('create-news-admin') }}" class="btn btn-success btn-sm px-3">
            <i class="fas fa-plus me-2"></i>Thêm bài viết
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="50">ID</th>
                        <th>Nội dung bài viết</th>
                        <th>Trạng thái</th>
                        <th>Hình ảnh</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentPosts as $post)
                    <tr>
                        <td class="text-center text-muted">#{{ $post->id }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $post->title }}</div>
                            <small class="text-muted">Ngày đăng: {{ $post->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            @if($post->status)
                                <span class="badge bg-success">Đã xuất bản</span>
                            @else
                                <span class="badge bg-warning text-dark">Bản nháp</span>
                            @endif
                        </td>
                        <td>
                            @php $firstImage = $post->images->first(); @endphp
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage->image_url) }}" 
                                     class="rounded shadow-sm" style="width:60px; height:40px; object-fit:cover;">
                            @else
                                <div class="bg-light rounded text-center" style="width:60px; height:40px; line-height:40px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                <a href="{{ route('show-news-admin', $post->id) }}" class="btn btn-sm btn-action text-info" title="Xem"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('edit-news-admin', $post->id) }}" class="btn btn-sm btn-action text-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('destroy-news-admin', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa bài viết này?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action text-danger" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                            Chưa có bài viết nào được tìm thấy.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $rentPosts->links() }}
    </div>
</div>
@endsection