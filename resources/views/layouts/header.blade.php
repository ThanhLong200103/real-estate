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

                    {{-- Dropdown Profile với Xin chào --}}
                    <div class="dropdown">
                        <button class="btn-custom btn-glass d-flex align-items-center gap-2" 
                                type="button" 
                                id="profileDropdown" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false"
                                style="text-decoration: none; border: none; background: rgba(255,255,255,0.2); color: white;">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                     alt="Avatar" 
                                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.3); flex-shrink: 0;">
                            @else
                                @php
                                    $nameParts = explode(' ', Auth::user()->name);
                                    $initials = '';
                                    foreach ($nameParts as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                    }
                                    $initials = substr($initials, 0, 2);
                                @endphp
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: white; border: 2px solid rgba(255,255,255,0.3); flex-shrink: 0;">
                                    {{ $initials }}
                                </div>
                            @endif
                            <span class="d-none d-lg-inline" style="white-space: nowrap;">
                                Xin chào, <strong>{{ explode(' ', Auth::user()->name)[0] }}</strong>
                            </span>
                            <i class="fas fa-chevron-down d-none d-lg-inline" style="font-size: 10px; opacity: 0.7; margin-left: 4px;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" 
                            aria-labelledby="profileDropdown"
                            style="border-radius: 15px; padding: 8px; min-width: 200px; margin-top: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.profile') }}" style="border-radius: 10px; padding: 10px 15px; transition: 0.2s;">
                                    <i class="fas fa-user text-primary"></i>
                                    <span>Hồ sơ cá nhân</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="margin: 8px 0;"></li>
                            <li>
                                <button type="button" 
                                        class="dropdown-item d-flex align-items-center gap-2 text-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#logoutModal"
                                        style="border-radius: 10px; padding: 10px 15px; transition: 0.2s; border: none; background: transparent; width: 100%; text-align: left;">
                                    <i class="fas fa-power-off"></i>
                                    <span>Đăng xuất</span>
                                </button>
                            </li>
                        </ul>
                    </div>

<style>
    .dropdown-item:hover {
        background-color: #f8f9fa !important;
    }
    
    .dropdown-menu {
        animation: slideDown 0.2s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
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
    // Logout form submit
    const confirmBtn = document.getElementById('logoutConfirmBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            const logoutForm = document.createElement('form');
            logoutForm.method = 'POST';
            logoutForm.action = '{{ route("logout") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            logoutForm.appendChild(csrfToken);
            
            document.body.appendChild(logoutForm);
            logoutForm.submit();
        });
    }
});
</script>
@endpush
