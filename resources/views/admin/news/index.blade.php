@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Quản lý Tin tức</h2>
    <span class="text-muted"><i class="far fa-calendar-alt me-2"></i>{{ date('d/m/Y') }}</span>
</div>

{{-- KHỐI THÔNG BÁO FLASH --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div><strong>Thành công!</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

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
                                
                                {{-- FORM XÓA TÙY CHỈNH --}}
                                <form action="{{ route('destroy-news-admin', $post->id) }}" method="POST" class="d-inline delete-news-form" up-submit up-target=".main-content">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-action text-danger btn-trigger-delete" 
                                            data-title="{{ e($post->title) }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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

{{-- MODAL XÁC NHẬN XÓA TÙY CHỈNH --}}
<div class="modal fade" id="deleteNewsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa bài viết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <p class="text-muted">Bạn có chắc chắn muốn xóa bài viết tin tức này không? Hành động này không thể hoàn tác.</p>
                <div class="p-3 bg-light rounded-3 border">
                    <strong id="newsTitleDisplay" class="text-dark"></strong>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" id="btnConfirmDeleteNews">Đồng ý xóa</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Hiển thị Toast thành công nếu có session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        // 2. Xử lý Modal xóa tùy chỉnh
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteNewsModal'));
        const titleDisplay = document.getElementById('newsTitleDisplay');
        const confirmBtn = document.getElementById('btnConfirmDeleteNews');
        let currentForm = null;

        // Bắt sự kiện click vào nút xóa
        document.querySelectorAll('.btn-trigger-delete').forEach(button => {
            button.addEventListener('click', function() {
                currentForm = this.closest('.delete-news-form');
                titleDisplay.textContent = this.getAttribute('data-title');
                deleteModal.show();
            });
        });

        // Bắt sự kiện xác nhận xóa trên Modal
        confirmBtn.addEventListener('click', function() {
            if (currentForm) {
                deleteModal.hide();
                currentForm.submit();
            }
        });
    });
</script>
@endsection