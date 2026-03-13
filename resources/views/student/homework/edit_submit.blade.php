@extends('layouts.app')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endsection

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Resubmit Homework</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('student/my_submitted_homework') }}"
                                   class="text-muted text-decoration-none">Back to Submitted List</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            {{-- Original Assignment Details --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-info-circle-fill text-info"></i>
                    <h6 class="mb-0 fw-semibold">Original Assignment</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <div class="text-muted small mb-1">Subject</div>
                                    <div class="fw-semibold text-dark">{{ $getHomework->subject_name }}</div>
                                </div>
                                <div>
                                    <div class="text-muted small mb-1">Homework Date</div>
                                    <div class="fw-semibold text-dark">
                                        {{ date('d M Y', strtotime($getHomework->homework_date)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-muted small mb-1">Submission Deadline</div>
                                    <div class="fw-semibold text-dark d-flex align-items-center gap-2">
                                        {{ date('d M Y', strtotime($getHomework->submission_date)) }}
                                        @php
                                            $dl  = \Carbon\Carbon::parse($getHomework->submission_date)->endOfDay();
                                            $daysLeft = (int)\Carbon\Carbon::now()->diffInDays($dl, false);
                                        @endphp
                                        @if($daysLeft > 0)
                                            <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.68rem;">
                                                {{ $daysLeft }}d left
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($getHomework->document_file))
                                    <div>
                                        <div class="text-muted small mb-1">Teacher's Document</div>
                                        <a href="{{ asset('upload/homework/'.$getHomework->document_file) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-info px-3">
                                            <i class="bi bi-eye me-1"></i>View Document
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="text-muted small mb-2">Teacher's Instructions</div>
                            <div class="p-3 rounded-2 border bg-light text-secondary" style="line-height:1.7;">
                                {!! $getHomework->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Update Submission Form --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-pencil-fill text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Update Your Submission</h6>
                </div>

                <form method="POST"
                      action="{{ url('student/homework/edit_submit/'.$getHomework->id) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">

                            {{-- Document upload --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Replace Document <span class="text-muted">(optional)</span>
                                </label>
                                <input type="file" name="document_file" class="form-control"
                                       accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                @if(!empty($getSubmission->document_file))
                                    <div class="mt-2 p-2 rounded-2 bg-light border d-flex align-items-center gap-2"
                                         style="font-size:.78rem;">
                                        <i class="bi bi-file-earmark-fill text-primary"></i>
                                        <span class="text-muted">Previous file attached</span>
                                        <a href="{{ asset('upload/homework/'.$getSubmission->document_file) }}"
                                           target="_blank"
                                           class="btn btn-xs btn-outline-primary ms-auto px-2"
                                           style="font-size:.7rem;padding:2px 8px;">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    </div>
                                    <div class="text-warning small mt-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Uploading a new file will replace your previous submission.
                                    </div>
                                @endif
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">
                                    Your Description / Notes <span class="text-danger">*</span>
                                </label>
                                <textarea name="message" id="compose-textarea"
                                          class="form-control" required>{{ $getSubmission->description }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>Description is required.
                        </span>
                        <div class="d-flex gap-2">
                            <a href="{{ url('student/my_submitted_homework') }}"
                               class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                <i class="bi bi-arrow-repeat me-2"></i>Update Submission
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
$(document).ready(function () {
    $('#compose-textarea').summernote({
        height: 200,
        toolbar: [
            ['font',  ['bold','underline','italic','clear']],
            ['para',  ['ul','ol']],
            ['view',  ['fullscreen','codeview']]
        ]
    });
});
</script>
@endsection