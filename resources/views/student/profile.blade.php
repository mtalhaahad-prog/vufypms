@extends('student.layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-person-circle"></i></span>
      <div>
        <p class="eyebrow mb-1">Account</p>
        <h1 class="h3 mb-1">Profile</h1>
        <p class="text-muted mb-0">Manage your personal details and account information.</p>
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
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <section class="row g-3 mt-1">
    {{-- LEFT: PROFILE CARD --}}
    <div class="col-12 col-xl-4">
      <div class="panel h-100 text-center profile-card">
        {{-- <div class="profile-cover"><img src="{{ asset('assets/images/png/dasher-ui-bootstrap-5.jpg') }}" alt="Cover"></div> --}}
        @if($student && $student->profile_picture)
  <img class="avatar-img avatar-xl profile-photo" src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ $user->name }}" style="object-fit:cover;">
@else
  <div class="avatar-img avatar-xl profile-photo" style="width:120px;height:120px;border-radius:16px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:white;font-size:50px;font-weight:bold;margin-left:auto;margin-right:auto;">
    {{ strtoupper(substr($user->name, 0, 1)) }}
  </div>
@endif
        <h2 class="h5 mt-3 mb-1">{{ $user->name }}</h2>
        <p class="text-muted mb-3">{{ $student->program ?? 'Student' }} @if($student) · Semester {{ $student->semester }} @endif</p>
        <div class="d-flex justify-content-center gap-2">
          <span class="badge text-bg-primary">Student</span>
          <span class="badge text-bg-success">Active</span>
        </div>
        <div class="info-list mt-4 text-start">
          <div><span>VUID</span><strong>{{ $student->vuid ?? '—' }}</strong></div>
          <div><span>Email</span><strong>{{ $user->email }}</strong></div>
          <div><span>Program</span><strong>{{ $student->program ?? '—' }}</strong></div>
          <div><span>Semester</span><strong>{{ $student ? 'Semester ' . $student->semester : '—' }}</strong></div>
          <div><span>Team</span><strong>{{ $team->team_name ?? 'No team yet' }}</strong></div>
        </div>
      </div>
    </div>

    {{-- RIGHT: EDIT PROFILE + CHANGE PASSWORD --}}
    <div class="col-12 col-xl-8 d-flex flex-column gap-3">

      {{-- EDIT PROFILE FORM --}}
      <form class="panel" method="POST" action="{{ route('student.profile.update') }}">
        @csrf
        @method('PUT')
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-person-gear"></i><span>Edit Profile</span></h2>
            <p class="text-muted mb-0">Update your personal information.</p>
          </div>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="150" required>
          </div>
          {{-- Ye fields readonly hain - academic record change nahi ho sakta --}}
          <div class="col-md-6">
            <label class="form-label">VUID</label>
            <input class="form-control" type="text" value="{{ $student->vuid ?? '—' }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Program</label>
            <input class="form-control" type="text" value="{{ $student->program ?? '—' }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Semester</label>
            <input class="form-control" type="text" value="{{ $student ? 'Semester ' . $student->semester : '—' }}" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Group Code</label>
            <input class="form-control" type="text" value="{{ $team->group_code ?? 'No team yet' }}" readonly>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
          <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Save Profile</button>
        </div>
      </form>

      {{-- CHANGE PASSWORD FORM --}}
      <form class="panel" method="POST" action="{{ route('student.profile.password') }}">
        @csrf
        @method('PUT')
        <div class="panel-header">
          <div>
            <h2 class="h5 mb-1 section-title"><i class="bi bi-lock"></i><span>Change Password</span></h2>
            <p class="text-muted mb-0">Keep your account secure.</p>
          </div>
        </div>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Current Password</label>
            <input class="form-control" type="password" name="current_password" placeholder="••••••••" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">New Password</label>
            <input class="form-control" type="password" name="new_password" placeholder="••••••••" minlength="6" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Confirm Password</label>
            <input class="form-control" type="password" name="new_password_confirmation" placeholder="••••••••" minlength="6" required>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
          <button class="btn btn-primary" type="submit"><i class="bi bi-lock me-1"></i>Update Password</button>
        </div>
      </form>
    </div>
  </section>

</div>
@endsection