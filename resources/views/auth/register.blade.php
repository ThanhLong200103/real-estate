<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng ký | ESTATE HUB</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --sky:#38bdf8;
      --sky-dark:#0ea5e9;
      --text:#0f172a;
      --muted:#64748b;
      --bg-right:#eef2f7;
      --stroke:#e6edf5;
      --radius:22px;
    }

    body{ margin:0; min-height:100vh; font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif; color:var(--text); background:#f3f6fb; }
    .auth-shell{ min-height:100vh; display:flex; align-items:stretch; }

    /* LEFT */
    .auth-left{
      position:relative; flex:1.25;
      background:
        linear-gradient(180deg, rgba(0,0,0,.16), rgba(0,0,0,.35)),
        url("https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1600&q=80")
        center/cover no-repeat;
      overflow:hidden;
    }
    .left-overlay{ position:absolute; inset:0; display:flex; align-items:flex-end; padding:56px; }
    .left-brand{ color:#fff; max-width:560px; text-shadow:0 8px 24px rgba(0,0,0,.25); }
    .left-brand h1{ margin:0 0 10px; font-weight:900; font-size:clamp(34px,3.4vw,56px); letter-spacing:.2px; }
    .left-brand p{ margin:0; opacity:.95; font-weight:600; font-size:15px; }

    /* RIGHT */
    .auth-right{ flex:0.85; background:var(--bg-right); display:flex; align-items:center; justify-content:center; padding:44px 36px; }
    .login-card{
      width:100%; max-width:560px; background:#fff; border:1px solid rgba(15,23,42,.06);
      border-radius:var(--radius); box-shadow:0 22px 60px rgba(15,23,42,.12);
      padding:34px 34px 28px;
    }

    /* LOGO */
    .brand-row{ display:flex; align-items:center; gap:12px; margin-bottom:18px; }
    .brand-icon{
      width:44px;height:44px;border-radius:14px;display:grid;place-items:center;
      background:rgba(56,189,248,.14); color:var(--sky-dark); font-size:18px;
    }
    .brand-title{ font-weight:900; font-size:22px; margin:0; line-height:1.1; letter-spacing:.2px; }
    .brand-title .hub{ color:var(--sky); }
    .brand-sub{ margin:3px 0 0; font-size:13px; color:var(--muted); font-weight:700; }

    .welcome{ margin:8px 0 18px; }
    .welcome h2{ margin:0 0 6px; font-size:34px; font-weight:900; letter-spacing:-.2px; }
    .welcome p{ margin:0; color:var(--muted); font-weight:600; }

    .form-label{ font-size:13px; font-weight:800; color:#334155; margin-bottom:8px; }
    .input-wrap{ position:relative; }
    .input-icon{ position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:16px; pointer-events:none; }

    .form-control{
      border-radius:14px; padding:13px 14px 13px 44px; border:1px solid var(--stroke);
      font-weight:700; box-shadow:none !important;
    }
    .form-control:focus{
      border-color:rgba(56,189,248,.65);
      box-shadow:0 0 0 4px rgba(56,189,248,.16) !important;
    }

    .pass-tools{ position:absolute; right:10px; top:50%; transform:translateY(-50%); display:flex; align-items:center; gap:6px; }
    .eye-btn{ width:38px;height:38px;border:none;background:transparent;color:#94a3b8;border-radius:12px;display:grid;place-items:center; }
    .eye-btn:hover{ background:rgba(148,163,184,.14); color:#64748b; }

    .btn-brand{
      margin-top:16px; width:100%;
      background:var(--sky); border:none; color:#fff; padding:14px 16px; border-radius:14px;
      font-weight:900; letter-spacing:.2px; transition:.18s;
    }
    .btn-brand:hover{ background:var(--sky-dark); transform:translateY(-1px); }

    .divider{ display:flex; align-items:center; gap:14px; margin:18px 0 14px; color:#94a3b8; font-weight:800; font-size:12px; }
    .divider::before,.divider::after{ content:""; height:1px; flex:1; background:#e7edf5; }

    .social-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
    .btn-social{
      border:1px solid #e7edf5; background:#fff; border-radius:14px; padding:12px 14px;
      display:flex; align-items:center; justify-content:center; gap:10px;
      font-weight:900; color:#0f172a; transition:.15s; white-space:nowrap;
      cursor:pointer; user-select:none;
    }
    .btn-social:hover{ transform:translateY(-1px); box-shadow:0 10px 28px rgba(15,23,42,.08); }

    .bottom-link{ margin-top:16px; text-align:center; color:var(--muted); font-weight:700; font-size:14px; }
    .bottom-link a{ color:var(--sky-dark); font-weight:900; text-decoration:none; }
    .bottom-link a:hover{ color:var(--sky); }

    .error-text{ color:#ef4444; font-size:13px; font-weight:800; margin-top:6px; }

    .grid-2{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }

    /* ====== Maintenance Modal ====== */
    .m-modal{
      position:fixed; inset:0;
      background: rgba(15,23,42,.55);
      display:none;
      align-items:center;
      justify-content:center;
      padding: 18px;
      z-index: 9999;
    }
    .m-modal.show{ display:flex; }

    .m-dialog{
      width:100%;
      max-width: 420px;
      background:#fff;
      border-radius: 18px;
      box-shadow: 0 25px 70px rgba(15,23,42,.25);
      overflow:hidden;
      border: 1px solid rgba(15,23,42,.08);
      animation: pop .16s ease-out;
    }
    @keyframes pop{
      from{ transform: translateY(8px) scale(.98); opacity:.6; }
      to{ transform: translateY(0) scale(1); opacity:1; }
    }

    .m-head{
      padding: 16px 18px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 10px;
      background: rgba(56,189,248,.10);
    }
    .m-title{
      display:flex;
      align-items:center;
      gap:10px;
      font-weight: 900;
      color:#0f172a;
    }
    .m-icon{
      width:36px;height:36px;border-radius:12px;
      display:grid;place-items:center;
      background:#fff;
      border:1px solid rgba(15,23,42,.06);
      color: var(--sky-dark);
    }
    .m-close{
      width:36px;height:36px;border-radius:12px;
      border:none;background:#fff;
      border:1px solid rgba(15,23,42,.06);
      color:#64748b;
      display:grid;place-items:center;
    }
    .m-close:hover{ background:#f8fafc; color:#0f172a; }

    .m-body{ padding: 16px 18px 6px; }
    .m-body p{ margin:0 0 12px; color:#334155; font-weight:700; line-height:1.5; }
    .m-note{
      background:#f8fafc;
      border:1px dashed rgba(15,23,42,.15);
      border-radius: 14px;
      padding: 10px 12px;
      color:#475569;
      font-weight:700;
      font-size: 13px;
    }

    .m-actions{
      padding: 14px 18px 18px;
      display:flex;
      gap:10px;
    }
    .m-btn{
      width:100%;
      padding: 12px 14px;
      border-radius: 14px;
      border: none;
      font-weight: 900;
      letter-spacing:.1px;
    }
    .m-btn.primary{ background: var(--sky); color:#fff; }
    .m-btn.primary:hover{ background: var(--sky-dark); }
    .m-btn.ghost{
      background: #fff;
      border: 1px solid rgba(15,23,42,.12);
      color:#0f172a;
    }
    .m-btn.ghost:hover{ background:#f8fafc; }

    @media (max-width: 992px){
      .auth-shell{ flex-direction:column; }
      .auth-left{ min-height: 44vh; }
      .auth-right{ padding: 24px; }
      .login-card{ max-width: 680px; }
      .left-overlay{ padding: 26px; }
    }
    @media (max-width: 520px){
      .login-card{ padding: 22px; }
      .social-grid{ grid-template-columns: 1fr; }
      .grid-2{ grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
  <div class="auth-shell">

    <section class="auth-left">
      <div class="left-overlay">
        <div class="left-brand">
          <h1>ESTATE<span style="color: var(--sky);">HUB</span></h1>
          <p>Nền tảng bất động sản uy tín • Minh bạch • Nhanh chóng</p>
        </div>
      </div>
    </section>

    <section class="auth-right">
      <div class="login-card">

        <div class="brand-row">
          <div class="brand-icon"><i class="fa-solid fa-house"></i></div>
          <div>
            <p class="brand-title mb-0">ESTATE<span class="hub">HUB</span></p>
            <p class="brand-sub">Agent Portal</p>
          </div>
        </div>

        <div class="welcome">
          <h2>Create account</h2>
          <p>Join to start using your dashboard</p>
        </div>

        <form action="{{ route('register') }}" method="POST" autocomplete="off">
          @csrf
          <input type="text" style="display:none" name="fake_user_name">

          <div class="mb-3">
            <label class="form-label">Full name</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-regular fa-user"></i></span>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     placeholder="Enter your full name" required autocomplete="off">
            </div>
            @error('name') <div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Phone number</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-solid fa-phone"></i></span>
              <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                     placeholder="e.g. 0901234567" required autocomplete="off">
            </div>
            @error('phone_number') <div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     placeholder="email@example.com" required autocomplete="off">
            </div>
            @error('email') <div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> @enderror
          </div>

          <div class="grid-2">
            <div class="mb-2">
              <label class="form-label">Password</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                <input id="passwordInput" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required autocomplete="new-password">
                <div class="pass-tools">
                  <button type="button" class="eye-btn" id="togglePass" aria-label="Toggle password">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>
              @error('password') <div class="error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label">Confirm</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-shield-halved"></i></span>
                <input id="confirmInput" type="password" name="password_confirmation"
                       class="form-control" placeholder="••••••••" required autocomplete="new-password">
                <div class="pass-tools">
                  <button type="button" class="eye-btn" id="toggleConfirm" aria-label="Toggle confirm password">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn-brand">Create account</button>

          <div class="divider">or continue with</div>

          <!-- Social: click -> show maintenance modal -->
          <div class="social-grid">
            <div class="btn-social" role="button" tabindex="0" data-maintenance="Google">
              <img alt="Google" width="18" height="18" src="https://www.svgrepo.com/show/475656/google-color.svg">
              Google
            </div>

            <div class="btn-social" role="button" tabindex="0" data-maintenance="Microsoft">
              <img alt="Microsoft" width="18" height="18" src="https://www.svgrepo.com/show/452062/microsoft.svg">
              Microsoft
            </div>
          </div>

          <div class="bottom-link">
            Already have an account? <a href="{{ route('login-form') }}">Sign in</a>
          </div>
        </form>

      </div>
    </section>
  </div>

  <!-- Maintenance Modal -->
  <div class="m-modal" id="maintenanceModal" aria-hidden="true">
    <div class="m-dialog" role="dialog" aria-modal="true" aria-labelledby="mTitle">
      <div class="m-head">
        <div class="m-title" id="mTitle">
          <span class="m-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
          <span id="mProvider">Tính năng đang bảo trì</span>
        </div>
        <button class="m-close" type="button" id="mClose" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="m-body">
        <p id="mText">Đăng nhập bằng mạng xã hội hiện đang được bảo trì. Vui lòng đăng nhập bằng email/mật khẩu.</p>
        <div class="m-note">
          Gợi ý: Nếu bạn chưa có tài khoản, hãy đăng ký ở trang Register.
        </div>
      </div>

      <div class="m-actions">
        <button class="m-btn ghost" type="button" id="mOk">Đã hiểu</button>
        <button class="m-btn primary" type="button" id="mFocusEmail">Đi tới Email</button>
      </div>
    </div>
  </div>

  <script>
    // toggle password
    function bindToggle(inputId, btnId){
      const input = document.getElementById(inputId);
      const btn = document.getElementById(btnId);
      btn?.addEventListener('click', () => {
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        btn.innerHTML = isHidden
          ? '<i class="fa-regular fa-eye-slash"></i>'
          : '<i class="fa-regular fa-eye"></i>';
      });
    }
    bindToggle('passwordInput', 'togglePass');
    bindToggle('confirmInput', 'toggleConfirm');

    // maintenance modal
    const modal = document.getElementById('maintenanceModal');
    const providerEl = document.getElementById('mProvider');
    const textEl = document.getElementById('mText');

    function openMaintenance(provider){
      providerEl.textContent = provider + " đang bảo trì";
      textEl.textContent = "Đăng nhập bằng " + provider + " hiện đang được bảo trì. Vui lòng đăng nhập bằng email/mật khẩu.";
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }
    function closeMaintenance(){
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('[data-maintenance]').forEach(el => {
      el.addEventListener('click', () => openMaintenance(el.dataset.maintenance));
      el.addEventListener('keydown', (e) => {
        if(e.key === 'Enter' || e.key === ' ') openMaintenance(el.dataset.maintenance);
      });
    });

    document.getElementById('mClose')?.addEventListener('click', closeMaintenance);
    document.getElementById('mOk')?.addEventListener('click', closeMaintenance);
    modal?.addEventListener('click', (e) => { if(e.target === modal) closeMaintenance(); });
    window.addEventListener('keydown', (e) => { if(e.key === 'Escape' && modal.classList.contains('show')) closeMaintenance(); });

    document.getElementById('mFocusEmail')?.addEventListener('click', () => {
      closeMaintenance();
      document.querySelector('input[name="email"]')?.focus();
    });
  </script>
</body>
</html>
