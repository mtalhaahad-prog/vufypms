@extends('student.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  {{-- PAGE HEADING --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-chat-dots"></i></span>
      <div>
        <p class="eyebrow mb-1">Communication</p>
        <h1 class="h3 mb-1">Messages</h1>
        <p class="text-muted mb-0">Chat directly with your supervisor.</p>
      </div>
    </div>
  </div>

  {{-- FLASH MESSAGES --}}
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- CASE 1: TEAM YA SUPERVISOR NAHI --}}
  @if(!$team)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Team Found</h2>
      <p class="text-muted mb-3">Join or create a team first to message your supervisor.</p>
      <a href="{{ route('student.team') }}" class="btn btn-primary btn-sm"><i class="bi bi-people me-1"></i>Go to My Team</a>
    </div>

  @elseif(!$supervisor)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-person-badge fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Supervisor Assigned</h2>
      <p class="text-muted mb-0">Once the admin assigns a supervisor to your team, you can start messaging here.</p>
    </div>

  {{-- CASE 2: CHAT DIKHAO --}}
  @else
    @php
      $supName = $supervisor->user->name;
      $supInitials = collect(explode(' ', $supName))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
      $myInitials = collect(explode(' ', Auth::user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
    @endphp

    <div class="row g-3 mt-1">
      {{-- LEFT: CHAT PANEL --}}
      <div class="col-12 col-xl-8">
        <div class="panel d-flex flex-column" style="min-height:500px;">

          {{-- CHAT HEADER --}}
          <div class="panel-header pb-3 mb-3" style="border-bottom:1px solid var(--admin-border);">
            <div class="d-flex align-items-center gap-3">
              <div style="width:42px;height:42px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;">{{ $supInitials }}</div>
              <div>
                <div class="fw-semibold">{{ $supName }}</div>
                <small class="text-muted">Supervisor · {{ $supervisor->department }}</small>
              </div>
            </div>
          </div>

          {{-- MESSAGE THREAD --}}
          <div id="msgThread" style="flex:1;overflow-y:auto;max-height:340px;display:flex;flex-direction:column;gap:14px;padding-bottom:10px;">
            @forelse($messages as $msg)
              @if($msg->sender_id === Auth::user()->user_id)
                {{-- MERA MESSAGE (right side, purple) --}}
                <div style="display:flex;gap:10px;flex-direction:row-reverse;">
                  <div style="width:32px;height:32px;border-radius:50%;background:#eef2ff;color:#6366f1;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:11px;flex-shrink:0;">{{ $myInitials }}</div>
                  <div>
                    <div style="background:#6366f1;color:#fff;padding:10px 14px;border-radius:14px;border-bottom-right-radius:4px;font-size:13px;line-height:1.5;max-width:300px;">{{ $msg->content }}</div>
                    <div style="font-size:10px;color:var(--admin-text-muted);margin-top:4px;text-align:right;">{{ \Carbon\Carbon::parse($msg->sent_at)->format('d M Y, h:i A') }}</div>
                  </div>
                </div>
              @else
                {{-- SUPERVISOR KA MESSAGE (left side, grey) --}}
                <div style="display:flex;gap:10px;">
                  <div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:11px;flex-shrink:0;">{{ $supInitials }}</div>
                  <div>
                    <div style="background:var(--admin-surface-soft);padding:10px 14px;border-radius:14px;border-bottom-left-radius:4px;font-size:13px;line-height:1.5;max-width:300px;">{{ $msg->content }}</div>
                    <div style="font-size:10px;color:var(--admin-text-muted);margin-top:4px;">{{ \Carbon\Carbon::parse($msg->sent_at)->format('d M Y, h:i A') }}</div>
                  </div>
                </div>
              @endif
            @empty
              <div class="text-center text-muted py-5">
                <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
                <div class="fw-semibold">No messages yet</div>
                <small>Send your first message to {{ $supName }} below</small>
              </div>
            @endforelse
          </div>

          {{-- MESSAGE INPUT FORM --}}
          <form method="POST" action="{{ route('student.messages.store') }}"
                style="display:flex;gap:10px;margin-top:16px;border-top:1px solid var(--admin-border);padding-top:16px;">
            @csrf
            <input type="text" name="content" class="form-control" placeholder="Type your message..." maxlength="2000" required autocomplete="off">
            <button class="btn btn-primary px-3" type="submit"><i class="bi bi-send"></i></button>
          </form>
        </div>
      </div>

      {{-- RIGHT: SUPERVISOR INFO --}}
      <div class="col-12 col-xl-4">
        <div class="panel">
          <div class="panel-header"><h2 class="h5 mb-0 section-title"><i class="bi bi-person-badge"></i><span>Supervisor Info</span></h2></div>
          <div class="d-flex flex-column gap-2" style="font-size:13px;">
            <div class="d-flex gap-2 align-items-center text-muted"><i class="bi bi-envelope" style="color:#6366f1;"></i>{{ $supervisor->user->email }}</div>
            <div class="d-flex gap-2 align-items-center text-muted"><i class="bi bi-building" style="color:#6366f1;"></i>{{ $supervisor->department }}</div>
            <div class="d-flex gap-2 align-items-center text-muted"><i class="bi bi-people" style="color:#6366f1;"></i>Max {{ $supervisor->max_teams }} Teams</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Chat ko hamesha neeche (latest message) scroll rakho --}}
    <script>
      const thread = document.getElementById('msgThread');
      if (thread) { thread.scrollTop = thread.scrollHeight; }
    </script>
  @endif

</div>
@endsection