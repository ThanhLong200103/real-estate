<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Không tìm thấy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-1: #0b1220;
            --bg-2: #0a1a3a;

            --card: rgba(255, 255, 255, 0.78);
            --card-border: rgba(255, 255, 255, 0.35);

            --text-main: #111827;
            /* xám đen */
            --text-title: #1f2937;
            /* xám đậm */
            --text-desc: #4b5563;
            /* xám vừa */
            --text-muted: #6b7280;
            /* xám nhạt */

            --primary: #2563eb;
            --info: #06b6d4;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(1200px circle at 15% 20%, rgba(37, 99, 235, .25), transparent 45%),
                radial-gradient(900px circle at 85% 25%, rgba(6, 182, 212, .18), transparent 45%),
                radial-gradient(900px circle at 30% 85%, rgba(34, 197, 94, .14), transparent 45%),
                linear-gradient(180deg, var(--bg-2), var(--bg-1));
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 32px 0;
        }

        .card-glass {
            background: var(--card);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 28px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, .35);
            overflow: hidden;
        }

        .top-glow {
            height: 10px;
            background: linear-gradient(90deg, rgba(37, 99, 235, .9), rgba(6, 182, 212, .9), rgba(34, 197, 94, .8));
        }

        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(37, 99, 235, .12);
            color: #1d4ed8;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
            box-shadow: 0 0 0 6px rgba(37, 99, 235, .12);
        }

        .code {
            font-weight: 900;
            letter-spacing: -3px;
            color: var(--text-main);
            font-size: clamp(72px, 10vw, 120px);
            line-height: 1;
        }

        .title {
            font-weight: 800;
            color: var(--text-title);
            font-size: clamp(26px, 3.5vw, 40px);
        }

        .desc {
            color: var(--text-desc);
            font-size: 1.05rem;
        }

        .hint {
            color: var(--text-muted);
            font-size: .95rem;
        }

        .btn-pill {
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 700;
        }

        .btn-outline-soft {
            border: 1px solid rgba(17, 24, 39, .18);
            color: var(--text-title);
            background: rgba(255, 255, 255, .45);
        }

        .btn-outline-soft:hover {
            background: rgba(255, 255, 255, .7);
            color: #111827;
        }

        .btn-outline-info-soft {
            border: 1px solid rgba(6, 182, 212, .35);
            color: #0e7490;
            background: rgba(6, 182, 212, .10);
        }

        .btn-outline-info-soft:hover {
            background: rgba(6, 182, 212, .18);
            color: #0e7490;
        }

        .art {
            position: relative;
            height: 220px;
            border-radius: 22px;
            background:
                radial-gradient(120px circle at 30% 40%, rgba(37, 99, 235, .35), transparent 60%),
                radial-gradient(120px circle at 70% 50%, rgba(6, 182, 212, .25), transparent 60%),
                radial-gradient(140px circle at 55% 80%, rgba(34, 197, 94, .18), transparent 60%),
                linear-gradient(180deg, rgba(255, 255, 255, .65), rgba(255, 255, 255, .35));
            border: 1px solid rgba(17, 24, 39, .08);
            overflow: hidden;
        }

        .art::before,
        .art::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            filter: blur(0px);
            opacity: .9;
        }

        .art::before {
            width: 180px;
            height: 180px;
            left: -40px;
            top: -60px;
            background: rgba(37, 99, 235, .22);
        }

        .art::after {
            width: 220px;
            height: 220px;
            right: -80px;
            bottom: -90px;
            background: rgba(6, 182, 212, .18);
        }

        .search-box {
            max-width: 520px;
            margin: 0 auto;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid rgba(17, 24, 39, .14);
        }

        .form-control:focus {
            border-color: rgba(37, 99, 235, .45);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .footer-links a {
            color: rgba(255, 255, 255, .75);
            text-decoration: none;
        }

        .footer-links a:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="card-glass">
                        <div class="top-glow"></div>

                        <div class="p-4 p-md-5">
                            <div class="text-center mb-4">
                                <span class="badge-soft">
                                    <span class="dot"></span>
                                    Not Found
                                </span>
                            </div>

                            <div class="row align-items-center g-4">
                                <div class="col-lg-6 text-center text-lg-start">
                                    <div class="code">404</div>
                                    <div class="title mt-2">Không tìm thấy trang</div>
                                    <p class="desc mt-3 mb-0">
                                        Trang bạn truy cập không tồn tại, đã bị xoá hoặc đường dẫn sai.
                                    </p>

                                    <div class="mt-4 d-flex gap-2 justify-content-center justify-content-lg-start flex-wrap">
                                        <a href="{{ url('/') }}" class="btn btn-primary btn-pill">
                                            Về trang chủ
                                        </a>

                                        <a href="{{ url()->previous() }}" class="btn btn-outline-soft btn-pill">
                                            Quay lại
                                        </a>

                                        @guest
                                        <a href="{{ route('login') ?? url('/login') }}" class="btn btn-outline-info-soft btn-pill">
                                            Đăng nhập
                                        </a>
                                        @endguest
                                    </div>

                                    <div class="search-box mt-4">

                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="art d-flex align-items-center justify-content-center">
                                        <div class="text-center px-4">
                                            <div class="fw-bold" style="color: var(--text-title); font-size: 1.1rem;">
                                                Có vẻ bạn đi lạc 😅
                                            </div>
                                            <div class="hint mt-2">
                                                Trang này có thể đã chuyển vị trí hoặc bạn gõ nhầm đường dẫn.
                                            </div>
                                            <div class="mt-3">
                                                <span class="badge text-bg-light border" style="border-radius: 999px; padding: 10px 14px;">
                                                    {{ request()->path() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="border-color: rgba(17,24,39,.12);">

                            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                <div class="hint">
                                    Nếu bạn nghĩ đây là lỗi hệ thống, hãy báo lại cho admin.
                                </div>

                                <div class="d-flex gap-3 footer-links">
                                    <a href="{{ url('/') }}">Trang chủ</a>
                                    <a href="{{ url('/contact') }}">Liên hệ</a>
                                    <a href="{{ url('/help') }}">Trợ giúp</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center mt-4 footer-links">
                        <span style="color: rgba(255,255,255,.6);">© {{ date('Y') }} - Hệ thống</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>