<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | VUFYPMS — Virtual University FYP Management System</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* ===== VUFYPMS Public Theme ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    :root {
      --brand: #4f46e5;
      --brand-2: #7c3aed;
      --navy: #0f172a;
      --border: #e5eaf2;
      --muted: #64748b;
    }

    body {
      font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
      color: #0f172a;
      letter-spacing: -0.011em;
      background: #fff;
    }

    h1, h2, h3, h4, h5 { font-weight: 800; letter-spacing: -0.025em; }

    /* Navbar */
    .public-nav {
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
    }
    .public-nav .navbar-brand { font-weight: 800; font-size: 19px; }
    .brand-chip {
      display: inline-flex; align-items: center; justify-content: center;
      width: 34px; height: 34px; border-radius: 10px; margin-right: 8px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: #fff; font-size: 16px;
      box-shadow: 0 6px 14px rgba(99,102,241,.35);
    }
    .public-nav .nav-link { font-weight: 600; color: #334155; font-size: 14px; }
    .public-nav .nav-link:hover, .public-nav .nav-link.active { color: var(--brand); }

    .btn { border-radius: 10px; font-weight: 600; }
    .btn-brand {
      background: linear-gradient(135deg, #6366f1, #4f46e5);
      color: #fff; border: none;
      box-shadow: 0 4px 14px rgba(79,70,229,.3);
      transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-brand:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
    .btn-ghost { border: 1px solid var(--border); color: #334155; }
    .btn-ghost:hover { border-color: var(--brand); color: var(--brand); }

    /* Cards */
    .p-card {
      border: 1px solid var(--border);
      border-radius: 16px;
      background: #fff;
      box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 8px 24px rgba(15,23,42,.05);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .p-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(15,23,42,.1); }

    /* Page hero (inner pages) */
    .page-hero {
      background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%);
      color: #fff;
      padding: 56px 0 48px;
    }
    .page-hero .eyebrow {
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: .12em; color: #a5b4fc;
    }

    /* Footer */
    .public-footer {
      background: var(--navy); color: #94a3b8;
      padding: 32px 0; font-size: 13px;
    }
    .public-footer a { color: #cbd5e1; text-decoration: none; }
    .public-footer a:hover { color: #fff; }
  </style>
  @stack('styles')
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar navbar-expand-lg public-nav sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('guest.home') }}">
        <span class="brand-chip"><i class="bi bi-mortarboard-fill"></i></span>VUFYPMS
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#pubNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="pubNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.home') ? 'active' : '' }}" href="{{ route('guest.home') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.guidelines') ? 'active' : '' }}" href="{{ route('guest.guidelines') }}">Guidelines</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.projects') ? 'active' : '' }}" href="{{ route('guest.projects') }}">Projects</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.announcements') ? 'active' : '' }}" href="{{ route('guest.announcements') }}">Announcements</a></li>
        </ul>
        <div class="d-flex gap-2">
          <a href="{{ route('login') }}" class="btn btn-ghost btn-sm px-3">Sign In</a>
          <a href="{{ route('register') }}" class="btn btn-brand btn-sm px-3">Register</a>
        </div>
      </div>
    </div>
  </nav>

  {{-- CONTENT --}}
  @yield('content')

  {{-- FOOTER --}}
  <footer class="public-footer">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <strong style="color:#fff;">VUFYPMS</strong> · Virtual University of Pakistan<br>
          Final Year Project Management System &copy; 2026
        </div>
        <div class="d-flex gap-4">
          <a href="{{ route('guest.guidelines') }}">Guidelines</a>
          <a href="{{ route('guest.projects') }}">Projects</a>
          <a href="{{ route('login') }}">Sign In</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>