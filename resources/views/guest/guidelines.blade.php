@extends('guest.layouts.app')

@section('title', 'FYP Guidelines')

@section('content')

  <section class="page-hero">
    <div class="container">
      <p class="eyebrow mb-2">For Students</p>
      <h1 style="color:#fff;">FYP Guidelines</h1>
      <p class="mb-0" style="color:#c7d2fe;">Everything you need to know before starting your Final Year Project.</p>
    </div>
  </section>

  <section class="py-5">
    <div class="container" style="max-width:860px;">

      @php
        $sections = [
          ['bi-people-fill', 'Team Formation', [
            'A team can have a minimum of 1 and a maximum of 4 members.',
            'Each student can be part of only one team at a time.',
            'The student who creates the team automatically becomes the Team Leader.',
            'Members are added by their registered VUID — every member must have a VUFYPMS account.',
          ]],
          ['bi-file-earmark-text-fill', 'Proposal Submission', [
            'Only one proposal per team is allowed at a time.',
            'The proposal must include a clear title, domain, tools and technologies, and an abstract of at least 50 characters.',
            'Proposals are reviewed by your assigned supervisor who may Approve, Reject, or Request Revision.',
            'An approved proposal cannot be modified afterwards.',
          ]],
          ['bi-flag-fill', 'Milestones & Deliverables', [
            'Upon proposal approval, project milestones are defined with due dates.',
            'Your supervisor updates milestone statuses as your work progresses.',
            'Required documents include: Proposal, SRS, Design Document, Progress Report and Final Report.',
            'Documents must be uploaded in PDF, DOC, DOCX or ZIP format (max 20MB per file).',
          ]],
          ['bi-camera-video-fill', 'Defenses & Evaluation', [
            'There are three evaluation phases: Proposal Defense, Progress Review, and Final Defense.',
            'Presentation schedules (date, venue or meeting link, and panel) are published on your portal.',
            'Each phase is marked out of 100 by your supervisor with written feedback.',
            'Missing a scheduled defense without prior notice may affect your evaluation.',
          ]],
          ['bi-shield-check', 'General Rules', [
            'All communication with your supervisor should be done through the built-in messaging system.',
            'Keep an eye on Announcements for deadlines and schedule changes.',
            'Plagiarism in any deliverable leads to immediate rejection per university policy.',
            'For account issues, contact the FYP administration office.',
          ]],
        ];
      @endphp

      @foreach($sections as $i => $sec)
        <div class="p-card p-4 mb-4">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div style="width:44px;height:44px;border-radius:12px;background:#eef2ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:19px;">
              <i class="bi {{ $sec[0] }}"></i>
            </div>
            <h5 class="mb-0">{{ $i + 1 }}. {{ $sec[1] }}</h5>
          </div>
          <ul class="text-muted mb-0" style="font-size:14px;line-height:1.9;">
            @foreach($sec[2] as $rule)
              <li>{{ $rule }}</li>
            @endforeach
          </ul>
        </div>
      @endforeach

      <div class="text-center mt-5">
        <a href="{{ route('register') }}" class="btn btn-brand px-4">Ready? Register Now <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </section>

@endsection