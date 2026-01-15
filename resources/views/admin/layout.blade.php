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
        
        .sidebar { background-color: var(--sidebar-bg); position: fixed; height: 100vh; width: 16.666667%; z-index: 1000; transition: all 0.3s; }
        
        .sidebar .nav-link { 
            color: #94a3b8 !important; 
            border-radius: 8px; 
            padding: 12px 15px; 
            margin: 5px 0;
            transition: 0.2s; 
            display: flex; 
            align-items: center;
            text-decoration: none;
            background: transparent !important;
        }

        .sidebar .nav-link:hover:not(.active) { 
            background-color: var(--sidebar-hover) !important; 
            color: #fff !important; 
        }

        .sidebar .nav-link.active { 
            background-color: var(--primary-color) !important; 
            color: #fff !important; 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); 
            cursor: default;
        }

        .main-content { margin-left: 16.666667%; min-height: 100vh; background: var(--bg-body); }
        
        /* Hiệu ứng loading bar của Unpoly */
        .up-progress-bar { background: var(--primary-color); height: 3px; }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="d-flex">
        <div class="sidebar p-3 text-white" id="admin-sidebar">
            <h4 class="text-center mt-3 mb-4 fw-bold border-bottom pb-3 text-uppercase tracking-wider">Real Estate</h4>
            
            <div class="nav flex-column nav-pills" id="admin-sidebar-nav">
                
                {{-- Link Tin Tức --}}
                <a href="{{ route('index-news-admin') }}" 
                   class="nav-link {{ Route::is('index-news-admin*') ? 'active' : '' }}" 
                   up-follow 
                   up-target=".main-content, #admin-sidebar-nav">
                    <i class="fas fa-newspaper me-3"></i> 
                    <span>Tin tức</span>
                </a>

                {{-- Link Chờ Duyệt --}}
                <a href="{{ route('index-false-sale-post-admin') }}" 
                   class="nav-link d-flex justify-content-between align-items-center {{ Route::is('index-false-sale-post-admin*') ? 'active' : '' }}" 
                   up-follow 
                   up-target=".main-content, #admin-sidebar-nav">
                    <div>
                        <i class="fas fa-clock me-3"></i> 
                        <span>Chờ duyệt</span>
                    </div>
                    @if(isset($pendingPostsCount) && $pendingPostsCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $pendingPostsCount }}</span>
                    @endif
                </a>

                {{-- Link Đã Duyệt --}}
                <a href="{{ route('index-true-sale-post-admin') }}" 
                   class="nav-link {{ Route::is('index-true-sale-post-admin*') ? 'active' : '' }}" 
                   up-follow 
                   up-target=".main-content, #admin-sidebar-nav">
                    <i class="fas fa-check-circle me-3"></i> 
                    <span>Đã duyệt</span>
                </a>

                <div class="mt-auto pt-4 border-top" style="position: absolute; bottom: 20px; width: 85%;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 border-0 text-start d-flex align-items-center">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
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
    /**
     * Cấu hình Unpoly toàn cục
     */
    up.compiler('.nav-link', function(element) {
        // Tự động thêm up-follow cho tất cả link menu nếu chưa có
        if (!element.hasAttribute('up-follow')) {
            element.setAttribute('up-follow', '');
        }
    });

    // Lắng nghe sự kiện chuyển trang thành công để đảm bảo thanh URL luôn đúng
    up.on('up:link:follow', (event) => {
        // Ép Unpoly cập nhật lịch sử trình duyệt (thanh URL)
        event.renderOptions.history = true;
    });

    // Debug: In ra console nếu Unpoly lỗi
    up.on('up:request:error', (event) => {
        console.error('Unpoly Request Failed:', event.request.url);
    });
</script>

</body>
</html>