@extends('admin.layout')

@section('content')
<style>
    :root { --admin-primary: #6c5ce7; }
    .report-card { border-radius: 15px; overflow: hidden; }
    .table thead th { 
        background-color: #f8f9fa; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 0.5px; 
        font-weight: 700;
        color: #636e72;
        border-bottom: none;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    .btn-action {
        border-radius: 8px;
        padding: 5px 12px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .avatar-circle {
        width: 35px;
        height: 35px;
        background: #eee;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: var(--admin-primary);
        margin-right: 10px;
    }
</style>

<div class="container-fluid py-4">
    {{-- KHỐI THÔNG BÁO TRẠNG THÁI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i><strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold mb-0">Quản lý Báo cáo</h4>
            <p class="text-muted small">Kiểm duyệt và xử lý các nội dung vi phạm cộng đồng</p>
        </div>
    </div>

    <div class="card report-card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Danh sách yêu cầu</h6>
            <span class="badge bg-soft-danger text-danger px-3">{{ $reports->total() }} báo cáo</span>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Người báo cáo</th>
                            <th>Nội dung bị khiếu nại</th>
                            <th>Lý do & Chi tiết</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle small">
                                        {{ substr($report->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-0">{{ $report->user->name }}</div>
                                        <div class="text-muted small" style="font-size: 0.7rem;">ID: #{{ $report->user_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($report->salePost)
                                    <div class="fw-bold text-dark mb-1">{{ Str::limit($report->salePost->title, 35) }}</div>
                                    <a href="{{ route('show-sale-post-admin', $report->sale_post_id) }}" target="_blank" class="small text-primary text-decoration-none">
                                        <i class="fas fa-external-link-alt me-1"></i>Xem bài đăng
                                    </a>
                                @else
                                    <span class="badge bg-light text-danger status-badge">Bài viết đã bị gỡ</span>
                                @endif
                            </td>
                            <td>
                                <div class="badge bg-danger bg-opacity-10 text-danger mb-1" style="font-size: 0.7rem;">{{ $report->reason }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $report->content ?? 'Không có mô tả thêm' }}</div>
                            </td>
                            <td>
                                @if($report->status == 0)
                                    <span class="status-badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-clock me-1"></i>Chờ xử lý
                                    </span>
                                @elseif($report->status == 1)
                                    <span class="status-badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-check-circle me-1"></i>Đã gỡ bài
                                    </span>
                                @else
                                    <span class="status-badge bg-secondary bg-opacity-10 text-secondary">
                                        <i class="fas fa-times-circle me-1"></i>Đã bác bỏ
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($report->status == 0)
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="{{ route('update-report-admin', $report->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="btn btn-sm btn-success btn-action" onclick="return confirm('Bạn chắc chắn muốn gỡ bài viết này?')">
                                                Duyệt lỗi
                                            </button>
                                        </form>
                                        <form action="{{ route('update-report-admin', $report->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary btn-action">
                                                Bỏ qua
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small fw-italic">{{ $report->updated_at->diffForHumans() }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-4">
            <div class="d-flex justify-content-center">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection