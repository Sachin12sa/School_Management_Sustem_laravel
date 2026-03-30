@extends('layouts.app')

@section('style')
    <style>
        /* ── Stat Cards ─────────────────────────────────────── */
        .stat-card {
            border: none;
            border-radius: .75rem;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .12) !important;
        }

        .stat-icon {
            width: 58px;
            height: 58px;
            font-size: 1.55rem;
            border-radius: .6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Quick-Action Tiles ─────────────────────────────── */
        .action-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .9rem .5rem;
            border-radius: .65rem;
            border: 1.5px solid transparent;
            text-decoration: none;
            color: inherit;
            transition: all .18s ease;
            background: #f8f9fa;
            text-align: center;
        }

        .action-tile:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 .3rem .8rem rgba(0, 0, 0, .1);
        }

        .action-tile i {
            font-size: 1.35rem;
        }

        .action-tile span {
            font-size: .72rem;
            font-weight: 600;
            line-height: 1.2;
        }

        /* ── Section cards ──────────────────────────────────── */
        .section-card {
            border: none;
            border-radius: .75rem;
        }

        .section-card .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, .07);
        }

        /* ── Welcome Banner ─────────────────────────────────── */
        .welcome-banner {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            border-radius: .75rem;
            overflow: hidden;
            position: relative;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, .06);
            border-radius: 50%;
        }

        /* ── Progress bars ──────────────────────────────────── */
        .prog-bar {
            height: 7px;
            border-radius: 4px;
        }

        /* ── Activity dot ───────────────────────────────────── */
        .activity-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .8rem;
        }

        /* ── Status mini-badge ──────────────────────────────── */
        .hw-badge {
            font-size: .65rem;
            padding: .2em .55em;
            border-radius: .3rem;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        {{-- Page Header --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            @php
                                $__dayEn = \Carbon\Carbon::now()->format('l');
                                $__bsToday = \App\Helpers\NepaliCalendar::today();
                            @endphp
                            <span style="color:#6b7280;">{{ $__dayEn }}</span>
                            <span class="dot-sep"></span>
                            <strong style="color:#1e293b;">{{ $__bsToday['day'] }}
                                {{ $__bsToday['month_name'] }} {{ $__bsToday['year'] }}</strong>
                            <span style="color:#6b7280;font-size:.7rem;">B.S.</span>
                            <span class="dot-sep"></span>




                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- ════════════════════════════════════════════════
                 WELCOME BANNER
            ════════════════════════════════════════════════ --}}
                <div
                    class="welcome-banner shadow-sm mb-4 px-4 py-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        @php
                            $hour = \Carbon\Carbon::now()->hour;
                            $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
                            $greetIcon = $hour < 12 ? '🌅' : ($hour < 17 ? '☀️' : '🌙');
                        @endphp
                        <h5 class="text-white fw-bold mb-1">
                            {{ $greetIcon }} {{ $greeting }}, {{ Auth::user()->name }}!
                        </h5>
                        <p class="text-white-50 mb-0 small">
                            Here's a snapshot of your school today.

                        </p>
                        <div class="d-flex gap-3 mt-3">
                            <a href="{{ url('admin/attendance/student_attendance') }}"
                                class="btn btn-sm text-white border-white border-opacity-50 px-3">
                                <i class="bi bi-person-check me-1"></i>Mark Attendance
                            </a>
                            <a href="{{ url('admin/communicate/notice_board/add') }}"
                                class="btn btn-sm bg-white text-primary fw-semibold px-3">
                                <i class="bi bi-megaphone me-1"></i>Post Notice
                            </a>
                        </div>
                    </div>
                    <div class="text-white d-none d-md-flex flex-column align-items-end gap-1" style="opacity:.25;">
                        <i class="bi bi-mortarboard-fill" style="font-size:4rem;"></i>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════
                 STATS ROW  (6 cards)
            ════════════════════════════════════════════════ --}}
                <div class="row g-3 mb-4">

                    {{-- Admins --}}
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-dark lh-1">{{ $TotalAdmin }}</div>
                                    <div class="text-muted small mt-1">Admins</div>
                                </div>
                            </div>
                            <div class="px-3 pb-3">
                                <a href="{{ url('admin/admin/list') }}" class="btn btn-primary btn-sm w-100 fw-semibold">
                                    <i class="bi bi-arrow-right-circle me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Teachers --}}
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-person-video3"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-dark lh-1">{{ $TotalTeacher }}</div>
                                    <div class="text-muted small mt-1">Teachers</div>
                                </div>
                            </div>
                            <div class="px-3 pb-3">
                                <a href="{{ url('admin/teacher/list') }}" class="btn btn-success btn-sm w-100 fw-semibold">
                                    <i class="bi bi-arrow-right-circle me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Students --}}
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-dark lh-1">{{ $TotalStudent }}</div>
                                    <div class="text-muted small mt-1">Students</div>
                                </div>
                            </div>
                            <div class="px-3 pb-3">
                                <a href="{{ url('admin/student/list') }}" class="btn btn-warning btn-sm w-100 fw-semibold">
                                    <i class="bi bi-arrow-right-circle me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Parents --}}
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="bi bi-house-heart-fill"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-dark lh-1">{{ $TotalParent }}</div>
                                    <div class="text-muted small mt-1">Parents</div>
                                </div>
                            </div>
                            <div class="px-3 pb-3">
                                <a href="{{ url('admin/parent/list') }}" class="btn btn-danger btn-sm w-100 fw-semibold">
                                    <i class="bi bi-arrow-right-circle me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Classes --}}
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="stat-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-dark lh-1">{{ $TotalClass ?? '—' }}</div>
                                    <div class="text-muted small mt-1">Classes</div>
                                </div>
                            </div>
                            <div class="px-3 pb-3">
                                <a href="{{ url('admin/class/list') }}"
                                    class="btn btn-info btn-sm w-100 fw-semibold text-white">
                                    <i class="bi bi-arrow-right-circle me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Homework --}}
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold text-dark lh-1">{{ $TotalHomework ?? '—' }}</div>
                                    <div class="text-muted small mt-1">Homework</div>
                                </div>
                            </div>
                            <div class="px-3 pb-3">
                                <a href="{{ url('admin/homework/homework') }}"
                                    class="btn btn-secondary btn-sm w-100 fw-semibold">
                                    <i class="bi bi-arrow-right-circle me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ════════════════════════════════════════════════
                 ROW 2 : Enrollment Chart + Quick Actions
            ════════════════════════════════════════════════ --}}
                <div class="row g-3 mb-4">

                    {{-- Enrollment Overview --}}
                    <div class="col-lg-5">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Enrollment Overview
                                </h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.68rem;">
                                    Live Data
                                </span>
                            </div>
                            <div class="card-body">
                                @php
                                    $total = $TotalAdmin + $TotalTeacher + $TotalStudent + $TotalParent;
                                    $total = $total ?: 1;
                                    $items = [
                                        [
                                            'label' => 'Admins',
                                            'value' => $TotalAdmin,
                                            'color' => 'primary',
                                            'icon' => 'bi-person-badge-fill',
                                        ],
                                        [
                                            'label' => 'Teachers',
                                            'value' => $TotalTeacher,
                                            'color' => 'success',
                                            'icon' => 'bi-person-video3',
                                        ],
                                        [
                                            'label' => 'Students',
                                            'value' => $TotalStudent,
                                            'color' => 'warning',
                                            'icon' => 'bi-people-fill',
                                        ],
                                        [
                                            'label' => 'Parents',
                                            'value' => $TotalParent,
                                            'color' => 'danger',
                                            'icon' => 'bi-house-heart-fill',
                                        ],
                                    ];
                                @endphp

                                @foreach ($items as $item)
                                    @php $pct = round(($item['value'] / $total) * 100, 1); @endphp
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-semibold text-dark">
                                                <i class="bi {{ $item['icon'] }} me-1 text-{{ $item['color'] }}"></i>
                                                {{ $item['label'] }}
                                            </span>
                                            <span class="small text-muted">
                                                <strong class="text-dark">{{ $item['value'] }}</strong> &nbsp;·&nbsp;
                                                {{ $pct }}%
                                            </span>
                                        </div>
                                        <div class="progress prog-bar">
                                            <div class="progress-bar bg-{{ $item['color'] }}"
                                                style="width:{{ $pct }}%" aria-valuenow="{{ $item['value'] }}"
                                                aria-valuemin="0" aria-valuemax="{{ $total }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-between">
                                    <span class="text-muted small">Total registered users</span>
                                    <span class="fw-bold fs-5 text-primary">{{ $total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions grid --}}
                    <div class="col-lg-7">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header py-3">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Quick Actions
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">

                                    {{-- People --}}
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/admin/add') }}"
                                            class="action-tile border-primary border-opacity-25">
                                            <i class="bi bi-person-plus-fill text-primary"></i>
                                            <span>Add Admin</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/teacher/add') }}"
                                            class="action-tile border-success border-opacity-25">
                                            <i class="bi bi-person-video3 text-success"></i>
                                            <span>Add Teacher</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/student/add') }}"
                                            class="action-tile border-warning border-opacity-25">
                                            <i class="bi bi-people-fill text-warning"></i>
                                            <span>Add Student</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/parent/add') }}"
                                            class="action-tile border-danger border-opacity-25">
                                            <i class="bi bi-house-heart-fill text-danger"></i>
                                            <span>Add Parent</span>
                                        </a>
                                    </div>

                                    {{-- Academic --}}
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/class/add') }}"
                                            class="action-tile border-info border-opacity-25">
                                            <i class="bi bi-building text-info"></i>
                                            <span>Add Class</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/subject/add') }}"
                                            class="action-tile border-secondary border-opacity-25">
                                            <i class="bi bi-journal-plus text-secondary"></i>
                                            <span>Add Subject</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/homework/homework/add') }}"
                                            class="action-tile border-secondary border-opacity-25">
                                            <i class="bi bi-pencil-square text-secondary"></i>
                                            <span>Add Homework</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/examination/exam/add') }}"
                                            class="action-tile border-secondary border-opacity-25"
                                            style="border-color:rgba(111,66,193,.3)!important;">
                                            <i class="bi bi-mortarboard-fill" style="color:#6f42c1;"></i>
                                            <span>Add Exam</span>
                                        </a>
                                    </div>

                                    {{-- Attendance & Reports --}}
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/attendance/student_attendance') }}"
                                            class="action-tile border-success border-opacity-25">
                                            <i class="bi bi-person-check-fill text-success"></i>
                                            <span>Attendance</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/attendance/attendance_report') }}"
                                            class="action-tile border-info border-opacity-25">
                                            <i class="bi bi-bar-chart-fill text-info"></i>
                                            <span>Att. Report</span>
                                        </a>
                                    </div>

                                    {{-- Communicate --}}
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/communicate/notice_board/add') }}"
                                            class="action-tile border-warning border-opacity-25">
                                            <i class="bi bi-megaphone-fill text-warning"></i>
                                            <span>Post Notice</span>
                                        </a>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <a href="{{ url('admin/communicate/send_email') }}"
                                            class="action-tile border-primary border-opacity-25">
                                            <i class="bi bi-envelope-fill text-primary"></i>
                                            <span>Send Email</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ════════════════════════════════════════════════
                 ROW 3 : Recent Homework + Recent Notices + Modules
            ════════════════════════════════════════════════ --}}
                <div class="row g-3 mb-4">

                    {{-- Recent Homework --}}
                    <div class="col-lg-5">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-journal-text me-2 text-secondary"></i>Recent Homework
                                </h6>
                                <a href="{{ url('admin/homework/homework') }}"
                                    class="btn btn-sm btn-outline-secondary px-2" style="font-size:.72rem;">
                                    View All <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="card-body p-0">
                                @if (!empty($getRecentHomework) && $getRecentHomework->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($getRecentHomework as $hw)
                                            @php
                                                $deadline = \Carbon\Carbon::parse($hw->submission_date)->endOfDay();
                                                $now = \Carbon\Carbon::now();
                                                $daysLeft = $now->diffInDays($deadline, false);
                                                $isOverdue = $now->gt($deadline);
                                                $isDueSoon = !$isOverdue && $daysLeft <= 3;
                                            @endphp
                                            <div
                                                class="list-group-item list-group-item-action px-3 py-2 border-0 border-bottom">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div
                                                        class="activity-dot bg-secondary bg-opacity-10 text-secondary mt-1">
                                                        <i class="bi bi-journal-text" style="font-size:.8rem;"></i>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between gap-2">
                                                            <div class="fw-semibold small text-dark text-truncate"
                                                                style="max-width:160px;">
                                                                {{ $hw->subject_name ?? 'Homework' }}
                                                            </div>
                                                            @if ($isOverdue)
                                                                <span class="hw-badge bg-danger text-white">Overdue</span>
                                                            @elseif($isDueSoon)
                                                                <span class="hw-badge bg-warning text-dark">Due Soon</span>
                                                            @else
                                                                <span
                                                                    class="hw-badge bg-success bg-opacity-10 text-success">Active</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted" style="font-size:.72rem;">
                                                            <i
                                                                class="bi bi-building me-1"></i>{{ $hw->class_name ?? '—' }}
                                                            &nbsp;·&nbsp;
                                                            <i class="bi bi-calendar3 me-1"></i>Due
                                                            @bsDate($hw->submission_date)
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-journal d-block mb-2 text-muted"
                                            style="font-size:2rem;opacity:.3;"></i>
                                        <div class="text-muted small">No homework assigned yet</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Recent Notices --}}
                    <div class="col-lg-4">
                        <div class="card section-card shadow-sm h-100">
                            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-megaphone-fill me-2 text-warning"></i>Recent Notices
                                </h6>
                                <a href="{{ url('admin/communicate/notice_board') }}"
                                    class="btn btn-sm btn-outline-warning px-2" style="font-size:.72rem;">
                                    View All <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="card-body p-0">
                                @if (!empty($getRecentNotice) && $getRecentNotice->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach ($getRecentNotice as $notice)
                                            <div class="list-group-item border-0 border-bottom px-3 py-2">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="activity-dot bg-warning bg-opacity-10 text-warning mt-1">
                                                        <i class="bi bi-megaphone" style="font-size:.8rem;"></i>
                                                    </div>
                                                    <div class="flex-fill min-width-0">
                                                        <div class="fw-semibold small text-dark text-truncate"
                                                            style="max-width:180px;">
                                                            {{ $notice->title }}
                                                        </div>
                                                        <div class="text-muted" style="font-size:.72rem;">
                                                            <i class="bi bi-calendar3 me-1"></i>@bsDate($notice->notice_date)
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @foreach ($notice->getMessage as $msg)
                                                                @php
                                                                    $rl = [
                                                                        2 => 'Teacher',
                                                                        3 => 'Student',
                                                                        4 => 'Parent',
                                                                    ];
                                                                    $rc = [
                                                                        2 => 'success',
                                                                        3 => 'warning',
                                                                        4 => 'danger',
                                                                    ];
                                                                @endphp
                                                                @if (isset($rl[$msg->message_to]))
                                                                    <span
                                                                        class="badge bg-{{ $rc[$msg->message_to] }} bg-opacity-10 text-{{ $rc[$msg->message_to] }}"
                                                                        style="font-size:.62rem;">
                                                                        {{ $rl[$msg->message_to] }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-megaphone d-block mb-2 text-muted"
                                            style="font-size:2rem;opacity:.3;"></i>
                                        <div class="text-muted small">No notices posted yet</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Exam & Attendance Module cards --}}
                    <div class="col-lg-3">
                        <div class="d-flex flex-column gap-3 h-100">

                            {{-- Examination --}}
                            <div class="card section-card shadow-sm flex-fill">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="stat-icon bg-opacity-10 text-white d-flex align-items-center justify-content-center rounded-3"
                                            style="background:rgba(111,66,193,.15);color:#6f42c1!important;width:44px;height:44px;font-size:1.2rem;">
                                            <i class="bi bi-mortarboard-fill" style="color:#6f42c1;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold small text-dark">Examination</div>
                                            <div class="text-muted" style="font-size:.72rem;">Manage exams &amp; marks
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/examination/exam/list') }}"
                                            class="btn btn-sm flex-fill fw-semibold text-white"
                                            style="background:#6f42c1;font-size:.72rem;">
                                            Exams
                                        </a>
                                        <a href="{{ url('admin/examination/marks_register') }}"
                                            class="btn btn-sm flex-fill btn-outline-secondary fw-semibold"
                                            style="font-size:.72rem;">
                                            Marks
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Attendance --}}
                            <div class="card section-card shadow-sm flex-fill">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="stat-icon"
                                            style="background:rgba(25,135,84,.1);color:#198754;width:44px;height:44px;font-size:1.2rem;">
                                            <i class="bi bi-person-check-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold small text-dark">Attendance</div>
                                            <div class="text-muted" style="font-size:.72rem;">Mark &amp; view reports
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/attendance/student_attendance') }}"
                                            class="btn btn-sm flex-fill btn-success fw-semibold text-white"
                                            style="font-size:.72rem;">
                                            Mark
                                        </a>
                                        <a href="{{ url('admin/attendance/attendance_report') }}"
                                            class="btn btn-sm flex-fill btn-outline-success fw-semibold"
                                            style="font-size:.72rem;">
                                            Report
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- ════════════════════════════════════════════════
                 ROW 4 : Module Navigation Grid
            ════════════════════════════════════════════════ --}}
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card section-card shadow-sm">
                            <div class="card-header py-3">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="bi bi-grid-3x3-gap-fill me-2 text-info"></i>School Modules
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 text-center">

                                    @php
                                        $modules = [
                                            [
                                                'icon' => 'bi-person-badge-fill',
                                                'color' => 'primary',
                                                'label' => 'Admins',
                                                'url' => url('admin/admin/list'),
                                            ],
                                            [
                                                'icon' => 'bi-person-video3',
                                                'color' => 'success',
                                                'label' => 'Teachers',
                                                'url' => url('admin/teacher/list'),
                                            ],
                                            [
                                                'icon' => 'bi-people-fill',
                                                'color' => 'warning',
                                                'label' => 'Students',
                                                'url' => url('admin/student/list'),
                                            ],
                                            [
                                                'icon' => 'bi-house-heart-fill',
                                                'color' => 'danger',
                                                'label' => 'Parents',
                                                'url' => url('admin/parent/list'),
                                            ],
                                            [
                                                'icon' => 'bi-building',
                                                'color' => 'info',
                                                'label' => 'Classes',
                                                'url' => url('admin/class/list'),
                                            ],
                                            [
                                                'icon' => 'bi-journal-bookmark',
                                                'color' => 'secondary',
                                                'label' => 'Subjects',
                                                'url' => url('admin/subject/list'),
                                            ],
                                            [
                                                'icon' => 'bi-mortarboard-fill',
                                                'color' => 'purple',
                                                'label' => 'Exams',
                                                'url' => url('admin/examination/exam/list'),
                                            ],
                                            [
                                                'icon' => 'bi-award-fill',
                                                'color' => 'success',
                                                'label' => 'Grades',
                                                'url' => url('admin/examination/marks_grade/list'),
                                            ],
                                            [
                                                'icon' => 'bi-pencil-square',
                                                'color' => 'secondary',
                                                'label' => 'Homework',
                                                'url' => url('admin/homework/homework'),
                                            ],
                                            [
                                                'icon' => 'bi-person-check-fill',
                                                'color' => 'success',
                                                'label' => 'Students Attendance',
                                                'url' => url('admin/attendance/student_attendance'),
                                            ],
                                            [
                                                'icon' => 'bi-person-check-fill',
                                                'color' => 'success',
                                                'label' => 'Teachers Attendance',
                                                'url' => url('admin/attendance/teacher_attendance'),
                                            ],
                                            [
                                                'icon' => 'bi-bar-chart-fill',
                                                'color' => 'info',
                                                'label' => 'Att. Report',
                                                'url' => url('admin/attendance/attendance_report'),
                                            ],
                                            [
                                                'icon' => 'bi-megaphone-fill',
                                                'color' => 'warning',
                                                'label' => 'Notice Board',
                                                'url' => url('admin/communicate/notice_board'),
                                            ],
                                            [
                                                'icon' => 'bi-envelope-fill',
                                                'color' => 'primary',
                                                'label' => 'Send Email',
                                                'url' => url('admin/communicate/send_email'),
                                            ],
                                            [
                                                'icon' => 'bi-calendar3-week',
                                                'color' => 'secondary',
                                                'label' => 'Timetable',
                                                'url' => url('admin/class/class_timetable'),
                                            ],
                                            [
                                                'icon' => 'bi-shield-lock-fill',
                                                'color' => 'danger',
                                                'label' => 'My Account',
                                                'url' => url('admin/profile/my_account'),
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($modules as $mod)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-1-5">
                                            <a href="{{ $mod['url'] }}"
                                                class="d-flex flex-column align-items-center justify-content-center gap-2 p-3 rounded-3 text-decoration-none module-tile"
                                                style="background:#f8f9fa;transition:all .18s ease;">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                                    style="width:40px;height:40px;font-size:1.1rem;
                                             background:{{ $mod['color'] == 'purple' ? 'rgba(111,66,193,.12)' : '' }};
                                             color:{{ $mod['color'] == 'purple' ? '#6f42c1' : '' }};"
                                                    @if ($mod['color'] !== 'purple') class="bg-{{ $mod['color'] }} bg-opacity-10 text-{{ $mod['color'] }}" @endif>
                                                    <i class="bi {{ $mod['icon'] }}"
                                                        @if ($mod['color'] === 'purple') style="color:#6f42c1;" @endif></i>
                                                </div>
                                                <span class="text-dark fw-semibold"
                                                    style="font-size:.72rem;">{{ $mod['label'] }}</span>
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

    <style>
        .module-tile:hover {
            background: #fff !important;
            transform: translateY(-2px);
            box-shadow: 0 .3rem .8rem rgba(0, 0, 0, .1);
        }

        /* 8-column-ish layout for xl */
        @media (min-width: 1400px) {
            .col-xl-1-5 {
                width: 12.5%;
            }
        }
    </style>

@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInputs = document.querySelectorAll('.nepali-date');
            dateInputs.forEach(function(input) {
                input.NepaliDatePicker({
                    ndpYear: true,
                    ndpMonth: true,
                    readOnlyInput: true
                });
            });
        });
    </script>
@endsection
