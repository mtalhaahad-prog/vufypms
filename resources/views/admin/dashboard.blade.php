@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

  {{-- PAGE HEADING --}}
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
      <small class="text-muted">System overview — Virtual University FYP Management</small>
    </div>
  </div>

  {{-- ================= STAT CARDS ================= --}}
  <div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
            </div>
            <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Supervisors</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSupervisors }}</div>
            </div>
            <div class="col-auto"><i class="fas fa-user-tie fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Teams</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTeams }}</div>
            </div>
            <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2 {{ $unassignedTeams > 0 ? 'border-warning' : '' }}">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Unassigned Teams</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unassignedTeams }}</div>
              @if($unassignedTeams > 0)
                <small class="text-warning">Need a supervisor!</small>
              @endif
            </div>
            <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row">

    {{-- ================= PROPOSAL STATUS PIE CHART ================= --}}
    <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Proposals by Status</h6>
        </div>
        <div class="card-body">
          @if(array_sum($proposalStats) === 0)
            <div class="text-center py-4 text-muted">No proposals yet</div>
          @else
            <div class="chart-pie pt-4 pb-2">
              <canvas id="proposalChart"></canvas>
            </div>
            <div class="mt-4 text-center small">
              <span class="mr-2"><i class="fas fa-circle" style="color:#f6c23e;"></i> Pending ({{ $proposalStats['pending'] }})</span>
              <span class="mr-2"><i class="fas fa-circle" style="color:#1cc88a;"></i> Approved ({{ $proposalStats['approved'] }})</span>
              <span class="mr-2"><i class="fas fa-circle" style="color:#36b9cc;"></i> Revision ({{ $proposalStats['revision'] }})</span>
              <span class="mr-2"><i class="fas fa-circle" style="color:#e74a3b;"></i> Rejected ({{ $proposalStats['rejected'] }})</span>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ================= SUPERVISOR LOAD ================= --}}
    <div class="col-xl-4 col-lg-7">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Supervisor Workload</h6>
        </div>
        <div class="card-body">
          @if(count($supervisorLoad) === 0)
            <div class="text-center py-4 text-muted">No supervisors yet</div>
          @else
            @foreach($supervisorLoad as $sl)
              @php $pct = $sl['max'] > 0 ? round(($sl['assigned'] / $sl['max']) * 100) : 0; @endphp
              <h4 class="small font-weight-bold">{{ $sl['name'] }}
                <span class="float-right">{{ $sl['assigned'] }} / {{ $sl['max'] }} teams</span>
              </h4>
              <div class="progress mb-4" style="height:10px;">
                <div class="progress-bar {{ $pct >= 80 ? 'bg-danger' : ($pct >= 50 ? 'bg-warning' : 'bg-success') }}"
                     style="width:{{ $pct }}%"></div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>

    {{-- ================= RECENT USERS ================= --}}
    <div class="col-xl-4 col-lg-6">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Recently Registered</h6>
          <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">All Users</a>
        </div>
        <div class="card-body">
          @foreach($recentUsers as $u)
            <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                   style="width:36px;height:36px;background:#eef2ff;color:#4f46e5;font-weight:700;font-size:12px;flex-shrink:0;">
                {{ strtoupper(substr($u->name, 0, 2)) }}
              </div>
              <div class="flex-grow-1" style="min-width:0;">
                <div class="small font-weight-bold text-truncate">{{ $u->name }}</div>
                <div class="text-muted" style="font-size:11px;">{{ $u->email }}</div>
              </div>
              @if($u->role === 'student')
                <span class="badge badge-primary">Student</span>
              @elseif($u->role === 'supervisor')
                <span class="badge badge-success">Supervisor</span>
              @else
                <span class="badge badge-dark">Admin</span>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

@endsection

@push('scripts')
<script>
// ================================================
// PROPOSALS PIE CHART (Chart.js - template ka apna)
// ================================================
@if(array_sum($proposalStats) > 0)
var ctx = document.getElementById("proposalChart");
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Pending", "Approved", "Revision", "Rejected"],
    datasets: [{
      data: [
        {{ $proposalStats['pending'] }},
        {{ $proposalStats['approved'] }},
        {{ $proposalStats['revision'] }},
        {{ $proposalStats['rejected'] }}
      ],
      backgroundColor: ['#f6c23e', '#1cc88a', '#36b9cc', '#e74a3b'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: { backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, caretPadding: 10 },
    legend: { display: false },
    cutoutPercentage: 75,
  },
});
@endif
</script>
@endpush