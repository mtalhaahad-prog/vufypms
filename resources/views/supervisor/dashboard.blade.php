@extends('supervisor.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">

  <h1 class="mt-4">Supervisor Dashboard</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Welcome back, {{ Auth::user()->name }} — here's an overview of your teams.</li>
  </ol>

  {{-- ================= STAT CARDS ================= --}}
  <div class="row g-3 mb-4">

    {{-- Assigned Teams --}}
    <div class="col-xl-3 col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column gap-2 p-3">
          <div class="bg-primary bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-people-fill text-primary fs-5"></i>
          </div>
          <div class="fw-bold fs-4 text-dark">{{ $teams->count() }} <span class="fs-6 fw-normal text-muted">/ {{ $supervisor->max_teams ?? '—' }}</span></div>
          <div class="text-muted small">Assigned Teams</div>
        </div>
      </div>
    </div>

    {{-- Pending Proposals --}}
    <div class="col-xl-3 col-md-6">
      <div class="card shadow-sm h-100 {{ $pendingProposals->count() > 0 ? 'border-start border-warning border-4' : '' }}">
        <div class="card-body d-flex flex-column gap-2 p-3">
          <div class="bg-warning bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-file-earmark-text text-warning fs-5"></i>
          </div>
          <div class="fw-bold fs-4 text-dark">{{ $pendingProposals->count() }}</div>
          <div class="text-muted small">Pending Proposal Reviews</div>
        </div>
      </div>
    </div>

    {{-- Total Students --}}
    <div class="col-xl-3 col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column gap-2 p-3">
          <div class="bg-success bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-mortarboard-fill text-success fs-5"></i>
          </div>
          <div class="fw-bold fs-4 text-dark">{{ $totalStudents }}</div>
          <div class="text-muted small">Students Under Supervision</div>
        </div>
      </div>
    </div>

    {{-- Upcoming Defenses --}}
    <div class="col-xl-3 col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column gap-2 p-3">
          <div class="bg-danger bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-camera-video-fill text-danger fs-5"></i>
          </div>
          <div class="fw-bold fs-4 text-dark">{{ $upcomingDefenses->count() }}</div>
          <div class="text-muted small">Upcoming Defenses</div>
        </div>
      </div>
    </div>

  </div>

  <div class="row g-3">

    {{-- ================= PENDING PROPOSALS TABLE ================= --}}
    <div class="col-xl-7">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <span><i class="bi bi-hourglass-split me-2 text-warning"></i><strong>Proposals Awaiting Review</strong></span>
          <a href="{{ route('supervisor.proposals') }}" class="btn btn-sm btn-outline-primary">Review All</a>
        </div>
        <div class="card-body">
          @if($pendingProposals->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="bi bi-check2-circle fs-2 d-block mb-2 text-success"></i>
              All caught up! No proposals pending review.
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Project Title</th><th>Team</th><th>Submitted</th><th class="text-end">Action</th></tr>
                </thead>
                <tbody>
                  @foreach($pendingProposals as $p)
                  <tr>
                    <td class="fw-semibold" style="max-width:220px;">{{ Str::limit($p->title, 50) }}</td>
                    <td><small>{{ $teamNames[$p->team_id] ?? '—' }}</small></td>
                    <td><small>{{ \Carbon\Carbon::parse($p->submitted_at)->diffForHumans() }}</small></td>
                    <td class="text-end">
                      <a href="{{ route('supervisor.proposals') }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-eye me-1"></i>Review
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- ================= UPCOMING DEFENSES ================= --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
          <i class="bi bi-calendar-event me-2 text-danger"></i><strong>Upcoming Defense Schedule</strong>
        </div>
        <div class="card-body">
          @if($upcomingDefenses->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
              No upcoming presentations scheduled.
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Team</th><th>Phase</th><th>Date &amp; Time</th></tr>
                </thead>
                <tbody>
                  @foreach($upcomingDefenses as $d)
                  <tr>
                    <td class="fw-semibold">{{ $teamNames[$d->team_id] ?? '—' }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucwords(str_replace('_',' ', $d->phase)) }}</span></td>
                    <td><small>{{ \Carbon\Carbon::parse($d->scheduled_at)->format('d M Y, h:i A') }}</small></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ================= TEAMS PROGRESS ================= --}}
    <div class="col-xl-5">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <span><i class="bi bi-graph-up me-2 text-primary"></i><strong>Teams Progress</strong></span>
          <a href="{{ route('supervisor.teams') }}" class="btn btn-sm btn-outline-primary">View Teams</a>
        </div>
        <div class="card-body">
          @if($teams->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="bi bi-people fs-2 d-block mb-2"></i>
              No teams assigned to you yet.
            </div>
          @else
            @foreach($teamProgress as $tp)
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <div>
                    <span class="fw-semibold">{{ $tp['team']->team_name }}</span>
                    <small class="text-muted d-block" style="font-family:monospace;font-size:11px;">{{ $tp['team']->group_code }}</small>
                  </div>
                  <div class="text-end">
                    <span class="fw-bold {{ $tp['percent'] >= 60 ? 'text-success' : ($tp['percent'] >= 30 ? 'text-warning' : 'text-danger') }}">{{ $tp['percent'] }}%</span>
                    @if($tp['proposal'])
                      <small class="text-muted d-block" style="font-size:11px;">Proposal: {{ ucfirst($tp['proposal']->status) }}</small>
                    @else
                      <small class="text-danger d-block" style="font-size:11px;">No proposal yet</small>
                    @endif
                  </div>
                </div>
                <div class="progress" style="height:8px;border-radius:99px;">
                  <div class="progress-bar {{ $tp['percent'] >= 60 ? 'bg-success' : ($tp['percent'] >= 30 ? 'bg-warning' : 'bg-danger') }}"
                       style="width:{{ $tp['percent'] }}%"></div>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>

  </div>
</div>
@endsection