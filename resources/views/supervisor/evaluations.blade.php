@extends('supervisor.layouts.app')

@section('title', 'Evaluations')

@section('content')
<div class="container-fluid px-4">

  <h1 class="mt-4">Evaluations</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('supervisor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Enter marks and feedback for each defense phase</li>
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
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  @if($teams->isEmpty())
    <div class="card shadow-sm">
      <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-people fs-1 d-block mb-3"></i>
        No teams assigned yet.
      </div>
    </div>
  @else

    {{-- HAR TEAM KA EVALUATION CARD --}}
    @foreach($teams as $team)
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <span>
            <i class="bi bi-people-fill me-2 text-primary"></i>
            <strong>{{ $team->team_name }}</strong>
            <small class="text-muted ms-2" style="font-family:monospace;">{{ $team->group_code }}</small>
          </span>
          @php
            $teamEvals = $grid[$team->team_id] ?? [];
            $totalMarks = collect($teamEvals)->sum('marks');
          @endphp
          @if(count($teamEvals) > 0)
            <span class="badge bg-primary bg-opacity-10 text-primary">Total: {{ $totalMarks }} / {{ count($phases) * 100 }}</span>
          @endif
        </div>
        <div class="card-body">
          <div class="row g-3">
            @foreach($phases as $phaseKey => $phaseLabel)
              @php $eval = $grid[$team->team_id][$phaseKey] ?? null; @endphp
              <div class="col-md-4">
                <div class="p-3 rounded-3 h-100" style="border:1px solid #e5eaf2;background:{{ $eval ? '#f8fafd' : '#fff' }};">

                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold" style="font-size:13px;">{{ $phaseLabel }}</span>
                    @if($eval)
                      <span class="badge {{ $eval->marks >= 50 ? 'bg-success' : 'bg-danger' }}">{{ $eval->marks }}/100</span>
                    @else
                      <span class="badge bg-secondary bg-opacity-10 text-secondary">Not evaluated</span>
                    @endif
                  </div>

                  @if($eval && $eval->remarks)
                    <p class="text-muted mb-2" style="font-size:12px;line-height:1.5;"><i class="bi bi-chat-left-quote me-1"></i>{{ Str::limit($eval->remarks, 80) }}</p>
                  @endif

                  {{-- ENTER / EDIT FORM --}}
                  <form method="POST" action="{{ route('supervisor.evaluations.store') }}">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                    <input type="hidden" name="phase" value="{{ $phaseKey }}">
                    <div class="mb-2">
                      <input class="form-control form-control-sm" type="number" name="marks" min="0" max="100"
                             value="{{ $eval->marks ?? '' }}" placeholder="Marks (0-100)" required>
                    </div>
                    <div class="mb-2">
                      <textarea class="form-control form-control-sm" name="remarks" rows="2"
                                placeholder="Remarks (optional)">{{ $eval->remarks ?? '' }}</textarea>
                    </div>
                    <button class="btn btn-sm {{ $eval ? 'btn-outline-primary' : 'btn-primary' }} w-100" type="submit">
                      <i class="bi bi-{{ $eval ? 'pencil' : 'plus-lg' }} me-1"></i>{{ $eval ? 'Update' : 'Save Marks' }}
                    </button>
                  </form>

                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endforeach
  @endif

</div>
@endsection