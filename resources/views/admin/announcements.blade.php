@extends('admin.layouts.app')

@section('title', 'Announcements')

@section('content')

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Announcements</h1>
      <small class="text-muted">Publish notices — visible to all students instantly</small>
    </div>
  </div>

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

  <div class="row">

    {{-- LEFT: PUBLISH FORM --}}
    <div class="col-lg-5">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bullhorn mr-2"></i>Publish New Announcement</h6>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf
            <div class="form-group">
              <label>Title <span class="text-danger">*</span></label>
              <input class="form-control" type="text" name="title" value="{{ old('title') }}"
                     placeholder="e.g. Final Defense Schedule Released" maxlength="200" required>
            </div>
            <div class="form-group">
              <label>Message <span class="text-danger">*</span></label>
              <textarea class="form-control" name="body" rows="5" maxlength="2000" required
                        placeholder="Write the announcement details here...">{{ old('body') }}</textarea>
            </div>
            <button class="btn btn-primary btn-block" type="submit">
              <i class="fas fa-paper-plane mr-1"></i>Publish Announcement
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- RIGHT: PUBLISHED LIST --}}
    <div class="col-lg-7">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Published ({{ $announcements->count() }})</h6>
        </div>
        <div class="card-body">
          @if($announcements->isEmpty())
            <div class="text-center py-5 text-muted">
              <i class="fas fa-bullhorn fa-2x mb-3 d-block"></i>No announcements yet
            </div>
          @else
            @foreach($announcements as $ann)
              <div class="d-flex align-items-start py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="rounded d-flex align-items-center justify-content-center mr-3"
                     style="width:40px;height:40px;background:#eef2ff;color:#4f46e5;flex-shrink:0;">
                  <i class="fas fa-bullhorn"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="font-weight-bold" style="font-size:14px;">{{ $ann->title }}</div>
                  <p class="text-muted mb-1" style="font-size:13px;line-height:1.5;">{{ $ann->body }}</p>
                  <small class="text-muted"><i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($ann->published_at)->format('d M Y, h:i A') }}</small>
                </div>
                <form method="POST" action="{{ route('admin.announcements.destroy', $ann->announcement_id) }}"
                      onsubmit="return confirm('Delete this announcement?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>

  </div>

@endsection