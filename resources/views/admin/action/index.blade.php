@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Lịch sử hoạt động Admin</h2>
        {{-- Hiển thị tổng số log --}}
        <span class="badge bg-dark px-3 py-2">Tổng số: {{ $actions->total() }} dữ liệu</span>
    </div>
    
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary small fw-bold">THỜI GIAN</th>
                            <th class="py-3 text-secondary small fw-bold">ADMIN</th>
                            <th class="py-3 text-secondary small fw-bold">HÀNH ĐỘNG</th>
                            <th class="py-3 text-secondary small fw-bold text-center">ID</th>
                            <th class="py-3 text-secondary small fw-bold">MÔ TẢ CHI TIẾT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($actions as $action)
                        <tr>
                            <td class="ps-4 small text-muted">
                                {{ $action->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                    <i class="fas fa-user-shield me-1 small"></i>{{ $action->admin->name }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($action->action_type) {
                                        'CREATE' => 'success',
                                        'UPDATE' => 'warning',
                                        'DELETE' => 'danger',
                                        'APPROVE' => 'primary',
                                        'RESOLVE' => 'info',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }} px-3 py-2" style="min-width: 85px;">
                                    {{ $action->action_type }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($action->target_id)
                                    <span class="fw-bold text-secondary">#{{ $action->target_id }}</span>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </td>
                            <td class="small text-dark pe-4">{{ $action->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($actions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $actions->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Thêm thông báo Notification giống các trang khác --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
@endsection