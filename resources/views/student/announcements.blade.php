@extends('student.layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-megaphone"></i></span>
      <div>
        <p class="eyebrow mb-1">Communication</p>
        <h1 class="h3 mb-1">Announcements</h1>
        <p class="text-muted mb-0">Important notices from the administration.</p>
      </div>
    </div>
  </div>

  <div class="panel mt-2">
    <div class="panel-header">
      <div>
        <h2 class="h5 mb-1 section-title"><i class="bi bi-megaphone"></i><span>All Announcements</span></h2>
        <p class="text-muted mb-0">{{ $announcements->count() }} {{ Str::plural('announcement', $announcements->count()) }}</p>
      </div>
    </div>

    @forelse($announcements as $ann)
      <div style="display:flex;align-items:flex-start;gap:14px;padding:16px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--admin-border);' : '' }}">
        <div style="width:42px;height:42px;border-radius:10px;background:#eef2ff;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
          <i class="bi bi-megaphone"></i>
        </div>
        <div class="flex-grow-1">
          <div class="fw-semibold mb-1">{{ $ann->title }}</div>
          <p class="text-muted mb-2" style="font-size:13px;line-height:1.6;">{{ $ann->body }}</p>
          <small class="text-muted">
            <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($ann->published_at)->format('d M Y') }}
            · Posted by {{ $adminNames[$ann->admin_id] ?? 'Administration' }}
          </small>
        </div>
        {{-- 3 din ke andar wali announcement par "New" badge --}}
        @if(\Carbon\Carbon::parse($ann->published_at)->gt(now()->subDays(3)))
          <span class="badge text-bg-primary">New</span>
        @endif
      </div>
    @empty
      <div class="text-center py-5 text-muted">
        <i class="bi bi-megaphone fs-1 d-block mb-3"></i>
        <div class="fw-semibold">No announcements yet</div>
        <small>Notices from the administration will appear here</small>
      </div>
    @endforelse
  </div>

</div>
@endsection