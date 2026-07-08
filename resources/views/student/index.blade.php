@extends('student.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
  /* ===== Dashboard-specific professional styling ===== */

  /* Welcome Hero Banner */
  .dash-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 60%, #7c3aed 100%);
    border-radius: 20px;
    padding: 28px 30px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 44px rgba(79, 70, 229, 0.28);
  }
  .dash-hero::before {
    content: '';
    position: absolute;
    right: -60px; top: -80px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
  }
  .dash-hero::after {
    content: '';
    position: absolute;
    right: 80px; bottom: -100px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
  }
  .dash-hero .hero-eyebrow {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em;
    color: rgba(255,255,255,0.75);
  }
  .dash-hero h1 { font-weight: 800; letter-spacing: -0.02em; }
  .dash-hero .hero-sub { color: rgba(255,255,255,0.8); font-size: 14px; }
  .dash-hero .btn-hero-light {
    background: #fff; color: #4f46e5; border: none; font-weight: 600;
  }
  .dash-hero .btn-hero-light:hover { background: #eef2ff; }
  .dash-hero .btn-hero-ghost {
    background: rgba(255,255,255,0.14); color: #fff;
    border: 1px solid rgba(255,255,255,0.25); font-weight: 600;
  }
  .dash-hero .btn-hero-ghost:hover { background: rgba(255,255,255,0.22); color:#fff; }

  /* Stat Cards */
  .stat-card {
    background: #fff;
    border: 1px solid var(--admin-border);
    border-radius: 16px;
    padding: 20px;
    height: 100%;
    transition: transform .2s ease, box-shadow .2s ease;
    box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 8px 24px rgba(15,23,42,.05);
  }
  .stat-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(15,23,42,.1); }
  .stat-chip {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .stat-label {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--admin-muted);
  }
  .stat-value { font-size: 26px; font-weight: 800; letter-spacing: -0.03em; color: var(--admin-text); }
  .stat-meta { font-size: 12px; color: var(--admin-muted); }

  /* Milestone Timeline */
  .ms-timeline { position: relative; padding-left: 8px; }
  .ms-item { position: relative; display: flex; gap: 16px; padding-bottom: 22px; }
  .ms-item:last-child { padding-bottom: 4px; }
  .ms-item::before {
    content: ''; position: absolute;
    left: 17px; top: 38px; bottom: -2px;
    width: 2px; background: #e5eaf2;
  }
  .ms-item:last-child::before { display: none; }
  .ms-dot {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; z-index: 1; font-size: 15px;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(15,23,42,.08);
  }
  .ms-body {
    flex: 1; padding: 12px 16px; border-radius: 12px;
    border: 1px solid var(--admin-border); background: #fff;
    transition: border-color .15s ease, background .15s ease;
  }
  .ms-item:hover .ms-body { border-color: #c7d2fe; }

  /* Side panels */
  .sup-card-top {
    background: linear-gradient(135deg, #eef2ff, #f5f3ff);
    border: 1px solid #e0e7ff;
    border-radius: 14px; padding: 16px;
  }
  .activity-row {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 10px 4px; border-bottom: 1px dashed #eef1f7;
  }
  .activity-row:last-child { border-bottom: none; }
  .activity-ic {
    width: 32px; height: 32px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
  }

  /* Team table */
  .team-avatar {
    width: 36px; height: 36px; border-radius: 12px;
    background: linear-gradient(135deg, #eef2ff, #ede9fe);
    color: #4f46e5; font-weight: 700; font-size: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  {{-- ================= WELCOME HERO ================= --}}
  <div class="dash-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3" style="position:relative;z-index:1;">
      <div>
        <div class="hero-eyebrow mb-1">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</div>
        <h1 class="h3 mb-1">Welcome back, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
        <p class="hero-sub mb-0">
          @if($proposal && $proposal->status === 'approved')
            Your proposal is approved — keep pushing towards your next milestone.
          @elseif($proposal)
            Your proposal is {{ str_replace('_',' ', $proposal->status) }} — track everything from here.
          @else
            Start by creating a team and submitting your project proposal.
          @endif
        </p>
      </div>
      <div class="d-flex gap-2">
        <a class="btn btn-hero-ghost btn-sm" href="{{ route('student.proposal') }}">
          <i class="bi bi-file-earmark-text me-1"></i>My Proposal
        </a>
        <a class="btn btn-hero-light btn-sm" href="{{ route('student.documents') }}">
          <i class="bi bi-upload me-1"></i>Upload Document
        </a>
      </div>
    </div>
  </div>

  {{-- ================= STAT CARDS ================= --}}
  <section class="row g-3">

    {{-- Proposal Status --}}
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="stat-label pt-1">Proposal Status</div>
          <div class="stat-chip" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-file-earmark-text"></i></div>
        </div>
        <div class="stat-value" style="font-size:20px;">{{ $proposal ? ucfirst($proposal->status) : 'Not Submitted' }}</div>
        <div class="stat-meta mt-1">
          @if(!$proposal)
            <i class="bi bi-exclamation-circle text-danger me-1"></i>No proposal yet
          @elseif($proposal->status === 'approved')
            <i class="bi bi-check-circle text-success me-1"></i>Approved by supervisor
          @elseif($proposal->status === 'rejected')
            <i class="bi bi-x-circle text-danger me-1"></i>Revise and resubmit
          @elseif($proposal->status === 'revision')
            <i class="bi bi-arrow-clockwise text-warning me-1"></i>Revision requested
          @else
            <i class="bi bi-clock text-warning me-1"></i>Awaiting review
          @endif
        </div>
      </div>
    </div>

    {{-- Milestones --}}
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="stat-label pt-1">Milestones</div>
          <div class="stat-chip" style="background:#f0fdf4;color:#059669;"><i class="bi bi-flag-fill"></i></div>
        </div>
        <div class="stat-value">{{ $doneMilestones }} <span style="font-size:15px;font-weight:500;color:var(--admin-muted);">/ {{ $totalMilestones }}</span></div>
        <div class="progress mt-2 mb-1" style="height:6px;border-radius:99px;background:#eef1f8;">
          <div class="progress-bar" style="width:{{ $milestonePercent }}%;background:linear-gradient(90deg,#10b981,#059669);border-radius:99px;"></div>
        </div>
        <div class="stat-meta">{{ $milestonePercent }}% complete</div>
      </div>
    </div>

    {{-- Team Members --}}
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="stat-label pt-1">Team Members</div>
          <div class="stat-chip" style="background:#fffbeb;color:#d97706;"><i class="bi bi-people"></i></div>
        </div>
        <div class="stat-value">{{ $teamMembers->count() }} <span style="font-size:15px;font-weight:500;color:var(--admin-muted);">/ 4</span></div>
        <div class="stat-meta mt-1">
          @if($team)
            <i class="bi bi-check-circle text-success me-1"></i>{{ $team->team_name }}
          @else
            <i class="bi bi-exclamation-circle text-danger me-1"></i>No team yet
          @endif
        </div>
      </div>
    </div>

    {{-- Documents --}}
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="stat-label pt-1">Documents</div>
          <div class="stat-chip" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-folder2-open"></i></div>
        </div>
        <div class="stat-value">{{ $uploadedDocs }} <span style="font-size:15px;font-weight:500;color:var(--admin-muted);">/ 5</span></div>
        <div class="stat-meta mt-1">
          @if($uploadedDocs < 5)
            <i class="bi bi-exclamation-circle text-danger me-1"></i>{{ 5 - $uploadedDocs }} pending
          @else
            <i class="bi bi-check-circle text-success me-1"></i>All uploaded
          @endif
        </div>
      </div>
    </div>

  </section>

  {{-- ================= MILESTONES + RIGHT COLUMN ================= --}}
  <section class="row g-3 mt-1">

    {{-- Milestone Timeline --}}
    <div class="col-12 col-xl-8">
      <div class="panel h-100">
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-flag"></i><span>Milestone Progress</span></h2>
            <p class="text-muted mb-0" style="font-size:13px;">Proposal → SRS → Design → Development → Final Defense</p>
          </div>
          <a class="btn btn-light btn-sm" href="{{ route('student.milestones') }}">View All</a>
        </div>

        <div class="d-flex justify-content-between mb-1">
          <small class="text-muted">Overall Progress</small>
          <small class="fw-semibold" style="color:#4f46e5;">{{ $milestonePercent }}%</small>
        </div>
        <div class="progress mb-4" style="height:8px;border-radius:99px;background:#eef1f8;">
          <div class="progress-bar" style="width:{{ $milestonePercent }}%;background:linear-gradient(90deg,#6366f1,#8b5cf6);border-radius:99px;"></div>
        </div>

        <div class="ms-timeline">
          @forelse($milestones as $ms)
            @php
              $dot = match($ms->status) {
                'done'        => 'background:#059669;color:#fff;',
                'in_progress' => 'background:#f59e0b;color:#fff;',
                default       => 'background:#f1f5f9;color:#94a3b8;',
              };
              $icon = match($ms->status) {
                'done'        => 'bi-check-lg',
                'in_progress' => 'bi-arrow-clockwise',
                default       => 'bi-circle',
              };
              $badge = match($ms->status) {
                'done'        => 'background:#dcfce7;color:#16a34a;',
                'in_progress' => 'background:#fef3c7;color:#d97706;',
                default       => 'background:#f1f5f9;color:#94a3b8;',
              };
              $label = match($ms->status) {
                'done'        => 'Done',
                'in_progress' => 'In Progress',
                default       => 'Pending',
              };
            @endphp
            <div class="ms-item">
              <div class="ms-dot" style="{{ $dot }}"><i class="bi {{ $icon }}"></i></div>
              <div class="ms-body {{ $ms->status === 'in_progress' ? 'border-warning-subtle' : '' }}"
                   @if($ms->status === 'in_progress') style="background:#fffdf5;border-color:#fde68a;" @endif>
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                  <div>
                    <div class="fw-semibold" style="font-size:14px;">{{ $ms->title }}</div>
                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>Due {{ \Carbon\Carbon::parse($ms->due_date)->format('d M Y') }}</small>
                  </div>
                  <span class="badge" style="border-radius:99px;padding:5px 14px;font-weight:600;{{ $badge }}">{{ $label }}</span>
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bi bi-flag fs-2 d-block mb-2"></i>
              <div>No milestones yet — submit a proposal first</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Right Column --}}
    <div class="col-12 col-xl-4 d-flex flex-column gap-3">

      {{-- Supervisor --}}
      <div class="panel">
        <div class="panel-header">
          <h2 class="h5 mb-0 section-title"><i class="bi bi-person-badge"></i><span>My Supervisor</span></h2>
        </div>
        @if($team && $team->supervisor)
          <div class="sup-card-top d-flex align-items-center gap-3 mb-3">
            <div style="width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;box-shadow:0 6px 14px rgba(79,70,229,.3);">
              {{ strtoupper(substr($team->supervisor->user->name, 0, 2)) }}
            </div>
            <div>
              <div class="fw-semibold" style="font-size:14px;">{{ $team->supervisor->user->name }}</div>
              <small class="text-muted">{{ $team->supervisor->department }}</small>
            </div>
          </div>
          <div class="d-flex flex-column gap-2 mb-3" style="font-size:13px;">
            <div class="d-flex align-items-center gap-2 text-muted">
              <i class="bi bi-envelope" style="color:#4f46e5;"></i>{{ $team->supervisor->user->email }}
            </div>
            <div class="d-flex align-items-center gap-2 text-muted">
              <i class="bi bi-people" style="color:#4f46e5;"></i>Max {{ $team->supervisor->max_teams }} Teams
            </div>
          </div>
          <a href="{{ route('student.messages') }}" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-chat-dots me-1"></i>Send Message
          </a>
        @else
          <div class="text-center py-3 text-muted">
            <i class="bi bi-person-badge fs-2 d-block mb-2"></i>
            <div style="font-size:13px;">No supervisor assigned yet</div>
          </div>
        @endif
      </div>

      {{-- Recent Activity --}}
      <div class="panel">
        <div class="panel-header">
          <h2 class="h5 mb-0 section-title"><i class="bi bi-activity"></i><span>Recent Activity</span></h2>
        </div>
        @if($unreadMessages > 0)
          <div class="activity-row">
            <div class="activity-ic" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-chat-dots"></i></div>
            <div>
              <p class="mb-0 fw-semibold" style="font-size:13px;">{{ $unreadMessages }} unread message(s)</p>
              <small class="text-muted">From your supervisor</small>
            </div>
          </div>
        @endif
        @foreach($milestones->where('status','in_progress') as $active)
          <div class="activity-row">
            <div class="activity-ic" style="background:#fffbeb;color:#d97706;"><i class="bi bi-flag"></i></div>
            <div>
              <p class="mb-0 fw-semibold" style="font-size:13px;">{{ $active->title }} in progress</p>
              <small class="text-muted">Due {{ \Carbon\Carbon::parse($active->due_date)->format('d M Y') }}</small>
            </div>
          </div>
        @endforeach
        @if($announcements->count() > 0)
          <div class="activity-row">
            <div class="activity-ic" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-megaphone"></i></div>
            <div>
              <p class="mb-0 fw-semibold" style="font-size:13px;">{{ $announcements->first()->title }}</p>
              <small class="text-muted">{{ \Carbon\Carbon::parse($announcements->first()->published_at)->diffForHumans() }}</small>
            </div>
          </div>
        @endif
        @if($unreadMessages == 0 && $milestones->where('status','in_progress')->count() == 0 && $announcements->count() == 0)
          <div class="text-center py-3 text-muted" style="font-size:13px;">No recent activity</div>
        @endif
      </div>

      {{-- Latest Announcement --}}
      @if($announcements->count() > 0)
        <div class="panel">
          <div class="panel-header">
            <h2 class="h5 mb-0 section-title"><i class="bi bi-megaphone"></i><span>Announcement</span></h2>
            <a href="{{ route('student.announcements') }}" class="btn btn-light btn-sm">All</a>
          </div>
          <div class="p-3 rounded-3" style="background:linear-gradient(135deg,#fffbeb,#fef9ec);border:1px solid #fde68a;">
            <div class="fw-semibold mb-1" style="font-size:13px;color:#92400e;">
              <i class="bi bi-megaphone me-1"></i>{{ $announcements->first()->title }}
            </div>
            <div style="font-size:12px;color:#78350f;line-height:1.6;">
              {{ Str::limit($announcements->first()->body, 100) }}
            </div>
            <div style="font-size:11px;color:#a16207;margin-top:8px;">
              <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($announcements->first()->published_at)->format('d M Y') }}
            </div>
          </div>
        </div>
      @endif

    </div>
  </section>

  {{-- ================= TEAM TABLE ================= --}}
  <section class="panel mt-3">
    <div class="panel-header">
      <div>
        <h2 class="h5 mb-1 section-title"><i class="bi bi-people"></i><span>My Team</span></h2>
        <p class="text-muted mb-0" style="font-size:13px;">
          @if($team)
            {{ $team->team_name }} · <span style="font-family:monospace;">{{ $team->group_code }}</span>
          @else
            You are not part of any team yet
          @endif
        </p>
      </div>
      <a class="btn btn-outline-secondary btn-sm" href="{{ route('student.team') }}">Manage Team</a>
    </div>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Member Name</th>
            <th>Student ID</th>
            <th>Role</th>
            <th>Status</th>
            <th>Joined</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($teamMembers as $member)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="team-avatar">{{ strtoupper(substr($member->student->user->name, 0, 2)) }}</div>
                  <div>
                    <p class="fw-semibold mb-0" style="font-size:14px;">{{ $member->student->user->name }}</p>
                    <small class="text-muted">{{ $member->student->user->email }}</small>
                  </div>
                </div>
              </td>
              <td style="font-family:monospace;font-size:13px;">{{ $member->student->vuid }}</td>
              <td>
                @if($member->role === 'leader')
                  <span class="badge" style="background:#eef2ff;color:#4f46e5;border-radius:99px;padding:5px 12px;"><i class="bi bi-star-fill me-1" style="font-size:9px;"></i>Leader</span>
                @else
                  <span class="badge" style="background:#f1f5f9;color:#64748b;border-radius:99px;padding:5px 12px;">Member</span>
                @endif
              </td>
              <td><span class="badge" style="background:#dcfce7;color:#16a34a;border-radius:99px;padding:5px 12px;">Active</span></td>
              <td><small>{{ \Carbon\Carbon::parse($member->joined_at)->format('d M Y') }}</small></td>
              <td class="text-end">
                <a class="btn btn-light btn-sm" href="{{ route('student.profile') }}">View</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">
                <i class="bi bi-people fs-2 d-block mb-2"></i>
                No team members yet
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

</div>
@endsection