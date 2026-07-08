@extends('guest.layouts.app')

@section('title', 'Approved Projects')

@section('content')

  <section class="page-hero">
    <div class="container">
      <p class="eyebrow mb-2">Showcase</p>
      <h1 style="color:#fff;">Approved Projects</h1>
      <p class="mb-3" style="color:#c7d2fe;">Explore FYP projects approved at Virtual University.</p>

      {{-- SEARCH BAR --}}
      <form method="GET" action="{{ route('guest.projects') }}" class="d-flex gap-2" style="max-width:520px;">
        <input type="text" name="search" class="form-control" placeholder="Search by title, domain or technology..."
               value="{{ $search }}" style="border-radius:10px;">
        <button class="btn btn-brand px-4" type="submit"><i class="bi bi-search"></i></button>
      </form>
    </div>
  </section>

  <section class="py-5">
    <div class="container">
      @if($search)
        <p class="text-muted mb-4">Showing {{ $projects->count() }} result(s) for "<strong>{{ $search }}</strong>"
          · <a href="{{ route('guest.projects') }}">Clear search</a></p>
      @endif

      @if($projects->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
          <h5>No projects found</h5>
          <p style="font-size:14px;">Approved projects will be showcased here.</p>
        </div>
      @else
        <div class="row g-4">
          @foreach($projects as $p)
            <div class="col-md-6 col-lg-4">
              <div class="p-card p-4 h-100 d-flex flex-column">
                <span class="badge align-self-start mb-3" style="background:#eef2ff;color:#4f46e5;">{{ $p->domain }}</span>
                <h5 style="font-size:17px;">{{ $p->title }}</h5>
                <p class="text-muted flex-grow-1" style="font-size:13px;line-height:1.7;">{{ Str::limit($p->abstract, 140) }}</p>
                <div class="pt-3 mt-2" style="border-top:1px solid var(--border);font-size:12px;">
                  <div class="text-muted mb-1"><i class="bi bi-people me-2" style="color:#4f46e5;"></i>{{ $teamNames[$p->team_id] ?? 'Team' }}</div>
                  <div class="text-muted"><i class="bi bi-tools me-2" style="color:#4f46e5;"></i>{{ Str::limit($p->tools, 45) }}</div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

@endsection