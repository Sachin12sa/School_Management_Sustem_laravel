<!--begin::Footer-->
<footer class="app-footer">
    @php
        $__bsYear = \App\Helpers\NepaliCalendar::currentYear();
        $__adYear = \Carbon\Carbon::now()->format('Y');
        $__prefix = match (Auth::user()->user_type ?? 1) {
            1 => 'admin',
            2 => 'teacher',
            3 => 'student',
            4 => 'parent',
            5 => 'accountant',
            6 => 'librarian',
            default => 'admin',
        };
        $__roleLabel = match (Auth::user()->user_type ?? 1) {
            1 => 'Admin',
            2 => 'Teacher',
            3 => 'Student',
            4 => 'Parent',
            5 => 'Accountant',
            6 => 'Librarian',
            default => 'User',
        };
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100" style="font-size:.8rem;">

        {{-- Left: Copyright --}}
        <div class="d-flex align-items-center gap-2 text-muted">
            <i class="bi bi-c-circle text-primary"></i>
            <span>
                <strong class="text-dark">{{ $__adYear }}</strong>
                &nbsp;·&nbsp;
                <a href="{{ url('') }}" class="text-decoration-none fw-semibold text-primary">
                    Brain Fart Institute
                </a>
                &nbsp;·&nbsp; All rights reserved.
            </span>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"
                style="font-size:.68rem;">
                {{ $__bsYear }} B.S.
            </span>
        </div>

        {{-- Right: quick links + version --}}
        <div class="d-flex align-items-center gap-3 text-muted">
            <a href="{{ url($__prefix . '/dashboard') }}"
                class="text-decoration-none text-muted d-flex align-items-center gap-1">
                <i class="bi bi-speedometer2 text-primary"></i>
                <span class="d-none d-sm-inline">Dashboard</span>
            </a>

            <a href="{{ url($__prefix . '/account') }}"
                class="text-decoration-none text-muted d-flex align-items-center gap-1">
                <i class="bi bi-person-circle text-success"></i>
                <span class="d-none d-sm-inline">{{ $__roleLabel }}: {{ Auth::user()->name }}</span>
            </a>

            <a href="{{ url($__prefix . '/profile/change_password') }}"
                class="text-decoration-none text-muted d-flex align-items-center gap-1">
                <i class="bi bi-shield-lock text-warning"></i>
                <span class="d-none d-md-inline">Security</span>
            </a>

            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                style="font-size:.65rem;">
                <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>
                Online
            </span>
        </div>

    </div>
</footer>
<!--end::Footer-->
