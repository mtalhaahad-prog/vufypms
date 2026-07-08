@extends('student.layouts.app')

@section('title', 'My Proposal')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  {{-- PAGE HEADING --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-file-earmark-text"></i></span>
      <div>
        <p class="eyebrow mb-1">Project</p>
        <h1 class="h3 mb-1">My Proposal</h1>
        <p class="text-muted mb-0">Submit, view and manage your FYP proposal.</p>
      </div>
    </div>
  </div>

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
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- ============================================ --}}
  {{-- CASE 1: STUDENT KI KOI TEAM NAHI HAI --}}
  {{-- ============================================ --}}
  @if(!$team)
    <div class="panel text-center py-5">
      <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Team Found</h2>
      <p class="text-muted mb-3">You must be part of a team before you can submit a project proposal.</p>
      <a href="{{ route('student.team') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Go to My Team
      </a>
    </div>

  {{-- ============================================ --}}
  {{-- CASE 2: TEAM HAI LEKIN PROPOSAL SUBMIT NAHI HUA --}}
  {{-- ============================================ --}}
  @elseif(!$proposal)
    <div class="alert d-flex align-items-center gap-3" style="background:#eff6ff;border:1px solid #dbeafe;border-radius:12px;">
      <i class="bi bi-info-circle fs-4" style="color:#2563eb;flex-shrink:0;"></i>
      <div class="flex-grow-1">
        <strong style="color:#1e40af;">No Proposal Submitted Yet</strong><br>
        <small style="color:#1e3a8a;">Fill the form below to submit your FYP proposal for supervisor review.</small>
      </div>
    </div>

    <div class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-file-earmark-plus"></i><span>Submit New Proposal</span></h2>
          <p class="text-muted mb-0">Team: {{ $team->team_name }} ({{ $team->group_code }})</p>
        </div>
      </div>
      <form method="POST" action="{{ route('student.proposal.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Project Title <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="title" value="{{ old('title') }}" maxlength="200" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Domain <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="domain" value="{{ old('domain') }}" placeholder="e.g. Web Application" maxlength="100" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tools &amp; Technologies <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="tools" value="{{ old('tools') }}" placeholder="e.g. Laravel, MySQL, Bootstrap" maxlength="500" required>
          </div>
          <div class="col-12">
            <label class="form-label">Abstract <span class="text-danger">*</span></label>
            <textarea class="form-control" name="abstract" rows="5" minlength="50" required placeholder="Describe your project in at least 50 characters...">{{ old('abstract') }}</textarea>
          </div>
          <div class="col-12">
            <button class="btn btn-primary" type="submit"><i class="bi bi-send me-1"></i>Submit Proposal</button>
          </div>
        </div>
      </form>
    </div>

  {{-- ============================================ --}}
  {{-- CASE 3: PROPOSAL MAUJOOD HAI - STATUS KE HISAB SE DIKHAO --}}
  {{-- ============================================ --}}
  @else
    @php
      // Status ke hisaab se alert ka style set karo (DB enum: pending/approved/rejected/revision)
      $statusConfig = [
        'pending'  => ['bg' => '#fffbeb', 'border' => '#fef3c7', 'icon' => 'bi-hourglass-split', 'iconColor' => '#d97706', 'titleColor' => '#92400e', 'textColor' => '#78350f', 'badgeBg' => '#fef3c7', 'badgeColor' => '#d97706', 'label' => 'Pending', 'title' => 'Status: Pending Review', 'text' => 'Your proposal has been submitted and is awaiting supervisor approval.'],
        'approved' => ['bg' => '#f0fdf4', 'border' => '#dcfce7', 'icon' => 'bi-check-circle', 'iconColor' => '#16a34a', 'titleColor' => '#166534', 'textColor' => '#14532d', 'badgeBg' => '#dcfce7', 'badgeColor' => '#16a34a', 'label' => 'Approved', 'title' => 'Status: Approved', 'text' => 'Congratulations! Your proposal has been approved by your supervisor.'],
        'rejected' => ['bg' => '#fef2f2', 'border' => '#fee2e2', 'icon' => 'bi-x-circle', 'iconColor' => '#dc2626', 'titleColor' => '#991b1b', 'textColor' => '#7f1d1d', 'badgeBg' => '#fee2e2', 'badgeColor' => '#dc2626', 'label' => 'Rejected', 'title' => 'Status: Rejected', 'text' => 'Your proposal was rejected. Please review the feedback and resubmit.'],
        'revision' => ['bg' => '#fff7ed', 'border' => '#ffedd5', 'icon' => 'bi-arrow-repeat', 'iconColor' => '#ea580c', 'titleColor' => '#9a3412', 'textColor' => '#7c2d12', 'badgeBg' => '#ffedd5', 'badgeColor' => '#ea580c', 'label' => 'Revision', 'title' => 'Status: Revision Required', 'text' => 'Your supervisor has requested changes. Please update and resubmit your proposal.'],
      ];
      $sc = $statusConfig[$proposal->status] ?? $statusConfig['pending'];
      $canEdit = $proposal->status !== 'approved';
    @endphp

    {{-- STATUS ALERT --}}
    <div class="alert d-flex align-items-center gap-3" style="background:{{ $sc['bg'] }};border:1px solid {{ $sc['border'] }};border-radius:12px;">
      <i class="bi {{ $sc['icon'] }} fs-4" style="color:{{ $sc['iconColor'] }};flex-shrink:0;"></i>
      <div class="flex-grow-1">
        <strong style="color:{{ $sc['titleColor'] }};">{{ $sc['title'] }}</strong><br>
        <small style="color:{{ $sc['textColor'] }};">{{ $sc['text'] }}</small>
      </div>
      <span class="badge" style="background:{{ $sc['badgeBg'] }};color:{{ $sc['badgeColor'] }};border-radius:99px;padding:6px 14px;">{{ $sc['label'] }}</span>
    </div>

    {{-- SUPERVISOR REMARKS (agar hain) --}}
    @if($proposal->supervisor_remarks && in_array($proposal->status, ['rejected', 'revision']))
      <div class="panel mt-3" style="border-left:4px solid #ea580c;">
        <div class="d-flex gap-2 align-items-start">
          <i class="bi bi-chat-left-quote fs-5" style="color:#ea580c;"></i>
          <div>
            <div class="fw-semibold mb-1" style="font-size:13px;">Supervisor's Feedback</div>
            <p class="text-muted mb-0" style="font-size:13px;line-height:1.6;">{{ $proposal->supervisor_remarks }}</p>
          </div>
        </div>
      </div>
    @endif

    <div class="row g-3 mt-1">
      {{-- LEFT: PROPOSAL DETAILS --}}
      <div class="col-12 col-xl-8">
        <div class="panel">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-file-earmark-text"></i><span>Proposal Details</span></h2>
              <p class="text-muted mb-0">Your currently submitted proposal</p>
            </div>
            @if($canEdit)
            <div class="d-flex gap-2">
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick="toggleEdit()"><i class="bi bi-pencil"></i> Edit</button>
            </div>
            @endif
          </div>

          {{-- VIEW MODE --}}
          <div id="viewMode">
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Project Title</label>
                <p class="fw-semibold mb-0" style="font-size:15px;">{{ $proposal->title }}</p>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Domain</label>
                <p class="mb-0">{{ $proposal->domain }}</p>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Submitted</label>
                <p class="mb-0">{{ \Carbon\Carbon::parse($proposal->submitted_at)->format('d M Y') }}</p>
              </div>
              <div class="col-12">
                <label class="form-label fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Tools &amp; Technologies</label>
                <p class="mb-0">{{ $proposal->tools }}</p>
              </div>
              <div class="col-12">
                <label class="form-label fw-bold text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;">Abstract</label>
                <p class="mb-0" style="line-height:1.7;">{{ $proposal->abstract }}</p>
              </div>
            </div>
          </div>

          {{-- EDIT MODE (sirf tab jab approved na ho) --}}
          @if($canEdit)
          <form id="editMode" class="d-none mt-3" method="POST" action="{{ route('student.proposal.update') }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Project Title</label>
                <input class="form-control" type="text" name="title" value="{{ old('title', $proposal->title) }}" maxlength="200" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Domain</label>
                <input class="form-control" type="text" name="domain" value="{{ old('domain', $proposal->domain) }}" maxlength="100" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tools</label>
                <input class="form-control" type="text" name="tools" value="{{ old('tools', $proposal->tools) }}" maxlength="500" required>
              </div>
              <div class="col-12">
                <label class="form-label">Abstract</label>
                <textarea class="form-control" name="abstract" rows="4" minlength="50" required>{{ old('abstract', $proposal->abstract) }}</textarea>
              </div>
              <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-send"></i> Save &amp; Resubmit</button>
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="toggleEdit()">Cancel</button>
              </div>
            </div>
          </form>
          @endif
        </div>
      </div>

      {{-- RIGHT: TIMELINE + SUPERVISOR --}}
      <div class="col-12 col-xl-4 d-flex flex-column gap-3">

        {{-- TIMELINE --}}
        <div class="panel">
          <div class="panel-header"><h2 class="h5 mb-0 section-title"><i class="bi bi-clock-history"></i><span>Timeline</span></h2></div>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex gap-3 align-items-start">
              <div style="width:10px;height:10px;border-radius:50%;background:#6366f1;margin-top:4px;flex-shrink:0;"></div>
              <div>
                <div class="fw-semibold" style="font-size:13px;">Submitted</div>
                <small class="text-muted">{{ \Carbon\Carbon::parse($proposal->submitted_at)->format('d M Y') }}</small>
              </div>
            </div>
            <div class="d-flex gap-3 align-items-start">
              <div style="width:10px;height:10px;border-radius:50%;background:{{ $proposal->status === 'pending' ? '#d97706' : ($proposal->status === 'approved' ? '#16a34a' : '#dc2626') }};margin-top:4px;flex-shrink:0;"></div>
              <div>
                <div class="fw-semibold" style="font-size:13px;">Under Review</div>
                <small class="text-muted">{{ $proposal->status === 'pending' ? 'Awaiting supervisor' : 'Review completed' }}</small>
              </div>
            </div>
            <div class="d-flex gap-3 align-items-start">
              <div style="width:10px;height:10px;border-radius:50%;background:{{ $proposal->status === 'approved' ? '#16a34a' : '#e2e8f0' }};margin-top:4px;flex-shrink:0;"></div>
              <div>
                <div class="fw-semibold {{ $proposal->status === 'approved' ? '' : 'text-muted' }}" style="font-size:13px;">Approved</div>
                <small class="text-muted">{{ $proposal->status === 'approved' ? \Carbon\Carbon::parse($proposal->updated_at)->format('d M Y') : 'Pending' }}</small>
              </div>
            </div>
          </div>
        </div>

        {{-- SUPERVISOR CARD --}}
        <div class="panel">
          <div class="panel-header"><h2 class="h5 mb-0 section-title"><i class="bi bi-person-badge"></i><span>Supervisor</span></h2></div>
          @if($team->supervisor && $team->supervisor->user)
            @php
              $supName = $team->supervisor->user->name;
              $initials = collect(explode(' ', $supName))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
            @endphp
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-3" style="background:var(--admin-surface-soft);border:1px solid var(--admin-border);">
              <div style="width:40px;height:40px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">{{ $initials }}</div>
              <div>
                <div class="fw-semibold" style="font-size:14px;">{{ $supName }}</div>
                <small class="text-muted">{{ $team->supervisor->department }}</small>
              </div>
            </div>
            <a href="{{ route('student.messages') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-chat-dots me-1"></i>Message Supervisor</a>
          @else
            <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i>No supervisor assigned yet.</p>
          @endif
        </div>

      </div>
    </div>

    @if($canEdit)
    <script>
      function toggleEdit() {
        document.getElementById('viewMode').classList.toggle('d-none');
        document.getElementById('editMode').classList.toggle('d-none');
      }
      // Agar validation error aaye to edit mode khula rakho
      @if($errors->any())
        toggleEdit();
      @endif
    </script>
    @endif
  @endif

</div>
@endsection