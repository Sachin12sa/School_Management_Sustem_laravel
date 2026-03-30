@extends('layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    <style>
        .section-checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 6px;
        }

        .section-check-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            cursor: pointer;
            font-size: .8rem;
            font-weight: 600;
            color: #374151;
            background: #fff;
            transition: all .15s;
            user-select: none;
        }

        .section-check-pill:hover {
            border-color: #6366f1;
            background: #eef2ff;
            color: #4338ca;
        }

        .section-check-pill input[type="checkbox"] {
            display: none;
        }

        .section-check-pill.checked {
            border-color: #6366f1;
            background: #eef2ff;
            color: #4338ca;
        }

        .section-check-pill.all-pill.checked {
            border-color: #f59e0b;
            background: #fef3c7;
            color: #92400e;
        }

        #section-loading,
        #subject-loading-msg {
            display: none;
            font-size: .78rem;
            color: #6b7280;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-journal-plus"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Add New Homework</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('teacher/homework/homework') }}"
                                        class="text-muted text-decoration-none">Back to Homework List</a>
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
                        <i class="bi bi-journal-plus text-secondary"></i>
                        <h6 class="mb-0 fw-semibold">Homework Details</h6>
                    </div>

                    <form method="POST" action="{{ url('teacher/homework/homework/add') }}" enctype="multipart/form-data"
                        id="homework-form">
                        @csrf

                        {{-- Hidden field to carry selected section_ids as JSON --}}
                        <input type="hidden" name="section_ids" id="f-section-ids" value="">

                        <div class="card-body">
                            <div class="row g-4">

                                {{-- ── Step 1: Class ── --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Class <span class="text-danger">*</span>
                                    </label>
                                    <select name="class_id" id="sel-class"
                                        class="form-select @error('class_id') is-invalid @enderror" required>
                                        <option value="">— Select Class —</option>
                                        @foreach ($getClass as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- ── Step 2: Section (checkboxes) ── --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Section
                                        <span class="text-muted" style="font-size:.7rem;">(select one or All)</span>
                                    </label>

                                    <div id="section-loading">
                                        <span class="spinner-border spinner-border-sm text-secondary me-1"></span>
                                        Loading sections…
                                    </div>

                                    <div class="section-checkbox-group" id="section-pills">
                                        <span class="text-muted small">Select a class first</span>
                                    </div>
                                </div>

                                {{-- ── Step 3: Subject ── --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Subject <span class="text-danger">*</span>
                                    </label>

                                    <div id="subject-loading-msg">
                                        <span class="spinner-border spinner-border-sm text-secondary me-1"></span>
                                        Loading subjects…
                                    </div>

                                    <select name="subject_id" id="sel-subject"
                                        class="form-select @error('subject_id') is-invalid @enderror" required>
                                        <option value="">— Select class first —</option>
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Homework Date --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Homework Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="homework_date"
                                        class="form-control @error('homework_date') is-invalid @enderror"
                                        value="{{ old('homework_date', date('Y-m-d')) }}" required>
                                    @error('homework_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Submission Date --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Submission Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="submission_date"
                                        class="form-control @error('submission_date') is-invalid @enderror"
                                        value="{{ old('submission_date') }}" required>
                                    @error('submission_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Document Upload --}}
                                <div class="col-md-4">
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

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Description / Instructions <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="message" id="compose_textarea" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>Fields marked
                                <span class="text-danger">*</span> are required.
                            </span>
                            <div class="d-flex gap-2">
                                <a href="{{ url('teacher/homework/homework') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-secondary px-4 fw-semibold">
                                    <i class="bi bi-journal-plus me-2"></i>Assign Homework
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
        $(document).ready(function() {

            // ── Summernote ───────────────────────────────────────────────
            $('#compose_textarea').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // ── Before submit — encode checked sections into hidden field ─
            $('#homework-form').on('submit', function() {
                var checked = [];
                $('#section-pills input[type="checkbox"]:checked').each(function() {
                    checked.push($(this).val());
                });
                $('#f-section-ids').val(JSON.stringify(checked));
            });

            // ── STEP 1: Class changes ────────────────────────────────────
            $('#sel-class').on('change', function() {
                var classId = $(this).val();

                // Reset section pills and subject
                $('#section-pills').html('<span class="text-muted small">Loading…</span>');
                $('#sel-subject').html('<option value="">— Select class first —</option>').prop('disabled',
                    true);

                if (!classId) {
                    $('#section-pills').html('<span class="text-muted small">Select a class first</span>');
                    return;
                }

                // Load sections
                $('#section-loading').show();
                $.post('{{ url('teacher/class_timetable/get_sections') }}', {
                    _token: '{{ csrf_token() }}',
                    class_id: classId
                }, function(res) {
                    $('#section-loading').hide();
                    buildSectionPills(res.sections || []);
                    // Load subjects immediately after sections load
                    loadSubjects(classId);
                });
            });

            // ── Build section pill checkboxes ────────────────────────────
            function buildSectionPills(sections) {
                var $container = $('#section-pills');
                $container.html('');

                if (sections.length === 0) {
                    // No sections — show "No Section" info, nothing to select
                    $container.html(
                        '<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">' +
                        '<i class="bi bi-info-circle me-1"></i>No sections for this class</span>'
                    );
                    return;
                }

                // Individual section pills
                sections.forEach(function(sec) {
                    var $pill = $(
                        '<label class="section-check-pill">' +
                        '<input type="checkbox" value="' + sec.id + '"> ' +
                        'Section ' + sec.name +
                        '</label>'
                    );
                    $container.append($pill);
                });

                // ── Pill toggle logic ────────────────────────────────────
                // Inside buildSectionPills — update the click handler
                $container.off('click', '.section-check-pill').on('click', '.section-check-pill', function(e) {
                    e.preventDefault();
                    var $pill = $(this);
                    var $checkbox = $pill.find('input[type="checkbox"]');
                    var isAll = $pill.hasClass('all-pill');

                    if (isAll) {
                        var willCheck = !$checkbox.prop('checked');
                        $checkbox.prop('checked', willCheck);
                        $pill.toggleClass('checked', willCheck);

                        if (willCheck) {
                            $container.find('.section-check-pill:not(.all-pill)').each(function() {
                                $(this).find('input').prop('checked', false);
                                $(this).removeClass('checked');
                            });
                        }
                    } else {
                        var willCheck = !$checkbox.prop('checked');
                        $checkbox.prop('checked', willCheck);
                        $pill.toggleClass('checked', willCheck);

                        $container.find('.all-pill input').prop('checked', false);
                        $container.find('.all-pill').removeClass('checked');

                        var anyChecked = $container.find('.section-check-pill:not(.all-pill) input:checked')
                            .length > 0;
                        if (!anyChecked) {
                            $container.find('.all-pill input').prop('checked', true);
                            $container.find('.all-pill').addClass('checked');
                        }
                    }

                    // ── Reload subjects based on new section selection ──
                    var classId = $('#sel-class').val();
                    if (classId) {
                        loadSubjects(classId, null); // null = auto-detect from checked pills
                    }
                });
            }

            // ── Load subjects ────────────────────────────────────────────
            // ── Load subjects based on class + selected section ──────────
            function loadSubjects(classId, sectionId) {
                $('#subject-loading-msg').show();
                $('#sel-subject').hide();

                // Determine which section to send
                var sendSection = '';
                if (sectionId && sectionId !== 'all') {
                    sendSection = sectionId;
                } else {
                    // Check if any individual pill is checked
                    var checkedIndividual = [];
                    $('#section-pills .section-check-pill:not(.all-pill) input:checked').each(function() {
                        checkedIndividual.push($(this).val());
                    });
                    // If exactly one individual section checked, filter by it
                    // If multiple or All, send empty (get all subjects)
                    if (checkedIndividual.length === 1) {
                        sendSection = checkedIndividual[0];
                    }
                }

                $.post('{{ url('teacher/homework/ajax_get_subject') }}', {
                    _token: '{{ csrf_token() }}',
                    class_id: classId,
                    section_id: sendSection
                }, function(res) {
                    $('#subject-loading-msg').hide();
                    $('#sel-subject')
                        .show()
                        .prop('disabled', false)
                        .html(res.subject_html);
                }).fail(function() {
                    $('#subject-loading-msg').hide();
                    $('#sel-subject')
                        .show()
                        .prop('disabled', false)
                        .html('<option value="">Failed to load subjects</option>');
                });
            }

        });
    </script>
@endsection
