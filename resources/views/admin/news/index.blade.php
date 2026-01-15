@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Quản lý Tin tức</h2>
    <span class="text-muted"><i class="far fa-calendar-alt me-2"></i>{{ date('d/m/Y') }}</span>
</div>

<div class="card card-table shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Danh sách bài viết tin tức</h5>
        <a href="{{ route('create-news-admin') }}" class="btn btn-success btn-sm px-3" up-follow up-target=".main-content">
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
                        <th class="text-end pe-4">Hành động</th>
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
                            <span class="badge {{ $post->status ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $post->status ? 'Đã xuất bản' : 'Bản nháp' }}
                            </span>
                        </td>
                        <td>
                            @php $firstImage = $post->images->first(); @endphp
                            @if($firstImage)
                                <img src="{{ str_starts_with($firstImage->image_url, 'http') ? $firstImage->image_url : asset('storage/' . $firstImage->image_url) }}" 
                                     class="rounded shadow-sm" style="width:60px; height:40px; object-fit:cover;">
                            @else
                                <div class="bg-light rounded text-center" style="width:60px; height:40px; line-height:40px;"><i class="fas fa-image text-muted"></i></div>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('show-news-admin', $post->id) }}" class="btn btn-sm btn-action text-info" up-follow up-target=".main-content"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('edit-news-admin', $post->id) }}" class="btn btn-sm btn-action text-warning" up-follow up-target=".main-content"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('destroy-news-admin', $post->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-action text-danger" onclick="return confirm('Xóa?')"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5">Chưa có bài viết nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection