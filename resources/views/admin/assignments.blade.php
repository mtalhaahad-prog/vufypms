@extends('admin.layouts.app')

@section('title', 'Supervisor Assignment')

@section('content')

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Supervisor Assignment</h1>
      <small class="text-muted">Assign supervisors to teams — unassigned teams appear first</small>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  @endif

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">All Teams ({{ $teams->count() }})</h6>
    </div>
    <div class="card-body">
      @if($teams->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="fas fa-users fa-2x mb-3 d-block"></i>No teams created yet
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="thead-light">
              <tr><th>Team</th><th>Members</th><th>Current Supervisor</th><th style="width:320px;">Assign / Change</th></tr>
            </thead>
            <tbody>
              @foreach($teams as $team)
              <tr class="{{ !$team->supervisor_id ? 'table-warning' : '' }}">
                <td>
                  <div class="font-weight-bold">{{ $team->team_name }}</div>
                  <small class="text-muted" style="font-family:monospace;">{{ $team->group_code }}</small>
                </td>
                <td>{{ $memberCounts[$team->team_id] ?? 0 }} member(s)</td>
                <td>
                  @if($team->supervisor_id)
                    @php $currentSup = $supervisors->firstWhere('supervisor_id', $team->supervisor_id); @endphp
                    <span class="badge badge-success">{{ $currentSup->user->name ?? '—' }}</span>
                  @else
                    <span class="badge badge-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Not Assigned</span>
                  @endif
                </td>
                <td>
                  <form method="POST" action="{{ route('admin.assignments.assign', $team->team_id) }}" class="form-inline">
                    @csrf
                    @method('PUT')
                    <select name="supervisor_id" class="form-control form-control-sm mr-2" style="max-width:200px;" required>
                      <option value="">Select supervisor...</option>
                      @foreach($supervisors as $sup)
                        @php
                          $load = $loadMap[$sup->supervisor_id] ?? 0;
                          $full = $load >= $sup->max_teams && $team->supervisor_id != $sup->supervisor_id;
                        @endphp
                        <option value="{{ $sup->supervisor_id }}"
                                {{ $team->supervisor_id == $sup->supervisor_id ? 'selected' : '' }}
                                {{ $full ? 'disabled' : '' }}>
                          {{ $sup->user->name ?? '—' }} ({{ $load }}/{{ $sup->max_teams }}){{ $full ? ' - FULL' : '' }}
                        </option>
                      @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-check"></i></button>
                  </form>
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