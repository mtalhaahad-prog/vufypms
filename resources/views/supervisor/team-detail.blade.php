@extends('supervisor.layouts.app')

@section('title', $team->team_name)

@section('content')
<div class="container-fluid px-4">

  <h1 class="mt-4">{{ $team->team_name }}</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('supervisor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('supervisor.teams') }}">My Teams</a></li>
    <li class="breadcrumb-item active" style="font-family:monospace;">{{ $team->group_code }}</li>
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

  <div class="row g-3">

    {{-- ============ LEFT COLUMN ============ --}}
    <div class="col-xl-7">

      {{-- MILESTONES (status update ke saath) --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
          <i class="bi bi-flag me-2 text-primary"></i><strong>Milestones</strong>
          <small class="text-muted ms-2">— update status as the team progresses</small>
        </div>
        <div class="card-body">
          @if($milestones->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="bi bi-flag fs-2 d-block mb-2"></i>
              No milestones yet. Approve the team's proposal to generate milestones.
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr><th>Milestone</th><th>Due Date</th><th style="width:180px;">Status</th></tr>
                </thead>
                <tbody>
                  @foreach($milestones as $ms)
                  <tr>
                    <td class="fw-semibold">{{ $ms->title }}</td>
                    <td><small>{{ \Carbon\Carbon::parse($ms->due_date)->format('d M Y') }}</small></td>
                    <td>
                      {{-- Dropdown change hote hi form submit ho jata hai --}}
                      <form method="POST" action="{{ route('supervisor.milestones.update', $ms->milestone_id) }}">
                        @csrf
                        @method('PUT')
                        <select class="form-select form-select-sm
                                {{ $ms->status === 'done' ? 'border-success text-success' : ($ms->status === 'in_progress' ? 'border-warning text-warning' : '') }}"
                                name="status" onchange="this.form.submit()">
                          <option value="pending"     {{ $ms->status === 'pending' ? 'selected' : '' }}>Pending</option>
                          <option value="in_progress" {{ $ms->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                          <option value="done"        {{ $ms->status === 'done' ? 'selected' : '' }}>Done</option>
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

      {{-- DOCUMENTS --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
          <i class="bi bi-folder2-open me-2 text-primary"></i><strong>Submitted Documents</strong>
        </div>
        <div class="card-body">
          @if($documents->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>
              No documents uploaded by this team yet.
            </div>
          @else
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr><th>File</th><th>Type</th><th>Milestone</th><th>Uploaded</th><th class="text-end">Action</th></tr>
                </thead>
                <tbody>
                  @foreach($documents as $doc)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                        <small class="fw-semibold">{{ Str::limit(basename($doc->file_path), 40) }}</small>
                      </div>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ str_replace('_',' ', ucfirst($doc->doc_type)) }}</span></td>
                    <td><small>{{ $milestoneTitles[$doc->milestone_id] ?? '—' }}</small></td>
                    <td><small>{{ \Carbon\Carbon::parse($doc->uploaded_at)->format('d M Y') }}</small></td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-outline-primary" href="{{ route('supervisor.documents.download', $doc->document_id) }}">
                        <i class="bi bi-download"></i>
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
    </div>

    {{-- ============ RIGHT COLUMN ============ --}}
    <div class="col-xl-5">

      {{-- MEMBERS --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
          <i class="bi bi-people me-2 text-primary"></i><strong>Team Members</strong>
        </div>
        <div class="card-body">
          @foreach($members as $m)
            <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="rounded-3 d-flex align-items-center justify-content-center"
                   style="width:38px;height:38px;background:#eef2ff;color:#4f46e5;font-weight:700;font-size:12px;flex-shrink:0;">
                {{ strtoupper(substr($m->student->user->name, 0, 2)) }}
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:14px;">{{ $m->student->user->name }}</div>
                <small class="text-muted" style="font-family:monospace;">{{ $m->student->vuid }}</small>
              </div>
              @if($m->role === 'leader')
                <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-star-fill me-1" style="font-size:9px;"></i>Leader</span>
              @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary">Member</span>
              @endif
            </div>
          @endforeach
        </div>
      </div>

      {{-- PROPOSAL SUMMARY --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <span><i class="bi bi-file-earmark-text me-2 text-primary"></i><strong>Proposal</strong></span>
          @if($proposal)
            <a href="{{ route('supervisor.proposals') }}" class="btn btn-sm btn-outline-primary">Open Reviews</a>
          @endif
        </div>
        <div class="card-body">
          @if(!$proposal)
            <div class="text-center py-3 text-muted">
              <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>
              No proposal submitted yet.
            </div>
          @else
            <div class="fw-semibold mb-2">{{ $proposal->title }}</div>
            <div class="mb-2" style="font-size:13px;">
              <span class="text-muted">Status:</span>
              @if($proposal->status === 'approved')
                <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
              @elseif($proposal->status === 'pending')
                <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
              @elseif($proposal->status === 'revision')
                <span class="badge bg-info bg-opacity-10 text-info">Revision</span>
              @else
                <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
              @endif
            </div>
            <div style="font-size:13px;"><span class="text-muted">Domain:</span> {{ $proposal->domain }}</div>
            <div style="font-size:13px;"><span class="text-muted">Tools:</span> {{ $proposal->tools }}</div>
          @endif
        </div>
      </div>

    </div>
  </div>
</div>
@endsection