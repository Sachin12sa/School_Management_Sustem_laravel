@extends('layouts.app')

@section('style')
<style>
/* ── Base ──────────────────────────────────────────── */
.stat-card { border:none;border-radius:.85rem;overflow:hidden;transition:transform .2s,box-shadow .2s; }
.stat-card:hover { transform:translateY(-4px);box-shadow:0 .6rem 2rem rgba(0,0,0,.13)!important; }
.stat-icon { width:58px;height:58px;font-size:1.5rem;border-radius:.65rem;display:flex;align-items:center;justify-content:center;flex-shrink:0; }

/* ── Action tiles ──────────────────────────────────── */
.action-tile { display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.45rem;
    padding:1rem .5rem;border-radius:.7rem;border:1.5px solid #e9ecef;text-decoration:none;
    background:#f8f9fa;text-align:center;transition:all .18s ease; }
.action-tile:hover { background:#fff;transform:translateY(-3px);
    box-shadow:0 .35rem .9rem rgba(0,0,0,.1);border-color:rgba(255,193,7,.45); }
.action-tile i { font-size:1.35rem; }
.action-tile span { font-size:.71rem;font-weight:600;line-height:1.2;color:#334; }

/* ── Section cards ─────────────────────────────────── */
.section-card { border:none;border-radius:.85rem; }
.section-card .card-header { background:transparent;border-bottom:1px solid rgba(0,0,0,.07); }

/* ── Info rows (profile) ───────────────────────────── */
.info-row { display:flex;justify-content:space-between;padding:.5rem 0;
    border-bottom:1px solid rgba(0,0,0,.05);font-size:.82rem; }
.info-row:last-child { border-bottom:none; }

/* ── Feed items ────────────────────────────────────── */
.feed-item { display:flex;align-items:flex-start;gap:.75rem;padding:.7rem .85rem;
    border-bottom:1px solid rgba(0,0,0,.05);transition:background .12s; }
.feed-item:hover { background:rgba(255,193,7,.05); }
.feed-item:last-child { border-bottom:none; }
.feed-dot { width:34px;height:34px;border-radius:50%;display:flex;align-items:center;
    justify-content:center;flex-shrink:0;font-size:.82rem; }

/* ── Attendance ring ───────────────────────────────── */
.att-ring { position:relative;width:104px;height:104px;flex-shrink:0; }
.att-ring svg { transform:rotate(-90deg); }
.att-ring-label { position:absolute;inset:0;display:flex;flex-direction:column;
    align-items:center;justify-content:center; }
</style>
@endsection

@section('content')
<main class="app-main">

    {{-- ══ PAGE HEADER ════════════════════════════════════════════════ --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-8">
                    <div class="d-flex align-items-center gap-3">

                        {{-- Avatar --}}
                        @if(!empty(Auth::user()->getProfile()))
                            <img src="{{ Auth::user()->getProfile() }}"
                                 class="rounded-circle shadow flex-shrink-0"
                                 style="width:54px;height:54px;object-fit:cover;border:3px solid rgba(255,193,7,.5);">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow flex-shrink-0"
                                 style="width:54px;height:54px;font-size:1.25rem;
                                        background:rgba(255,193,7,.22);border:3px solid rgba(255,193,7,.4);color:#856404;">
                                {{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name??'',0,1)) }}
                            </div>
                        @endif

                        <div>
                            @php
                                $h = now()->hour;
                                $greetEmoji = $h < 12 ? '🌅' : ($h < 17 ? '☀️' : '🌙');
                                $greet      = $h < 12 ? 'Good Morning' : ($h < 17 ? 'Good Afternoon' : 'Good Evening');
                            @endphp
                            <div class="text-muted small">{{ $greetEmoji }} {{ $greet }}</div>
                            <h4 class="mb-0 fw-bold text-dark lh-sm">
                                {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                            </h4>
                            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                                <span class="badge px-2 py-1"
                                      style="background:rgba(255,193,7,.15);color:#856404;border:1px solid rgba(255,193,7,.35);font-size:.7rem;">
                                    <i class="bi bi-mortarboard-fill me-1"></i>Student
                                </span>
                                @if(Auth::user()->class_name ?? false)
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1" style="font-size:.7rem;">
                                        <i class="bi bi-building me-1"></i>{{ Auth::user()->class_name }}
                                    </span>
                                @endif
                                @if(Auth::user()->admission_number ?? false)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 font-monospace" style="font-size:.68rem;">
                                        #{{ Auth::user()->admission_number }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-end mt-2 mt-sm-0">
                    <div class="text-muted small mb-1">
                        <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url('student/my_homework') }}"
                           class="btn btn-sm fw-semibold text-dark px-3"
                           style="background:rgba(255,193,7,.2);border:1px solid rgba(255,193,7,.4);font-size:.75rem;">
                            <i class="bi bi-clipboard2-fill me-1"></i>My Homework
                        </a>
                        <a href="{{ url('student/my_attendance') }}"
                           class="btn btn-sm btn-outline-info fw-semibold px-3" style="font-size:.75rem;">
                            <i class="bi bi-person-check me-1"></i>Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- ══ STAT CARDS ══════════════════════════════════════════════ --}}
            <div class="row g-3 mb-4">

                {{-- Subjects --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:rgba(255,193,7,.15);color:#d39e00;">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold fs-2 text-dark lh-1">{{ $TotalSubject ?? '0' }}</div>
                                <div class="text-muted small mt-1">My Subjects</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <a href="{{ url('student/my_subject') }}"
                               class="btn btn-sm w-100 fw-semibold text-dark"
                               style="background:rgba(255,193,7,.2);border:1px solid rgba(255,193,7,.4);">
                                <i class="bi bi-arrow-right-circle me-1"></i>View Subjects
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Attendance % --}}
                <div class="col-xl-3 col-md-6">
                    @php $attPct = (int)$AttendancePercent ?? null; @endphp
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold fs-2 text-dark lh-1">
                                    {{ $attPct ?? '—' }}
                                    @if($attPct !== null)<span class="fs-6 fw-normal text-muted">%</span>@endif
                                </div>
                                <div class="text-muted small mt-1">Attendance</div>
                                @if($attPct !== null)
                                    @php $attBar = $attPct >= 75 ? 'success' : ($attPct >= 50 ? 'warning' : 'danger'); @endphp
                                    <div class="progress mt-2" style="height:4px;border-radius:2px;width:90px;">
                                        <div class="progress-bar bg-{{ $attBar }}" style="width:{{ $attPct }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <a href="{{ url('student/my_attendance') }}"
                               class="btn btn-info btn-sm w-100 fw-semibold text-white">
                                <i class="bi bi-arrow-right-circle me-1"></i>View Attendance
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Homework --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-clipboard2-fill"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold fs-2 text-dark lh-1">{{ $TotalHomework ?? '0' }}</div>
                                <div class="text-muted small mt-1">Assigned Homework</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <a href="{{ url('student/my_homework') }}"
                               class="btn btn-success btn-sm w-100 fw-semibold">
                                <i class="bi bi-arrow-right-circle me-1"></i>My Homework
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Exams --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold fs-2 text-dark lh-1">{{ $TotalExam ?? '0' }}</div>
                                <div class="text-muted small mt-1">Exams</div>
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <a href="{{ url('student/my_exam_result') }}"
                               class="btn btn-danger btn-sm w-100 fw-semibold">
                                <i class="bi bi-arrow-right-circle me-1"></i>Exam Results
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ ROW 2 : Attendance Ring · Homework Feed · Profile ═══════ --}}
            <div class="row g-3 mb-4">

                {{-- ── Attendance Overview ──────────────────────────────── --}}
                <div class="col-lg-4">
                    <div class="card section-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-person-check-fill me-2 text-info"></i>Attendance Overview
                            </h6>
                            <a href="{{ url('student/my_attendance') }}"
                               class="btn btn-sm btn-outline-info px-2" style="font-size:.72rem;">
                                Details <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            @php
                                $pct       = (int)($AttendancePercent ?? 0);
                                $r         = 42; $circ = round(2*3.14159*$r, 1);
                                $dash      = round($pct/100*$circ, 1);
                                $ringColor = $pct >= 75 ? '#198754' : ($pct >= 50 ? '#ffc107' : '#dc3545');
                                $attStatus = $pct >= 75
                                    ? ['Good Standing',     'success']
                                    : ($pct >= 50
                                        ? ['Needs Improvement', 'warning']
                                        : ['Critical',          'danger']);
                            @endphp
                            <div class="d-flex align-items-center gap-4 mb-4">
                                <div class="att-ring">
                                    <svg width="104" height="104" viewBox="0 0 104 104">
                                        <circle cx="52" cy="52" r="{{ $r }}" fill="none" stroke="#e9ecef" stroke-width="9"/>
                                        <circle cx="52" cy="52" r="{{ $r }}" fill="none"
                                                stroke="{{ $ringColor }}" stroke-width="9"
                                                stroke-dasharray="{{ $dash }} {{ $circ }}"
                                                stroke-linecap="round"/>
                                    </svg>
                                    <div class="att-ring-label">
                                        <span class="fw-bold text-dark" style="font-size:1.15rem;line-height:1;">{{ $pct }}%</span>
                                        <span class="text-muted" style="font-size:.62rem;">Present</span>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="fw-semibold small text-dark mb-2">Status</div>
                                    <span class="badge rounded-pill px-3 py-1 bg-{{ $attStatus[1] }} bg-opacity-15 text-{{ $attStatus[1] }}"
                                          style="font-size:.75rem;">
                                        {{ $attStatus[0] }}
                                    </span>
                                    <p class="text-muted mt-2 mb-0" style="font-size:.73rem;">
                                        {{ $pct < 75 ? 'Min. 75% attendance required' : "You're meeting the requirement ✓" }}
                                    </p>
                                </div>
                            </div>

                            {{-- 4-type breakdown --}}
                            @php
                                $attTypes = [
                                    ['label'=>'Present',  'color'=>'success', 'val'=> $PresentCount  ?? null],
                                    ['label'=>'Absent',   'color'=>'danger',  'val'=> $AbsentCount   ?? null],
                                    ['label'=>'Late',     'color'=>'warning', 'val'=> $LateCount     ?? null],
                                    ['label'=>'Half Day', 'color'=>'info',    'val'=> $HalfDayCount  ?? null],
                                ];
                            @endphp
                            <div class="row g-2">
                                @foreach($attTypes as $t)
                                    <div class="col-6">
                                        <div class="rounded-2 p-2" style="background:#f8f9fa;">
                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                <i class="bi bi-circle-fill text-{{ $t['color'] }}" style="font-size:.4rem;"></i>
                                                <span class="text-muted" style="font-size:.72rem;">{{ $t['label'] }}</span>
                                            </div>
                                            <div class="fw-bold text-dark ms-2" style="font-size:1.05rem;">{{ $t['val'] ?? '—' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Homework Feed ─────────────────────────────────────── --}}
                <div class="col-lg-4">
                    <div class="card section-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-clipboard2-fill me-2 text-success"></i>Pending Homework
                            </h6>
                            <a href="{{ url('student/my_homework') }}"
                               class="btn btn-sm btn-outline-success px-2" style="font-size:.72rem;">
                                All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if(!empty($getRecentHomework) && count($getRecentHomework) > 0)
                                @foreach($getRecentHomework as $hw)
                                    @php
                                        $dl      = \Carbon\Carbon::parse($hw->submission_date)->endOfDay();
                                        $n       = \Carbon\Carbon::now();
                                        $left    = (int)$n->diffInDays($dl, false);
                                        $overdue = $n->gt($dl);
                                        $soon    = !$overdue && $left <= 3;
                                        $bc      = $overdue ? 'danger' : ($soon ? 'warning' : 'success');
                                        $bl      = $overdue ? 'Overdue' : ($soon ? "Due in {$left}d" : 'Active');
                                    @endphp
                                    <div class="feed-item">
                                        <div class="feed-dot bg-{{ $bc }} bg-opacity-10 text-{{ $bc }}">
                                            <i class="bi bi-clipboard2"></i>
                                        </div>
                                        <div class="flex-fill min-width-0">
                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                <span class="fw-semibold small text-dark text-truncate" style="max-width:145px;">
                                                    {{ $hw->subject_name ?? 'Homework' }}
                                                </span>
                                                <span class="badge rounded-pill bg-{{ $bc }} bg-opacity-10 text-{{ $bc }}"
                                                      style="font-size:.62rem;white-space:nowrap;">{{ $bl }}</span>
                                            </div>
                                            <div class="text-muted" style="font-size:.71rem;">
                                                <i class="bi bi-calendar3 me-1"></i>Due {{ date('d M Y', strtotime($hw->submission_date)) }}
                                            </div>
                                            <a href="{{ url('student/my_homework/submit_homework/'.$hw->id) }}"
                                               class="btn btn-outline-success mt-1 px-2"
                                               style="font-size:.65rem;padding:2px 8px;">
                                                <i class="bi bi-upload me-1"></i>Submit
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-clipboard2-check d-block mb-2 text-muted" style="font-size:2.2rem;opacity:.3;"></i>
                                    <div class="fw-semibold small text-muted">All caught up!</div>
                                    <div class="text-muted" style="font-size:.76rem;">No pending homework</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── Profile Card ──────────────────────────────────────── --}}
                <div class="col-lg-4">
                    <div class="card section-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-mortarboard-fill" style="color:#d39e00;"></i>
                            <h6 class="mb-0 fw-semibold">My Profile</h6>
                            <a href="{{ url('student/account') }}"
                               class="btn btn-sm ms-auto px-2 text-dark fw-semibold"
                               style="background:rgba(255,193,7,.15);border:1px solid rgba(255,193,7,.35);font-size:.7rem;">
                                <i class="bi bi-pencil-fill me-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center text-center pt-4 pb-3 px-3">
                            @if(!empty(Auth::user()->getProfile()))
                                <img src="{{ Auth::user()->getProfile() }}"
                                     class="rounded-circle shadow mb-3"
                                     style="width:86px;height:86px;object-fit:cover;border:3px solid rgba(255,193,7,.45);">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow mb-3"
                                     style="width:86px;height:86px;font-size:1.9rem;
                                            background:rgba(255,193,7,.22);border:3px solid rgba(255,193,7,.35);color:#856404;">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name??'',0,1)) }}
                                </div>
                            @endif
                            <div class="fw-bold text-dark">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                            <div class="text-muted small mb-3">{{ Auth::user()->email }}</div>

                            <div class="w-100 text-start">
                                <div class="info-row">
                                    <span class="text-muted"><i class="bi bi-hash me-1"></i>Admission No</span>
                                    <span class="fw-semibold text-dark font-monospace" style="font-size:.78rem;">
                                        {{ Auth::user()->admission_number ?? '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="text-muted"><i class="bi bi-building me-1"></i>Class</span>
                                    <span class="fw-semibold text-dark">{{ Auth::user()->class_name ?? '—' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="text-muted"><i class="bi bi-phone me-1"></i>Mobile</span>
                                    <span class="fw-semibold text-dark">{{ Auth::user()->mobile_number ?? '—' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="text-muted"><i class="bi bi-droplet-fill me-1"></i>Blood Group</span>
                                    <span class="fw-semibold text-dark">{{ Auth::user()->blood_group ?? '—' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>Enrolled</span>
                                    <span class="fw-semibold text-dark">
                                        {{ Auth::user()->admission_date
                                            ? \Carbon\Carbon::parse(Auth::user()->admission_date)->format('d M Y')
                                            : '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ ROW 3 : Upcoming Exams · Notices ═══════════════════════ --}}
            <div class="row g-3 mb-4">

                {{-- ── Upcoming Exams Table ─────────────────────────────── --}}
                <div class="col-lg-8">
                    <div class="card section-card shadow-sm">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-calendar3-week-fill me-2 text-primary"></i>Upcoming Exams
                            </h6>
                            <a href="{{ url('student/my_exam_timetable') }}"
                               class="btn btn-sm btn-outline-primary px-2" style="font-size:.72rem;">
                                Full Schedule <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if(!empty($getUpcomingExams) && count($getUpcomingExams) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr class="table-light text-uppercase text-secondary"
                                                style="font-size:.68rem;letter-spacing:.05em;">
                                                <th class="ps-4">Subject</th>
                                                <th>Exam</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th class="pe-4">Room</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getUpcomingExams as $ex)
                                                @php $isToday = \Carbon\Carbon::parse($ex['exam_date'])->isToday(); @endphp
                                                <tr class="{{ $isToday ? 'table-warning' : '' }}">
                                                    <td class="ps-4">
                                                        <div class="fw-semibold small text-dark">{{ $ex['subject_name'] ?? '—' }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.7rem;">
                                                            {{ $ex['exam_name'] ?? '—' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="small text-dark">{{ date('d M Y', strtotime($ex['exam_date'])) }}</div>
                                                        @if($isToday)
                                                            <span class="badge bg-warning text-dark" style="font-size:.6rem;">Today!</span>
                                                        @else
                                                            <div class="text-muted" style="font-size:.68rem;">{{ date('l', strtotime($ex['exam_date'])) }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="small text-muted">
                                                        {{ date('h:i A', strtotime($ex['start_time'])) }}
                                                        <span class="text-secondary">–</span>
                                                        {{ date('h:i A', strtotime($ex['end_time'])) }}
                                                    </td>
                                                    <td class="pe-4">
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            {{ $ex['room_number'] ?? '—' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-calendar3 d-block mb-2 text-muted" style="font-size:2.2rem;opacity:.3;"></i>
                                    <div class="fw-semibold small text-muted">No upcoming exams scheduled</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── Recent Notices ───────────────────────────────────── --}}
                <div class="col-lg-4">
                    <div class="card section-card shadow-sm h-100">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-megaphone-fill me-2 text-warning"></i>Notices
                            </h6>
                            <a href="{{ url('student/my_notice_board') }}"
                               class="btn btn-sm btn-outline-warning px-2" style="font-size:.72rem;">
                                All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if(!empty($getRecentNotice) && $getRecentNotice->count() > 0)
                                @foreach($getRecentNotice as $notice)
                                    <div class="feed-item">
                                        <div class="feed-dot bg-warning bg-opacity-10 text-warning">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                        <div class="flex-fill min-width-0">
                                            <div class="fw-semibold small text-dark text-truncate"
                                                 style="max-width:200px;">{{ $notice->title }}</div>
                                            <div class="text-muted" style="font-size:.71rem;">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ date('d M Y', strtotime($notice->publish_date)) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-megaphone d-block mb-2 text-muted" style="font-size:2.2rem;opacity:.3;"></i>
                                    <div class="text-muted small">No notices yet</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ ROW 4 : Quick Actions Grid ══════════════════════════════ --}}
            <div class="row g-3">
                <div class="col-12">
                    <div class="card section-card shadow-sm">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-lightning-charge-fill me-2" style="color:#d39e00;"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @php
                                $actions = [
                                    ['url'=>'student/my_subject',              'icon'=>'bi-journal-bookmark-fill','color'=>'warning',  'label'=>'My Subjects'],
                                    ['url'=>'student/my_timetable',            'icon'=>'bi-clock-fill',           'color'=>'info',     'label'=>'Timetable'],
                                    ['url'=>'student/my_exam_timetable',       'icon'=>'bi-calendar3-week-fill',  'color'=>'primary',  'label'=>'Exam Schedule'],
                                    ['url'=>'student/my_exam_result',          'icon'=>'bi-award-fill',           'color'=>'danger',   'label'=>'Exam Results'],
                                    ['url'=>'student/my_attendance',           'icon'=>'bi-person-check-fill',    'color'=>'info',     'label'=>'My Attendance'],
                                    ['url'=>'student/my_homework',             'icon'=>'bi-clipboard2-fill',      'color'=>'success',  'label'=>'My Homework'],
                                    ['url'=>'student/my_submitted_homework',   'icon'=>'bi-clipboard2-check-fill','color'=>'success',  'label'=>'Submitted HW'],
                                    ['url'=>'student/my_notice_board',         'icon'=>'bi-megaphone-fill',       'color'=>'warning',  'label'=>'Notice Board'],
                                    ['url'=>'student/my_calender',             'icon'=>'bi-calendar-event-fill',  'color'=>'primary',  'label'=>'My Calendar'],
                                    ['url'=>'student/account',                 'icon'=>'bi-person-circle',        'color'=>'secondary','label'=>'My Account'],
                                    ['url'=>'student/profile/change_password', 'icon'=>'bi-shield-lock-fill',     'color'=>'secondary','label'=>'Change Password'],
                                ];
                                @endphp
                                @foreach($actions as $a)
                                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                        <a href="{{ url($a['url']) }}" class="action-tile h-100">
                                            <div class="rounded-circle bg-{{ $a['color'] }} bg-opacity-10 text-{{ $a['color'] }} d-flex align-items-center justify-content-center"
                                                 style="width:44px;height:44px;font-size:1.1rem;">
                                                <i class="bi {{ $a['icon'] }}"></i>
                                            </div>
                                            <span>{{ $a['label'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection