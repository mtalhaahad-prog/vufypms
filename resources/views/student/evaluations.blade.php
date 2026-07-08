@extends('student.layouts.app')

@section('title', 'Evaluations')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-bar-chart-line"></i></span>
      <div>
        <p class="eyebrow mb-1">Project</p>
        <h1 class="h3 mb-1">Evaluations</h1>
        <p class="text-muted mb-0">View your marks and supervisor feedback per defense phase.</p>
      </div>
    </div>
  </div>

  @if(!$team)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Team Found</h2>
      <p class="text-muted mb-3">Join or create a team first to see evaluations.</p>
      <a href="{{ route('student.team') }}" class="btn btn-primary btn-sm"><i class="bi bi-people me-1"></i>Go to My Team</a>
    </div>
  @else

    {{-- PHASE-WISE MARKS CARDS --}}
    @php $cardStyles = ['metric-primary', 'metric-warning', 'metric-success']; @endphp
    <div class="row g-3 mt-1">
      @foreach($phases as $key => $label)
        @php $eval = $byPhase->get($key); @endphp
        <div class="col-md-4">
          <article class="metric-card {{ $cardStyles[$loop->index] }}">
            <div class="metric-top">
              <span class="metric-label">{{ $label }}</span>
              <span class="metric-icon"><i class="bi bi-{{ $loop->iteration }}-circle"></i></span>
            </div>
            <div class="metric-value">{{ $eval ? $eval->marks : '—' }}</div>
            <div class="metric-meta">
              @if($eval)
                <span class="text-success">Evaluated · {{ $eval->marks }}/100</span>
              @else
                <span class="text-muted">Not evaluated</span>
              @endif
            </div>
          </article>
        </div>
      @endforeach
    </div>

    {{-- EVALUATION RECORDS TABLE --}}
    <div class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-bar-chart-line"></i><span>Evaluation Records</span></h2>
          <p class="text-muted mb-0">Marks and remarks will appear after each defense phase.</p>
        </div>
      </div>

      @if($evaluations->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="bi bi-bar-chart-line fs-1 d-block mb-3"></i>
          <div class="fw-semibold">No evaluations recorded yet</div>
          <small>Your supervisor will enter marks after each defense</small>
        </div>
      @else
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr><th>Phase</th><th>Marks</th><th>Remarks</th><th>Date</th></tr>
            </thead>
            <tbody>
              @foreach($evaluations as $eval)
              <tr>
                <td class="fw-semibold">{{ $phases[$eval->phase] ?? $eval->phase }}</td>
                <td>
                  <span class="badge {{ $eval->marks >= 50 ? 'text-bg-success' : 'text-bg-danger' }}" style="font-size:13px;padding:6px 12px;">
                    {{ $eval->marks }} / 100
                  </span>
                </td>
                <td style="max-width:300px;"><small>{{ $eval->remarks ?? '—' }}</small></td>
                <td><small>{{ \Carbon\Carbon::parse($eval->evaluated_at)->format('d M Y') }}</small></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  @endif

</div>
@endsection