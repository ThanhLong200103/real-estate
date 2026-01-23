<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Không có quyền</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .glass { background: rgba(255,255,255,.75); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.35); }
        .bg-soft { background: radial-gradient(1200px circle at 20% 20%, rgba(255,193,7,.18), transparent 40%),
                             radial-gradient(1000px circle at 80% 30%, rgba(13,110,253,.15), transparent 35%),
                             radial-gradient(900px circle at 30% 90%, rgba(220,53,69,.12), transparent 35%),
                             #0b1220; }
        .code { font-weight: 900; letter-spacing: -2px; }
    </style>
</head>
<body class="bg-soft min-vh-100 d-flex align-items-center">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="glass rounded-4 p-4 p-md-5 shadow-lg text-center">
                <div class="mb-3">
                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Forbidden</span>
                </div>

                <h1 class="display-1 code text-white mb-0">403</h1>
                <h2 class="text-white mt-2">Bạn không có quyền truy cập</h2>
                <p class="text-white-50 mb-4">
                    Khu vực này chỉ dành cho tài khoản có quyền phù hợp (ví dụ: Admin).
                </p>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4">
                        Về trang chủ
                    </a>

                    <a href="{{ url()->previous() }}" class="btn btn-outline-light rounded-pill px-4">
                        Quay lại
                    </a>

                    @guest
                        <a href="{{ route('login') ?? url('/login') }}" class="btn btn-outline-info rounded-pill px-4">
                            Đăng nhập
                        </a>
                    @endguest
                </div>

                <hr class="border-white border-opacity-10 my-4">

                <div class="text-white-50 small">
                    @if(isset($exception) && $exception->getMessage())
                        {{-- Nếu bạn abort(403, '...') và muốn show message (cẩn thận lộ thông tin) --}}
                        <span class="opacity-75">{{ $exception->getMessage() }}</span>
                    @else
                        Nếu bạn cần quyền truy cập, hãy liên hệ quản trị viên.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
