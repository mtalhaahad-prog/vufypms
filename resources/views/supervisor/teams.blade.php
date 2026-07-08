@extends('supervisor.layouts.app')

@section('title', 'My Teams')

@section('content')
<div class="container-fluid px-4">

  <h1 class="mt-4">My Teams</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('supervisor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Teams under your supervision ({{ count($teamData) }} / {{ $supervisor->max_teams ?? '—' }})</li>
  </ol>

  {{-- FLASH MESSAGES --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(count($teamData) === 0)
    <div class="card shadow-sm">
      <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-people fs-1 d-block mb-3"></i>
        <div class="fw-semibold">No teams assigned yet</div>
        <small>The admin will assign teams to you</small>
      </div>
    </div>
  @else
    <div class="row g-3">
      @foreach($teamData as $td)
        <div class="col-12 col-md-6 col-xl-4">
          <div class="card shadow-sm h-100">
            <div class="card-body p-4">

              {{-- Team header --}}
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:46px;height:46px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;font-weight:700;font-size:15px;flex-shrink:0;">
                  {{ strtoupper(substr($td['team']->team_name, 0, 2)) }}
                </div>
                <div>
                  <div class="fw-bold">{{ $td['team']->team_name }}</div>
                  <small class="text-muted" style="font-family:monospace;font-size:11px;">{{ $td['team']->group_code }}</small>
                </div>
              </div>

              {{-- Quick info --}}
              <div class="d-flex justify-content-between mb-3" style="font-size:13px;">
                <span class="text-muted"><i class="bi bi-people me-1"></i>{{ $td['membersCount'] }} member(s)</span>
                @if($td['proposal'])
                  @if($td['proposal']->status === 'approved')
                    <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                  @elseif($td['proposal']->status === 'pending')
                    <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                  @elseif($td['proposal']->status === 'revision')
                    <span class="badge bg-info bg-opacity-10 text-info">Revision</span>
                  @else
                    <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
                  @endif
                @else
                  <span class="badge bg-secondary bg-opacity-10 text-secondary">No Proposal</span>
                @endif
              </div>

              {{-- Progress --}}
              <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">Progress</small>
                <small class="fw-bold {{ $td['percent'] >= 60 ? 'text-success' : ($td['percent'] >= 30 ? 'text-warning' : 'text-danger') }}">{{ $td['percent'] }}%</small>
              </div>
              <div class="progress mb-3" style="height:8px;">
                <div class="progress-bar {{ $td['percent'] >= 60 ? 'bg-success' : ($td['percent'] >= 30 ? 'bg-warning' : 'bg-danger') }}"
                     style="width:{{ $td['percent'] }}%"></div>
              </div>

              <a href="{{ route('supervisor.teams.show', $td['team']->team_id) }}" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-eye me-1"></i>View Team Details
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>
@endsection