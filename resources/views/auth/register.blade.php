<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên | ESTATE HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #6c5ce7; --dark: #2d3436; --light-bg: #f8faff; }
        body { background-color: var(--light-bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 40px 0; font-family: 'Inter', sans-serif; }
        .auth-card { background: #fff; padding: 40px; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 480px; }
        .brand-logo { text-align: center; font-weight: 800; font-size: 26px; margin-bottom: 25px; color: var(--dark); text-decoration: none; display: block; }
        .form-control { border-radius: 12px; padding: 12px 15px; border: 1px solid #e1e5ee; }
        .btn-primary-custom { background: var(--primary); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; width: 100%; transition: 0.3s; }
        .error-text { color: #ff7675; font-size: 13px; margin-top: 5px; }
    </style>
</head>
<body>

<div class="auth-card">
    <a href="/" class="brand-logo">ESTATE<span style="color: #00cec9;">HUB</span></a>
    <h5 class="text-center fw-bold mb-4">Bắt đầu hành trình của bạn</h5>

    <form action="{{ route('register') }}" method="POST" autocomplete="off">
        @csrf

        {{-- Input ẩn chống Autofill --}}
        <input type="text" style="display:none" name="fake_user_name">

        <div class="mb-3">
            <label class="form-label small fw-bold">Họ và Tên</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   placeholder="Nhập đầy đủ họ tên" required autocomplete="off">
            @error('name') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Số điện thoại</label>
            <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                   placeholder="Ví dụ: 0901234567" required autocomplete="off">
            @error('phone_number') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Email công việc</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   placeholder="email@example.com" required autocomplete="off">
            @error('email') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required autocomplete="new-password">
                @error('password') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold">Xác nhận lại</label>
                <input type="password" name="password_confirmation" class="form-control" 
                       placeholder="••••••••" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-primary-custom mt-3">Đăng ký thành viên</button>

        <div class="text-center mt-4">
            <p class="small text-muted">Bạn đã có tài khoản? <a href="{{ route('login-form') }}" class="fw-bold text-primary text-decoration-none">Đăng nhập tại đây</a></p>
        </div>
    </form>
</div>

</body>
</html>