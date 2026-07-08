@extends('student.layouts.app')

@section('title', 'Documents')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

  {{-- PAGE HEADING --}}
  <div class="page-heading">
    <div class="page-heading-copy">
      <span class="page-icon"><i class="bi bi-folder2-open"></i></span>
      <div>
        <p class="eyebrow mb-1">Project</p>
        <h1 class="h3 mb-1">Documents</h1>
        <p class="text-muted mb-0">Upload and manage your FYP project documents.</p>
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
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
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

  {{-- CASE 1: TEAM NAHI --}}
  @if(!$team)
    <div class="panel text-center py-5 mt-3">
      <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
      <h2 class="h5 mb-2">No Team Found</h2>
      <p class="text-muted mb-3">Join or create a team first to upload documents.</p>
      <a href="{{ route('student.team') }}" class="btn btn-primary btn-sm"><i class="bi bi-people me-1"></i>Go to My Team</a>
    </div>

  {{-- CASE 2: TEAM HAI --}}
  @else
    <div class="row g-3 mt-1">
      <div class="col-12 col-xl-7">

        {{-- UPLOAD FORM --}}
        <div class="panel mb-3">
          <div class="panel-header">
            <div>
              <h2 class="h5 mb-1 section-title"><i class="bi bi-upload"></i><span>Upload Document</span></h2>
              <p class="text-muted mb-0">Supported: PDF, DOCX, ZIP (max 20MB)</p>
            </div>
          </div>
          <form method="POST" action="{{ route('student.documents.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">Document Type <span class="text-danger">*</span></label>
                <select class="form-select" name="doc_type" required>
                  <option value="">Select type...</option>
                  <option value="proposal">Proposal</option>
                  <option value="SRS">SRS</option>
                  <option value="design">Design Document</option>
                  <option value="progress_report">Progress Report</option>
                  <option value="final_report">Final Report</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Linked Milestone</label>
                <select class="form-select" name="milestone_id">
                  <option value="">Select milestone (optional)...</option>
                  @foreach($milestones as $ms)
                    <option value="{{ $ms->milestone_id }}">{{ $ms->title }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- UPLOAD BOX --}}
            <div class="text-center p-4 rounded-3 mb-3" id="dropZone"
                 style="border:2px dashed var(--admin-border);background:var(--admin-surface-soft);cursor:pointer;"
                 onclick="document.getElementById('fileInput').click()">
              <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-2"></i>
              <div class="fw-semibold mb-1" id="fileLabel">Click to select a file</div>
              <small class="text-muted">PDF, DOC, DOCX, ZIP up to 20MB</small>
              <input type="file" id="fileInput" name="file" class="d-none" accept=".pdf,.doc,.docx,.zip" required>
            </div>

            <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-upload me-1"></i> Upload Document</button>
          </form>
        </div>

        {{-- UPLOADED DOCUMENTS LIST --}}
        <div class="panel">
          <div class="panel-header">
            <div><h2 class="h5 mb-1 section-title"><i class="bi bi-folder2-open"></i><span>Uploaded Documents</span></h2></div>
          </div>

          @if($documents->isEmpty())
            <div class="text-center py-5 text-muted">
              <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
              <div class="fw-semibold">No documents uploaded yet</div>
              <small>Upload your first document above</small>
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr><th>File</th><th>Type</th><th>Uploaded</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                  @foreach($documents as $doc)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                        <span class="fw-semibold" style="font-size:13px;">{{ basename($doc->file_path) }}</span>
                      </div>
                    </td>
                    <td><span class="badge text-bg-light border">{{ str_replace('_', ' ', ucfirst($doc->doc_type)) }}</span></td>
                    <td><small>{{ \Carbon\Carbon::parse($doc->uploaded_at)->format('d M Y, h:i A') }}</small></td>
                    <td class="text-end">
                      <a class="btn btn-light btn-sm" href="{{ route('student.documents.download', $doc->document_id) }}">
                        <i class="bi bi-download"></i>
                      </a>
                      <form method="POST" action="{{ route('student.documents.destroy', $doc->document_id) }}" class="d-inline"
                            onsubmit="return confirm('Delete this document permanently?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- RIGHT: REQUIRED DOCUMENTS CHECKLIST --}}
      <div class="col-12 col-xl-5">
        <div class="panel">
          <div class="panel-header"><h2 class="h5 mb-0 section-title"><i class="bi bi-list-check"></i><span>Required Documents</span></h2></div>
          <div class="d-flex flex-column gap-2">
            @foreach($requiredDocs as $type => $label)
              @if($uploadedTypes->contains($type))
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                  <i class="bi bi-check-circle-fill text-success"></i>
                  <span style="font-size:13px;">{{ $label }}</span>
                  <span class="badge text-bg-success ms-auto">Done</span>
                </div>
              @else
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:var(--admin-surface-soft);border:1px solid var(--admin-border);">
                  <i class="bi bi-circle text-muted"></i>
                  <span style="font-size:13px;">{{ $label }}</span>
                  <span class="badge text-bg-secondary ms-auto">Pending</span>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- File select hone par uska naam box mein dikhao --}}
    <script>
      document.getElementById('fileInput').addEventListener('change', function () {
        if (this.files.length > 0) {
          document.getElementById('fileLabel').textContent = this.files[0].name;
          document.getElementById('dropZone').style.borderColor = '#6366f1';
        }
      });
    </script>
  @endif

</div>
@endsection