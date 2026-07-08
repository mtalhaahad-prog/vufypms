@extends('student.layouts.app')

@section('title', 'Presentations')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-camera-video"></i></span>
      <div>
        <p class="eyebrow mb-1">Project</p>
        <h1 class="h3 mb-1">Presentations</h1>
        <p class="text-muted mb-0">View your scheduled presentation and defense dates.</p>
      </div>
    </div>
  </div>

  @if(!$team)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Team Found</h2>
      <p class="text-muted mb-3">Join or create a team first to see presentation schedules.</p>
      <a href="{{ route('student.team') }}" class="btn btn-primary btn-sm"><i class="bi bi-people me-1"></i>Go to My Team</a>
    </div>
  @else

    {{-- SCHEDULED PRESENTATIONS --}}
    <div class="panel mt-2">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-calendar-event"></i><span>Scheduled Presentations</span></h2>
          <p class="text-muted mb-0">Your supervisor will schedule defense dates here.</p>
        </div>
      </div>

      @if($presentations->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
          <div class="fw-semibold">No presentations scheduled yet</div>
          <small>Your supervisor will assign presentation slots</small>
        </div>
      @else
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr><th>Phase</th><th>Date &amp; Time</th><th>Venue / Link</th><th>Panel</th><th>Status</th></tr>
            </thead>
            <tbody>
              @foreach($presentations as $p)
              <tr>
                <td class="fw-semibold">{{ $phases[$p->phase]['label'] ?? $p->phase }}</td>
                <td>{{ \Carbon\Carbon::parse($p->scheduled_at)->format('d M Y, h:i A') }}</td>
                <td>
                  @if($p->venue_or_link && Str::startsWith($p->venue_or_link, 'http'))
                    <a href="{{ $p->venue_or_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-camera-video me-1"></i>Join Online
                    </a>
                  @else
                    <small>{{ $p->venue_or_link ?? 'TBA' }}</small>
                  @endif
                </td>
                <td><small>{{ $p->panel_info ?? 'TBA' }}</small></td>
                <td>
                  @if($p->status === 'scheduled')
                    <span class="badge text-bg-primary">Scheduled</span>
                  @elseif($p->status === 'completed')
                    <span class="badge text-bg-success">Completed</span>
                  @else
                    <span class="badge text-bg-secondary">Cancelled</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- DEFENSE PHASES CARDS --}}
    @php
      $phaseColors = [
        'proposal_defense' => ['bg' => '#eef2ff', 'color' => '#6366f1'],
        'progress_review'  => ['bg' => '#fef3c7', 'color' => '#d97706'],
        'final_defense'    => ['bg' => '#dcfce7', 'color' => '#16a34a'],
      ];
    @endphp
    <div class="panel mt-3">
      <div class="panel-header"><h2 class="h5 mb-1 section-title"><i class="bi bi-layers"></i><span>Defense Phases</span></h2></div>
      <div class="row g-3">
        @foreach($phases as $key => $info)
          @php
            $p = $byPhase->get($key);
            $c = $phaseColors[$key];
          @endphp
          <div class="col-md-4">
            <div class="p-4 text-center rounded-3" style="border:1px solid var(--admin-border);">
              <div style="width:48px;height:48px;border-radius:50%;background:{{ $c['bg'] }};color:{{ $c['color'] }};display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px;">
                <i class="bi bi-{{ $loop->iteration }}-circle"></i>
              </div>
              <div class="fw-semibold mb-1">{{ $info['label'] }}</div>
              <small class="text-muted d-block mb-2">{{ $info['desc'] }}</small>
              @if($p && $p->status === 'completed')
                <span class="badge text-bg-success">Completed</span>
              @elseif($p)
                <span class="badge text-bg-primary">{{ \Carbon\Carbon::parse($p->scheduled_at)->format('d M Y') }}</span>
              @else
                <span class="badge text-bg-secondary">Not Scheduled</span>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

</div>
@endsection