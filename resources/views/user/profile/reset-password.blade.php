<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - EstateHub</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary: #6c5ce7;
            --primary-light: #a29bfe;
            --dark: #2d3436;
            --light-bg: #f8faff;
            --success: #00b894;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--dark);
        }

        /* Header Đồng Bộ Hero-style */
        .hero-banner-mini {
            background: var(--dark);
            background-image: linear-gradient(rgba(45, 52, 54, 0.85), rgba(45, 52, 54, 0.85)), 
                              url('https://images.unsplash.com/photo-1460472178825-e5250fb13e8d?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 60px 0 100px 0;
            color: white;
            margin-bottom: -50px;
        }

        .nav-actions {
            display: flex; gap: 12px; background: rgba(255,255,255,0.1);
            padding: 8px; border-radius: 50px; backdrop-filter: blur(10px);
        }

        .btn-custom {
            padding: 10px 20px; border-radius: 50px; font-weight: 600; font-size: 14px;
            transition: 0.3s; border: none; display: flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-glass { background: rgba(255,255,255,0.2); color: white; }
        .btn-glass:hover { background: white; color: var(--primary); }
        .btn-primary-custom { background: var(--primary); color: white; }

        /* Password Container */
        .password-container {
            position: relative;
            z-index: 10;
            max-width: 700px;
            margin: 0 auto 30px;
        }

        .password-card {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .password-body {
            padding: 40px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #dfe6e9;
            background-color: #f8f9fa;
            transition: 0.3s;
            width: 100%;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
            outline: none;
        }

        .input-group {
            display: flex;
            align-items: stretch;
        }

        .input-group {
            display: flex;
            align-items: stretch;
        }

        .input-group-text {
            background: #f8f9fa;
            border-radius: 10px 0 0 10px;
            border: 1px solid #dfe6e9;
            border-right: none;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            color: #636e72;
        }

        .input-group:hover .input-group-text {
            background: #f0f1ff;
            border-color: var(--primary-light);
            color: var(--primary);
        }

        .has-icon .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .has-icon .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            padding: 14px 30px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            transition: 0.3s;
            width: 100%;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            background: #5a4bcf;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        .btn-secondary-custom {
            background: #95a5a6;
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-secondary-custom:hover {
            background: #7f8c8d;
            color: white;
        }

        .error-text {
            color: #ff7675;
            font-size: 13px;
            margin-top: 5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .info-box {
            background: #f0f1ff;
            border: 1px solid #e1e5ff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            background: #e8eaff;
            border-color: var(--primary-light);
        }

        .info-box i {
            color: var(--primary);
            margin-right: 10px;
        }

        .password-card {
            transition: all 0.3s ease;
        }

        .password-card:hover {
            box-shadow: 0 25px 50px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body>

<header class="hero-banner-mini">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="fw-800 m-0 text-white" style="cursor: pointer; letter-spacing: -1px;" onclick="window.location='{{ route('home') }}'">
                ESTATE<span class="text-info">HUB</span>
            </h2>
            <div class="nav-actions">
                <a href="{{ route('home') }}" class="btn-custom btn-glass"><i class="fas fa-home"></i> Trang chủ</a>
                <a href="{{ route('user.profile') }}" class="btn-custom btn-primary-custom"><i class="fas fa-user"></i> Hồ sơ</a>
                <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass"><i class="fas fa-comment-dots"></i></a>
                <a href="{{ route('favorite.index') }}" class="btn-custom btn-glass"><i class="fas fa-heart text-danger"></i></a>
                <a href="{{ route('user.report.index') }}" class="btn-custom btn-glass"><i class="fas fa-flag text-warning"></i></a>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="button" class="btn-custom btn-glass border-0" data-bs-toggle="modal" data-bs-target="#logoutModal" title="Đăng xuất">
                        <i class="fas fa-power-off text-danger"></i>
                    </button>
                </form>
            </div>
        </div>
        <h1 class="fw-800 mb-2">Đổi mật khẩu</h1>
        <p class="opacity-75 fw-500">Bảo vệ tài khoản của bạn bằng mật khẩu mạnh</p>
    </div>
</header>

<main class="container password-container">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-lg mb-4 p-3 rounded-4 d-flex align-items-center">
            <i class="fas fa-check-circle fs-4 me-3"></i> 
            <span class="fw-bold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="password-card">
        <div class="password-body">
            <div class="info-box">
                <i class="fas fa-shield-alt"></i>
                <strong>Lưu ý:</strong> Mật khẩu phải có ít nhất 6 ký tự và không được trùng với mật khẩu hiện tại.
            </div>

            <form action="{{ route('user.profile.reset-password.post') }}" method="POST">
                @csrf

                <div class="section-title">
                    <i class="fas fa-lock"></i> Thông tin mật khẩu
                </div>

                <div class="mb-4">
                    <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                    <div class="input-group has-icon">
                        <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" 
                               placeholder="Nhập mật khẩu hiện tại" required autocomplete="current-password">
                    </div>
                    @error('current_password')
                        <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                    <div class="input-group has-icon">
                        <span class="input-group-text"><i class="fas fa-key text-muted"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" required autocomplete="new-password">
                    </div>
                    @error('password')
                        <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                    <div class="input-group has-icon">
                        <span class="input-group-text"><i class="fas fa-check-circle text-muted"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Nhập lại mật khẩu mới" required autocomplete="new-password">
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary-custom">
                        <i class="fas fa-save"></i>
                        <span>Cập nhật mật khẩu</span>
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('user.profile') }}" class="btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại hồ sơ</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

@include('layouts.footer')

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 22px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-800 text-dark">
                    <i class="fas fa-sign-out-alt me-2 text-danger"></i> Xác nhận đăng xuất
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div style="background:#fff5f5; border:1px solid #ffe0e0; border-radius:16px; padding:14px 16px;">
                    <div class="fw-800 text-danger mb-1">Bạn muốn đăng xuất khỏi tài khoản?</div>
                    <div class="small text-muted">
                        Sau khi đăng xuất, bạn cần đăng nhập lại để nhắn tin, đăng tin và quản lý bài đăng.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-800">Đăng xuất</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
