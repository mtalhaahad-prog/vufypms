@extends('student.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-gear"></i></span>
      <div>
        <p class="eyebrow mb-1">Account</p>
        <h1 class="h3 mb-1">Settings</h1>
        <p class="text-muted mb-0">Manage your notification preferences and account settings.</p>
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

  {{-- Dono panels EK form ke andar - ek Save button sab save karta hai --}}
  <form method="POST" action="{{ route('student.settings.update') }}">
    @csrf
    @method('PUT')
    <div class="row g-3 mt-1">

      {{-- LEFT: ACCOUNT SETTINGS --}}
      <div class="col-12 col-xl-6">
        <div class="panel h-100">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-sliders"></i><span>Account Settings</span></h2>
              <p class="text-muted mb-0">Update your account preferences.</p>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="100" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="150" required>
          </div>
          <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Save Settings</button>
        </div>
      </div>

      {{-- RIGHT: NOTIFICATION TOGGLES --}}
      <div class="col-12 col-xl-6">
        <div class="panel h-100">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-toggles2"></i><span>Notifications</span></h2>
              <p class="text-muted mb-0">Choose what to be notified about.</p>
            </div>
          </div>
          <div class="settings-list">
            @foreach($options as $key => $opt)
              <label class="settings-row">
                <span>
                  <strong>{{ $opt['label'] }}</strong>
                  <small>{{ $opt['desc'] }}</small>
                </span>
                <input class="form-check-input" type="checkbox" name="notify_{{ $key }}"
                       @if(!empty($prefs[$key])) checked @endif>
              </label>
            @endforeach
          </div>
        </div>
      </div>

    </div>
  </form>

</div>
@endsection