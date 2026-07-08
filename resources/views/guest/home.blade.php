@extends('guest.layouts.app')

@section('title', 'Home')

@push('styles')
<style>
  .hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #312e81 100%);
    color: #fff;
    padding: 90px 0 100px;
    position: relative;
    overflow: hidden;
  }
  .hero::before, .hero::after {
    content: ''; position: absolute; border-radius: 50%;
    background: rgba(99,102,241,0.15);
  }
  .hero::before { width: 420px; height: 420px; right: -120px; top: -140px; }
  .hero::after  { width: 300px; height: 300px; left: -100px; bottom: -160px; }
  .hero h1 { font-size: 46px; font-weight: 900; line-height: 1.15; }
  .hero .lead-text { color: #c7d2fe; font-size: 17px; max-width: 540px; }
  .hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(99,102,241,0.2); border: 1px solid rgba(129,140,248,0.4);
    color: #c7d2fe; font-size: 12px; font-weight: 600;
    padding: 6px 14px; border-radius: 99px;
  }
  .stat-box {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px; padding: 20px; text-align: center;
    backdrop-filter: blur(8px);
  }
  .stat-box .num { font-size: 30px; font-weight: 900; color: #fff; }
  .stat-box .lbl { font-size: 12px; color: #a5b4fc; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
  .feature-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin-bottom: 16px;
  }
  .step-num {
    width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800;
  }
</style>
@endpush

@section('content')

  {{-- ================= HERO ================= --}}
  <section class="hero">
    <div class="container" style="position:relative;z-index:1;">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <span class="hero-badge mb-3"><i class="bi bi-stars"></i>Virtual University of Pakistan</span>
          <h1 class="mt-3 mb-3">Manage Your Final Year Project,<br>From Proposal to Defense.</h1>
          <p class="lead-text mb-4">
            VUFYPMS streamlines the complete FYP lifecycle — team formation, proposal approval,
            milestone tracking, document submission and evaluations — all in one place.
          </p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-brand btn-lg px-4">
              <i class="bi bi-rocket-takeoff me-2"></i>Get Started
            </a>
            <a href="{{ route('guest.guidelines') }}" class="btn btn-lg px-4" style="border:1px solid rgba(255,255,255,.3);color:#fff;">
              <i class="bi bi-journal-text me-2"></i>Read Guidelines
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="row g-3">
            <div class="col-6"><div class="stat-box"><div class="num">{{ $stats['students'] }}</div><div class="lbl">Students</div></div></div>
            <div class="col-6"><div class="stat-box"><div class="num">{{ $stats['teams'] }}</div><div class="lbl">Teams</div></div></div>
            <div class="col-12"><div class="stat-box"><div class="num">{{ $stats['projects'] }}</div><div class="lbl">Approved Projects</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ================= FEATURES ================= --}}
  <section class="py-5" style="background:#f8fafd;">
    <div class="container py-4">
      <div class="text-center mb-5">
        <h2>Everything Your FYP Needs</h2>
        <p class="text-muted">One platform for students, supervisors and administrators.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="p-card p-4 h-100">
            <div class="feature-icon" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-people-fill"></i></div>
            <h5>Team Formation</h5>
            <p class="text-muted mb-0" style="font-size:14px;">Create your team, add members by VUID, and get a supervisor assigned — all online.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-card p-4 h-100">
            <div class="feature-icon" style="background:#f0fdf4;color:#059669;"><i class="bi bi-file-earmark-check-fill"></i></div>
            <h5>Proposal Workflow</h5>
            <p class="text-muted mb-0" style="font-size:14px;">Submit proposals, receive supervisor feedback, revise and get approved with full transparency.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-card p-4 h-100">
            <div class="feature-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-flag-fill"></i></div>
            <h5>Milestone Tracking</h5>
            <p class="text-muted mb-0" style="font-size:14px;">Track every phase from SRS to Final Defense with live progress updates.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-card p-4 h-100">
            <div class="feature-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-cloud-upload-fill"></i></div>
            <h5>Document Management</h5>
            <p class="text-muted mb-0" style="font-size:14px;">Upload deliverables securely and keep everything organized per milestone.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-card p-4 h-100">
            <div class="feature-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-chat-dots-fill"></i></div>
            <h5>Direct Messaging</h5>
            <p class="text-muted mb-0" style="font-size:14px;">Built-in chat between students and supervisors — no more lost emails.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-card p-4 h-100">
            <div class="feature-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-bar-chart-fill"></i></div>
            <h5>Evaluations &amp; Defense</h5>
            <p class="text-muted mb-0" style="font-size:14px;">Phase-wise marks, feedback and defense scheduling in one dashboard.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ================= HOW IT WORKS ================= --}}
  <section class="py-5">
    <div class="container py-4">
      <div class="text-center mb-5">
        <h2>How It Works</h2>
        <p class="text-muted">Four simple steps from registration to defense.</p>
      </div>
      <div class="row g-4">
        @php
          $steps = [
            ['Register & Form Team', 'Sign up with your VUID and create or join a team of up to 4 members.'],
            ['Submit Proposal', 'Define your project title, domain, tools and abstract for supervisor review.'],
            ['Build & Track', 'Work through milestones, upload documents and stay in sync with your supervisor.'],
            ['Defend & Graduate', 'Attend scheduled defenses, receive evaluations and complete your FYP.'],
          ];
        @endphp
        @foreach($steps as $i => $s)
          <div class="col-md-6 col-lg-3">
            <div class="d-flex gap-3">
              <div class="step-num">{{ $i + 1 }}</div>
              <div>
                <h6 class="mb-1">{{ $s[0] }}</h6>
                <p class="text-muted mb-0" style="font-size:13px;">{{ $s[1] }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ================= LATEST ANNOUNCEMENTS ================= --}}
  @if($announcements->count() > 0)
  <section class="py-5" style="background:#f8fafd;">
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Latest Announcements</h2>
        <a href="{{ route('guest.announcements') }}" class="btn btn-ghost btn-sm">View All</a>
      </div>
      <div class="row g-3">
        @foreach($announcements as $ann)
          <div class="col-md-4">
            <div class="p-card p-4 h-100">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-megaphone-fill" style="color:#4f46e5;"></i>
                <small class="text-muted">{{ \Carbon\Carbon::parse($ann->published_at)->format('d M Y') }}</small>
              </div>
              <h6>{{ $ann->title }}</h6>
              <p class="text-muted mb-0" style="font-size:13px;">{{ Str::limit($ann->body, 100) }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ================= CTA ================= --}}
  <section class="py-5">
    <div class="container py-4">
      <div class="p-5 text-center rounded-4" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;">
        <h2 class="mb-2" style="color:#fff;">Ready to Start Your FYP Journey?</h2>
        <p class="mb-4" style="color:#c7d2fe;">Join your fellow students on VUFYPMS today.</p>
        <a href="{{ route('register') }}" class="btn btn-lg px-5" style="background:#fff;color:#4f46e5;font-weight:700;">
          Create Your Account <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </section>

@endsection