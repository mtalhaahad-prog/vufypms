@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">User Management</h1>
      <small class="text-muted">All registered students, supervisors and admins</small>
    </div>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSupervisorModal">
      <i class="fas fa-user-plus mr-1"></i>Add Supervisor
    </button>
  </div>

  {{-- FLASH MESSAGES --}}
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

  {{-- SEARCH + FILTER BAR --}}
  <div class="card shadow mb-4">
    <div class="card-body py-3">
      <form method="GET" action="{{ route('admin.users') }}" class="form-inline">
        <input type="text" name="search" class="form-control form-control-sm mr-2 mb-2 mb-md-0" style="min-width:220px;"
               placeholder="Search name or email..." value="{{ $search }}">
        <select name="role" class="form-control form-control-sm mr-2 mb-2 mb-md-0">
          <option value="">All Roles</option>
          <option value="student"    {{ $role === 'student' ? 'selected' : '' }}>Students</option>
          <option value="supervisor" {{ $role === 'supervisor' ? 'selected' : '' }}>Supervisors</option>
          <option value="admin"      {{ $role === 'admin' ? 'selected' : '' }}>Admins</option>
        </select>
        <button class="btn btn-primary btn-sm mr-2" type="submit"><i class="fas fa-search mr-1"></i>Filter</button>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
      </form>
    </div>
  </div>

  {{-- USERS TABLE --}}
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Users ({{ $users->count() }})</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="thead-light">
            <tr><th>User</th><th>Role</th><th>Details</th><th>Registered</th><th class="text-right">Action</th></tr>
          </thead>
          <tbody>
            @forelse($users as $u)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                       style="width:36px;height:36px;background:#eef2ff;color:#4f46e5;font-weight:700;font-size:12px;flex-shrink:0;">
                    {{ strtoupper(substr($u->name, 0, 2)) }}
                  </div>
                  <div>
                    <div class="font-weight-bold" style="font-size:14px;">{{ $u->name }}</div>
                    <small class="text-muted">{{ $u->email }}</small>
                  </div>
                </div>
              </td>
              <td>
                @if($u->role === 'student')<span class="badge badge-primary">Student</span>
                @elseif($u->role === 'supervisor')<span class="badge badge-success">Supervisor</span>
                @else<span class="badge badge-dark">Admin</span>@endif
              </td>
              <td>
                @if($u->role === 'student' && isset($students[$u->user_id]))
                  <small style="font-family:monospace;">{{ $students[$u->user_id]->vuid }}</small><br>
                  <small class="text-muted">{{ $students[$u->user_id]->program }} · Sem {{ $students[$u->user_id]->semester }}</small>
                @elseif($u->role === 'supervisor' && isset($supervisors[$u->user_id]))
                  <small>{{ $supervisors[$u->user_id]->department }}</small><br>
                  <small class="text-muted">Max {{ $supervisors[$u->user_id]->max_teams }} teams</small>
                @else
                  <small class="text-muted">—</small>
                @endif
              </td>
              <td><small>{{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}</small></td>
              <td class="text-right">
                @if($u->role !== 'admin')
                  <form method="POST" action="{{ route('admin.users.destroy', $u->user_id) }}" class="d-inline"
                        onsubmit="return confirm('Delete {{ $u->name }}? This will remove all their related records.');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                  </form>
                @else
                  <small class="text-muted">Protected</small>
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">No users found</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ADD SUPERVISOR MODAL --}}
  <div class="modal fade" id="addSupervisorModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ route('admin.users.storeSupervisor') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New Supervisor</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Full Name <span class="text-danger">*</span></label>
              <input class="form-control" type="text" name="name" value="{{ old('name') }}" maxlength="100" required>
            </div>
            <div class="form-group">
              <label>Email <span class="text-danger">*</span></label>
              <input class="form-control" type="email" name="email" value="{{ old('email') }}" maxlength="150" required>
            </div>
            <div class="form-group">
              <label>Password <span class="text-danger">*</span></label>
              <input class="form-control" type="text" name="password" minlength="6" required
                     placeholder="Min 6 characters - share with supervisor">
            </div>
            <div class="form-row">
              <div class="form-group col-md-7">
                <label>Department <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="department" value="{{ old('department', 'Computer Science') }}" required>
              </div>
              <div class="form-group col-md-5">
                <label>Max Teams <span class="text-danger">*</span></label>
                <input class="form-control" type="number" name="max_teams" value="{{ old('max_teams', 5) }}" min="1" max="20" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus mr-1"></i>Create Supervisor</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection