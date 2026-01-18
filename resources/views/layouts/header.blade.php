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

                    {{-- ✅ LOGOUT (Custom + Modal confirm) --}}
                    <form action="{{ route('logout') }}" method="POST" class="m-0" id="logoutForm">
                        @csrf

                        {{-- Nút chỉ mở modal, KHÔNG submit ngay --}}
                        <button type="button"
                                class="btn-custom btn-glass border-0 btn-logout"
                                data-bs-toggle="modal"
                                data-bs-target="#logoutModal"
                                title="Đăng xuất"
                                style="display:inline-flex; align-items:center; gap:10px;">
                            <i class="fas fa-power-off text-danger"></i>
                            <span class="d-none d-md-inline fw-bold">Đăng xuất</span>
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

{{-- ✅ MODAL CONFIRM LOGOUT --}}
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
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-800" id="logoutConfirmBtn">
                    Đăng xuất
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const logoutForm = document.getElementById('logoutForm');
    const confirmBtn = document.getElementById('logoutConfirmBtn');

    if (!logoutForm || !confirmBtn) return;

    confirmBtn.addEventListener('click', function () {
        logoutForm.submit();
    });
});
</script>
@endpush
