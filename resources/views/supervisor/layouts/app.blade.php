<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>@yield('title') | VUFYPMS Supervisor</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">

  {{-- SB Admin CSS + Icons (CDN wahi jo template mein thay) --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="http://127.0.0.1:8000/sbadmin/css/styles.css" rel="stylesheet" />
  <link href="http://127.0.0.1:8000/sbadmin/css/vufypms-supervisor.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  {{-- VUFYPMS customization --}}
  <style>
    .sb-topnav { background: linear-gradient(90deg, #0f172a, #1e293b) !important; }
    .navbar-brand { font-weight: 800; letter-spacing: -0.02em; }
    .navbar-brand .brand-chip {
      display: inline-flex; align-items: center; justify-content: center;
      width: 30px; height: 30px; border-radius: 8px; margin-right: 8px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      font-size: 15px;
    }
    .sb-sidenav-dark { background: #0f172a; }
    .sb-sidenav-dark .nav-link.active {
      color: #fff;
      background: linear-gradient(90deg, rgba(99,102,241,.25), rgba(99,102,241,.05));
      border-left: 3px solid #818cf8;
    }
    .sb-sidenav-menu-heading { color: #64748b; }
    .card { border: none; border-radius: 14px; }
    .btn { border-radius: 9px; }
  </style>
  @stack('styles')
</head>
<body class="sb-nav-fixed">

  {{-- TOP NAVBAR --}}
  <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="{{ route('supervisor.dashboard') }}">
      <span class="brand-chip"><i class="bi bi-mortarboard-fill"></i></span>VUFYPMS
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    <ul class="navbar-nav ms-auto me-3 me-lg-4">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user fa-fw"></i>
          <span class="d-none d-md-inline ms-1">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <li>
            <a class="dropdown-item text-danger" href="#"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>
          </li>
        </ul>
      </li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
  </nav>

  <div id="layoutSidenav">
    {{-- SIDEBAR --}}
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">

            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}"
               href="{{ route('supervisor.dashboard') }}">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Dashboard
            </a>

            <div class="sb-sidenav-menu-heading">Supervision</div>
            <a class="nav-link {{ request()->routeIs('supervisor.proposals*') ? 'active' : '' }}"
               href="{{ route('supervisor.proposals') }}">
              <div class="sb-nav-link-icon"><i class="bi bi-file-earmark-text"></i></div>
              Proposal Reviews
              @if(isset($pendingCount) && $pendingCount > 0)
                <span class="badge bg-warning text-dark rounded-pill ms-auto">{{ $pendingCount }}</span>
              @endif
            </a>
            <a class="nav-link {{ request()->routeIs('supervisor.teams*') ? 'active' : '' }}"
               href="{{ route('supervisor.teams') }}">
              <div class="sb-nav-link-icon"><i class="bi bi-people-fill"></i></div>
              My Teams
            </a>
            <a class="nav-link {{ request()->routeIs('supervisor.evaluations*') ? 'active' : '' }}"
               href="{{ route('supervisor.evaluations') }}">
              <div class="sb-nav-link-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
              Evaluations
            </a>

            <div class="sb-sidenav-menu-heading">Communication</div>
            <a class="nav-link {{ request()->routeIs('supervisor.messages*') ? 'active' : '' }}"
               href="{{ route('supervisor.messages') }}">
              <div class="sb-nav-link-icon"><i class="bi bi-chat-dots-fill"></i></div>
              Messages
              @if(isset($supUnread) && $supUnread > 0)
                <span class="badge bg-primary rounded-pill ms-auto">{{ $supUnread }}</span>
              @endif
            </a>

          </div>
        </div>
        <div class="sb-sidenav-footer">
          <div class="small">Logged in as:</div>
          {{ Auth::user()->name }}
        </div>
      </nav>
    </div>

    {{-- CONTENT --}}
    <div id="layoutSidenav_content">
      <main>
        @yield('content')
      </main>
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; 2026 <strong>VUFYPMS</strong> · Virtual University of Pakistan</div>
            <div class="text-muted">Supervisor Portal</div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="{{ asset('sbadmin/js/scripts.js') }}"></script>
  @stack('scripts')
</body>
</html>