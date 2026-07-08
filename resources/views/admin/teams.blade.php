@extends('admin.layouts.app')

@section('title', 'Teams Overview')

@section('content')

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Teams Overview</h1>
      <small class="text-muted">All FYP teams across the system</small>
    </div>
    <a href="{{ route('admin.assignments') }}" class="btn btn-primary btn-sm">
      <i class="fas fa-user-tie mr-1"></i>Manage Assignments
    </a>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">All Teams ({{ $teams->count() }})</h6>
    </div>
    <div class="card-body">
      @if($teams->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="fas fa-users fa-2x mb-3 d-block"></i>No teams yet
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="thead-light">
              <tr><th>Team</th><th>Members</th><th>Supervisor</th><th>Proposal</th><th style="width:180px;">Progress</th><th>Status</th></tr>
            </thead>
            <tbody>
              @foreach($teams as $team)
                @php
                  $p   = $proposals[$team->team_id] ?? null;
                  $pct = $progress[$team->team_id] ?? 0;
                @endphp
                <tr>
                  <td>
                    <div class="font-weight-bold">{{ $team->team_name }}</div>
                    <small class="text-muted" style="font-family:monospace;">{{ $team->group_code }}</small>
                  </td>
                  <td>{{ $memberCounts[$team->team_id] ?? 0 }}</td>
                  <td>
                    @if($team->supervisor && $team->supervisor->user)
                      <small>{{ $team->supervisor->user->name }}</small>
                    @else
                      <span class="badge badge-warning">Unassigned</span>
                    @endif
                  </td>
                  <td>
                    @if(!$p)<span class="badge badge-secondary">None</span>
                    @elseif($p->status === 'approved')<span class="badge badge-success">Approved</span>
                    @elseif($p->status === 'pending')<span class="badge badge-warning">Pending</span>
                    @elseif($p->status === 'revision')<span class="badge badge-info">Revision</span>
                    @else<span class="badge badge-danger">Rejected</span>@endif
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="progress flex-grow-1 mr-2" style="height:8px;">
                        <div class="progress-bar {{ $pct >= 60 ? 'bg-success' : ($pct >= 30 ? 'bg-warning' : 'bg-danger') }}" style="width:{{ $pct }}%"></div>
                      </div>
                      <small class="font-weight-bold">{{ $pct }}%</small>
                    </div>
                  </td>
                  <td>
                    @if($team->status === 'active')
                      <span class="badge badge-success">Active</span>
                    @else
                      <span class="badge badge-secondary">Inactive</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

@endsection