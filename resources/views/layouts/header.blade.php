<div class="topbar-fixed">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <h2 class="fw-800 m-0 text-white">ESTATE<span class="text-info">HUB</span></h2>
            </a>

            <div class="nav-actions" id="navActions">
                <a href="{{ route('news.index') }}" class="btn-custom btn-glass">
                    <i class="fas fa-newspaper"></i> Tin tức
                </a>

                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('index-news-admin') }}" class="btn-custom btn-glass text-warning">
                            <i class="fas fa-user-shield"></i> Admin
                        </a>
                    @endif

                    <a href="{{ route('contacts.index') }}" class="btn-custom btn-glass" title="Tin nhắn">
                        <i class="fas fa-comment-dots"></i>
                    </a>

                    <a href="{{ route('favorite.index') }}" class="btn-custom btn-glass" title="Tin đã lưu">
                        <i class="fas fa-heart text-danger"></i>
                    </a>

                    <a href="{{ route('user.report.index') }}" class="btn-custom btn-glass" title="Lịch sử báo cáo">
                        <i class="fas fa-flag text-warning"></i>
                    </a>

                    <a href="{{ route('user-sale-post-index') }}" class="btn-custom btn-glass">Tin của tôi</a>
                    <a href="{{ route('create-sale-post') }}" class="btn-custom btn-primary-custom">Đăng tin ngay</a>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn-custom btn-glass border-0">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login-form') }}" class="btn-custom btn-glass">Đăng nhập</a>
                    <a href="{{ route('register.form') }}" class="btn-custom btn-primary-custom">Tham gia ngay</a>
                @endauth
            </div>
        </div>
    </div>
</div>