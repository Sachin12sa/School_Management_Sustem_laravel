@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-8">
                    <div class="d-flex align-items-center gap-3">
                        @if(!empty(Auth::user()->getProfile()))
                            <img src="{{ Auth::user()->getProfile() }}"
                                 class="rounded-circle shadow flex-shrink-0"
                                 style="width:54px;height:54px;object-fit:cover;border:3px solid rgba(25,135,84,.3);">
                        @else
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold shadow flex-shrink-0"
                                 style="width:54px;height:54px;font-size:1.3rem;">
                                {{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name??'',0,1)) }}
                            </div>
                        @endif
                        <div>
                            @php
                                $h = now()->hour;
                                $greet = $h < 12 ? 'Good Morning' : ($h < 17 ? 'Good Afternoon' : 'Good Evening');
                            @endphp
                            <p class="mb-0 text-muted small">{{ $greet }},</p>
                            <h4 class="mb-0 fw-bold text-dark">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h4>
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 small mt-1">
                                <i class="bi bi-person-video3 me-1"></i>Teacher
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-end text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- Stat Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-dark lh-1">{{ $TotalStudent ?? '0' }}</div>
                                <div class="text-muted small mt-1">My Students</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <a href="{{ url('teacher/my_student') }}" class="text-success text-decoration-none small">
                                View all <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-dark lh-1">{{ $TotalSubject ?? '0' }}</div>
                                <div class="text-muted small mt-1">My Subjects</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <a href="{{ url('teacher/my_class_subject') }}" class="text-info text-decoration-none small">
                                View all <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-clipboard2-check-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-dark lh-1">{{ $TotalHomework ?? '0' }}</div>
                                <div class="text-muted small mt-1">Homework Given</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <a href="{{ url('teacher/homework/homework') }}" class="text-warning text-decoration-none small">
                                View all <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-dark lh-1">{{ $TotalNotice ?? '0' }}</div>
                                <div class="text-muted small mt-1">Notices</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <a href="{{ url('teacher/my_notice_board') }}" class="text-danger text-decoration-none small">
                                View all <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Quick Actions --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-success"></i>
                            <h6 class="mb-0 fw-semibold">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @php
                                $actions = [
                                    ['url'=>'teacher/my_student',                    'icon'=>'bi-people-fill',           'color'=>'success',   'label'=>'My Students'],
                                    ['url'=>'teacher/my_class_subject',              'icon'=>'bi-journal-bookmark-fill', 'color'=>'info',      'label'=>'Class & Subject'],
                                    ['url'=>'teacher/my_exam_timetable',             'icon'=>'bi-calendar3-week-fill',   'color'=>'warning',   'label'=>'Exam Timetable'],
                                    ['url'=>'teacher/my_calender',                   'icon'=>'bi-calendar-event-fill',   'color'=>'primary',   'label'=>'My Calendar'],
                                    ['url'=>'teacher/marks_register',                'icon'=>'bi-pencil-square',         'color'=>'danger',    'label'=>'Marks Register'],
                                    ['url'=>'teacher/attendance/student_attendance', 'icon'=>'bi-person-check-fill',     'color'=>'success',   'label'=>'Take Attendance'],
                                    ['url'=>'teacher/attendance/attendance_report',  'icon'=>'bi-bar-chart-fill',        'color'=>'info',      'label'=>'Attendance Report'],
                                    ['url'=>'teacher/homework/homework',             'icon'=>'bi-clipboard2-fill',       'color'=>'warning',   'label'=>'Homework'],
                                    ['url'=>'teacher/homework/homework_report',      'icon'=>'bi-clipboard2-check-fill', 'color'=>'primary',   'label'=>'HW Report'],
                                    ['url'=>'teacher/my_notice_board',               'icon'=>'bi-megaphone-fill',        'color'=>'danger',    'label'=>'Notice Board'],
                                    ['url'=>'teacher/account',                       'icon'=>'bi-person-circle',         'color'=>'secondary', 'label'=>'My Account'],
                                    ['url'=>'teacher/profile/change_password',       'icon'=>'bi-lock-fill',             'color'=>'secondary', 'label'=>'Change Password'],
                                ];
                                @endphp
                                @foreach($actions as $a)
                                <div class="col-md-3 col-6">
                                    <a href="{{ url($a['url']) }}"
                                       class="text-decoration-none d-flex flex-column align-items-center gap-2 p-3 rounded-3 border h-100 text-center action-tile">
                                        <div class="rounded-circle bg-{{ $a['color'] }} bg-opacity-10 text-{{ $a['color'] }} d-flex align-items-center justify-content-center"
                                             style="width:44px;height:44px;font-size:1.1rem;">
                                            <i class="bi {{ $a['icon'] }}"></i>
                                        </div>
                                        <span class="small fw-semibold text-dark" style="font-size:.74rem;line-height:1.2;">{{ $a['label'] }}</span>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle text-success"></i>
                            <h6 class="mb-0 fw-semibold">My Profile</h6>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center text-center py-4 gap-3">
                            @if(!empty(Auth::user()->getProfile()))
                                <img src="{{ Auth::user()->getProfile() }}"
                                     class="rounded-circle shadow"
                                     style="width:90px;height:90px;object-fit:cover;border:3px solid rgba(25,135,84,.3);">
                            @else
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold shadow"
                                     style="width:90px;height:90px;font-size:2rem;">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name??'',0,1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                                <span class="badge bg-success bg-opacity-10 text-success mt-1">Teacher</span>
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between border-bottom py-2 small">
                                    <span class="text-muted"><i class="bi bi-phone me-1"></i>Mobile</span>
                                    <span class="fw-semibold text-dark">{{ Auth::user()->mobile_number ?? '—' }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2 small">
                                    <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>Joined</span>
                                    <span class="fw-semibold text-dark">
                                        {{ Auth::user()->admission_date ? \Carbon\Carbon::parse(Auth::user()->admission_date)->format('d M Y') : '—' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between py-2 small">
                                    <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Address</span>
                                    <span class="fw-semibold text-dark text-end text-truncate ms-2" style="max-width:140px;">
                                        {{ Auth::user()->address ?? '—' }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ url('teacher/account') }}" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-pencil-fill me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</main>

<style>
.action-tile { transition: all .15s ease; background: #f8f9fa; }
.action-tile:hover { background: rgba(25,135,84,.06) !important; border-color: rgba(25,135,84,.3) !important; transform: translateY(-2px); }
</style>
@endsection