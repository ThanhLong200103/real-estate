<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Real Estate</title>
    
    <script src="https://cdn.jsdelivr.net/npm/unpoly@3.0.0-beta.5/unpoly.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/unpoly@3.0.0-beta.5/unpoly.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --bg-body: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Thanh progress chạy trên đầu trang giống Youtube/Facebook khi chuyển trang */
        .up-progress-bar { background-color: var(--primary-color) !important; height: 3px !important; }

        .sidebar {
            background-color: var(--sidebar-bg);
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            min-height: 100vh;
            position: fixed;
            width: inherit;
            z-index: 100;
        }

        .sidebar h4 {
            letter-spacing: 2px;
            font-weight: 700;
            color: #f1f5f9;
            border-bottom: 1px solid #334155;
            padding: 25px 0;
            margin: 0 15px 20px 15px;
        }

        .sidebar .nav-link {
            border-radius: 8px;
            margin: 0 15px 8px 15px;
            padding: 12px 18px;
            transition: all 0.3s;
            font-weight: 500;
            color: var(--text-muted) !important;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff !important;
        }

        /* Unpoly sẽ tự động thêm class .up-current cho link đang ở trang hiện tại */
        .sidebar .nav-link.active, .sidebar .nav-link.up-current {
            background-color: var(--primary-color) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .main-wrapper {
            margin-left: 16.666667%;
            padding: 30px;
            width: 83.333333%;
        }

        .pending-badge {
            font-size: 0.7rem; padding: 4px 8px; background-color: #ef4444; color: white; border-radius: 20px; margin-left: auto;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 sidebar d-none d-md-block">
            <h4 class="text-center">REAL ESTATE</h4>
            <div class="nav flex-column mt-4" up-nav>
                <a href="{{ route('index-news-admin') }}" 
                   class="nav-link" 
                   up-follow up-target=".main-wrapper">
                    <i class="fas fa-newspaper me-3"></i> 
                    <span>Quản lý Tin tức</span>
                </a>

                <a href="{{ route('index-false-sale-post-admin') }}" 
                   class="nav-link" 
                   up-follow up-target=".main-wrapper">
                    <i class="fas fa-clock me-3"></i> 
                    <span>Chờ duyệt bài</span>
                    @if(isset($pendingPostsCount) && $pendingPostsCount > 0)
                        <span class="pending-badge">{{ $pendingPostsCount }}</span>
                    @endif
                </a>

                <a href="{{ route('index-true-sale-post-admin') }}" 
                   class="nav-link" 
                   up-follow up-target=".main-wrapper">
                    <i class="fas fa-check-circle me-3"></i> 
                    <span>Bài đã duyệt</span>
                </a>

                <div style="margin-top: 50px;">
                    <hr class="mx-3 opacity-10" style="background-color: #fff;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="fas fa-sign-out-alt text-danger me-3"></i> 
                            <span class="text-danger">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-10 main-wrapper">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center mb-4 fade show" role="alert">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>