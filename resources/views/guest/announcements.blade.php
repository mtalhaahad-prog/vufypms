@extends('guest.layouts.app')

@section('title', 'Announcements')

@section('content')

  <section class="page-hero">
    <div class="container">
      <p class="eyebrow mb-2">Notice Board</p>
      <h1 style="color:#fff;">Announcements</h1>
      <p class="mb-0" style="color:#c7d2fe;">Official notices from the FYP administration.</p>
    </div>
  </section>

  <section class="py-5">
    <div class="container" style="max-width:860px;">
      @forelse($announcements as $ann)
        <div class="p-card p-4 mb-3">
          <div class="d-flex gap-3">
            <div style="width:44px;height:44px;border-radius:12px;background:#eef2ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="bi bi-megaphone-fill"></i>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                <h5 class="mb-1">{{ $ann->title }}</h5>
                @if(\Carbon\Carbon::parse($ann->published_at)->gt(now()->subDays(3)))
                  <span class="badge" style="background:#eef2ff;color:#4f46e5;">New</span>
                @endif
              </div>
              <p class="text-muted mb-2" style="font-size:14px;line-height:1.7;">{{ $ann->body }}</p>
              <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($ann->published_at)->format('d F Y') }}</small>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-5 text-muted">
          <i class="bi bi-megaphone fs-1 d-block mb-3"></i>
          <h5>No announcements yet</h5>
        </div>
      @endforelse
    </div>
  </section>

@endsection