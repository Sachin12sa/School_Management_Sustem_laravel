@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Teacher Attendance</h4>
                            <span class="text-muted small">Mark daily attendance for all teachers</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/attendance/teacher_attendance_report') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-bar-chart-fill me-1"></i>View Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            {{-- Date Picker --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-calendar3 text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Select Date</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="{{ url('admin/attendance/teacher_attendance') }}" id="dateForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    Attendance Date <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="date"
                                           class="form-control"
                                           name="attendance_date"
                                           id="attendance_date"
                                           value="{{ Request::get('attendance_date', date('Y-m-d')) }}"
                                           required>
                                    <span class="input-group-text" onclick="document.getElementById('attendance_date').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary fw-semibold flex-fill">
                                    <i class="bi bi-search me-1"></i>Load Teachers
                                </button>
                                @if(Request::get('attendance_date'))
                                    <a href="{{ url('admin/teacher_attendance') }}" class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                @endif
                            </div>
                            @if(Request::get('attendance_date'))
                                <div class="col-md-5 text-end">
                                    <div class="d-inline-flex align-items-center gap-3 small">
                                        <span class="text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ \Carbon\Carbon::parse(Request::get('attendance_date'))->format('l, d F Y') }}
                                        </span>
                                        <span id="save-summary" class="text-muted"></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Teacher List --}}
            @if(Request::get('attendance_date') && !empty($getTeacher))

                {{-- Quick-mark all buttons --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body py-2 px-4 d-flex align-items-center gap-3 flex-wrap">
                        <span class="text-muted small fw-semibold me-2">Mark All:</span>
                        @foreach([1=>'success',2=>'danger',3=>'warning',4=>'info'] as $type => $color)
                            @php $labels = [1=>'Present',2=>'Absent',3=>'Late',4=>'Half Day']; @endphp
                            <button type="button"
                                    class="btn btn-sm btn-{{ $color }} mark-all-btn fw-semibold"
                                    data-type="{{ $type }}">
                                <i class="bi bi-check2-all me-1"></i>All {{ $labels[$type] }}
                            </button>
                        @endforeach
                        <div class="ms-auto">
                            <button type="button" id="saveAllBtn" class="btn btn-primary fw-semibold px-4">
                                <i class="bi bi-cloud-upload-fill me-2"></i>Save All
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill text-primary"></i>
                            <h6 class="mb-0 fw-semibold">Teachers — {{ Request::get('attendance_date') }}</h6>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3">
                            {{ count($getTeacher) }} teachers
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary text-uppercase"
                                       style="font-size:.72rem;letter-spacing:.04em;">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th style="min-width:220px;">Teacher</th>
                                        <th>Attendance</th>
                                        <th class="text-center">Save</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getTeacher as $teacher)
                                        @php
                                            $existing = \App\Models\TeacherAttendanceModel::getAttendance(
                                                $teacher->id,
                                                Request::get('attendance_date')
                                            );
                                            $current = $existing?->attendance_type;
                                        @endphp
                                        <tr id="row-{{ $teacher->id }}">
                                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if(!empty($teacher->getProfile()))
                                                        <img src="{{ $teacher->getProfile() }}"
                                                             class="rounded-circle flex-shrink-0"
                                                             style="width:38px;height:38px;object-fit:cover;border:2px solid rgba(13,110,253,.2);">
                                                    @else
                                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                             style="width:38px;height:38px;font-size:.78rem;">
                                                            {{ strtoupper(substr($teacher->name,0,1)) }}{{ strtoupper(substr($teacher->last_name??'',0,1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold small text-dark">
                                                            {{ $teacher->name }} {{ $teacher->last_name }}
                                                        </div>
                                                        <div class="text-muted" style="font-size:.72rem;">{{ $teacher->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-3 flex-wrap">
                                                    @foreach([1=>'success',2=>'danger',3=>'warning',4=>'info'] as $val => $col)
                                                        @php $lbls = [1=>'Present',2=>'Absent',3=>'Late',4=>'Half Day']; @endphp
                                                        <div class="form-check form-check-inline mb-0">
                                                            <input class="form-check-input attendance-radio"
                                                                   type="radio"
                                                                   name="attendance_{{ $teacher->id }}"
                                                                   id="att_{{ $teacher->id }}_{{ $val }}"
                                                                   value="{{ $val }}"
                                                                   data-teacher-id="{{ $teacher->id }}"
                                                                   {{ $current == $val ? 'checked' : '' }}>
                                                            <label class="form-check-label small fw-semibold text-{{ $col }}"
                                                                   for="att_{{ $teacher->id }}_{{ $val }}">
                                                                {{ $lbls[$val] }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-primary save-row-btn fw-semibold"
                                                        data-teacher-id="{{ $teacher->id }}">
                                                    <i class="bi bi-cloud-upload me-1"></i>Save
                                                </button>
                                                <span class="row-status ms-2 text-muted small"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Changes are saved immediately when you click Save or Save All.
                        </span>
                        <button type="button" id="saveAllBtnBottom" class="btn btn-primary fw-semibold px-4">
                            <i class="bi bi-cloud-upload-fill me-2"></i>Save All
                        </button>
                    </div>
                </div>

            @elseif(Request::get('attendance_date') && empty($getTeacher))
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No teachers found</div>
                        <div class="text-muted" style="font-size:.78rem;">There are no active teachers in the system.</div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar3 d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">Select a date above to load teachers</div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</main>

{{-- Fixed toast --}}
<div id="att-toast"
     class="alert shadow d-none"
     style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;min-width:280px;border-radius:.65rem;"
     role="alert"></div>

@endsection

@section('script')
<script>
const SAVE_URL      = '{{ url("admin/attendance/teacher_attendance/save") }}';
const CSRF          = '{{ csrf_token() }}';
const ATT_DATE      = '{{ Request::get("attendance_date") }}';

// ── Toast ──────────────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const el   = document.getElementById('att-toast');
    const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
    el.className = `alert alert-${type} shadow d-block`;
    el.innerHTML = `<i class="bi bi-${icon} me-2"></i>${msg}`;
    clearTimeout(el._t);
    el._t = setTimeout(() => el.className = 'alert shadow d-none', 4000);
}

// ── Save single row ────────────────────────────────────────────────────────
async function saveRow(teacherId) {
    const radio = document.querySelector(`input[name="attendance_${teacherId}"]:checked`);
    if (!radio) { showToast('Please select attendance for this teacher first.', 'warning'); return; }

    const btn    = document.querySelector(`.save-row-btn[data-teacher-id="${teacherId}"]`);
    const status = btn.closest('td').querySelector('.row-status');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

    try {
        const res  = await fetch(SAVE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ teacher_id: teacherId, attendance_date: ATT_DATE, attendance_type: radio.value })
        });
        const data = await res.json();
        showToast(data.message || 'Saved.');
        status.textContent = '✓ Saved';
        status.className   = 'row-status ms-2 text-success small fw-semibold';
        setTimeout(() => { status.textContent = ''; }, 3000);
    } catch (e) {
        showToast('Server error. Please try again.', 'danger');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Save';
    }
}

// ── Save All ───────────────────────────────────────────────────────────────
async function saveAll() {
    const radios = document.querySelectorAll('.attendance-radio:checked');
    if (!radios.length) { showToast('No attendance selected.', 'warning'); return; }

    const btn = document.getElementById('saveAllBtn');
    const btn2 = document.getElementById('saveAllBtnBottom');
    [btn, btn2].forEach(b => { if(b) { b.disabled = true; b.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…'; } });

    let saved = 0, failed = 0;
    for (const radio of radios) {
        try {
            await fetch(SAVE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ teacher_id: radio.dataset.teacherId, attendance_date: ATT_DATE, attendance_type: radio.value })
            });
            saved++;
        } catch { failed++; }
    }

    const msg = failed ? `${saved} saved, ${failed} failed.` : `All ${saved} records saved successfully.`;
    showToast(msg, failed ? 'warning' : 'success');
    document.getElementById('save-summary') && (document.getElementById('save-summary').textContent = `${saved} saved`);

    [btn, btn2].forEach(b => { if(b) { b.disabled = false; b.innerHTML = '<i class="bi bi-cloud-upload-fill me-2"></i>Save All'; } });
}

// ── Mark All ───────────────────────────────────────────────────────────────
document.querySelectorAll('.mark-all-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const type = this.dataset.type;
        document.querySelectorAll('.attendance-radio').forEach(r => {
            r.checked = (r.value === type);
        });
    });
});

// ── Bind save buttons ──────────────────────────────────────────────────────
document.querySelectorAll('.save-row-btn').forEach(btn => {
    btn.addEventListener('click', () => saveRow(btn.dataset.teacherId));
});

document.getElementById('saveAllBtn')    ?.addEventListener('click', saveAll);
document.getElementById('saveAllBtnBottom')?.addEventListener('click', saveAll);
</script>
@endsection