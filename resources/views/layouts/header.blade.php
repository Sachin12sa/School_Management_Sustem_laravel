{{-- ============================================================
     TOP NAVBAR
     ============================================================ --}}
<nav class="app-header navbar navbar-expand bg-body shadow-sm">
    <div class="container-fluid">

        {{-- Sidebar Toggle --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list fs-5"></i>
                </a>
            </li>

            <li class="nav-item d-none d-md-block">
                <span class="nav-link text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>
                    <span id="nepal-datetime"></span>
                </span>
            </li>
        </ul>

        <script>
            function updateNepalTime() {
                const options = {
                    timeZone: 'Asia/Kathmandu',
                    weekday: 'long',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };

                const now = new Date().toLocaleString('en-US', options);
                document.getElementById("nepal-datetime").innerHTML = now;
            }

            setInterval(updateNepalTime, 1000);
            updateNepalTime();
        </script>

        {{-- Right-side Nav Items --}}
        <ul class="navbar-nav ms-auto align-items-center">

            {{-- Messages Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" title="Messages">
                    <i class="bi bi-chat-text fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size:.6rem;">
                        3
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0 p-0"
                    style="min-width:320px;">
                    <div
                        class="dropdown-header bg-primary text-white rounded-top px-3 py-2 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-chat-text me-1"></i> Messages</span>
                        <span class="badge bg-white text-primary">3 New</span>
                    </div>

                    @php
                        $messages = [
                            [
                                'img' => 'user1-128x128.jpg',
                                'name' => 'Brad Diesel',
                                'text' => 'Call me whenever you can...',
                                'time' => '4 Hours Ago',
                                'star' => 'text-danger',
                            ],
                            [
                                'img' => 'user8-128x128.jpg',
                                'name' => 'John Pierce',
                                'text' => 'I got your message bro',
                                'time' => '6 Hours Ago',
                                'star' => 'text-secondary',
                            ],
                            [
                                'img' => 'user3-128x128.jpg',
                                'name' => 'Nora Silvester',
                                'text' => 'The subject goes here',
                                'time' => '1 Day Ago',
                                'star' => 'text-warning',
                            ],
                        ];
                    @endphp

                    @foreach ($messages as $msg)
                        <a href="#" class="dropdown-item py-2 px-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <img src="{{ asset('dist/assets/img/' . $msg['img']) }}" alt="{{ $msg['name'] }}"
                                    class="rounded-circle me-3 flex-shrink-0"
                                    style="width:42px;height:42px;object-fit:cover;">
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between">
                                        <strong class="text-dark small">{{ $msg['name'] }}</strong>
                                        <i class="bi bi-star-fill {{ $msg['star'] }} small"></i>
                                    </div>
                                    <p class="text-muted small mb-0 text-truncate">{{ $msg['text'] }}</p>
                                    <p class="text-secondary" style="font-size:.7rem;">
                                        <i class="bi bi-clock me-1"></i>{{ $msg['time'] }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach

                    <a href="#" class="dropdown-item text-center text-primary py-2 small fw-semibold">
                        <i class="bi bi-arrow-right-circle me-1"></i> See All Messages
                    </a>
                </div>
            </li>

            {{-- Notifications Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" title="Notifications">
                    <i class="bi bi-bell-fill fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                        style="font-size:.6rem;">
                        1
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0 p-0"
                    style="min-width:300px;">
                    <div
                        class="dropdown-header bg-warning text-dark rounded-top px-3 py-2 d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-bell me-1"></i> Notifications</span>
                        <span class="badge bg-dark text-white">15</span>
                    </div>

                    <a href="#" class="dropdown-item py-2 px-3 border-bottom d-flex align-items-center">
                        <span
                            class="me-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:36px;height:36px;">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <div>
                            <div class="small fw-semibold text-dark">4 new messages</div>
                            <div class="text-secondary" style="font-size:.72rem;"><i class="bi bi-clock me-1"></i>3 mins
                                ago</div>
                        </div>
                    </a>
                    <a href="#" class="dropdown-item py-2 px-3 border-bottom d-flex align-items-center">
                        <span
                            class="me-3 bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:36px;height:36px;">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <div>
                            <div class="small fw-semibold text-dark">8 new enrolment requests</div>
                            <div class="text-secondary" style="font-size:.72rem;"><i class="bi bi-clock me-1"></i>12
                                hours ago</div>
                        </div>
                    </a>
                    <a href="#" class="dropdown-item py-2 px-3 border-bottom d-flex align-items-center">
                        <span
                            class="me-3 bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:36px;height:36px;">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </span>
                        <div>
                            <div class="small fw-semibold text-dark">3 new reports submitted</div>
                            <div class="text-secondary" style="font-size:.72rem;"><i class="bi bi-clock me-1"></i>2 days
                                ago</div>
                        </div>
                    </a>

                    <a href="#" class="dropdown-item text-center text-primary py-2 small fw-semibold">
                        <i class="bi bi-arrow-right-circle me-1"></i> See All Notifications
                    </a>
                </div>
            </li>

            {{-- Divider --}}
            <li class="nav-item d-none d-md-block">
                <span class="nav-link text-muted px-1">|</span>
            </li>

            {{-- User Profile Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 pe-0" data-bs-toggle="dropdown"
                    href="#" role="button">
                    {{-- SMALL NAV ICON --}}
                    <img src="{{ Auth::user()->getProfile() }}" alt="{{ Auth::user()->name }}"
                        class="rounded-circle border border-2 border-primary"
                        style="width:34px;height:34px;object-fit:cover;">
                    {{ Auth::user()->name }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="min-width:220px;">
                    {{-- DROPDOWN HEADER PIC --}}
                    <div class="px-3 py-3 bg-light rounded-top border-bottom d-flex align-items-center gap-3">
                        <img src="{{ Auth::user()->getProfile() }}" alt="{{ Auth::user()->name }}"
                            class="rounded-circle border border-2 border-primary"
                            style="width:34px;height:34px;object-fit:cover;">
                        <div>
                            <div class="fw-semibold text-dark small">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size:.72rem;">
                                @if (Auth::user()->user_type == 1)
                                    Admin
                                @elseif(Auth::user()->user_type == 2)
                                    Teacher
                                @elseif(Auth::user()->user_type == 3)
                                    Student
                                @elseif(Auth::user()->user_type == 4)
                                    Parent
                                @elseif(Auth::user()->user_type == 5)
                                    Accountant
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Menu Items --}}
                    @php
                        $prefix = match (Auth::user()->user_type) {
                            1 => 'admin',
                            2 => 'teacher',
                            3 => 'student',
                            4 => 'parent',
                            5 => 'accountant',
                            default => 'admin',
                        };
                    @endphp
                    <a href="{{ url($prefix . '/account') }}"
                        class="dropdown-item py-2 px-3 d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-primary"></i> My Profile
                    </a>
                    <a href="{{ url($prefix . '/profile/change_password') }}"
                        class="dropdown-item py-2 px-3 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock text-warning"></i> Change Password
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <a href="{{ route('logout') }}"
                        class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-danger"
                        onclick="event.preventDefault(); document.getElementById('nav-logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </a>
                    <form id="nav-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>

        </ul>
    </div>
</nav>


{{-- ============================================================
     SIDEBAR
     ============================================================ --}}
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <a href="#" class="brand-link d-flex align-items-center gap-2 px-3 py-2">
            <img src="{{ asset('dist/assets/img/school.png') }}" class="brand-image opacity-75 shadow"
                alt="Logo" style="width:32px;height:32px;object-fit:contain;">
            <span class="brand-text fw-semibold fs-6">Brain Fart Institute</span>
        </a>
    </div>

    {{-- User Panel --}}
    <div class="user-panel mt-3 pb-4 mb-3 mx-4 d-flex align-items-center border-bottom border-secondary">
        <div class="image">
            {{-- Updated logic to check the profile_pic column and the upload/profile folder --}}
            <img src="{{ Auth::user()->getProfile() }}" alt="{{ Auth::user()->name }}"
                class="rounded-circle border border-2 border-primary"
                style="width:34px;height:34px;object-fit:cover;">
        </div>
        <div class="info ps-2">
            {{-- Linking to a profile page (optional, you can keep # if not ready) --}}
            <a href="{{ url(Auth::user()->user_type == 1 ? 'admin/account' : '#') }}"
                class="d-block text-white fw-semibold small text-truncate" style="max-width:160px;">
                {{ Auth::user()->name }} {{ Auth::user()->last_name }}
            </a>
            <span class="badge bg-primary bg-opacity-75 mt-1" style="font-size:.65rem;">
                @if (Auth::user()->user_type == 1)
                    Admin
                @elseif(Auth::user()->user_type == 2)
                    Teacher
                @elseif(Auth::user()->user_type == 3)
                    Student
                @elseif(Auth::user()->user_type == 4)
                    Parent
                @elseif(Auth::user()->user_type == 5)
                    Accountant
                @endif
            </span>
        </div>
    </div>

    {{-- Sidebar Menu --}}
    <div class="sidebar-wrapper">
        <nav class="mt-2 pb-3">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">


                {{-- ================================================
                     ADMIN MENU  (user_type == 1)
                     ================================================ --}}
                @if (Auth::user()->user_type == 1)
                    <li class="nav-header text-uppercase text-secondary small px-3 mb-1"
                        style="font-size:.67rem;letter-spacing:.08em;">Main</li>

                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}"
                            class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                        style="font-size:.67rem;letter-spacing:.08em;">Users</li>

                    <li class="nav-item">
                        <a href="{{ url('admin/admin/list') }}"
                            class="nav-link {{ request()->is('admin/admin*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-badge"></i>
                            <p>Admins</p>
                        </a>
                    </li>
                    @php
                        $staffRoutes = ['admin/teacher*', 'admin/accountant*', 'admin/librarian*'];
                        $adminStaffActive = request()->is($staffRoutes);
                    @endphp

                    <li class="nav-item {{ $adminStaffActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $adminStaffActive ? 'active' : '' }}">
                            <i class="nav-icon bi bi-megaphone-fill"></i>
                            <p>Staff <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>

                        <ul class="nav nav-treeview">
                            @php
                                $staffLinks = [
                                    [
                                        'url' => 'admin/teacher/list',
                                        'icon' => 'bi-person-video3',
                                        'label' => 'Teachers',
                                    ],
                                    [
                                        'url' => 'admin/accountant/list',
                                        'icon' => 'bi-person-badge',
                                        'label' => 'Accountants',
                                    ],
                                    [
                                        'url' => 'admin/librarian/list',
                                        'icon' => 'bi-person-badge',
                                        'label' => 'Librarians',
                                    ],
                                ];
                            @endphp

                            @foreach ($staffLinks as $link)
                                <li class="nav-item">
                                    <a href="{{ url($link['url']) }}"
                                        class="nav-link {{ request()->is(str_replace('list', '*', $link['url'])) ? 'active' : '' }}">
                                        <i class="nav-icon bi {{ $link['icon'] }}"></i>
                                        <p>{{ $link['label'] }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('admin/student/list') }}"
                            class="nav-link {{ request()->is('admin/student*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Students</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/parent/list') }}"
                            class="nav-link {{ request()->is('admin/parent*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-house-heart-fill"></i>
                            <p>Parents</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                        style="font-size:.67rem;letter-spacing:.08em;">Academic</li>

                    {{-- Academic Submenu --}}
                    @php
                        $academicActive = request()->is([
                            'admin/class*',
                            'admin/subject*',
                            'admin/assign_subject*',
                            'admin/assign_class_teacher*',
                            'admin/class_timetable*',
                        ]);
                    @endphp
                    <li class="nav-item {{ $academicActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $academicActive ? 'active' : '' }}">
                            <i class="nav-icon bi bi-mortarboard-fill"></i>
                            <p>Academic <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/class/list') }}"
                                    class="nav-link {{ request()->is('admin/class*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-building"></i>
                                    <p>Classes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/subject/list') }}"
                                    class="nav-link {{ request()->is('admin/subject*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                                    <p>Subjects</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/assign_subject/list') }}"
                                    class="nav-link {{ request()->is('admin/assign_subject*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-pencil-square"></i>
                                    <p>Assign Subject</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/assign_class_teacher/list') }}"
                                    class="nav-link {{ request()->is('admin/assign_class_teacher*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-person-check-fill"></i>
                                    <p>Assign Class Teacher</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/class_timetable/list') }}"
                                    class="nav-link {{ request()->is('admin/class_timetable*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-calendar3-week"></i>
                                    <p>Class Timetable</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Examinations Submenu --}}
                    @php
                        $examActive = request()->is([
                            'admin/examination/exam/list*',
                            'admin/examination/exam_schedule*',
                            'admin/examination/marks_register*',
                            'admin/examination/marks_grade/list*',
                        ]);
                    @endphp
                    <li class="nav-item {{ $examActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $examActive ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard2-check-fill"></i>
                            <p>Examinations <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/examination/exam/list') }}"
                                    class="nav-link {{ request()->is('admin/examination/exam/list*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-card-list"></i>
                                    <p>Exam List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/examination/exam_schedule') }}"
                                    class="nav-link {{ request()->is('admin/examination/exam_schedule*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-calendar-event-fill"></i>
                                    <p>Exam Schedule</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/examination/marks_register') }}"
                                    class="nav-link {{ request()->is('admin/examination/marks_register*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-table"></i>
                                    <p>Marks Register</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/examination/marks_grade/list') }}"
                                    class="nav-link {{ request()->is('admin/examination/marks_grade/list*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-award-fill"></i>
                                    <p>Marks Grade</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Library Submenu --}}
                    @php
                        $adminLibraryActive = request()->is(['admin/library*']);
                    @endphp
                    <li class="nav-item {{ $adminLibraryActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $adminLibraryActive ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book-fill"></i>
                            <p>Library <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/library/book/list') }}"
                                    class="nav-link {{ request()->is('admin/library/book*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-journals"></i>
                                    <p>Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/library/issue/list') }}"
                                    class="nav-link {{ request()->is('admin/library/issue*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-journal-arrow-up"></i>
                                    <p>Issue / Return</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/library/fine/list') }}"
                                    class="nav-link {{ request()->is('admin/library/fine*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-cash-coin"></i>
                                    <p>Library Fines</p>
                                </a>
                            </li>
                        </ul>
                    </li>


                    {{-- Attendance Submenu --}}
                    @php
                        $adminAttendanceActive = request()->is([
                            'admin/attendance/student_attendance*',
                            'admin/attendance/attendance_report*',
                            'admin/attendance/teacher_attendance',
                            'admin/attendance/teacher_attendance_report',
                        ]);
                    @endphp
                    <li class="nav-item {{ $adminAttendanceActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $adminAttendanceActive ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-check"></i>
                            <p>Attendance <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/attendance/student_attendance') }}"
                                    class="nav-link {{ request()->is('admin/attendance/student_attendance*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-calendar-check"></i>
                                    <p>Student Attendance</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/attendance/attendance_report') }}"
                                    class="nav-link {{ request()->is('admin/attendance/attendance_report*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                                    <p>Attendance Report</p>
                                </a>
                            </li>
                            {{-- </ul>
                    </li> --}}
                            {{-- <li class="nav-item {{ request()->is('admin/attendance/teacher_attendance*') ? 'menu-open' : '' }}">
                    <a href="#"
                    class="nav-link {{ request()->is('admin/attendance/teacher_attendance*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-check-fill"></i>
                        <p>
                            Teacher Attendance
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a> --}}
                            {{-- <ul class="nav nav-treeview">
                        <li class="nav-item"> --}}
                            <a href="{{ url('admin/attendance/teacher_attendance') }}"
                                class="nav-link {{ request()->is('admin/attendance/teacher_attendance*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-pencil-square"></i>
                                <p>Teacher Attendance</p>
                            </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/attendance/teacher_attendance_report') }}"
                            class="nav-link {{ request()->is('admin/attendance/teacher_attendance_report*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bar-chart-fill"></i>
                            <p>Attendance Report</p>
                        </a>
                    </li>
            </ul>
            {{-- </li> --}}

            {{-- Communicate Submenu --}}
            @php
                $adminCommunicateActive = request()->is([
                    'admin/communicate/notice_board*',
                    'admin/communicate/send_email*',
                ]);
            @endphp
            <li class="nav-item {{ $adminCommunicateActive ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $adminCommunicateActive ? 'active' : '' }}">
                    <i class="nav-icon bi bi-megaphone-fill"></i>
                    <p>Communicate <i class="nav-arrow bi bi-chevron-right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('admin/communicate/notice_board') }}"
                            class="nav-link {{ request()->is('admin/communicate/notice_board*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-pin-angle-fill"></i>
                            <p>Notice Board</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/communicate/send_email') }}"
                            class="nav-link {{ request()->is('admin/communicate/send_email*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-envelope-fill"></i>
                            <p>Send Email</p>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Homework Submenu --}}
            @php
                $adminHomeworkActive = request()->is(['admin/homework/homework*', 'admin/homework/homework_report*']);
            @endphp
            <li class="nav-item {{ $adminHomeworkActive ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $adminHomeworkActive ? 'active' : '' }}">
                    <i class="nav-icon bi bi-journal-text"></i>
                    <p>Home Work <i class="nav-arrow bi bi-chevron-right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('admin/homework/homework') }}"
                            class="nav-link {{ request()->is('admin/homework/homework*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-pencil-fill"></i>
                            <p>Homework</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/homework/homework_report') }}"
                            class="nav-link {{ request()->is('admin/homework/homework_report*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>
                            <p>Homework Report</p>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- Fee Management Submenu --}}
            @php
                $adminFeeActive = request()->is(['admin/fee_type*', 'admin/fee*']);
            @endphp
            <li class="nav-item {{ $adminFeeActive ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $adminFeeActive ? 'active' : '' }}">
                    <i class="nav-icon bi bi-cash-coin"></i>
                    <p>Fee Management <i class="nav-arrow bi bi-chevron-right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('admin/fee_type/list') }}"
                            class="nav-link {{ request()->is('admin/fee_type*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags-fill"></i>
                            <p>Fee Types</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/fee/list') }}"
                            class="nav-link {{ request()->is('admin/fee*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-receipt-cutoff"></i>
                            <p>Student Fees</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/fee/add') }}"
                            class="nav-link {{ request()->is('admin/fee/add*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-plus-circle-fill"></i>
                            <p>Assign Fee</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/fee/payment_report') }}"
                            class="nav-link {{ request()->is('admin/fee/payment_report*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                            <p>Payment Report</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                style="font-size:.67rem;letter-spacing:.08em;">Account</li>

            <li class="nav-item">
                <a href="{{ url('admin/account') }}"
                    class="nav-link {{ request()->is('admin/account*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person-circle"></i>
                    <p>My Account</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('admin/profile/change_password') }}"
                    class="nav-link {{ request()->is('admin/change_password*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-shield-lock-fill"></i>
                    <p>Change Password</p>
                </a>
            </li>
            @endif


            {{-- ================================================
                     TEACHER MENU  (user_type == 2)
                     ================================================ --}}
            @if (Auth::user()->user_type == 2)
                <li class="nav-header text-uppercase text-secondary small px-3 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Main</li>

                <li class="nav-item">
                    <a href="{{ url('teacher/dashboard') }}"
                        class="nav-link {{ request()->is('teacher/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/my_student') }}"
                        class="nav-link {{ request()->is('teacher/my_student*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>My Students</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/my_class_subject') }}"
                        class="nav-link {{ request()->is('teacher/my_class_subject*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                        <p>My Class &amp; Subject Timetable</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/my_exam_timetable') }}"
                        class="nav-link {{ request()->is('teacher/my_exam_timetable*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event-fill"></i>
                        <p>My Exam Timetable</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/library/my_books') }}"
                        class="nav-link {{ request()->is('teacher/library*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-book-fill"></i>
                        <p>My Books</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/library/my_fines') }}"
                        class="nav-link {{ request()->is('teacher/library/my_fines*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Library Fines</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/my_calender') }}"
                        class="nav-link {{ request()->is('teacher/my_calender*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar3"></i>
                        <p>My Calendar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/marks_register') }}"
                        class="nav-link {{ request()->is('teacher/marks_register*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard2-data-fill"></i>
                        <p>Marks Register</p>
                    </a>
                </li>

                {{-- Teacher Attendance Submenu --}}
                @php
                    $teacherAttendanceActive = request()->is([
                        'teacher/attendance/student_attendance*',
                        'teacher/attendance/attendance_report*',
                    ]);
                @endphp
                <li class="nav-item {{ $teacherAttendanceActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $teacherAttendanceActive ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-check"></i>
                        <p>Attendance <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('teacher/attendance/student_attendance') }}"
                                class="nav-link {{ request()->is('teacher/attendance/student_attendance*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-calendar-check"></i>
                                <p>Student Attendance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('teacher/attendance/attendance_report') }}"
                                class="nav-link {{ request()->is('teacher/attendance/attendance_report*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                                <p>Attendance Report</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ url('teacher/my_notice_board') }}"
                        class="nav-link {{ request()->is('teacher/my_notice_board*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-pin-angle-fill"></i>
                        <p>Notice Board</p>
                    </a>
                </li>

                {{-- Teacher Homework Submenu --}}
                @php
                    $teacherHomeworkActive = request()->is([
                        'teacher/homework/homework*',
                        'teacher/homework/homework_report*',
                    ]);
                @endphp

                <li class="nav-item {{ $teacherHomeworkActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $teacherHomeworkActive ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Home Work <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ url('teacher/homework/homework') }}"
                                class="nav-link {{ request()->is('teacher/homework/homework*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-pencil-fill"></i>
                                <p>Homework</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('teacher/homework/homework_report') }}"
                                class="nav-link {{ request()->is('teacher/homework/homework_report*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>
                                <p>Homework Report</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Account</li>

                <li class="nav-item">
                    <a href="{{ url('teacher/account') }}"
                        class="nav-link {{ request()->is('teacher/account*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('teacher/profile/change_password') }}"
                        class="nav-link {{ request()->is('teacher/change_password*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-lock-fill"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            @endif


            {{-- ================================================
                     STUDENT MENU  (user_type == 3)
                     ================================================ --}}
            @if (Auth::user()->user_type == 3)
                <li class="nav-header text-uppercase text-secondary small px-3 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Main</li>

                <li class="nav-item">
                    <a href="{{ url('student/dashboard') }}"
                        class="nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_subject') }}"
                        class="nav-link {{ request()->is('student/my_subject*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                        <p>My Subjects</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_timetable') }}"
                        class="nav-link {{ request()->is('student/my_timetable*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar3-week"></i>
                        <p>My Timetable</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_exam_timetable') }}"
                        class="nav-link {{ request()->is('student/my_exam_timetable*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event-fill"></i>
                        <p>Exam Timetable</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_calender') }}"
                        class="nav-link {{ request()->is('student/my_calender*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar3"></i>
                        <p>My Calendar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_exam_result') }}"
                        class="nav-link {{ request()->is('student/my_exam_result*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-award-fill"></i>
                        <p>Exam Results</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_attendance') }}"
                        class="nav-link {{ request()->is('student/my_attendance*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-check"></i>
                        <p>My Attendance</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/library/my_books') }}"
                        class="nav-link {{ request()->is('student/library*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-book-fill"></i>
                        <p>My Books</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/library/my_fines') }}"
                        class="nav-link {{ request()->is('student/library/my_fines*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Library Fines</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_notice_board') }}"
                        class="nav-link {{ request()->is('student/my_notice_board*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-pin-angle-fill"></i>
                        <p>Notice Board</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_homework') }}"
                        class="nav-link {{ request()->is('student/my_homework*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>My Homework</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_submitted_homework') }}"
                        class="nav-link {{ request()->is('student/my_submitted_homework*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-check2-square"></i>
                        <p>Submitted Homework</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/my_fees') }}"
                        class="nav-link {{ request()->is('student/my_fees*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>My Fees</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Account</li>

                <li class="nav-item">
                    <a href="{{ url('student/account') }}"
                        class="nav-link {{ request()->is('student/account*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('student/profile/change_password') }}"
                        class="nav-link {{ request()->is('student/change_password*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-lock-fill"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            @endif


            {{-- ================================================
                     PARENT MENU  (user_type == 4)
                     ================================================ --}}
            @if (Auth::user()->user_type == 4)
                <li class="nav-header text-uppercase text-secondary small px-3 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Main</li>

                <li class="nav-item">
                    <a href="{{ url('parent/dashboard') }}"
                        class="nav-link {{ request()->is('parent/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('parent/my_student') }}"
                        class="nav-link {{ request()->is('parent/my_student*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>My Children</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('parent/my_notice_board') }}"
                        class="nav-link {{ request()->is('parent/my_notice_board*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-pin-angle-fill"></i>
                        <p>Notice Board</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Account</li>

                <li class="nav-item">
                    <a href="{{ url('parent/account') }}"
                        class="nav-link {{ request()->is('parent/account*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('parent/profile/change_password') }}"
                        class="nav-link {{ request()->is('parent/change_password*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-lock-fill"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            @endif


            {{-- ================================================
                     ACCOUNTANT MENU  (user_type == 5)
                     ================================================ --}}
            @if (Auth::user()->user_type == 5)
                <li class="nav-header text-uppercase text-secondary small px-3 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Main</li>

                <li class="nav-item">
                    <a href="{{ url('accountant/dashboard') }}"
                        class="nav-link {{ request()->is('accountant/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Fee Management</li>

                <li class="nav-item">
                    <a href="{{ url('accountant/fee/list') }}"
                        class="nav-link {{ request()->is('accountant/fee*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Fee Collection</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('accountant/fee/payment_report') }}"
                        class="nav-link {{ request()->is('accountant/fee/payment_report*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                        <p>Payment Report</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Account</li>

                <li class="nav-item">
                    <a href="{{ url('accountant/account') }}"
                        class="nav-link {{ request()->is('accountant/account*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('accountant/profile/change_password') }}"
                        class="nav-link {{ request()->is('accountant/change_password*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-lock-fill"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                {{-- Librarian  --}}
            @endif
            @if (Auth::user()->user_type == 6)
                <li class="nav-header text-uppercase text-secondary small px-3 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Main</li>

                <li class="nav-item">
                    <a href="{{ url('librarian/dashboard') }}"
                        class="nav-link {{ request()->is('librarian/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Book Management</li>
                @php
                    $adminLibraryActive = request()->is(['admin/library*']);
                @endphp
                <li class="nav-item {{ $adminLibraryActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $adminLibraryActive ? 'active' : '' }}">
                        <i class="nav-icon bi bi-book-fill"></i>
                        <p>Library <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('librarian/library/book/list') }}"
                                class="nav-link {{ request()->is('librarian/library/book*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-journals"></i>
                                <p>Books</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('librarian/library/issue/list') }}"
                                class="nav-link {{ request()->is('librarian/library/issue*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-journal-arrow-up"></i>
                                <p>Issue / Return</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('librarian/library/fine/list') }}"
                                class="nav-link {{ request()->is('librarian/library/fine*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-cash-coin"></i>
                                <p>Library Fines</p>
                            </a>
                        </li>
                    </ul>
                </li>



                <li class="nav-header text-uppercase text-secondary small px-3 mt-2 mb-1"
                    style="font-size:.67rem;letter-spacing:.08em;">Account</li>

                <li class="nav-item">
                    <a href="{{ url('librarian/my_account') }}"
                        class="nav-link {{ request()->is('librarian/my_account*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>My Account</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('librarian/profile/change_password') }}"
                        class="nav-link {{ request()->is('librarian/change_password*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-shield-lock-fill"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            @endif


            {{-- ================================================
                     LOGOUT  (all users)
                     ================================================ --}}
            <li class="nav-item mt-3 border-top border-secondary pt-2">
                <a href="{{ route('logout') }}" class="nav-link text-danger"
                    onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <p>Sign Out</p>
                </a>
                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

            </ul>
        </nav>
    </div>
</aside>
