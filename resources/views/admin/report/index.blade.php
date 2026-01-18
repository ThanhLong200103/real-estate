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

    /* ✅ Modal box style đồng bộ */
    .confirm-box {
        border-radius: 14px;
        padding: 14px 16px;
        border: 1px solid rgba(0,0,0,0.05);
        background: #f8fafc;
    }
    .confirm-box.danger {
        background: #fff5f5;
        border-color: #ffe0e0;
    }
    .confirm-title {
        font-weight: 800;
        margin: 0 0 6px;
        letter-spacing: -0.2px;
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

                                        {{-- ✅ FORM DUYỆT (ACCEPT) - mở modal xác nhận --}}
                                        <form action="{{ route('update-report-admin', $report->id) }}" method="POST" class="m-0 report-form-accept">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="accept">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-success btn-action btn-open-accept-modal"
                                                data-report-id="{{ $report->id }}"
                                                data-reason="{{ e($report->reason) }}"
                                                data-title="{{ e(optional($report->salePost)->title ?? 'Bài viết đã bị gỡ') }}"
                                            >
                                                Duyệt lỗi
                                            </button>
                                        </form>

                                        {{-- ✅ FORM BỎ QUA (REJECT) - mở modal xác nhận --}}
                                        <form action="{{ route('update-report-admin', $report->id) }}" method="POST" class="m-0 report-form-reject">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="reject">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary btn-action btn-open-reject-modal"
                                                data-report-id="{{ $report->id }}"
                                                data-reason="{{ e($report->reason) }}"
                                                data-title="{{ e(optional($report->salePost)->title ?? 'Bài viết đã bị gỡ') }}"
                                            >
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

<!-- ✅ MODAL: XÁC NHẬN DUYỆT LỖI (GỠ BÀI) -->
<div class="modal fade" id="acceptReportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
      <div class="modal-header border-0 px-4 pt-4">
        <h5 class="modal-title fw-bold">
          <i class="fas fa-check-circle me-2 text-success"></i>Xác nhận duyệt báo cáo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4">
        <div class="confirm-box danger">
          <p class="confirm-title text-danger">Bạn sắp gỡ bài đăng khỏi hệ thống</p>
          <div class="small text-muted">
            <div class="mb-2">Bài bị báo cáo: <b id="acceptPostTitle"></b></div>
            <div>Lý do: <span class="badge bg-danger bg-opacity-10 text-danger" id="acceptReasonBadge"></span></div>
          </div>
        </div>

        <div class="small text-muted mt-3">
          Sau khi gỡ bài, bài đăng sẽ không còn hiển thị công khai.
        </div>
      </div>

      <div class="modal-footer border-0 px-4 pb-4">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-success rounded-pill px-4 fw-bold" id="acceptConfirmBtn">
          Xác nhận duyệt
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ✅ MODAL: XÁC NHẬN BỎ QUA (BÁC BỎ) -->
<div class="modal fade" id="rejectReportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
      <div class="modal-header border-0 px-4 pt-4">
        <h5 class="modal-title fw-bold">
          <i class="fas fa-times-circle me-2 text-secondary"></i>Xác nhận bỏ qua báo cáo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4">
        <div class="confirm-box">
          <p class="confirm-title">Bạn muốn bác bỏ báo cáo này?</p>
          <div class="small text-muted">
            <div class="mb-2">Bài bị báo cáo: <b id="rejectPostTitle"></b></div>
            <div>Lý do: <span class="badge bg-danger bg-opacity-10 text-danger" id="rejectReasonBadge"></span></div>
          </div>
        </div>

        <div class="small text-muted mt-3">
          Báo cáo sẽ được đánh dấu “Đã bác bỏ” và không xử lý thêm.
        </div>
      </div>

      <div class="modal-footer border-0 px-4 pb-4">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" id="rejectConfirmBtn">
          Xác nhận bỏ qua
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Bootstrap bundle (thường admin.layout đã có, nhưng thêm vẫn ok; nếu bị trùng thì xoá dòng này) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Accept modal =====
    const acceptModalEl = document.getElementById('acceptReportModal');
    const acceptTitleEl = document.getElementById('acceptPostTitle');
    const acceptReasonEl = document.getElementById('acceptReasonBadge');
    const acceptConfirmBtn = document.getElementById('acceptConfirmBtn');
    let acceptTargetForm = null;

    document.querySelectorAll('.btn-open-accept-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            acceptTargetForm = this.closest('form.report-form-accept');

            const title = this.dataset.title || 'Bài viết';
            const reason = this.dataset.reason || 'Không rõ';

            acceptTitleEl.textContent = title;
            acceptReasonEl.textContent = reason;

            const modal = new bootstrap.Modal(acceptModalEl);
            modal.show();
        });
    });

    acceptConfirmBtn.addEventListener('click', function () {
        if (acceptTargetForm) acceptTargetForm.submit();
    });

    // ===== Reject modal =====
    const rejectModalEl = document.getElementById('rejectReportModal');
    const rejectTitleEl = document.getElementById('rejectPostTitle');
    const rejectReasonEl = document.getElementById('rejectReasonBadge');
    const rejectConfirmBtn = document.getElementById('rejectConfirmBtn');
    let rejectTargetForm = null;

    document.querySelectorAll('.btn-open-reject-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            rejectTargetForm = this.closest('form.report-form-reject');

            const title = this.dataset.title || 'Bài viết';
            const reason = this.dataset.reason || 'Không rõ';

            rejectTitleEl.textContent = title;
            rejectReasonEl.textContent = reason;

            const modal = new bootstrap.Modal(rejectModalEl);
            modal.show();
        });
    });

    rejectConfirmBtn.addEventListener('click', function () {
        if (rejectTargetForm) rejectTargetForm.submit();
    });
});
</script>
@endsection
