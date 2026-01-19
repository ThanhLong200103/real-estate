<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - EstateHub</title>
    
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
            --warning: #fdcb6e;
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

        /* Profile Container */
        .profile-container {
            position: relative;
            z-index: 10;
            margin-bottom: 30px;
        }

        /* Left Column - User Summary Card */
        .user-summary-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            height: fit-content;
        }

        .summary-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 25px;
            color: white;
            text-align: center;
        }

        .summary-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .summary-body {
            padding: 30px 25px;
            text-align: center;
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 4px solid #f0f1ff;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            font-weight: 800;
            color: white;
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.2);
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-size: 22px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .user-title {
            color: #636e72;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .user-details {
            text-align: left;
            margin: 25px 0;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #636e72;
            font-size: 14px;
        }

        .detail-item i {
            width: 20px;
            color: var(--primary);
        }

        /* Right Column - Profile Details Card */
        .profile-details-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .nav-tabs {
            border-bottom: 1px solid #e9ecef;
            padding: 0 25px;
            background: #f8f9fa;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #636e72;
            font-weight: 600;
            font-size: 14px;
            padding: 15px 20px;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: var(--primary);
            background: transparent;
        }

        .nav-tabs .nav-link.active {
            background: transparent;
            border-color: var(--primary);
            color: var(--primary);
        }

        .tab-content {
            padding: 30px;
        }

        .section-heading {
            font-size: 16px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-heading i {
            color: var(--primary);
        }

        .info-table {
            width: 100%;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 35%;
            font-weight: 600;
            color: #636e72;
            font-size: 14px;
        }

        .info-value {
            width: 65%;
            color: var(--dark);
            font-size: 14px;
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

        .file-input-wrapper input[type="file"] {
            padding: 10px 15px;
            border: 1px solid #dfe6e9;
            border-radius: 10px;
            background: #f8f9fa;
            width: 100%;
            transition: 0.3s;
            font-size: 14px;
        }

        .file-input-wrapper input[type="file"]:hover {
            background: #f0f1ff;
            border-color: var(--primary-light);
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            transition: 0.3s;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
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
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
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

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #f0f1ff;
            color: var(--primary);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
            border: 1px solid rgba(108, 92, 231, 0.1);
        }

        .about-text {
            color: #636e72;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .profile-layout {
                flex-direction: column;
            }

            .avatar-circle {
                width: 100px;
                height: 100px;
            }
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
        <h1 class="fw-800 mb-2">Hồ sơ cá nhân</h1>
        <p class="opacity-75 fw-500">Quản lý thông tin tài khoản của bạn</p>
    </div>
</header>

<main class="container profile-container">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-lg mb-4 p-3 rounded-4 d-flex align-items-center">
            <i class="fas fa-check-circle fs-4 me-3"></i> 
            <span class="fw-bold">{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row profile-layout g-4">
        <!-- Left Column - User Summary Card -->
        <div class="col-lg-4">
            <div class="user-summary-card">
                <div class="summary-header">
                    <h3>Xin chào, {{ explode(' ', Auth::user()->name)[0] }}</h3>
                </div>
                <div class="summary-body">
                    <div class="avatar-circle">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Avatar">
                        @else
                            @php
                                $nameParts = explode(' ', Auth::user()->name);
                                $initials = '';
                                foreach ($nameParts as $part) {
                                    $initials .= strtoupper(substr($part, 0, 1));
                                }
                                $initials = substr($initials, 0, 2);
                            @endphp
                            {{ $initials }}
                        @endif
                    </div>
                    
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-title">{{ Auth::user()->email }}</div>
                    
                    <div class="user-details">
                        @if(Auth::user()->phone_number)
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ Auth::user()->phone_number }}</span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Profile Details Card -->
        <div class="col-lg-8">
            <div class="profile-details-card">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Hồ sơ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                            <i class="fas fa-key me-2"></i>Mật khẩu
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="section-heading">
                            <i class="fas fa-info-circle"></i>
                            <span>Thông tin cá nhân</span>
                        </div>

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <div class="input-group has-icon">
                                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', Auth::user()->name) }}" required>
                                </div>
                                @error('name')
                                    <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group has-icon">
                                    <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}" required>
                                </div>
                                @error('email')
                                    <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Số điện thoại</label>
                                <div class="input-group has-icon">
                                    <span class="input-group-text"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                                           value="{{ old('phone_number', Auth::user()->phone_number) }}" placeholder="Nhập số điện thoại">
                                </div>
                                @error('phone_number')
                                    <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Ảnh đại diện</label>
                                <div class="file-input-wrapper">
                                    <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" 
                                           accept="image/jpeg,image/png,image/jpg,image/webp">
                                </div>
                                <div class="info-badge">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Chấp nhận: JPG, PNG, WEBP (tối đa 2MB)</span>
                                </div>
                                @error('profile_picture')
                                    <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#confirmProfileModal">
                                    <i class="fas fa-save"></i>
                                    <span>Cập nhật thông tin</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="section-heading">
                            <i class="fas fa-lock"></i>
                            <span>Đổi mật khẩu</span>
                        </div>

                        <p class="about-text mb-4">Bảo vệ tài khoản của bạn bằng mật khẩu mạnh. Mật khẩu phải có ít nhất 6 ký tự.</p>

                        <form action="{{ route('user.profile.reset-password.post') }}" method="POST" id="passwordResetForm">
                            @csrf

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
                                <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#confirmPasswordModal">
                                    <i class="fas fa-save"></i>
                                    <span>Cập nhật mật khẩu</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('layouts.footer')

