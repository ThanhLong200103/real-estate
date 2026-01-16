<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Real Estate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/unpoly@3.5.0/unpoly.min.js"></script>
    <style>
        :root { --sidebar-bg: #1e293b; --sidebar-hover: #334155; --primary-color: #4f46e5; --bg-body: #f8fafc; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: #1e293b; margin: 0; }
        .sidebar { background-color: var(--sidebar-bg); position: fixed; height: 100vh; width: 16.666667%; z-index: 1000; transition: all 0.3s; overflow-y: auto; }
        .sidebar .nav-link { 
            color: #94a3b8 !important; border-radius: 8px; padding: 10px 15px; margin: 2px 0;
            transition: 0.2s; display: flex; align-items: center; text-decoration: none; background: transparent !important; font-size: 0.95rem;
        }
        .sidebar .nav-link:hover:not(.active) { background-color: var(--sidebar-hover) !important; color: #fff !important; }
        .sidebar .nav-link.active { 
            background-color: var(--primary-color) !important; color: #fff !important; 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); cursor: default;
        }
        .nav-header { color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 20px 0 10px 15px; }
        .main-content { margin-left: 16.666667%; min-height: 100vh; background: var(--bg-body); }
        .badge-count { font-size: 0.7rem; padding: 0.35em 0.65em; }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="d-flex">
        <div class="sidebar p-3 text-white" id="admin-sidebar">
            <h4 class="text-center mt-3 mb-4 fw-bold border-bottom pb-3 text-uppercase tracking-wider">Real Estate</h4>
            
            <div class="nav flex-column nav-pills" id="admin-sidebar-nav">
                
                {{-- Trang chủ/Tin tức --}}
                <a href="{{ route('index-news-admin') }}" 
                   class="nav-link {{ Route::is('index-news-admin*') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <i class="fas fa-newspaper me-3"></i> <span>Tin tức</span>
                </a>

                {{-- PHÂN MỤC: BẤT ĐỘNG SẢN BÁN --}}
                <div class="nav-header">Quản lý Bán</div>
                
                <a href="{{ route('index-false-sale-post-admin', ['type' => 'sale']) }}" 
                   class="nav-link d-flex justify-content-between align-items-center {{ request('type') == 'sale' && Route::is('index-false-sale-post-admin') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <div><i class="fas fa-clock me-3"></i><span>Chờ duyệt Bán</span></div>
                    @php $salePending = \App\Models\SalePost::where('status', 0)->where('type', 'sale')->count(); @endphp
                    @if($salePending > 0)
                        <span class="badge bg-danger rounded-pill badge-count">{{ $salePending }}</span>
                    @endif
                </a>

                <a href="{{ route('index-true-sale-post-admin', ['type' => 'sale']) }}" 
                   class="nav-link {{ request('type') == 'sale' && Route::is('index-true-sale-post-admin') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <i class="fas fa-check-circle me-3"></i> <span>Tin đang bán</span>
                </a>

                {{-- PHÂN MỤC: BẤT ĐỘNG SẢN CHO THUÊ --}}
                <div class="nav-header">Quản lý Cho Thuê</div>

                <a href="{{ route('index-false-sale-post-admin', ['type' => 'rent']) }}" 
                   class="nav-link d-flex justify-content-between align-items-center {{ request('type') == 'rent' && Route::is('index-false-sale-post-admin') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <div><i class="fas fa-history me-3"></i><span>Chờ duyệt Thuê</span></div>
                    @php $rentPending = \App\Models\SalePost::where('status', 0)->where('type', 'rent')->count(); @endphp
                    @if($rentPending > 0)
                        <span class="badge bg-danger rounded-pill badge-count">{{ $rentPending }}</span>
                    @endif
                </a>

                <a href="{{ route('index-true-sale-post-admin', ['type' => 'rent']) }}" 
                   class="nav-link {{ request('type') == 'rent' && Route::is('index-true-sale-post-admin') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <i class="fas fa-key me-3"></i> <span>Tin đang thuê</span>
                </a>

                {{-- HỆ THỐNG --}}
                <div class="nav-header">Hệ thống</div>

                <a href="{{ route('index-report-admin') }}" 
                   class="nav-link d-flex justify-content-between align-items-center {{ Route::is('index-report-admin*') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <div><i class="fas fa-exclamation-circle me-3"></i><span>Báo cáo vi phạm</span></div>
                    @php $pendingReports = \App\Models\SalePostReport::where('status', 0)->count(); @endphp
                    @if($pendingReports > 0)
                        <span class="badge bg-danger rounded-pill badge-count">{{ $pendingReports }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.logs.index') }}" 
                   class="nav-link d-flex justify-content-between align-items-center {{ request()->is('admin/logs*') ? 'active' : '' }}" 
                   up-follow up-target=".main-content, #admin-sidebar-nav">
                    <div><i class="fas fa-user-shield me-3"></i><span>Nhật ký hoạt động</span></div>
                    @if(isset($todayActionsCount) && $todayActionsCount > 0)
                        <span class="badge bg-info rounded-pill badge-count text-dark">
                            {{ $todayActionsCount > 99 ? '99+' : $todayActionsCount }}
                        </span>
                    @endif
                </a>

                <div class="mt-4 pt-4 border-top">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 border-0 text-start d-flex align-items-center nav-link">
                            <i class="fas fa-sign-out-alt me-3"></i> <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-content p-4 w-100">
            @yield('content')
        </div>
    </div>
</div>

<script>
    up.compiler('.nav-link', function(element) {
        if (!element.hasAttribute('up-follow')) {
            element.setAttribute('up-follow', '');
        }
    });

    up.on('up:link:follow', (event) => {
        event.renderOptions.history = true;
    });
</script>

</body>
</html>