<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title') | VUFYPMS Admin</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">

  {{-- SB Admin 2 fonts + css --}}
  <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
  <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

  {{-- VUFYPMS Admin customization --}}
  <style>
    /* Sidebar: navy gradient (student/supervisor portals se match) */
    .bg-gradient-primary {
      background: linear-gradient(180deg, #0f172a 0%, #1e293b 60%, #131a2e 100%) !important;
    }
    .sidebar-brand-icon { font-size: 1.6rem; }
    .sidebar .nav-item .nav-link.active {
      font-weight: 700;
      border-left: 3px solid #818cf8;
      background: linear-gradient(90deg, rgba(99,102,241,.25), transparent);
    }
    .btn-primary, .bg-primary {
      background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
      border-color: #4f46e5 !important;
    }
    .text-primary { color: #4f46e5 !important; }
    .border-left-primary { border-left-color: #4f46e5 !important; }
    .card { border: 1px solid #e5eaf2; border-radius: 14px; }
    .card-header { border-radius: 14px 14px 0 0 !important; }
    .btn { border-radius: 9px; }
  </style>
  @stack('styles')
</head>
<body id="page-top">
<div id="wrapper">

  {{-- ================= SIDEBAR ================= --}}
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
      <div class="sidebar-brand-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="sidebar-brand-text mx-2">VUFYPMS</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
      </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Management</div>

    <li class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.users') }}">
        <i class="fas fa-fw fa-users"></i><span>User Management</span>
      </a>
    </li>
    <li class="nav-item {{ request()->routeIs('admin.assignments*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.assignments') }}">
        <i class="fas fa-fw fa-user-tie"></i><span>Supervisor Assignment</span>
      </a>
    </li>
    <li class="nav-item {{ request()->routeIs('admin.teams*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.teams') }}">
        <i class="fas fa-fw fa-people-group fa-users-rectangle fa-table"></i><span>Teams Overview</span>
      </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Communication</div>

    <li class="nav-item {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.announcements') }}">
        <i class="fas fa-fw fa-bullhorn"></i><span>Announcements</span>
      </a>
    </li>
    <li class="nav-item {{ request()->routeIs('admin.presentations*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('admin.presentations') }}">
        <i class="fas fa-fw fa-calendar-alt"></i><span>Presentations</span>
      </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
  </ul>

  {{-- ================= CONTENT WRAPPER ================= --}}
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

      {{-- TOPBAR --}}
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
          <i class="fa fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small font-weight-bold">{{ Auth::user()->name }}</span>
              <img class="img-profile rounded-circle" src="{{ asset('sbadmin2/img/undraw_profile.svg') }}">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <a class="dropdown-item text-danger" href="#"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>Logout
              </a>
            </div>
          </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
      </nav>

      {{-- PAGE CONTENT --}}
      <div class="container-fluid">
        @yield('content')
      </div>

    </div>

    {{-- FOOTER --}}
    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>Copyright &copy; 2026 <strong>VUFYPMS</strong> · Virtual University of Pakistan — Admin Portal</span>
        </div>
      </div>
    </footer>
  </div>
</div>

{{-- Scroll to top --}}
<a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

{{-- SB Admin 2 scripts --}}
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/chart.js/Chart.min.js') }}"></script>
@stack('scripts')
</body>
</html>