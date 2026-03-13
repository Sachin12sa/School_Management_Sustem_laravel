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
                        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-upload"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Submit Homework</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('student/my_homework') }}"
                                   class="text-muted text-decoration-none">Back to My Homework</a>
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

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-upload text-success"></i>
                    <h6 class="mb-0 fw-semibold">Your Submission</h6>
                </div>

                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">

                            {{-- Document upload --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Attach Document <span class="text-muted">(optional)</span>
                                </label>
                                <input type="file" name="document_file"
                                       class="form-control @error('document_file') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                <div class="text-muted mt-1" style="font-size:.72rem;">
                                    Accepted: PDF, DOC, DOCX, PNG, JPG
                                </div>
                                @error('document_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description (Summernote) --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">
                                    Description / Notes <span class="text-danger">*</span>
                                </label>
                                <textarea name="message" id="compose_textarea"
                                          class="form-control @error('message') is-invalid @enderror"
                                          required></textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>Description is required.
                        </span>
                        <div class="d-flex gap-2">
                            <a href="{{ url('student/my_homework') }}"
                               class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-semibold">
                                <i class="bi bi-upload me-2"></i>Submit Homework
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
    $('#compose_textarea').summernote({
        height: 200,
        placeholder: 'Write your submission notes or answers here…',
        toolbar: [
            ['font',  ['bold','underline','italic','clear']],
            ['para',  ['ul','ol']],
            ['view',  ['fullscreen','codeview']]
        ]
    });
});
</script>
@endsection