<!-- Modal Confirm Profile Update -->
<div class="modal fade" id="confirmProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 22px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-800 text-dark">
                    <i class="fas fa-check-circle me-2 text-primary"></i> Xác nhận cập nhật
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div style="background:#f0f1ff; border:1px solid #e1e5ff; border-radius:16px; padding:14px 16px;">
                    <div class="fw-800 text-primary mb-1">Bạn có chắc muốn cập nhật thông tin cá nhân?</div>
                    <div class="small text-muted mt-2">
                        Thông tin tài khoản của bạn sẽ được thay đổi sau khi xác nhận. Vui lòng kiểm tra kỹ các thông tin trước khi xác nhận.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-800" id="confirmProfileBtn">
                    <i class="fas fa-save me-2"></i>Xác nhận cập nhật
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirm Password Reset -->
<div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 22px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-800 text-dark">
                    <i class="fas fa-key me-2 text-warning"></i> Xác nhận đổi mật khẩu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <div style="background:#fffaf0; border:1px solid #ffe0b2; border-radius:16px; padding:14px 16px;">
                    <div class="fw-800 text-warning mb-1">Bạn có chắc muốn thay đổi mật khẩu?</div>
                    <div class="small text-muted mt-2">
                        Mật khẩu của bạn sẽ được thay đổi sau khi xác nhận. Bạn sẽ cần sử dụng mật khẩu mới để đăng nhập lần sau.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-warning rounded-pill px-4 fw-800" id="confirmPasswordBtn">
                    <i class="fas fa-key me-2"></i>Xác nhận đổi mật khẩu
                </button>
            </div>
        </div>
    </div>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirm Profile Update
    const confirmProfileBtn = document.getElementById('confirmProfileBtn');
    const profileUpdateForm = document.getElementById('profileUpdateForm');
    
    if (confirmProfileBtn && profileUpdateForm) {
        confirmProfileBtn.addEventListener('click', function() {
            profileUpdateForm.submit();
        });
    }

    // Confirm Password Reset
    const confirmPasswordBtn = document.getElementById('confirmPasswordBtn');
    const passwordResetForm = document.getElementById('passwordResetForm');
    
    if (confirmPasswordBtn && passwordResetForm) {
        confirmPasswordBtn.addEventListener('click', function() {
            passwordResetForm.submit();
        });
    }
});
</script>

</body>
</html>
