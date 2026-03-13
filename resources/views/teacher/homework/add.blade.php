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
                            <i class="bi bi-clipboard2-plus-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Add New Homework</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('teacher/homework/homework') }}" class="text-muted text-decoration-none">Back to Homework</a>
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
                    <i class="bi bi-pencil-fill text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Homework Details</h6>
                </div>

                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Class <span class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror"
                                        name="class_id" id="getClass" required>
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Subject <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject_id') is-invalid @enderror"
                                        name="subject_id" id="getSubject" required>
                                    <option value="">— Select Subject —</option>
                                </select>
                                @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Homework Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="date" name="homework_date" id="homework_date"
                                           value="{{ old('homework_date') }}" required
                                           class="form-control @error('homework_date') is-invalid @enderror">
                                    <span class="input-group-text" onclick="document.getElementById('homework_date').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                    @error('homework_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Submission Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="date" name="submission_date" id="submission_date"
                                           value="{{ old('submission_date') }}" required
                                           class="form-control @error('submission_date') is-invalid @enderror">
                                    <span class="input-group-text" onclick="document.getElementById('submission_date').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                    @error('submission_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Document</label>
                                <input type="file" class="form-control @error('document_file') is-invalid @enderror"
                                       name="document_file">
                                @error('document_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="text-muted mt-1" style="font-size:.72rem;">
                                    <i class="bi bi-info-circle me-1"></i>Attach a PDF, Word, or image file (optional)
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">Description <span class="text-danger">*</span></label>
                                <textarea name="message" id="compose_textarea"
                                          class="form-control @error('message') is-invalid @enderror"></textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                        <a href="{{ url('teacher/homework/homework') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning px-5 fw-semibold text-white">
                            <i class="bi bi-check-circle-fill me-2"></i>Submit Homework
                        </button>
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
    $('#compose_textarea').summernote({ height: 200, placeholder: 'Enter homework description…' });

    $('#getClass').on('change', function () {
        var class_id = $(this).val();
        if (class_id) {
            $.ajax({
                type: 'POST',
                url: '{{ url("teacher/ajax_get_subject") }}',
                data: { class_id: class_id, _token: '{{ csrf_token() }}' },
                dataType: 'json',
                success: function (response) { $('#getSubject').html(response.success); },
                error:   function ()          { $('#getSubject').html('<option value="">Select Subject</option>'); }
            });
        } else {
            $('#getSubject').html('<option value="">— Select Subject —</option>');
        }
    });
});
</script>
@endsection