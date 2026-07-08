@extends('student.layouts.app')

@section('title', 'My Team')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  {{-- PAGE HEADING --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-people"></i></span>
      <div>
        <p class="eyebrow mb-1">Project</p>
        <h1 class="h3 mb-1">My Team</h1>
        <p class="text-muted mb-0">View and manage your FYP project team.</p>
      </div>
    </div>
    {{-- Add Member button sirf leader ko dikhao, aur jab team full na ho --}}
    @if($team && $teamMember->role === 'leader' && $members->count() < 4)
    <div class="heading-actions">
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inviteModal">
        <i class="bi bi-person-plus"></i> Add Member
      </button>
    </div>
    @endif
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
  {{-- CASE 1: KOI TEAM NAHI - CREATE FORM DIKHAO --}}
  {{-- ============================================ --}}
  @if(!$team)
    <div class="row justify-content-center mt-3">
      <div class="col-12 col-md-8 col-xl-6">
        <div class="panel text-center py-4">
          <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
          <h2 class="h5 mb-2">You are not part of any team yet</h2>
          <p class="text-muted mb-4">Create your own team to get started. You will become the team leader and can add members using their VUID.</p>
          <form method="POST" action="{{ route('student.team.store') }}" class="text-start">
            @csrf
            <div class="mb-3">
              <label class="form-label">Team Name <span class="text-danger">*</span></label>
              <input class="form-control" type="text" name="team_name" value="{{ old('team_name') }}" placeholder="e.g. Team VUFYPMS" maxlength="100" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">
              <i class="bi bi-plus-circle me-1"></i>Create Team
            </button>
          </form>
        </div>
      </div>
    </div>

  {{-- ============================================ --}}
  {{-- CASE 2: TEAM MAUJOOD HAI --}}
  {{-- ============================================ --}}
  @else
    <div class="row g-3 mt-1">
      {{-- LEFT: MEMBERS TABLE --}}
      <div class="col-12 col-xl-8">
        <div class="panel">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-people"></i><span>Team Members</span></h2>
              <p class="text-muted mb-0">{{ $team->team_name }} · {{ $team->group_code }}</p>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Member</th>
                  <th>VUID</th>
                  <th>Role</th>
                  <th>Status</th>
                  @if($teamMember->role === 'leader')
                  <th class="text-end">Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach($members as $m)
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <img class="avatar-img avatar-sm" src="{{ asset('assets/images/avatar/avatar.jpg') }}" alt="{{ $m->student->user->name }}">
                      <div>
                        <p class="fw-semibold mb-0">{{ $m->student->user->name }}</p>
                        <small class="text-muted">{{ $m->student->user->email }}</small>
                      </div>
                    </div>
                  </td>
                  <td>{{ $m->student->vuid }}</td>
                  <td>
                    @if($m->role === 'leader')
                      <span class="badge text-bg-primary">Leader</span>
                    @else
                      <span class="badge text-bg-secondary">Member</span>
                    @endif
                  </td>
                  <td><span class="badge text-bg-success">Active</span></td>
                  @if($teamMember->role === 'leader')
                  <td class="text-end">
                    @if($m->role !== 'leader')
                    <form method="POST" action="{{ route('student.team.remove', $m->id) }}" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to remove this member?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-outline-danger btn-sm" type="submit">
                        <i class="bi bi-person-dash"></i> Remove
                      </button>
                    </form>
                    @else
                    <span class="text-muted" style="font-size:12px;">—</span>
                    @endif
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- RIGHT: TEAM INFO + SUPERVISOR --}}
      <div class="col-12 col-xl-4 d-flex flex-column gap-3">
        <div class="panel">
          <div class="panel-header"><h2 class="h5 mb-0 section-title"><i class="bi bi-info-circle"></i><span>Team Info</span></h2></div>
          <div class="d-flex flex-column gap-2" style="font-size:13px;">
            <div class="d-flex justify-content-between p-2 rounded-3" style="background:var(--admin-surface-soft);">
              <span class="text-muted">Team Name</span><strong>{{ $team->team_name }}</strong>
            </div>
            <div class="d-flex justify-content-between p-2 rounded-3" style="background:var(--admin-surface-soft);">
              <span class="text-muted">Group Code</span><strong style="font-family:monospace;font-size:12px;">{{ $team->group_code }}</strong>
            </div>
            <div class="d-flex justify-content-between p-2 rounded-3" style="background:var(--admin-surface-soft);">
              <span class="text-muted">Members</span><strong>{{ $members->count() }} / 4</strong>
            </div>
            <div class="d-flex justify-content-between p-2 rounded-3" style="background:var(--admin-surface-soft);">
              <span class="text-muted">Status</span>
              @if($team->status === 'active')
                <span class="badge text-bg-success">Active</span>
              @else
                <span class="badge text-bg-secondary">Inactive</span>
              @endif
            </div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header"><h2 class="h5 mb-0 section-title"><i class="bi bi-person-badge"></i><span>Supervisor</span></h2></div>
          @if($team->supervisor && $team->supervisor->user)
            @php
              $supName = $team->supervisor->user->name;
              $initials = collect(explode(' ', $supName))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
            @endphp
            <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:var(--admin-surface-soft);border:1px solid var(--admin-border);">
              <div style="width:40px;height:40px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">{{ $initials }}</div>
              <div>
                <div class="fw-semibold" style="font-size:14px;">{{ $supName }}</div>
                <small class="text-muted">{{ $team->supervisor->department }}</small>
              </div>
            </div>
          @else
            <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i>No supervisor assigned yet. The admin will assign one soon.</p>
          @endif
        </div>
      </div>
    </div>

    {{-- ADD MEMBER MODAL (sirf leader ke liye) --}}
    @if($teamMember->role === 'leader')
    <div class="modal fade" id="inviteModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('student.team.addMember') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Add Team Member</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Student VUID <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="vuid" placeholder="e.g. BC240401999" maxlength="20" required>
                <small class="text-muted">The student must already be registered on VUFYPMS.</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Add Member</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif
  @endif

</div>
@endsection