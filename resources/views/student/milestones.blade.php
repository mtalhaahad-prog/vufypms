@extends('student.layouts.app')

@section('title', 'Milestones')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  {{-- PAGE HEADING --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-flag"></i></span>
      <div>
        <p class="eyebrow mb-1">Project</p>
        <h1 class="h3 mb-1">Milestones</h1>
        <p class="text-muted mb-0">Track each phase of your Final Year Project.</p>
      </div>
    </div>
  </div>

  {{-- ============================================ --}}
  {{-- CASE 1: TEAM YA PROPOSAL NAHI HAI --}}
  {{-- ============================================ --}}
  @if(!$team)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Team Found</h2>
      <p class="text-muted mb-3">Join or create a team first to see project milestones.</p>
      <a href="{{ route('student.team') }}" class="btn btn-primary btn-sm"><i class="bi bi-people me-1"></i>Go to My Team</a>
    </div>

  @elseif(!$proposal)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-file-earmark-text fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Proposal Submitted</h2>
      <p class="text-muted mb-3">Milestones are created after your proposal is submitted and approved.</p>
      <a href="{{ route('student.proposal') }}" class="btn btn-primary btn-sm"><i class="bi bi-send me-1"></i>Go to My Proposal</a>
    </div>

  @elseif($milestones->isEmpty())
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-flag fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Milestones Yet</h2>
      <p class="text-muted mb-0">Your supervisor or admin has not defined any milestones for your project yet.</p>
    </div>

  {{-- ============================================ --}}
  {{-- CASE 2: MILESTONES MAUJOOD HAIN --}}
  {{-- ============================================ --}}
  @else

    {{-- STATS CARDS --}}
    <section class="row g-3 mt-1">
      <div class="col-12 col-sm-4">
        <article class="metric-card metric-success">
          <div class="metric-top"><span class="metric-label">Completed</span><span class="metric-icon"><i class="bi bi-check-circle"></i></span></div>
          <div class="metric-value">{{ $done }}</div>
          <div class="metric-meta"><span class="text-success">On track</span></div>
        </article>
      </div>
      <div class="col-12 col-sm-4">
        <article class="metric-card metric-warning">
          <div class="metric-top"><span class="metric-label">In Progress</span><span class="metric-icon"><i class="bi bi-arrow-clockwise"></i></span></div>
          <div class="metric-value">{{ $inProgress }}</div>
          <div class="metric-meta"><span class="text-warning">Active now</span></div>
        </article>
      </div>
      <div class="col-12 col-sm-4">
        <article class="metric-card metric-primary">
          <div class="metric-top"><span class="metric-label">Pending</span><span class="metric-icon"><i class="bi bi-hourglass"></i></span></div>
          <div class="metric-value">{{ $pending }}</div>
          <div class="metric-meta"><span>Upcoming</span></div>
        </article>
      </div>
    </section>

    {{-- PROGRESS + TABLE --}}
    <div class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-graph-up"></i><span>Overall Progress</span></h2>
          <p class="text-muted mb-0">{{ $total }} milestones total · {{ $percent }}% complete</p>
        </div>
      </div>

      <div class="d-flex justify-content-between mb-1">
        <small class="text-muted">Progress</small>
        <small class="fw-semibold text-primary">{{ $percent }}%</small>
      </div>
      <div class="progress mb-4" style="height:10px;border-radius:99px;">
        <div class="progress-bar bg-primary" style="width:{{ $percent }}%"></div>
      </div>

      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr><th>#</th><th>Milestone</th><th>Due Date</th><th>Status</th><th>Documents</th></tr>
          </thead>
          <tbody>
            @foreach($milestones as $i => $ms)
              {{-- In-progress row ko halka yellow highlight do --}}
              <tr @if($ms->status === 'in_progress') style="background:#fffbeb;" @endif>
                <td class="fw-semibold">{{ $i + 1 }}</td>
                <td><div class="fw-semibold">{{ $ms->title }}</div></td>
                <td>{{ \Carbon\Carbon::parse($ms->due_date)->format('d M Y') }}</td>
                <td>
                  @if($ms->status === 'done')
                    <span class="badge" style="background:#dcfce7;color:#16a34a;border-radius:99px;padding:4px 12px;">Done</span>
                  @elseif($ms->status === 'in_progress')
                    <span class="badge" style="background:#fef3c7;color:#d97706;border-radius:99px;padding:4px 12px;">In Progress</span>
                  @else
                    <span class="badge" style="background:#f1f5f9;color:#94a3b8;border-radius:99px;padding:4px 12px;">Pending</span>
                  @endif
                </td>
                <td>
                  @if($docMilestoneIds->contains($ms->milestone_id))
                    <span class="badge text-bg-success"><i class="bi bi-paperclip me-1"></i>Submitted</span>
                  @elseif($ms->status === 'in_progress')
                    <span class="badge text-bg-warning">Pending</span>
                  @else
                    <span class="badge text-bg-secondary">Not Started</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

</div>
@endsection