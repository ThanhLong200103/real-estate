<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử báo cáo - EstateHub</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5a4bcf;
            --secondary: #00cec9;
            --dark: #2d3436;
            --light-bg: #f8faff;
            --white: #ffffff;
            --warning: #ff9f43;
            --success: #00b894;
            --danger: #ff4757;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--dark);
        }

        /* Header Đồng Bộ Trang Chủ */
        .hero-banner-mini {
            background: var(--dark);
            background-image: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)), url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1373&q=80');
            background-size: cover;
            background-position: center;
            padding: 40px 0 80px 0;
            color: white;
            margin-bottom: -40px;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-glass { background: rgba(255,255,255,0.2); color: white; }
        .btn-glass:hover { background: white; color: var(--primary); }
        .btn-primary-custom { background: var(--primary); color: white; }

        /* Report Content Card */
        .report-container {
            position: relative;
            z-index: 10;
        }

        .report-item {
            background: white;
            border-radius: 24px;
            border: 1px solid rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 25px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border-left: 8px solid #dfe6e9;
        }
        
        .report-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }

        /* Trạng thái */
        .status-pending { border-left-color: var(--warning); }
        .status-resolved { border-left-color: var(--success); }
        .status-rejected { border-left-color: var(--danger); }

        .badge-status {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-pending { background: #fff9f0; color: var(--warning); }
        .bg-resolved { background: #e6fffb; color: var(--success); }
        .bg-rejected { background: #fff1f2; color: var(--danger); }

        .admin-note {
            background: #f8f9fd;
            border-radius: 18px;
            padding: 20px;
            margin-top: 20px;
            border: 1px dashed #dcdde1;
            position: relative;
        }

        .post-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.2s;
        }
        .post-link:hover { color: var(--primary-dark); }

        .empty-state {
            background: white;
            border-radius: 30px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>

<header class="hero-banner-mini">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="fw-800 m-0 text-white" style="cursor: pointer" onclick="window.location='{{ route('home') }}'">
                ESTATE<span class="text-info">HUB</span>
            </h2>
            
            <div class="nav-actions">
                <a href="{{ route('home') }}" class="btn-custom btn-glass"><i class="fas fa-home"></i> Trang chủ</a>
                <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass"><i class="fas fa-comment-dots"></i></a>
                <a href="{{ route('favorite.index') }}" class="btn-custom btn-glass"><i class="fas fa-heart text-danger"></i></a>
                <a href="{{ route('user.report.index') }}" class="btn-custom btn-primary-custom"><i class="fas fa-flag"></i> Báo cáo</a>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-custom btn-glass border-0"><i class="fas fa-power-off"></i></button>
                </form>
            </div>
        </div>
        <h1 class="fw-800 mb-2">Lịch sử báo cáo</h1>
        <p class="opacity-75 fw-500">Theo dõi trạng thái xử lý các phản hồi của bạn</p>
    </div>
</header>

<main class="container report-container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @forelse($reports as $report)
                @php
                    $statusClass = 'status-pending';
                    $badgeClass = 'bg-pending';
                    $statusText = 'Đang chờ duyệt';
                    
                    if($report->status == 1) {
                        $statusClass = 'status-resolved';
                        $badgeClass = 'bg-resolved';
                        $statusText = 'Đã xử lý';
                    } elseif($report->status == 2) {
                        $statusClass = 'status-rejected';
                        $badgeClass = 'bg-rejected';
                        $statusText = 'Đã từ chối';
                    }
                @endphp

                <div class="report-item {{ $statusClass }}">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                        <div>
                            <span class="badge-status {{ $badgeClass }} mb-3 d-inline-block">{{ $statusText }}</span>
                            <h4 class="fw-800 mb-2">Lý do: {{ $report->reason }}</h4>
                            <div class="text-muted small fw-600">
                                <i class="far fa-calendar-alt me-1"></i> Ngày gửi: {{ $report->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <a href="{{ route('create-sale-show', $report->sale_post_id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                            Xem tin gốc <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </div>

                    <div class="report-content">
                        <label class="search-label mb-2">Chi tiết báo cáo</label>
                        <p class="text-dark fw-500 lead-sm">{{ $report->content ?? 'Không có mô tả chi tiết.' }}</p>
                    </div>

                    @if($report->admin_note)
                        <div class="admin-note">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-reply-all text-primary me-2"></i>
                                <span class="search-label m-0 text-primary">Phản hồi từ Ban quản trị</span>
                            </div>
                            <p class="m-0 fw-600 text-secondary">{{ $report->admin_note }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <img src="https://cdn-icons-png.flaticon.com/512/6598/6598519.png" width="120" class="mb-4 opacity-25">
                    <h3 class="fw-800">Chưa có báo cáo nào</h3>
                    <p class="text-muted mb-4">Bạn chưa gửi báo cáo vi phạm nào cho hệ thống chúng tôi.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary-custom px-4 py-2">Quay lại trang chủ</a>
                </div>
            @endforelse

            <div class="mt-5 d-flex justify-content-center">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</main>

<footer class="py-5 text-center text-muted small">
    <div class="container border-top pt-4">
        &copy; 2026 EstateHub. Hệ thống BĐS minh bạch & an toàn.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>