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
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Edit Homework</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/homework/homework') }}" class="text-muted text-decoration-none">Back to Homework List</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                        <i class="bi bi-building me-1"></i>{{ $getRecord->class_name ?? '' }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-journal-bookmark me-1"></i>{{ $getRecord->subject_name ?? '' }}
                    </span>
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
                    <h6 class="mb-0 fw-semibold">Edit Homework Details</h6>
                </div>

                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">

                            {{-- Class --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Class <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('class_id') is-invalid @enderror"
                                        name="class_id" required id="getClass">
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option {{ $getRecord->class_id == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Subject --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('subject_id') is-invalid @enderror"
                                        name="subject_id" required id="getSubject">
                                    <option value="">— Select Subject —</option>
                                    @foreach($getSubject as $subject)
                                        <option {{ $getRecord->subject_id == $subject->subject_id ? 'selected' : '' }}
                                                value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Homework Date --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    Homework Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="homework_date" id="homework_date"
                                       value="{{ $getRecord->homework_date }}" required
                                       class="form-control @error('homework_date') is-invalid @enderror">
                                @error('homework_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Submission Date --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    Submission Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="submission_date" id="submission_date"
                                       value="{{ $getRecord->submission_date }}" required
                                       class="form-control @error('submission_date') is-invalid @enderror">
                                @error('submission_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Document Upload --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Replace Document <span class="text-muted">(optional)</span>
                                </label>
                                <input type="file" name="document_file"
                                       class="form-control @error('document_file') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                                @error('document_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if(!empty($getRecord->document_file))
                                    <div class="mt-2 p-2 rounded-2 bg-light border d-flex align-items-center gap-2"
                                         style="font-size:.78rem;">
                                        <i class="bi bi-file-earmark-fill text-primary"></i>
                                        <span class="text-muted">Current file attached</span>
                                        <a href="{{ asset('upload/homework/' . $getRecord->document_file) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary ms-auto px-2"
                                           style="font-size:.7rem;padding:2px 8px;">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Description (Summernote) --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">
                                    Description / Instructions <span class="text-danger">*</span>
                                </label>
                                <textarea name="message" id="compose_textarea"
                                          class="form-control @error('message') is-invalid @enderror"
                                          required>{{ $getRecord->description }}</textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                        <div class="d-flex gap-2">
                            <a href="{{ url('admin/homework/homework') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                <i class="bi bi-floppy-fill me-2"></i>Update Homework
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
        height: 220,
        placeholder: 'Write homework instructions here…',
        toolbar: [
            ['style',   ['style']],
            ['font',    ['bold', 'underline', 'italic', 'clear']],
            ['fontsize',['fontsize']],
            ['color',   ['color']],
            ['para',    ['ul', 'ol', 'paragraph']],
            ['insert',  ['link']],
            ['view',    ['fullscreen', 'codeview']]
        ]
    });

    $('#getClass').on('change', function () {
        var class_id = $(this).val();
        if (class_id) {
            $('#getSubject').html('<option value="">Loading…</option>').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/ajax_get_subject") }}',
                data: { class_id: class_id, _token: '{{ csrf_token() }}' },
                dataType: 'json',
                success: function (response) {
                    $('#getSubject').html(response.success).prop('disabled', false);
                },
                error: function () {
                    $('#getSubject').html('<option value="">Error loading subjects</option>').prop('disabled', false);
                }
            });
        } else {
            $('#getSubject').html('<option value="">— Select Subject —</option>');
        }
    });
});
</script>
@endsection