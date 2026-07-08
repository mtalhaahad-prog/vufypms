@extends('admin.layouts.app')

@section('title', 'Presentations')

@section('content')

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Presentations Scheduling</h1>
      <small class="text-muted">Schedule defense dates — teams see them on their portals</small>
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
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="row">

    {{-- LEFT: SCHEDULE FORM --}}
    <div class="col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-plus mr-2"></i>Schedule Presentation</h6>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.presentations.store') }}">
            @csrf
            <div class="form-group">
              <label>Team <span class="text-danger">*</span></label>
              <select class="form-control" name="team_id" required>
                <option value="">Select team...</option>
                @foreach($teams as $t)
                  <option value="{{ $t->team_id }}">{{ $t->team_name }} ({{ $t->group_code }})</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Phase <span class="text-danger">*</span></label>
              <select class="form-control" name="phase" required>
                @foreach($phases as $key => $label)
                  <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Date &amp; Time <span class="text-danger">*</span></label>
              <input class="form-control" type="datetime-local" name="scheduled_at" required>
            </div>
            <div class="form-group">
              <label>Venue / Meeting Link</label>
              <input class="form-control" type="text" name="venue_or_link" maxlength="255"
                     placeholder="Room 101 ya https://teams.microsoft.com/...">
            </div>
            <div class="form-group">
              <label>Evaluation Panel</label>
              <input class="form-control" type="text" name="panel_info" maxlength="255"
                     placeholder="e.g. Dr. Azeem Sarwar, Dr. Ali Khan">
            </div>
            <button class="btn btn-primary btn-block" type="submit">
              <i class="fas fa-calendar-check mr-1"></i>Schedule
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- RIGHT: SCHEDULED LIST --}}
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">All Presentations ({{ $presentations->count() }})</h6>
        </div>
        <div class="card-body">
          @if($presentations->isEmpty())
            <div class="text-center py-5 text-muted">
              <i class="fas fa-calendar-alt fa-2x mb-3 d-block"></i>No presentations scheduled yet
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="thead-light">
                  <tr><th>Team</th><th>Phase</th><th>Date &amp; Time</th><th>Venue / Panel</th><th style="width:150px;">Status</th></tr>
                </thead>
                <tbody>
                  @foreach($presentations as $p)
                  <tr>
                    <td class="font-weight-bold">{{ $teamNames[$p->team_id] ?? '—' }}</td>
                    <td><span class="badge badge-primary">{{ $phases[$p->phase] ?? $p->phase }}</span></td>
                    <td><small>{{ \Carbon\Carbon::parse($p->scheduled_at)->format('d M Y, h:i A') }}</small></td>
                    <td>
                      <small>{{ $p->venue_or_link ?? 'TBA' }}</small><br>
                      <small class="text-muted">{{ $p->panel_info ?? '' }}</small>
                    </td>
                    <td>
                      {{-- Status dropdown - change hote hi submit --}}
                      <form method="POST" action="{{ route('admin.presentations.updateStatus', $p->presentation_id) }}">
                        @csrf
                        @method('PUT')
                        <select class="form-control form-control-sm
                                {{ $p->status === 'completed' ? 'border-success text-success' : ($p->status === 'cancelled' ? 'border-danger text-danger' : '') }}"
                                name="status" onchange="this.form.submit()">
                          <option value="scheduled" {{ $p->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                          <option value="completed" {{ $p->status === 'completed' ? 'selected' : '' }}>Completed</option>
                          <option value="cancelled" {{ $p->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
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
    </div>

  </div>

@endsection