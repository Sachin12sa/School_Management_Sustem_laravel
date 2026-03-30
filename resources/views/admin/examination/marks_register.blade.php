@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">

                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Marks Register</h4>
                                <span class="text-muted small">Select exam &amp; class to enter student marks</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-danger"></i>
                        <h6 class="mb-0 fw-semibold">Search Marks Register</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="GET" action="{{ url('admin/examination/marks_register') }}">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-mortarboard me-1"></i>Exam Name <span class="text-danger">*</span>
                                    </label>
                                    <select name="exam_id" required class="form-select">
                                        <option value="">— Select Exam —</option>
                                        @foreach ($getExam as $exam)
                                            <option {{ Request::get('exam_id') == $exam->id ? 'selected' : '' }}
                                                value="{{ $exam->id }}">{{ $exam->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-building me-1"></i>Class <span class="text-danger">*</span>
                                    </label>
                                    <select name="class_id" id="mr_class_id" required class="form-select">
                                        <option value="">— Select Class —</option>
                                        @foreach ($getClass as $class)
                                            <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Section Filter — loads via AJAX when class changes --}}
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-diagram-3 me-1" style="color:#7c3aed;"></i>Section
                                        <span class="text-muted" style="font-size:.7rem;">(optional)</span>
                                    </label>
                                    <select name="section_id" id="mr_section_id" class="form-select"
                                        {{ !Request::get('class_id') ? 'disabled' : '' }}>
                                        <option value="">— All Sections —</option>
                                        @if (Request::get('class_id'))
                                            @foreach (\App\Models\ClassSectionModel::getSectionsByClass(Request::get('class_id')) as $sec)
                                                <option value="{{ $sec->id }}"
                                                    {{ Request::get('section_id') == $sec->id ? 'selected' : '' }}>
                                                    Section {{ $sec->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="form-text small text-muted" id="mr-section-hint">
                                        Select class first
                                    </div>
                                </div>

                                <div class="col-md-2 d-flex gap-2 align-self-end">
                                    <button type="submit" class="btn btn-danger flex-fill fw-semibold">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url('admin/examination/marks_register') }}"
                                        class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        {{-- AJAX message banner --}}
        <div id="ajax-response-message" class="alert mx-3" style="display:none;"></div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                @if (!empty($getSubject) && $getSubject->count() > 0)

                    <form action="{{ url('admin/examination/submit_marks_register') }}" method="post"
                        id="SubmitMarksForm">
                        @csrf

                        <div class="card border-0 shadow-sm">
                            <div
                                class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-pencil-square me-2 text-danger"></i>Marks Entry
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    @if (Request::get('exam_id'))
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            {{ $getExam->firstWhere('id', Request::get('exam_id'))->name ?? '' }}
                                        </span>
                                    @endif
                                    @if (Request::get('class_id'))
                                        <span class="badge bg-info bg-opacity-10 text-info px-3">
                                            <i class="bi bi-building me-1"></i>
                                            {{ $getClass->firstWhere('id', Request::get('class_id'))->name ?? '' }}
                                        </span>
                                    @endif
                                    @if (Request::get('section_id'))
                                        @php $secName = \App\Models\ClassSectionModel::getSingle(Request::get('section_id')); @endphp
                                        <span class="badge px-3" style="background:#ede9fe;color:#7c3aed;">
                                            <i class="bi bi-diagram-3 me-1"></i>Section {{ $secName->name ?? '' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-0" style="min-width:900px;">
                                        <thead>
                                            <tr class="table-light text-uppercase text-secondary"
                                                style="font-size:.7rem;letter-spacing:.05em;">
                                                <th class="ps-3" style="min-width:160px;">Student</th>
                                                @foreach ($getSubject as $subject)
                                                    <th style="min-width:160px;" class="text-center">
                                                        <div class="fw-semibold text-dark small">
                                                            {{ $subject->subject_name }}</div>
                                                        <div class="d-flex justify-content-center gap-1 mt-1 flex-wrap">
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary"
                                                                style="font-size:.62rem;">
                                                                {{ $subject->subject_type == 0 ? 'Theory' : 'Practical' }}
                                                            </span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary"
                                                                style="font-size:.62rem;">
                                                                Full: {{ $subject->full_mark }}
                                                            </span>
                                                            <span class="badge bg-warning bg-opacity-10 text-warning"
                                                                style="font-size:.62rem;">
                                                                Pass: {{ $subject->passing_mark }}
                                                            </span>
                                                        </div>
                                                        {{-- Save Column button in header --}}
                                                        <div class="mt-2">
                                                            <button type="button"
                                                                class="btn btn-xs btn-outline-primary save-single-column-btn"
                                                                data-schedule-id="{{ $subject->id }}"
                                                                style="font-size:.7rem;padding:2px 8px;">
                                                                <i class="bi bi-floppy me-1"></i>Save Col
                                                            </button>
                                                        </div>
                                                    </th>
                                                @endforeach
                                                <th style="min-width:170px;" class="text-center">Summary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($getStudent) && $getStudent->count() > 0)
                                                @foreach ($getStudent as $student)
                                                    @php
                                                        $totalStudentMark = 0;
                                                        $totalFullMark = 0;
                                                        $totalPassingMark = 0;
                                                    @endphp
                                                    <tr>
                                                        <td class="ps-3">
                                                            <div class="d-flex align-items-center gap-2">
                                                                @if ($student->profile_pic)
                                                                    <img src="{{ asset('storage/' . $student->profile_pic) }}"
                                                                        class="rounded-circle flex-shrink-0"
                                                                        style="width:32px;height:32px;object-fit:cover;">
                                                                @else
                                                                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                                        style="width:32px;height:32px;font-size:.72rem;">
                                                                        {{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-semibold small text-dark">
                                                                        {{ $student->name }} {{ $student->last_name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        @foreach ($getSubject as $subject)
                                                            @php
                                                                $idKey = $student->id . '_' . $subject->id;
                                                                $existing = $getMarks[$idKey] ?? null;

                                                                $totalMark = 0;
                                                                $totalFullMark += $subject->full_mark;
                                                                $totalPassingMark += $subject->passing_mark;

                                                                if (!empty($existing)) {
                                                                    $totalMark =
                                                                        ($existing->class_work ?? 0) +
                                                                        ($existing->home_work ?? 0) +
                                                                        ($existing->test_work ?? 0) +
                                                                        ($existing->exam ?? 0);
                                                                }
                                                                $totalStudentMark += $totalMark;
                                                                $getGrade = App\Models\MarksGradeModel::getGrade(
                                                                    $totalMark,
                                                                );
                                                            @endphp

                                                            <td class="align-top p-2">
                                                                {{-- Hidden fields --}}
                                                                <input type="hidden"
                                                                    name="mark[{{ $idKey }}][student_id]"
                                                                    value="{{ $student->id }}">
                                                                <input type="hidden"
                                                                    name="mark[{{ $idKey }}][exam_schedule_id]"
                                                                    value="{{ $subject->id }}">
                                                                <input type="hidden"
                                                                    name="mark[{{ $idKey }}][exam_id]"
                                                                    value="{{ Request::get('exam_id') }}">
                                                                <input type="hidden"
                                                                    name="mark[{{ $idKey }}][class_id]"
                                                                    value="{{ Request::get('class_id') }}">
                                                                <input type="hidden"
                                                                    name="mark[{{ $idKey }}][full_mark]"
                                                                    value="{{ $subject->full_mark }}">
                                                                <input type="hidden"
                                                                    name="mark[{{ $idKey }}][passing_mark]"
                                                                    value="{{ $subject->passing_mark }}">

                                                                {{-- Mark inputs --}}
                                                                <div class="row g-1">
                                                                    <div class="col-6">
                                                                        <label class="form-label mb-0"
                                                                            style="font-size:.68rem;color:#666;">Class
                                                                            Work</label>
                                                                        <input type="number" min="0"
                                                                            class="form-control form-control-sm mark-input"
                                                                            name="mark[{{ $idKey }}][class_work]"
                                                                            value="{{ old("mark.$idKey.class_work", $existing->class_work ?? 0) }}"
                                                                            placeholder="0">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label mb-0"
                                                                            style="font-size:.68rem;color:#666;">Home
                                                                            Work</label>
                                                                        <input type="number" min="0"
                                                                            class="form-control form-control-sm mark-input"
                                                                            name="mark[{{ $idKey }}][home_work]"
                                                                            value="{{ old("mark.$idKey.home_work", $existing->home_work ?? 0) }}"
                                                                            placeholder="0">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label mb-0"
                                                                            style="font-size:.68rem;color:#666;">Test
                                                                            Work</label>
                                                                        <input type="number" min="0"
                                                                            class="form-control form-control-sm mark-input"
                                                                            name="mark[{{ $idKey }}][test_work]"
                                                                            value="{{ old("mark.$idKey.test_work", $existing->test_work ?? 0) }}"
                                                                            placeholder="0">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label mb-0"
                                                                            style="font-size:.68rem;color:#666;">Exam</label>
                                                                        <input type="number" min="0"
                                                                            class="form-control form-control-sm mark-input"
                                                                            name="mark[{{ $idKey }}][exam]"
                                                                            value="{{ old("mark.$idKey.exam", $existing->exam ?? 0) }}"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>

                                                                {{-- Subject result summary --}}
                                                                @if (!empty($totalMark))
                                                                    <div class="mt-2 p-2 rounded-2 bg-light border"
                                                                        style="font-size:.72rem;">
                                                                        <div><span class="text-muted">Total:</span>
                                                                            <strong>{{ $totalMark }}</strong> /
                                                                            {{ $subject->full_mark }}</div>
                                                                        @if (!empty($getGrade))
                                                                            <div><span class="text-muted">Grade:</span>
                                                                                <strong>{{ $getGrade }}</strong></div>
                                                                        @endif
                                                                        <div>
                                                                            @if ($totalMark >= $subject->passing_mark)
                                                                                <span
                                                                                    class="badge bg-success bg-opacity-10 text-success">Pass</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-danger bg-opacity-10 text-danger">Fail</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        @endforeach

                                                        {{-- Summary column --}}
                                                        <td class="align-top text-center p-3">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success single-save-row-btn w-100 mb-3">
                                                                <i class="bi bi-floppy-fill me-1"></i>Save Row
                                                            </button>
                                                            @php
                                                                $percentage =
                                                                    $totalFullMark > 0
                                                                        ? round(
                                                                            ($totalStudentMark * 100) / $totalFullMark,
                                                                        )
                                                                        : 0;
                                                                $summaryGrade = App\Models\MarksGradeModel::getGrade(
                                                                    $percentage,
                                                                );
                                                            @endphp
                                                            <div class="text-start" style="font-size:.78rem;">
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span class="text-muted">Obtained</span>
                                                                    <strong>{{ $totalStudentMark }}</strong>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span class="text-muted">Full Mark</span>
                                                                    <strong>{{ $totalFullMark }}</strong>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span class="text-muted">Pass Mark</span>
                                                                    <strong>{{ $totalPassingMark }}</strong>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between border-bottom py-1">
                                                                    <span class="text-muted">%</span>
                                                                    <strong>{{ $percentage }}%</strong>
                                                                </div>
                                                                @if (!empty($summaryGrade))
                                                                    <div
                                                                        class="d-flex justify-content-between border-bottom py-1">
                                                                        <span class="text-muted">Grade</span>
                                                                        <strong>{{ $summaryGrade }}</strong>
                                                                    </div>
                                                                @endif
                                                                <div class="text-center mt-2">
                                                                    @if ($totalStudentMark >= $totalFullMark * 0.4)
                                                                        <span
                                                                            class="badge bg-success bg-opacity-10 text-success px-3 py-1">
                                                                            <i class="bi bi-check-circle me-1"></i>Pass
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                                            <i class="bi bi-x-circle me-1"></i>Fail
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top d-flex justify-content-end py-3">
                                <button type="submit" class="btn btn-danger px-5 fw-semibold">
                                    <i class="bi bi-floppy-fill me-2"></i>Submit All Marks
                                </button>
                            </div>
                        </div>
                    </form>

                @endif
            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
        function showAjaxMessage(message, type) {
            const $msg = $('#ajax-response-message');
            $msg.removeClass('alert-success alert-danger').addClass('alert alert-' + type)
                .html(message).stop(true, true).hide().fadeIn();
            setTimeout(() => $msg.fadeOut(), 4000);
        }

        $(document).ready(function() {

            // ── Section cascade for marks register filter ──────────────────────
            $('#mr_class_id').on('change', function() {
                const class_id = $(this).val();
                const $sec = $('#mr_section_id');
                const $hint = $('#mr-section-hint');
                if (!class_id) {
                    $sec.html('<option value="">— All Sections —</option>').prop('disabled', true);
                    $hint.text('Select class first');
                    return;
                }
                $sec.html('<option value="">Loading…</option>').prop('disabled', true);
                $.get('{{ url('admin/class_section/get_sections') }}', {
                    class_id
                }, function(sections) {
                    let html = '<option value="">— All Sections —</option>';
                    if (sections.length) {
                        sections.forEach(s => {
                            html += `<option value="${s.id}">Section ${s.name}</option>`;
                        });
                        $hint.html('<i class="bi bi-diagram-3 me-1" style="color:#7c3aed;"></i>' +
                            sections.length + ' section(s)');
                    } else {
                        $hint.text('No sections for this class');
                    }
                    $sec.html(html).prop('disabled', sections.length === 0);
                }).fail(function() {
                    $sec.html('<option value="">— Error —</option>').prop('disabled', false);
                });
            });



            // ── Submit All ─────────────────────────────────────────────────
            $('#SubmitMarksForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i>Saving…');

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-floppy-fill me-2"></i>Submit All Marks');
                        showAjaxMessage(data.message, 'success');
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-floppy-fill me-2"></i>Submit All Marks');
                        const msg = xhr.status === 422 ?
                            JSON.parse(xhr.responseText).message :
                            'A server error occurred. Please try again.';
                        showAjaxMessage(msg, 'danger');
                    }
                });
            });

            // ── Save Row ───────────────────────────────────────────────────
            $(document).on('click', '.single-save-row-btn', function() {
                const btn = $(this);
                const row = btn.closest('tr');
                const data = row.find('input').serialize() + '&_token={{ csrf_token() }}';
                btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Saving…');

                $.ajax({
                    type: 'POST',
                    url: '{{ url('admin/examination/submit_marks_register') }}',
                    data: data,
                    dataType: 'json',
                    success: function(res) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-floppy-fill me-1"></i>Save Row');
                        showAjaxMessage(res.message, 'success');
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-floppy-fill me-1"></i>Save Row');
                        const msg = xhr.status === 422 ?
                            JSON.parse(xhr.responseText).message :
                            'A server error occurred. Please try again.';
                        showAjaxMessage(msg, 'danger');
                    }
                });
            });

            // ── Save Column ────────────────────────────────────────────────
            $(document).on('click', '.save-single-column-btn', function() {
                const btn = $(this);
                const scheduleId = btn.data('schedule-id');
                const colInputs = $('input[name^="mark["][name*="_' + scheduleId + ']["]');

                if (!colInputs.length) {
                    alert('No data found for this column.');
                    return;
                }

                const data = colInputs.serialize() + '&_token={{ csrf_token() }}';
                btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Saving…');

                $.ajax({
                    type: 'POST',
                    url: '{{ url('admin/examination/submit_marks_register') }}',
                    data: data,
                    dataType: 'json',
                    success: function(res) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-floppy me-1"></i>Save Col');
                        showAjaxMessage(res.message, 'success');
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-floppy me-1"></i>Save Col');
                        const msg = xhr.status === 422 ?
                            JSON.parse(xhr.responseText).message :
                            'A server error occurred. Please try again.';
                        showAjaxMessage(msg, 'danger');
                    }
                });
            });

        });
    </script>
@endsection
