<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | VUFYPMS</title>
  <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
  <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      background: #f1f5f9;
    }

    /* LEFT PANEL */
    .auth-left {
      width: 45%;
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 60px 50px;
      position: relative;
      overflow: hidden;
    }

    .auth-left::before {
      content: '';
      position: absolute;
      width: 400px; height: 400px;
      background: rgba(99, 102, 241, 0.15);
      border-radius: 50%;
      top: -100px; right: -100px;
    }

    .auth-left::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      background: rgba(14, 165, 233, 0.1);
      border-radius: 50%;
      bottom: -80px; left: -80px;
    }

    .left-content { position: relative; z-index: 1; text-align: center; }

    .brand-logo {
      width: 64px; height: 64px;
      background: linear-gradient(135deg, #6366f1, #0ea5e9);
      border-radius: 18px;
      display: flex; align-items: center; justify-content: center;
      font-size: 28px; color: #fff;
      margin: 0 auto 24px;
      box-shadow: 0 8px 32px rgba(99, 102, 241, 0.4);
    }

    .brand-name {
      font-size: 28px; font-weight: 700;
      color: #fff; letter-spacing: -0.5px;
      margin-bottom: 8px;
    }

    .brand-tagline {
      font-size: 14px; color: #94a3b8;
      line-height: 1.6; max-width: 300px;
      margin: 0 auto 48px;
    }

    .features { display: flex; flex-direction: column; gap: 20px; width: 100%; max-width: 320px; }

    .feature-item {
      display: flex; align-items: center; gap: 14px;
      padding: 16px 20px;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 14px;
      text-align: left;
    }

    .feature-icon {
      width: 40px; height: 40px; flex-shrink: 0;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
    }

    .feature-icon.blue  { background: rgba(99,102,241,0.2); color: #818cf8; }
    .feature-icon.green { background: rgba(34,197,94,0.2);  color: #4ade80; }
    .feature-icon.sky   { background: rgba(14,165,233,0.2); color: #38bdf8; }

    .feature-text h4 { font-size: 13px; font-weight: 600; color: #e2e8f0; margin-bottom: 2px; }
    .feature-text p  { font-size: 12px; color: #64748b; }

    /* RIGHT PANEL */
    .auth-right {
      flex: 1;
      display: flex; align-items: center; justify-content: center;
      padding: 40px;
    }

    .auth-card {
      width: 100%; max-width: 440px;
      background: #fff;
      border-radius: 24px;
      padding: 48px 40px;
      box-shadow: 0 4px 40px rgba(0,0,0,0.08);
    }

    .auth-header { margin-bottom: 32px; }
    .auth-header .eyebrow {
      font-size: 12px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.08em;
      color: #6366f1; margin-bottom: 8px;
    }
    .auth-header h1 { font-size: 26px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .auth-header p  { font-size: 14px; color: #64748b; }

    /* FORM */
    .form-group { margin-bottom: 20px; }

    .form-label {
      display: block; font-size: 13px; font-weight: 500;
      color: #374151; margin-bottom: 6px;
    }

    .input-wrapper { position: relative; }

    .input-icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: #94a3b8; font-size: 16px; pointer-events: none;
    }

    .form-input {
      width: 100%; padding: 11px 14px 11px 42px;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px; font-size: 14px;
      color: #1e293b; background: #f8fafc;
      outline: none; transition: all 0.2s;
    }

    .form-input:focus {
      border-color: #6366f1;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    .form-input.is-invalid { border-color: #ef4444; background: #fff; }
    .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

    .invalid-msg {
      font-size: 12px; color: #ef4444;
      margin-top: 5px; display: flex; align-items: center; gap: 4px;
    }

    /* REMEMBER ROW */
    .remember-row {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 24px;
    }

    .checkbox-label {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px; color: #374151; cursor: pointer;
    }

    .checkbox-label input[type="checkbox"] {
      width: 16px; height: 16px;
      accent-color: #6366f1; cursor: pointer;
    }

    .forgot-link {
      font-size: 13px; color: #6366f1;
      text-decoration: none; font-weight: 500;
    }
    .forgot-link:hover { color: #4f46e5; }

    /* SUBMIT BUTTON */
    .btn-submit {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #6366f1, #4f46e5);
      color: #fff; border: none; border-radius: 12px;
      font-size: 15px; font-weight: 600;
      cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(99,102,241,0.45);
    }

    .btn-submit:active { transform: translateY(0); }

    /* FOOTER */
    .auth-footer {
      text-align: center; margin-top: 28px;
      font-size: 14px; color: #64748b;
    }

    .auth-footer a { color: #6366f1; font-weight: 600; text-decoration: none; }
    .auth-footer a:hover { color: #4f46e5; }

    /* ALERT */
    .alert-error {
      background: #fef2f2; border: 1px solid #fecaca;
      border-radius: 12px; padding: 14px 16px;
      margin-bottom: 24px;
      display: flex; align-items: flex-start; gap: 10px;
    }

    .alert-error i { color: #ef4444; font-size: 16px; margin-top: 1px; flex-shrink: 0; }
    .alert-error ul { list-style: none; }
    .alert-error ul li { font-size: 13px; color: #dc2626; }

    /* DIVIDER */
    .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 24px 0; color: #94a3b8; font-size: 12px;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px; background: #e2e8f0;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .auth-left { display: none; }
      .auth-right { padding: 20px; }
      .auth-card { padding: 32px 24px; }
    }
  </style>
</head>
<body>

  <!-- LEFT PANEL -->
  <div class="auth-left">
    <div class="left-content">
      <div class="brand-logo">
        <i class="bi bi-mortarboard-fill"></i>
      </div>
      <div class="brand-name">VUFYPMS</div>
      <p class="brand-tagline">
        Virtual University Final Year Project Management System — Streamline your FYP journey.
      </p>

      <div class="features">
        <div class="feature-item">
          <div class="feature-icon blue"><i class="bi bi-file-earmark-text"></i></div>
          <div class="feature-text">
            <h4>Proposal Management</h4>
            <p>Submit & track your project proposals</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon green"><i class="bi bi-flag-fill"></i></div>
          <div class="feature-text">
            <h4>Milestone Tracking</h4>
            <p>Monitor progress across all phases</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon sky"><i class="bi bi-chat-dots-fill"></i></div>
          <div class="feature-text">
            <h4>Direct Communication</h4>
            <p>Chat with your supervisor anytime</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="auth-right">
    <div class="auth-card">

      <div class="auth-header">
        <p class="eyebrow">Welcome Back</p>
        <h1>Sign In</h1>
        <p>Enter your credentials to access your dashboard</p>
      </div>

      {{-- ERROR MESSAGES --}}
      @if($errors->any())
        <div class="alert-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('status'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:14px 16px; margin-bottom:24px; font-size:13px; color:#16a34a;">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <div class="input-wrapper">
            <i class="bi bi-envelope input-icon"></i>
            <input
              class="form-input @error('email') is-invalid @enderror"
              id="email" name="email" type="email"
              value="{{ old('email') }}"
              placeholder="you@vu.edu.pk"
              required autofocus>
          </div>
          @error('email')
            <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrapper">
            <i class="bi bi-lock input-icon"></i>
            <input
              class="form-input @error('password') is-invalid @enderror"
              id="password" name="password" type="password"
              placeholder="Enter your password"
              required>
          </div>
          @error('password')
            <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Remember Me --}}
        <div class="remember-row">
          <label class="checkbox-label">
            <input type="checkbox" name="remember">
            Remember me
          </label>
          <a href="#" class="forgot-link">Forgot password?</a>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit">
          <i class="bi bi-box-arrow-in-right"></i> Sign In
        </button>

      </form>

      <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
      </div>

    </div>
  </div>

</body>
</html>
