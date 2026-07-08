<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | VUFYPMS</title>
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
  <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
  <style>
    .nav-section-label {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--admin-text-muted);
      padding: 14px 16px 4px;
      margin: 0;
    }
  </style>
  @stack('styles')
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('student.dashboard') }}">
          <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
          <span class="brand-copy">
            <span class="brand-title">VUFYPMS</span>
            <span class="brand-subtitle">Student Portal</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <p class="nav-section-label">Main</p>
        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
          href="{{ route('student.dashboard') }}">
          <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.proposal*') ? 'active' : '' }}"
          href="{{ route('student.proposal') }}">
          <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span>
          <span class="nav-text">My Proposal</span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.team*') ? 'active' : '' }}"
          href="{{ route('student.team') }}">
          <span class="nav-icon"><i class="bi bi-people"></i></span>
          <span class="nav-text">My Team</span>
        </a>

        <p class="nav-section-label">Project</p>
        <a class="nav-link {{ request()->routeIs('student.milestones*') ? 'active' : '' }}"
          href="{{ route('student.milestones') }}">
          <span class="nav-icon"><i class="bi bi-flag"></i></span>
          <span class="nav-text">Milestones</span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.documents*') ? 'active' : '' }}"
          href="{{ route('student.documents') }}">
          <span class="nav-icon"><i class="bi bi-folder2-open"></i></span>
          <span class="nav-text">Documents</span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.evaluations*') ? 'active' : '' }}"
          href="{{ route('student.evaluations') }}">
          <span class="nav-icon"><i class="bi bi-bar-chart-line"></i></span>
          <span class="nav-text">Evaluations</span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.presentations*') ? 'active' : '' }}"
          href="{{ route('student.presentations') }}">
          <span class="nav-icon"><i class="bi bi-camera-video"></i></span>
          <span class="nav-text">Presentations</span>
        </a>

        <p class="nav-section-label">Communication</p>
        <a class="nav-link {{ request()->routeIs('student.messages*') ? 'active' : '' }}"
          href="{{ route('student.messages') }}">
          <span class="nav-icon"><i class="bi bi-chat-dots"></i></span>
          <span class="nav-text">
            Messages
            @if($unreadMessages > 0)
            <span class="badge bg-primary rounded-pill ms-auto" style="font-size:10px;">
              {{ $unreadMessages }}
            </span>
            @endif
          </span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.announcements*') ? 'active' : '' }}"
          href="{{ route('student.announcements') }}">
          <span class="nav-icon"><i class="bi bi-megaphone"></i></span>
          <span class="nav-text">Announcements</span>
        </a>

        <p class="nav-section-label">Account</p>
        <a class="nav-link {{ request()->routeIs('student.profile*') ? 'active' : '' }}"
          href="{{ route('student.profile') }}">
          <span class="nav-icon"><i class="bi bi-person-circle"></i></span>
          <span class="nav-text">Profile</span>
        </a>
        <a class="nav-link {{ request()->routeIs('student.settings*') ? 'active' : '' }}"
          href="{{ route('student.settings') }}">
          <span class="nav-icon"><i class="bi bi-gear"></i></span>
          <span class="nav-text">Settings</span>
        </a>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
          style="color:#ef4444;">
          <span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
          <span class="nav-text">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
      </nav>

      <div class="sidebar-user">
        @if($student && $student->profile_picture)
          <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ Auth::user()->name }}" style="width:60px;height:60px;border-radius:12px;object-fit:cover;">
        @else
          <div class="avatar-img avatar-md sidebar-user-avatar" style="width:60px;height:60px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:24px;">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
        @endif
        <span><b>{{ Auth::user()->name }}</b></span>
        <small>
          {{ $student->vuid ?? '' }}
          @if($student) · Sem {{ $student->semester }} @endif
        </small>
      </div>

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">
          Active · {{ $student->program ?? 'Student' }}
        </span>
      </div>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">
      {{-- NAVBAR --}}
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle>
            <span></span><span></span><span></span>
          </button>
          <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
            <input class="form-control search-input" type="search"
              placeholder="Search proposals, milestones, documents...">
          </form>
          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle>
              <i class="bi bi-moon-stars" data-theme-icon></i>
            </button>
            <div class="dropdown">
              <button class="icon-button" type="button" data-bs-toggle="dropdown">
                <span class="notification-dot"></span>
                <i class="bi bi-bell"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end notification-menu">
                <div class="dropdown-header fw-bold text-body">Notifications</div>
                @forelse($announcements as $ann)
                <a class="dropdown-item" href="{{ route('student.announcements') }}">
                  <span class="notification-title">{{ $ann->title }}</span>
                  <span class="notification-time">
                    {{ \Carbon\Carbon::parse($ann->published_at)->diffForHumans() }}
                  </span>
                </a>
                @empty
                <div class="dropdown-item text-muted">No notifications</div>
                @endforelse
              </div>
            </div>
            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <div class="avatar-img avatar-sm" style="...indigo-purple gradient...">
                  {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span>{{ Auth::user()->name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('student.profile') }}">
                    <i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="{{ route('student.settings') }}">
                    <i class="bi bi-gear me-2"></i>Settings</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item text-danger" href="#"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i>Sign out
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      {{-- PAGE CONTENT --}}
      <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
          @yield('content')
        </div>
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright © {{ date('Y') }} <strong>VUFYPMS</strong> · Virtual University of Pakistan</span>
          <span>Student Portal</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  @stack('scripts')
</body>

</html>