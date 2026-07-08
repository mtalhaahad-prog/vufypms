@extends('supervisor.layouts.app')

@section('title', 'Proposal Reviews')

@section('content')
<div class="container-fluid px-4">

  <h1 class="mt-4">Proposal Reviews</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('supervisor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Review and decide on team proposals</li>
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

  {{-- STATS ROW --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm h-100 {{ $counts['pending'] > 0 ? 'border-start border-warning border-4' : '' }}">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="bg-warning bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-hourglass-split text-warning fs-5"></i>
          </div>
          <div><div class="fw-bold fs-4">{{ $counts['pending'] }}</div><div class="text-muted small">Pending</div></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="bg-success bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-check-circle text-success fs-5"></i>
          </div>
          <div><div class="fw-bold fs-4">{{ $counts['approved'] }}</div><div class="text-muted small">Approved</div></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="bg-info bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-arrow-repeat text-info fs-5"></i>
          </div>
          <div><div class="fw-bold fs-4">{{ $counts['revision'] }}</div><div class="text-muted small">In Revision</div></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm h-100">
        <div class="card-body p-3 d-flex align-items-center gap-3">
          <div class="bg-danger bg-opacity-10 rounded-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
            <i class="bi bi-x-circle text-danger fs-5"></i>
          </div>
          <div><div class="fw-bold fs-4">{{ $counts['rejected'] }}</div><div class="text-muted small">Rejected</div></div>
        </div>
      </div>
    </div>
  </div>

  {{-- PROPOSALS LIST --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
      <i class="bi bi-file-earmark-text me-2 text-primary"></i><strong>All Proposals</strong>
    </div>
    <div class="card-body">
      @if($proposals->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="bi bi-inbox fs-1 d-block mb-3"></i>
          <div class="fw-semibold">No proposals yet</div>
          <small>Proposals from your assigned teams will appear here</small>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr><th>Project Title</th><th>Team</th><th>Domain</th><th>Submitted</th><th>Status</th><th class="text-end">Action</th></tr>
            </thead>
            <tbody>
              @foreach($proposals as $p)
                @php $team = $teams[$p->team_id] ?? null; @endphp
                <tr>
                  <td class="fw-semibold" style="max-width:240px;">{{ Str::limit($p->title, 55) }}</td>
                  <td>
                    <div>{{ $team->team_name ?? '—' }}</div>
                    <small class="text-muted" style="font-family:monospace;font-size:11px;">{{ $team->group_code ?? '' }}</small>
                  </td>
                  <td><small>{{ $p->domain }}</small></td>
                  <td><small>{{ \Carbon\Carbon::parse($p->submitted_at)->format('d M Y') }}</small></td>
                  <td>
                    @if($p->status === 'pending')
                      <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                    @elseif($p->status === 'approved')
                      <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                    @elseif($p->status === 'revision')
                      <span class="badge bg-info bg-opacity-10 text-info">Revision</span>
                    @else
                      <span class="badge bg-danger bg-opacity-10 text-danger">Rejected</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <button class="btn btn-sm {{ $p->status === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}"
                            data-bs-toggle="modal" data-bs-target="#reviewModal{{ $p->proposal_id }}">
                      <i class="bi bi-eye me-1"></i>{{ $p->status === 'pending' ? 'Review' : 'View' }}
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  {{-- ============ REVIEW MODALS (har proposal ka apna) ============ --}}
  @foreach($proposals as $p)
    @php $team = $teams[$p->team_id] ?? null; @endphp
    <div class="modal fade" id="reviewModal{{ $p->proposal_id }}" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <div>
              <h5 class="modal-title mb-0">{{ $p->title }}</h5>
              <small class="text-muted">{{ $team->team_name ?? '' }} · {{ $team->group_code ?? '' }}</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            {{-- PROPOSAL DETAILS --}}
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <div class="text-muted small fw-bold text-uppercase" style="font-size:10px;letter-spacing:.05em;">Domain</div>
                <div>{{ $p->domain }}</div>
              </div>
              <div class="col-md-6">
                <div class="text-muted small fw-bold text-uppercase" style="font-size:10px;letter-spacing:.05em;">Tools &amp; Technologies</div>
                <div>{{ $p->tools }}</div>
              </div>
              <div class="col-12">
                <div class="text-muted small fw-bold text-uppercase" style="font-size:10px;letter-spacing:.05em;">Abstract</div>
                <p class="mb-0" style="line-height:1.7;">{{ $p->abstract }}</p>
              </div>
              @if($p->supervisor_remarks)
              <div class="col-12">
                <div class="text-muted small fw-bold text-uppercase" style="font-size:10px;letter-spacing:.05em;">Previous Remarks</div>
                <div class="p-2 rounded bg-light"><small>{{ $p->supervisor_remarks }}</small></div>
              </div>
              @endif
            </div>

            {{-- DECISION FORM (approved proposals dobara review nahi hote) --}}
            @if($p->status !== 'approved')
              <hr>
              <form method="POST" action="{{ route('supervisor.proposals.review', $p->proposal_id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label class="form-label fw-semibold">Remarks / Feedback</label>
                  <textarea class="form-control" name="remarks" rows="3"
                            placeholder="Required for Reject or Revision. Give the team clear feedback...">{{ $p->supervisor_remarks }}</textarea>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                  <button class="btn btn-success" type="submit" name="decision" value="approved"
                          onclick="return confirm('Approve this proposal? Default milestones will be created for the team.');">
                    <i class="bi bi-check-lg me-1"></i>Approve
                  </button>
                  <button class="btn btn-info text-white" type="submit" name="decision" value="revision">
                    <i class="bi bi-arrow-repeat me-1"></i>Request Revision
                  </button>
                  <button class="btn btn-danger" type="submit" name="decision" value="rejected"
                          onclick="return confirm('Reject this proposal? The team will need to submit a new one.');">
                    <i class="bi bi-x-lg me-1"></i>Reject
                  </button>
                </div>
              </form>
            @else
              <div class="alert alert-success mb-0 mt-2">
                <i class="bi bi-check-circle me-2"></i>This proposal has been approved. Milestones are active for the team.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  @endforeach

</div>
@endsection