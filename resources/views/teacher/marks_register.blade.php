@extends('layouts.app')

@section('style')
<style>
#marks-table th, #marks-table td { vertical-align: middle; white-space: nowrap; }
#marks-table thead th.subject-col { white-space: normal; min-width: 185px; font-size: .72rem; }
.mark-cell { min-width: 180px; padding: .5rem !important; }
.mark-input { width:100%;font-size:.8rem;padding:.25rem .4rem;border-radius:.35rem;border:1px solid #dee2e6;background:#f9fafb;transition:border-color .15s,background .15s; }
.mark-input:focus { outline:none;border-color:#86b7fe;background:#fff;box-shadow:0 0 0 2px rgba(13,110,253,.15); }
.sub-summary { font-size:.72rem;margin-top:.4rem;line-height:1.65; }
.student-name-col { min-width:160px;position:sticky;left:0;background:#fff;z-index:2;box-shadow:2px 0 5px rgba(0,0,0,.06); }
.summary-col { min-width:170px;position:sticky;right:0;background:#f8f9fa;z-index:2;box-shadow:-2px 0 5px rgba(0,0,0,.06); }
#ajax-flash { position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;min-width:280px;border-radius:.65rem;animation:slideUp .25s ease; }
@keyframes slideUp { from{transform:translateY(30px);opacity:0} to{transform:translateY(0);opacity:1} }
</style>
@endsection

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-ui-checks-grid"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Marks Register</h4>
                            <span class="text-muted small">Enter and save student marks by subject</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-danger"></i>
                    <h6 class="mb-0 fw-semibold">Select Exam &amp; Class</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="{{ url('teacher/marks_register') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small text-secondary"><i class="bi bi-award me-1"></i>Exam Name</label>
                                <select name="exam_id" required class="form-select">
                                    <option value="">— Select Exam —</option>
                                    @foreach($getExam as $exam)
                                        <option value="{{ $exam->exam_id }}" {{ Request::get('exam_id') == $exam->exam_id ? 'selected' : '' }}>
                                            {{ $exam->exam_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small text-secondary"><i class="bi bi-building me-1"></i>Class</label>
                                <select name="class_id" required class="form-select">
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option value="{{ $class->class_id }}" {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}>
                                            {{ $class->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-danger flex-fill fw-semibold"><i class="bi bi-search me-1"></i>Load</button>
                                <a href="{{ url('teacher/marks_register') }}" class="btn btn-outline-secondary flex-fill"><i class="bi bi-arrow-counterclockwise"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="ajax-flash" class="alert d-none shadow-lg" role="alert"></div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            @if(!empty($getSubject) && $getSubject->count() > 0)
                <form action="{{ url('teacher/submit_marks_register') }}" method="POST" id="SubmitMarksForm">
                    @csrf
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3 flex-wrap gap-2">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-ui-checks-grid me-2 text-danger"></i>Student Marks Entry
                                <span class="badge bg-danger bg-opacity-10 text-danger ms-2">
                                    {{ $getStudent->count() }} {{ Str::plural('student', $getStudent->count()) }}
                                    &middot; {{ $getSubject->count() }} {{ Str::plural('subject', $getSubject->count()) }}
                                </span>
                            </h6>
                            <button type="submit" class="btn btn-danger fw-semibold px-4">
                                <i class="bi bi-check-all me-1"></i>Submit All Marks
                            </button>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle" id="marks-table">
                                    <thead>
                                        <tr class="table-light text-uppercase text-secondary" style="font-size:.7rem;letter-spacing:.04em;">
                                            <th class="ps-4 student-name-col">Student</th>
                                            @foreach($getSubject as $subject)
                                                <th class="subject-col text-center">
                                                    <div class="fw-bold text-dark" style="font-size:.75rem;">{{ $subject->subject_name }}</div>
                                                    <div class="d-flex justify-content-center gap-1 mt-1 flex-wrap">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.62rem;">{{ $subject->subject_type == 0 ? 'Theory' : 'Practical' }}</span>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:.62rem;">Full: {{ $subject->full_mark }}</span>
                                                        <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size:.62rem;">Pass: {{ $subject->passing_mark }}</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-outline-primary save-col-btn px-2 fw-semibold"
                                                                data-schedule-id="{{ $subject->id }}"
                                                                style="font-size:.67rem;padding:2px 8px;">
                                                            <i class="bi bi-save me-1"></i>Save Column
                                                        </button>
                                                    </div>
                                                </th>
                                            @endforeach
                                            <th class="summary-col text-center">Summary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($getStudent) && $getStudent->count())
                                            @foreach($getStudent as $student)
                                                @php
                                                    $totalStudentMark = 0;
                                                    $totalFullMark    = 0;
                                                    $totalPassingMark = 0;
                                                @endphp
                                                <tr>
                                                    <td class="ps-4 student-name-col">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                                 style="width:34px;height:34px;font-size:.75rem;">
                                                                {{ strtoupper(substr($student->name,0,1)) }}{{ strtoupper(substr($student->last_name??'',0,1)) }}
                                                            </div>
                                                            <div class="fw-semibold small text-dark">{{ $student->name }} {{ $student->last_name }}</div>
                                                        </div>
                                                    </td>

                                                    @foreach($getSubject as $subject)
                                                        @php
                                                            $idKey    = $student->id . '_' . $subject->id;
                                                            $existing = $getMarks[$idKey] ?? null;
                                                            $totalMark = 0;
                                                            if (!empty($existing)) {
                                                                $totalMark = ($existing->class_work ?? 0)
                                                                           + ($existing->home_work  ?? 0)
                                                                           + ($existing->test_work  ?? 0)
                                                                           + ($existing->exam       ?? 0);
                                                            }
                                                            $totalStudentMark += $totalMark;
                                                            $totalFullMark    += $subject->full_mark;
                                                            $totalPassingMark += $subject->passing_mark;
                                                            $passed = $totalMark >= $subject->passing_mark;
                                                            $grade  = App\Models\MarksGradeModel::getGrade($totalMark);
                                                        @endphp
                                                        <td class="mark-cell">
                                                            <input type="hidden" name="mark[{{ $idKey }}][student_id]"       value="{{ $student->id }}">
                                                            <input type="hidden" name="mark[{{ $idKey }}][exam_schedule_id]" value="{{ $subject->id }}">
                                                            <input type="hidden" name="mark[{{ $idKey }}][exam_id]"          value="{{ Request::get('exam_id') }}">
                                                            <input type="hidden" name="mark[{{ $idKey }}][class_id]"         value="{{ Request::get('class_id') }}">
                                                            <input type="hidden" name="mark[{{ $idKey }}][full_mark]"        value="{{ $subject->full_mark }}">
                                                            <input type="hidden" name="mark[{{ $idKey }}][passing_mark]"     value="{{ $subject->passing_mark }}">

                                                            <div class="row g-1">
                                                                <div class="col-6">
                                                                    <label class="text-muted mb-0" style="font-size:.64rem;">Class Work</label>
                                                                    <input type="number" min="0" max="{{ $subject->full_mark }}" step="0.5"
                                                                           class="mark-input" name="mark[{{ $idKey }}][class_work]"
                                                                           value="{{ old("mark.$idKey.class_work", $existing->class_work ?? 0) }}" placeholder="0">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="text-muted mb-0" style="font-size:.64rem;">Home Work</label>
                                                                    <input type="number" min="0" max="{{ $subject->full_mark }}" step="0.5"
                                                                           class="mark-input" name="mark[{{ $idKey }}][home_work]"
                                                                           value="{{ old("mark.$idKey.home_work", $existing->home_work ?? 0) }}" placeholder="0">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="text-muted mb-0" style="font-size:.64rem;">Test Work</label>
                                                                    <input type="number" min="0" max="{{ $subject->full_mark }}" step="0.5"
                                                                           class="mark-input" name="mark[{{ $idKey }}][test_work]"
                                                                           value="{{ old("mark.$idKey.test_work", $existing->test_work ?? 0) }}" placeholder="0">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="text-muted mb-0" style="font-size:.64rem;">Exam</label>
                                                                    <input type="number" min="0" max="{{ $subject->full_mark }}" step="0.5"
                                                                           class="mark-input" name="mark[{{ $idKey }}][exam]"
                                                                           value="{{ old("mark.$idKey.exam", $existing->exam ?? 0) }}" placeholder="0">
                                                                </div>
                                                            </div>

                                                            @if($totalMark > 0)
                                                                <div class="sub-summary mt-2 px-1">
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="text-muted">Total</span>
                                                                        <span class="fw-bold text-dark">{{ $totalMark }} / {{ $subject->full_mark }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="text-muted">Grade</span>
                                                                        <span class="badge bg-primary bg-opacity-10 text-primary px-1" style="font-size:.63rem;">{{ $grade }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="text-muted">Result</span>
                                                                        @if($passed)
                                                                            <span class="badge bg-success bg-opacity-10 text-success px-1" style="font-size:.63rem;">Pass</span>
                                                                        @else
                                                                            <span class="badge bg-danger bg-opacity-10 text-danger px-1" style="font-size:.63rem;">Fail</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach

                                                    @php
                                                        $pct          = $totalFullMark > 0 ? round($totalStudentMark * 100 / $totalFullMark) : 0;
                                                        $overallGrade = App\Models\MarksGradeModel::getGrade($pct);
                                                        $overallPass  = $totalStudentMark >= $totalFullMark * 0.4;
                                                        $pctColor     = $pct >= 80 ? 'success' : ($pct >= 60 ? 'primary' : ($pct >= 40 ? 'warning' : 'danger'));
                                                    @endphp
                                                    <td class="summary-col text-center">
                                                        <div class="d-flex flex-column gap-2 py-2 px-2">
                                                            <div>
                                                                <div class="text-muted" style="font-size:.68rem;">Obtained / Full</div>
                                                                <div class="fw-bold small text-dark">{{ $totalStudentMark }} / {{ $totalFullMark }}</div>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted" style="font-size:.68rem;">Percentage</div>
                                                                <div class="fw-bold text-{{ $pctColor }}">{{ $pct }}%</div>
                                                                <div class="progress mt-1" style="height:3px;">
                                                                    <div class="progress-bar bg-{{ $pctColor }}" style="width:{{ $pct }}%"></div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $overallGrade }}</span>
                                                            </div>
                                                            <div>
                                                                @if($overallPass)
                                                                    <span class="badge bg-success px-2 fw-bold">PASS</span>
                                                                @else
                                                                    <span class="badge bg-danger px-2 fw-bold">FAIL</span>
                                                                @endif
                                                            </div>
                                                            <button type="button" class="btn btn-success btn-sm fw-semibold save-row-btn" style="font-size:.72rem;">
                                                                <i class="bi bi-save me-1"></i>Save Row
                                                            </button>
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
                            <button type="submit" class="btn btn-danger fw-semibold px-5">
                                <i class="bi bi-check-all me-2"></i>Submit All Marks
                            </button>
                        </div>
                    </form>
                </div>

            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-funnel d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">
                            @if(Request::get('exam_id') || Request::get('class_id'))
                                No subjects or students found for the selected exam &amp; class
                            @else
                                Select an exam and class above to load the marks register
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</main>
@endsection

@section('script')
<script>
function showFlash(msg, type) {
    var el = document.getElementById('ajax-flash');
    var icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
    el.className = 'alert alert-' + type + ' shadow-lg d-block';
    el.innerHTML = '<i class="bi bi-' + icon + ' me-2"></i>' + msg;
    clearTimeout(el._timer);
    el._timer = setTimeout(function () { el.className = 'alert d-none'; }, 4000);
}

$(document).ready(function () {

    $('#SubmitMarksForm').on('submit', function (e) {
        e.preventDefault();
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Saving…');
        $.ajax({
            type: 'POST', url: $(this).attr('action'),
            data: $(this).serialize(), dataType: 'json',
            success: function (data) {
                btn.prop('disabled', false).html('<i class="bi bi-check-all me-1"></i>Submit All Marks');
                showFlash(data.message, 'success');
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="bi bi-check-all me-1"></i>Submit All Marks');
                showFlash(xhr.status === 422 ? JSON.parse(xhr.responseText).message : 'Server error. Please try again.', 'danger');
            }
        });
    });

    $(document).on('click', '.save-row-btn', function () {
        var btn = $(this);
        var rowData = btn.closest('tr').find('input').serialize() + '&_token={{ csrf_token() }}';
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Saving…');
        $.ajax({
            type: 'POST', url: '{{ url("teacher/submit_marks_register") }}',
            data: rowData, dataType: 'json',
            success: function (data) {
                btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Save Row');
                showFlash(data.message, 'success');
            },
            error: function () {
                btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Save Row');
                showFlash('Error saving row. Please try again.', 'danger');
            }
        });
    });

    $(document).on('click', '.save-col-btn', function () {
        var btn = $(this);
        var sid = btn.data('schedule-id');
        var colInputs = $('input[name*="_' + sid + ']"]');
        if (!colInputs.length) { showFlash('No data found for this column.', 'warning'); return; }
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Saving…');
        $.ajax({
            type: 'POST', url: '{{ url("teacher/submit_marks_register") }}',
            data: colInputs.serialize() + '&_token={{ csrf_token() }}', dataType: 'json',
            success: function (data) {
                btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Save Column');
                showFlash(data.message, 'success');
            },
            error: function () {
                btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Save Column');
                showFlash('Error saving column. Please try again.', 'danger');
            }
        });
    });

});
</script>
@endsection