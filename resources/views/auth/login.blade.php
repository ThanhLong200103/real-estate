<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập an toàn | ESTATE HUB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #6c5ce7; --dark: #2d3436; --light-bg: #f8faff; }
        body { background-color: var(--light-bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: 'Inter', sans-serif; }
        .auth-card { background: #fff; padding: 40px; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 100%; max-width: 420px; }
        .brand-logo { text-align: center; font-weight: 800; font-size: 26px; margin-bottom: 30px; color: var(--dark); text-decoration: none; display: block; }
        .form-control { border-radius: 12px; padding: 12px 15px; border: 1px solid #e1e5ee; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); }
        .btn-primary-custom { background: var(--primary); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; width: 100%; transition: 0.3s; }
        .btn-primary-custom:hover { background: #5a4bcf; transform: translateY(-2px); }
        .error-text { color: #ff7675; font-size: 13px; margin-top: 5px; font-weight: 500; }
    </style>
</head>
<body>

<div class="auth-card">
    <a href="/" class="brand-logo">ESTATE<span style="color: #00cec9;">HUB</span></a>
    
    <h5 class="text-center fw-bold mb-4">Đăng nhập tài khoản</h5>

    @if(session('status'))
        <div class="alert alert-success py-2 small" style="border-radius: 10px;">{{ session('status') }}</div>
    @endif

    {{-- Form tắt hoàn toàn autocomplete để tránh rò rỉ --}}
    <form action="{{ route('login') }}" method="POST" autocomplete="off">
        @csrf
        
        {{-- Honeypot: Đánh lừa trình duyệt tự động điền --}}
        <input type="text" style="display:none" name="prevent_autofill_user">
        <input type="password" style="display:none" name="prevent_autofill_pass">

        <div class="mb-3">
            <label class="form-label small fw-bold">Email công việc</label>
            {{-- Không sử dụng value="{{ old('email') }}" --}}
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   placeholder="Nhập email của bạn" required autocomplete="off">
            @error('email') <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Mật khẩu</label>
            <input type="password" name="password" class="form-control" 
                   placeholder="••••••••" required autocomplete="new-password">
        </div>

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                <label class="form-check-label small text-muted" for="rememberMe">Ghi nhớ phiên đăng nhập</label>
            </div>
        </div>

        <button type="submit" class="btn-primary-custom mb-3">Đăng nhập ngay</button>
        
        <div class="text-center">
            <p class="small text-muted">Chưa có tài khoản? <a href="{{ route('register.form') }}" class="fw-bold text-primary text-decoration-none">Đăng ký mới</a></p>
        </div>
    </form>
</div>

</body>
</html>