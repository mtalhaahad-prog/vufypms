@extends('supervisor.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container-fluid px-4">

  <h1 class="mt-4">Messages</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('supervisor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Chat with students under your supervision</li>
  </ol>

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-3">

    {{-- LEFT: STUDENTS LIST --}}
    <div class="col-md-4 col-xl-3">
      <div class="card shadow-sm">
        <div class="card-header bg-white"><i class="bi bi-people me-2 text-primary"></i><strong>My Students</strong></div>
        <div class="card-body p-2">
          @if($students->isEmpty())
            <div class="text-center py-4 text-muted" style="font-size:13px;">No students yet</div>
          @else
            @foreach($students as $m)
              @php
                $sUser  = $m->student->user;
                $unread = $unreadCounts[$sUser->user_id] ?? 0;
                $active = $selectedStudent && $selectedStudent->user_id == $sUser->user_id;
              @endphp
              <a href="{{ route('supervisor.messages', ['student' => $sUser->user_id]) }}"
                 class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none mb-1"
                 style="{{ $active ? 'background:#eef2ff;border:1px solid #c7d2fe;' : '' }}">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:{{ $active ? 'linear-gradient(135deg,#6366f1,#8b5cf6)' : '#eef2ff' }};color:{{ $active ? '#fff' : '#4f46e5' }};font-weight:700;font-size:11px;flex-shrink:0;">
                  {{ strtoupper(substr($sUser->name, 0, 2)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                  <div class="fw-semibold text-dark text-truncate" style="font-size:13px;">{{ $sUser->name }}</div>
                  <small class="text-muted" style="font-size:11px;font-family:monospace;">{{ $m->student->vuid }}</small>
                </div>
                @if($unread > 0)
                  <span class="badge bg-primary rounded-pill">{{ $unread }}</span>
                @endif
              </a>
            @endforeach
          @endif
        </div>
      </div>
    </div>

    {{-- RIGHT: CHAT AREA --}}
    <div class="col-md-8 col-xl-9">
      <div class="card shadow-sm d-flex flex-column" style="min-height:500px;">

        @if(!$selectedStudent)
          <div class="card-body d-flex align-items-center justify-content-center text-muted flex-column">
            <i class="bi bi-chat-square-dots fs-1 mb-3"></i>
            <div class="fw-semibold">Select a student to start chatting</div>
            <small>Choose from the list on the left</small>
          </div>
        @else
          {{-- CHAT HEADER --}}
          <div class="card-header bg-white d-flex align-items-center gap-2">
            <div class="rounded-3 d-flex align-items-center justify-content-center"
                 style="width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;font-weight:700;font-size:12px;">
              {{ strtoupper(substr($selectedStudent->name, 0, 2)) }}
            </div>
            <div>
              <div class="fw-bold" style="font-size:14px;">{{ $selectedStudent->name }}</div>
              <small class="text-muted">{{ $selectedStudent->email }}</small>
            </div>
          </div>

          {{-- THREAD --}}
          <div class="card-body" id="supThread" style="flex:1;overflow-y:auto;max-height:360px;display:flex;flex-direction:column;gap:14px;">
            @forelse($messages as $msg)
              @if($msg->sender_id === Auth::user()->user_id)
                {{-- MERA (supervisor) MESSAGE - right --}}
                <div style="display:flex;justify-content:flex-end;">
                  <div style="max-width:70%;">
                    <div style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;padding:10px 14px;border-radius:14px;border-bottom-right-radius:4px;font-size:13px;line-height:1.5;">{{ $msg->content }}</div>
                    <div class="text-end" style="font-size:10px;color:#94a3b8;margin-top:3px;">{{ \Carbon\Carbon::parse($msg->sent_at)->format('d M, h:i A') }}</div>
                  </div>
                </div>
              @else
                {{-- STUDENT KA MESSAGE - left --}}
                <div style="display:flex;">
                  <div style="max-width:70%;">
                    <div style="background:#f1f5f9;padding:10px 14px;border-radius:14px;border-bottom-left-radius:4px;font-size:13px;line-height:1.5;">{{ $msg->content }}</div>
                    <div style="font-size:10px;color:#94a3b8;margin-top:3px;">{{ \Carbon\Carbon::parse($msg->sent_at)->format('d M, h:i A') }}</div>
                  </div>
                </div>
              @endif
            @empty
              <div class="text-center text-muted py-5">
                <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
                No messages yet — say hello!
              </div>
            @endforelse
          </div>

          {{-- REPLY FORM --}}
          <div class="card-footer bg-white">
            <form method="POST" action="{{ route('supervisor.messages.store', $selectedStudent->user_id) }}" class="d-flex gap-2">
              @csrf
              <input type="text" name="content" class="form-control" placeholder="Type your reply..." maxlength="2000" required autocomplete="off">
              <button class="btn btn-primary px-3" type="submit"><i class="bi bi-send"></i></button>
            </form>
          </div>
        @endif

      </div>
    </div>
  </div>

  <script>
    const t = document.getElementById('supThread');
    if (t) { t.scrollTop = t.scrollHeight; }
  </script>
</div>
@endsection