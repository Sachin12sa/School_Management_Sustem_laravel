@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Student Attendance</h4>
                            <span class="text-muted small">Select a class and date to mark attendance</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-success"></i>
                    <h6 class="mb-0 fw-semibold">Select Class &amp; Date</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="{{ url('admin/attendance/student_attendance') }}">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-building me-1"></i>Class <span class="text-danger">*</span>
                                </label>
                                <select name="class_id" id="getClass" required class="form-select">
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Attendance Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="attendance_date" id="attendance_date" required
                                       value="{{ Request::get('attendance_date') }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Load Students
                                </button>
                                <a href="{{ url('admin/attendance/student_attendance') }}" class="btn btn-outline-secondary flex-fill">
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

            @if(!empty(Request::get('class_id')) && !empty(Request::get('attendance_date')))

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-check-fill me-2 text-success"></i>Mark Attendance
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        @if(Request::get('class_id'))
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3">
                                <i class="bi bi-building me-1"></i>
                                {{ $getClass->firstWhere('id', Request::get('class_id'))->name ?? '' }}
                            </span>
                        @endif
                        <span class="badge bg-success bg-opacity-10 text-success px-3">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ \Carbon\Carbon::parse(Request::get('attendance_date'))->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Quick-mark all buttons --}}
                <div class="px-4 py-2 border-bottom bg-light d-flex align-items-center gap-3 flex-wrap">
                    <span class="text-muted small fw-semibold">Mark All:</span>
                    <button type="button" class="btn btn-sm btn-outline-success mark-all-btn" data-val="1">
                        <i class="bi bi-check-all me-1"></i>Present
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger mark-all-btn" data-val="2">
                        <i class="bi bi-x-circle me-1"></i>Absent
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning mark-all-btn" data-val="3">
                        <i class="bi bi-clock me-1"></i>Late
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info mark-all-btn" data-val="4">
                        <i class="bi bi-calendar2-half me-1"></i>Half Day
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary"
                                    style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Student</th>
                                    <th>Mark Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($getStudent) && $getStudent->count() > 0)
                                    @foreach($getStudent as $value)
                                        @php
                                            $attendance_type = '';
                                            $getAttendance = $value->getAttendance(
                                                $value->id,
                                                Request::get('class_id'),
                                                Request::get('attendance_date')
                                            );
                                            if (!empty($getAttendance->attendance_type)) {
                                                $attendance_type = $getAttendance->attendance_type;
                                            }
                                        @endphp
                                        <tr id="row-{{ $value->id }}">
                                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if(!empty($value->profile_pic))
                                                        <img src="{{ asset('upload/profile/' . $value->profile_pic) }}"
                                                             class="rounded-circle flex-shrink-0"
                                                             style="width:34px;height:34px;object-fit:cover;">
                                                    @else
                                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                             style="width:34px;height:34px;font-size:.78rem;">
                                                            {{ strtoupper(substr($value->name, 0, 1)) }}{{ strtoupper(substr($value->last_name ?? '', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold small text-dark">
                                                            {{ $value->name }} {{ $value->last_name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @php
                                                    $options = [
                                                        1 => ['label' => 'Present',  'color' => 'success'],
                                                        2 => ['label' => 'Absent',   'color' => 'danger'],
                                                        3 => ['label' => 'Late',     'color' => 'warning'],
                                                        4 => ['label' => 'Half Day', 'color' => 'info'],
                                                    ];
                                                @endphp
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($options as $val => $opt)
                                                        <label class="att-label d-flex align-items-center gap-1 px-3 py-1 rounded-pill border"
                                                               style="cursor:pointer;font-size:.8rem;transition:all .15s ease;"
                                                               data-color="{{ $opt['color'] }}">
                                                            <input type="radio"
                                                                   class="SaveAttendance d-none"
                                                                   name="attendance{{ $value->id }}"
                                                                   value="{{ $val }}"
                                                                   data-student-id="{{ $value->id }}"
                                                                   {{ $attendance_type == $val ? 'checked' : '' }}>
                                                            <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                                                            {{ $opt['label'] }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No students found in this class</div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @endif
        </div>
    </div>

</main>

<style>
.att-label { background: #f8f9fa; color: #555; user-select: none; }
.att-label:hover { filter: brightness(.95); }
.att-label.active-success { background: rgba(25,135,84,.12) !important; border-color: rgba(25,135,84,.5) !important; color: #198754 !important; font-weight: 600; }
.att-label.active-danger  { background: rgba(220,53,69,.12)  !important; border-color: rgba(220,53,69,.5)  !important; color: #dc3545 !important; font-weight: 600; }
.att-label.active-warning { background: rgba(255,193,7,.18)  !important; border-color: rgba(255,193,7,.6)  !important; color: #856404 !important; font-weight: 600; }
.att-label.active-info    { background: rgba(13,202,240,.12) !important; border-color: rgba(13,202,240,.5) !important; color: #087990 !important; font-weight: 600; }
</style>

@endsection

@section('script')
<script>
function showAjaxMessage(message, type) {
    const $msg = $('#ajax-response-message');
    $msg.removeClass('alert-success alert-danger')
        .addClass('alert alert-' + type)
        .html(message).stop(true, true).hide().fadeIn();
    setTimeout(() => $msg.fadeOut(), 4000);
}

const colorMap = { 1: 'success', 2: 'danger', 3: 'warning', 4: 'info' };

function applyLabel($radio) {
    const $allLabels = $radio.closest('td').find('.att-label');
    $allLabels.removeClass('active-success active-danger active-warning active-info');
    $radio.closest('.att-label').addClass('active-' + colorMap[$radio.val()]);
}

// Apply active state on page load for pre-checked radios
$(document).ready(function () {
    $('.SaveAttendance:checked').each(function () { applyLabel($(this)); });
});

// Mark-all buttons
$(document).on('click', '.mark-all-btn', function () {
    const val = $(this).data('val');
    $('.SaveAttendance[value="' + val + '"]').each(function () {
        $(this).prop('checked', true);
        applyLabel($(this));
        saveAttendance($(this));
    });
});

// Individual change
$(document).on('change', '.SaveAttendance', function () {
    applyLabel($(this));
    saveAttendance($(this));
});

function saveAttendance($radio) {
    const student_id      = $radio.data('student-id');
    const attendance_type = $radio.val();
    const class_id        = $('#getClass').val();
    const attendance_date = $('#attendance_date').val();

    if (!class_id || !attendance_date) {
        showAjaxMessage('Please select class and date first.', 'danger');
        return;
    }

    $.ajax({
        type: 'POST',
        url: '{{ url("admin/attendance/student_attendance_save") }}',
        data: {
            _token: '{{ csrf_token() }}',
            student_id:      student_id,
            attendance_type: attendance_type,
            class_id:        class_id,
            attendance_date: attendance_date
        },
        dataType: 'json',
        success: function (data) { showAjaxMessage(data.message, 'success'); },
        error: function (xhr) {
            const msg = (xhr.status === 422 && xhr.responseJSON)
                ? xhr.responseJSON.message
                : 'A server error occurred. Please try again.';
            showAjaxMessage(msg, 'danger');
        }
    });
}
</script>
@endsection