<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | VUFYPMS</title>
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

    .steps { display: flex; flex-direction: column; gap: 0; width: 100%; max-width: 300px; }

    .step-item {
      display: flex; align-items: flex-start; gap: 14px;
      padding: 16px 0;
      position: relative;
    }

    .step-item:not(:last-child)::after {
      content: '';
      position: absolute;
      left: 19px; top: 52px;
      width: 2px; height: calc(100% - 20px);
      background: rgba(255,255,255,0.08);
    }

    .step-num {
      width: 40px; height: 40px; flex-shrink: 0;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 700;
      position: relative; z-index: 1;
    }

    .step-num.done   { background: rgba(34,197,94,0.2);  color: #4ade80; border: 2px solid rgba(34,197,94,0.3); }
    .step-num.active { background: rgba(99,102,241,0.3); color: #a5b4fc; border: 2px solid rgba(99,102,241,0.4); }
    .step-num.pending{ background: rgba(255,255,255,0.05); color: #475569; border: 2px solid rgba(255,255,255,0.08); }

    .step-text h4 { font-size: 13px; font-weight: 600; color: #e2e8f0; margin-bottom: 2px; margin-top: 8px; }
    .step-text p  { font-size: 12px; color: #64748b; }

    /* RIGHT PANEL */
    .auth-right {
      flex: 1;
      display: flex; align-items: center; justify-content: center;
      padding: 40px;
      overflow-y: auto;
    }

    .auth-card {
      width: 100%; max-width: 480px;
      background: #fff;
      border-radius: 24px;
      padding: 44px 40px;
      box-shadow: 0 4px 40px rgba(0,0,0,0.08);
    }

    .auth-header { margin-bottom: 28px; }
    .auth-header .eyebrow {
      font-size: 12px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.08em;
      color: #6366f1; margin-bottom: 8px;
    }
    .auth-header h1 { font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .auth-header p  { font-size: 14px; color: #64748b; }

    /* FORM */
    .form-group { margin-bottom: 18px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .form-label {
      display: block; font-size: 13px; font-weight: 500;
      color: #374151; margin-bottom: 6px;
    }

    .input-wrapper { position: relative; }

    .input-icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: #94a3b8; font-size: 15px; pointer-events: none;
    }

    .form-input {
      width: 100%; padding: 10px 14px 10px 40px;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px; font-size: 14px;
      color: #1e293b; background: #f8fafc;
      outline: none; transition: all 0.2s;
    }

    .form-input.no-icon { padding-left: 14px; }

    .form-input:focus {
      border-color: #6366f1;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    .form-input.is-invalid { border-color: #ef4444; }
    .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

    .form-select {
      width: 100%; padding: 10px 14px;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px; font-size: 14px;
      color: #1e293b; background: #f8fafc;
      outline: none; transition: all 0.2s;
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
    }

    .form-select:focus {
      border-color: #6366f1;
      background-color: #fff;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    .form-select.is-invalid { border-color: #ef4444; }

    .invalid-msg {
      font-size: 12px; color: #ef4444;
      margin-top: 5px; display: flex; align-items: center; gap: 4px;
    }

    /* ROLE SECTION */
    .role-fields {
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      padding: 18px;
      margin-bottom: 18px;
    }

    .role-fields-title {
      font-size: 12px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.06em;
      color: #6366f1; margin-bottom: 14px;
      display: flex; align-items: center; gap: 6px;
    }

    /* ALERT */
    .alert-error {
      background: #fef2f2; border: 1px solid #fecaca;
      border-radius: 12px; padding: 14px 16px;
      margin-bottom: 20px;
      display: flex; align-items: flex-start; gap: 10px;
    }
    .alert-error i { color: #ef4444; font-size: 16px; margin-top: 1px; flex-shrink: 0; }
    .alert-error ul { list-style: none; }
    .alert-error ul li { font-size: 13px; color: #dc2626; }

    /* SUBMIT BUTTON */
    .btn-submit {
      width: 100%; padding: 13px;
      background: linear-gradient(135deg, #6366f1, #4f46e5);
      color: #fff; border: none; border-radius: 12px;
      font-size: 15px; font-weight: 600;
      cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 4px 14px rgba(99,102,241,0.35);
      margin-top: 8px;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(99,102,241,0.45);
    }

    /* FOOTER */
    .auth-footer {
      text-align: center; margin-top: 24px;
      font-size: 14px; color: #64748b;
    }
    .auth-footer a { color: #6366f1; font-weight: 600; text-decoration: none; }
    .auth-footer a:hover { color: #4f46e5; }

    /* SECTION DIVIDER */
    .section-divider {
      border: none; border-top: 1px solid #f1f5f9;
      margin: 20px 0;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .auth-left { display: none; }
      .auth-right { padding: 20px; }
      .auth-card { padding: 32px 24px; }
      .form-row { grid-template-columns: 1fr; }
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
        Create your account and start managing your Final Year Project efficiently.
      </p>

      <div class="steps">
        <div class="step-item">
          <div class="step-num active">1</div>
          <div class="step-text">
            <h4>Create Account</h4>
            <p>Fill in your basic information</p>
          </div>
        </div>
        <div class="step-item">
          <div class="step-num pending">2</div>
          <div class="step-text">
            <h4>Setup Profile</h4>
            <p>Add your academic details</p>
          </div>
        </div>
        <div class="step-item">
          <div class="step-num pending">3</div>
          <div class="step-text">
            <h4>Join or Create Team</h4>
            <p>Connect with your project team</p>
          </div>
        </div>
        <div class="step-item">
          <div class="step-num pending">4</div>
          <div class="step-text">
            <h4>Start Your FYP</h4>
            <p>Submit proposals & track progress</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="auth-right">
    <div class="auth-card">

      <div class="auth-header">
        <p class="eyebrow">Get Started</p>
        <h1>Create Account</h1>
        <p>Join VUFYPMS to manage your Final Year Project</p>
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

      <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name & Email --}}
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="name">Full Name</label>
            <div class="input-wrapper">
              <i class="bi bi-person input-icon"></i>
              <input class="form-input @error('name') is-invalid @enderror"
                     id="name" name="name" type="text"
                     value="{{ old('name') }}"
                     placeholder="Muhammad Talha"
                     required>
            </div>
            @error('name')
              <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-wrapper">
              <i class="bi bi-envelope input-icon"></i>
              <input class="form-input @error('email') is-invalid @enderror"
                     id="email" name="email" type="email"
                     value="{{ old('email') }}"
                     placeholder="you@vu.edu.pk"
                     required>
            </div>
            @error('email')
              <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Password & Confirm --}}
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrapper">
              <i class="bi bi-lock input-icon"></i>
              <input class="form-input @error('password') is-invalid @enderror"
                     id="password" name="password" type="password"
                     placeholder="Min. 6 characters"
                     minlength="6" required>
            </div>
            @error('password')
              <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <div class="input-wrapper">
              <i class="bi bi-lock-fill input-icon"></i>
              <input class="form-input"
                     id="password_confirmation"
                     name="password_confirmation" type="password"
                     placeholder="Repeat password"
                     required>
            </div>
          </div>
        </div>

        {{-- Role --}}
        <div class="form-group">
          <label class="form-label" for="role">Register As</label>
          <select class="form-select @error('role') is-invalid @enderror"
                  id="role" name="role" required
                  onchange="toggleRoleFields()">
            <option value="" disabled selected>Select your role...</option>
            <option value="student"    {{ old('role') === 'student'    ? 'selected' : '' }}>👨‍🎓 Student</option>
            <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>👨‍🏫 Supervisor</option>
          </select>
          @error('role')
            <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- STUDENT FIELDS --}}
        <div id="student-fields" style="display:none;">
          <div class="role-fields">
            <div class="role-fields-title">
              <i class="bi bi-mortarboard"></i> Student Information
            </div>

            <div class="form-group">
              <label class="form-label" for="vuid">VU Student ID</label>
              <div class="input-wrapper">
                <i class="bi bi-person-badge input-icon"></i>
                <input class="form-input @error('vuid') is-invalid @enderror"
                       id="vuid" name="vuid" type="text"
                       value="{{ old('vuid') }}"
                       placeholder="e.g. BC240401856">
              </div>
              @error('vuid')
                <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="program">Program</label>
                <select class="form-select @error('program') is-invalid @enderror"
                        id="program" name="program">
                  <option value="">Select program...</option>
                  <option value="BS Computer Science"       {{ old('program') === 'BS Computer Science'       ? 'selected' : '' }}>BS Computer Science</option>
                  <option value="BS Software Engineering"   {{ old('program') === 'BS Software Engineering'   ? 'selected' : '' }}>BS Software Engineering</option>
                  <option value="BS Information Technology" {{ old('program') === 'BS Information Technology' ? 'selected' : '' }}>BS Information Technology</option>
                </select>
                @error('program')
                  <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label class="form-label" for="semester">Semester</label>
                <select class="form-select @error('semester') is-invalid @enderror"
                        id="semester" name="semester">
                  <option value="">Select...</option>
                  @for($i = 1; $i <= 8; $i++)
                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                      Semester {{ $i }}
                    </option>
                  @endfor
                </select>
                @error('semester')
                  <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
              </div>
            </div>

          </div>
        </div>

        {{-- SUPERVISOR FIELDS --}}
        <div id="supervisor-fields" style="display:none;">
          <div class="role-fields">
            <div class="role-fields-title">
              <i class="bi bi-building"></i> Supervisor Information
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="department">Department</label>
                <select class="form-select @error('department') is-invalid @enderror"
                        id="department" name="department">
                  <option value="">Select department...</option>
                  <option value="Computer Science"       {{ old('department') === 'Computer Science'       ? 'selected' : '' }}>Computer Science</option>
                  <option value="Software Engineering"   {{ old('department') === 'Software Engineering'   ? 'selected' : '' }}>Software Engineering</option>
                  <option value="Information Technology" {{ old('department') === 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                </select>
                @error('department')
                  <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label class="form-label" for="max_teams">Max Teams</label>
                <div class="input-wrapper">
                  <i class="bi bi-people input-icon"></i>
                  <input class="form-input @error('max_teams') is-invalid @enderror"
                         id="max_teams" name="max_teams" type="number"
                         min="1" max="20"
                         value="{{ old('max_teams', 5) }}">
                </div>
                @error('max_teams')
                  <div class="invalid-msg"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
              </div>
            </div>

          </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit">
          <i class="bi bi-person-plus-fill"></i> Create Account
        </button>

      </form>

      <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
      </div>

    </div>
  </div>

  <script>
    function toggleRoleFields() {
      const role = document.getElementById('role').value;
      document.getElementById('student-fields').style.display
        = role === 'student' ? 'block' : 'none';
      document.getElementById('supervisor-fields').style.display
        = role === 'supervisor' ? 'block' : 'none';
    }

    // Page load par old() restore karo
    document.addEventListener('DOMContentLoaded', toggleRoleFields);
  </script>

</body>
</html>
