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
                            <h4 class="mb-0 fw-semibold text-dark">Take Attendance</h4>
                            <span class="text-muted small">Select class and date to mark attendance</span>
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
                    <form method="GET" action="{{ url('teacher/attendance/student_attendance') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Class <span class="text-danger">*</span></label>
                                <select name="class_id" id="getClass" required class="form-select">
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option value="{{ $class->class_id }}"
                                                {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}>
                                            {{ $class->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="attendance_date" id="attendance_date"
                                           value="{{ Request::get('attendance_date') }}" required>
                                    <span class="input-group-text" onclick="document.getElementById('attendance_date').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Load Students
                                </button>
                                <a href="{{ url('teacher/attendance/student_attendance') }}"
                                   class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- AJAX flash --}}
    <div id="ajax-flash" class="alert d-none shadow-sm"
         style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;min-width:280px;border-radius:.65rem;"
         role="alert"></div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            @if(!empty(Request::get('class_id')) && !empty(Request::get('attendance_date')))
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill text-success"></i>
                            <h6 class="mb-0 fw-semibold">Student List</h6>
                        </div>
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>{{ date('d M Y', strtotime(Request::get('attendance_date'))) }}
                            &nbsp;&middot;&nbsp;
                            Changes save automatically
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.04em;">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Student</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($getStudent) && $getStudent->count() > 0)
                                        @foreach($getStudent as $value)
                                            @php
                                                $attendance_type = '';
                                                $getAttendance = $value->getAttendance($value->id, Request::get('class_id'), Request::get('attendance_date'));
                                                if (!empty($getAttendance->attendance_type)) {
                                                    $attendance_type = $getAttendance->attendance_type;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                             style="width:34px;height:34px;font-size:.75rem;">
                                                            {{ strtoupper(substr($value->name,0,1)) }}{{ strtoupper(substr($value->last_name??'',0,1)) }}
                                                        </div>
                                                        <div class="fw-semibold small text-dark">
                                                            {{ $value->name }} {{ $value->last_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        @php
                                                        $opts = [1=>'success',2=>'danger',3=>'warning',4=>'info'];
                                                        $labels = [1=>'Present',2=>'Absent',3=>'Late',4=>'Half Day'];
                                                        @endphp
                                                        @foreach($opts as $val => $color)
                                                            <div class="form-check form-check-inline mb-0">
                                                                <input class="form-check-input SaveAttendance"
                                                                       type="radio"
                                                                       name="attendance{{ $value->id }}"
                                                                       id="att_{{ $value->id }}_{{ $val }}"
                                                                       value="{{ $val }}"
                                                                       data-student-id="{{ $value->id }}"
                                                                       {{ $attendance_type == $val ? 'checked' : '' }}>
                                                                <label class="form-check-label small fw-semibold text-{{ $color }}"
                                                                       for="att_{{ $value->id }}_{{ $val }}">
                                                                    {{ $labels[$val] }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                                <div class="text-muted small">No students found for this class</div>
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
@endsection

@section('script')
<script>
function showFlash(msg, type) {
    var el = document.getElementById('ajax-flash');
    var icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
    el.className = 'alert alert-' + type + ' shadow-sm d-block';
    el.innerHTML = '<i class="bi bi-' + icon + ' me-2"></i>' + msg;
    clearTimeout(el._t);
    el._t = setTimeout(function () { el.className = 'alert d-none'; }, 4000);
}

document.querySelectorAll('.SaveAttendance').forEach(function (radio) {
    radio.addEventListener('change', function () {
        var student_id      = this.dataset.studentId;
        var attendance_type = this.value;
        var class_id        = document.getElementById('getClass').value;
        var attendance_date = document.getElementById('attendance_date').value;

        if (!class_id || !attendance_date) {
            showFlash('Please select class and date first.', 'danger');
            return;
        }

        fetch('{{ url("teacher/attendance/student_attendance_save") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ student_id, attendance_type, class_id, attendance_date })
        })
        .then(r => r.json())
        .then(data => showFlash(data.message, 'success'))
        .catch(() => showFlash('A server error occurred. Please try again.', 'danger'));
    });
});
</script>
@endsection