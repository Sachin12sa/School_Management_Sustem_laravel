{{-- ============================================================
     HEADER & SIDEBAR — School Management System
     ============================================================ --}}

@php
    $__u = Auth::user();
    $__type = $__u->user_type ?? 1;

    $__roleLabel = match ($__type) {
        1 => 'Administrator',
        2 => 'Teacher',
        3 => 'Student',
        4 => 'Parent',
        5 => 'Accountant',
        6 => 'Librarian',
        default => 'User',
    };
    $__roleShort = match ($__type) {
        1 => 'Admin',
        2 => 'Teacher',
        3 => 'Student',
        4 => 'Parent',
        5 => 'Accountant',
        6 => 'Librarian',
        default => 'User',
    };
    $__roleColor = match ($__type) {
        1 => '#ef4444',
        2 => '#22c55e',
        3 => '#f59e0b',
        4 => '#06b6d4',
        5 => '#3b82f6',
        6 => '#8b5cf6',
        default => '#6b7280',
    };
    $__roleBg = match ($__type) {
        1 => 'rgba(239,68,68,.18)',
        2 => 'rgba(34,197,94,.18)',
        3 => 'rgba(245,158,11,.18)',
        4 => 'rgba(6,182,212,.18)',
        5 => 'rgba(59,130,246,.18)',
        6 => 'rgba(139,92,246,.18)',
        default => 'rgba(107,114,128,.18)',
    };
    $__prefix = match ($__type) {
        1 => 'admin',
        2 => 'teacher',
        3 => 'student',
        4 => 'parent',
        5 => 'accountant',
        6 => 'librarian',
        default => 'admin',
    };

    $__bsToday = \App\Helpers\NepaliCalendar::today();
    $__dayEn = \Carbon\Carbon::now()->format('l');
@endphp

<style>
    /* ══ NAVBAR ══════════════════════════════════════════════════════════ */
    .app-header {
        background: rgba(255, 255, 255, .95) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(0, 0, 0, .08) !important;
        box-shadow: 0 1px 0 rgba(0, 0, 0, .06), 0 4px 16px rgba(0, 0, 0, .04) !important;
        height: 56px;
    }

    .nav-datetime {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        background: #f8faff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-size: .78rem;
        font-weight: 500;
        color: #374151;
        white-space: nowrap;
    }

    .nav-datetime .dot-sep {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #cbd5e1;
        flex-shrink: 0;
    }

    .nav-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #4b5563;
        border: none;
        transition: background .15s, color .15s, transform .1s;
        position: relative;
        cursor: pointer;
    }

    .nav-icon-btn:hover {
        background: rgba(0, 0, 0, .06);
        color: #111827;
        transform: translateY(-1px);
    }

    .nav-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        min-width: 16px;
        height: 16px;
        border-radius: 8px;
        font-size: .6rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        line-height: 1;
        padding: 0 3px;
    }

    .nav-dropdown-panel {
        border: 1px solid rgba(0, 0, 0, .1) !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, .14), 0 2px 8px rgba(0, 0, 0, .06) !important;
        overflow: hidden;
        padding: 0 !important;
        animation: panelIn .15s cubic-bezier(.16, 1, .3, 1);
    }

    @keyframes panelIn {
        from {
            opacity: 0;
            transform: translateY(6px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .nav-panel-header {
        padding: 12px 16px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(0, 0, 0, .06);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .drop-view-all {
        display: block;
        text-align: center;
        padding: 10px 16px;
        font-size: .73rem;
        font-weight: 600;
        border-top: 1px solid rgba(0, 0, 0, .06);
        text-decoration: none;
        transition: background .12s;
    }

    .drop-view-all:hover {
        background: rgba(0, 0, 0, .03);
    }

    /* ── Profile trigger ── */
    .profile-dropdown-header {
        padding: 16px;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
        position: relative;
        overflow: hidden;
    }

    .profile-dropdown-header::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .05);
    }

    .profile-avatar-ring {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 2px solid rgba(255, 255, 255, .2);
        object-fit: cover;
        flex-shrink: 0;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        font-size: .82rem;
        color: #374151;
        text-decoration: none;
        transition: background .12s;
    }

    .profile-menu-item:hover {
        background: rgba(0, 0, 0, .04);
        color: #111827;
    }

    .profile-menu-item .pm-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .84rem;
        flex-shrink: 0;
    }

    .profile-trigger {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 8px 4px 4px;
        border-radius: 30px;
        border: 1px solid rgba(0, 0, 0, .1);
        background: rgba(0, 0, 0, .02);
        cursor: pointer;
        transition: background .15s, box-shadow .15s;
        text-decoration: none;
    }

    .profile-trigger:hover {
        background: rgba(0, 0, 0, .05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
    }

    .profile-trigger img {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* ── Dropdown scroll area ── */
    .nav-dropdown-scroll {
        max-height: 340px;
        overflow-y: auto;
    }

    .nav-dropdown-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .nav-dropdown-scroll::-webkit-scrollbar-thumb {
        background: #d1d9e0;
        border-radius: 4px;
    }

    /* ── Unread pulse animation ── */
    @keyframes badgePulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, .4);
        }

        50% {
            box-shadow: 0 0 0 5px rgba(239, 68, 68, 0);
        }
    }

    .badge-pulse {
        animation: badgePulse 2s ease-in-out infinite;
    }

    /* ══ SIDEBAR ══════════════════════════════════════════════════════════ */
    .app-sidebar {
        background: #1e293b !important;
        border-right: none !important;
    }

    .sidebar-brand {
        background: #162032;
        border-bottom: 1px solid rgba(255, 255, 255, .08);
        position: relative;
        overflow: hidden;
    }

    .sidebar-brand .brand-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px !important;
        text-decoration: none;
        position: relative;
        z-index: 1;
    }

    .brand-bg {
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(59, 130, 246, .35), transparent 70%);
        top: -20px;
        left: -20px;
        filter: blur(20px);
        z-index: 0;
    }

    .logo-wrapper {
        background: rgba(255, 255, 255, .1);
        padding: 7px;
        border-radius: 12px;
        flex-shrink: 0;
        transition: transform .25s;
    }

    .logo-wrapper:hover {
        transform: scale(1.08) rotate(4deg);
    }

    .brand-image {
        width: 34px;
        height: 34px;
        object-fit: contain;
    }

    .sb-school-name {
        font-size: .9rem;
        font-weight: 700;
        color: #f0f6ff;
        letter-spacing: .01em;
        transition: color .2s;
    }

    .sb-school-sub {
        font-size: .68rem;
        color: #94a3b8;
        margin-top: 1px;
        letter-spacing: .03em;
    }

    .brand-link:hover .sb-school-name {
        color: #7dd3fc;
    }

    .user-panel {
        margin: 10px 12px !important;
        padding: 10px 12px !important;
        border-radius: 12px !important;
        background: rgba(255, 255, 255, .07) !important;
        border: 1px solid rgba(255, 255, 255, .1) !important;
        border-bottom: 1px solid rgba(255, 255, 255, .1) !important;
        position: relative;
        overflow: hidden;
    }

    .user-bg {
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(56, 189, 248, .2), transparent 70%);
        top: -40px;
        left: -20px;
        filter: blur(25px);
        z-index: 0;
    }

    .avatar-wrapper {
        z-index: 1;
        padding: 2px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .08);
        transition: transform .25s;
        flex-shrink: 0;
    }

    .avatar-wrapper:hover {
        transform: scale(1.07);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 10px;
        display: block;
    }

    .online-dot {
        position: absolute;
        bottom: -1px;
        right: -1px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #22c55e;
        border: 2px solid #1e293b;
        box-shadow: 0 0 6px rgba(34, 197, 94, .5);
    }

    .user-name {
        font-size: .82rem !important;
        font-weight: 600;
        color: #e8f0fe !important;
        text-decoration: none;
        display: block;
        z-index: 1;
        transition: color .2s;
    }

    .user-panel:hover .user-name {
        color: #7dd3fc !important;
    }

    .user-role-badge {
        font-size: .68rem;
        padding: 2px 9px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: .03em;
        display: inline-block;
        z-index: 1;
    }

    .nav-header {
        font-size: .65rem !important;
        font-weight: 700 !important;
        letter-spacing: .1em !important;
        text-transform: uppercase !important;
        color: rgba(255, 255, 255, .45) !important;
        padding: 14px 18px 5px !important;
    }

    .sidebar-menu .nav-link {
        padding: 8px 14px 8px 18px !important;
        border-radius: 8px !important;
        margin: 1px 8px !important;
        color: rgba(255, 255, 255, .82) !important;
        font-size: .83rem !important;
        font-weight: 500;
        line-height: 1.4;
        display: flex;
        align-items: center;
        transition: background .15s, color .15s, transform .1s !important;
        position: relative;
    }

    .sidebar-menu .nav-link:hover {
        background: rgba(255, 255, 255, .1) !important;
        color: #fff !important;
        transform: translateX(2px);
    }

    .sidebar-menu .nav-link.active {
        background: rgba(255, 255, 255, .14) !important;
        color: #fff !important;
        font-weight: 600;
    }

    .sidebar-menu .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: var(--role-color, #3b82f6);
    }

    .nav-icon {
        width: 20px;
        text-align: center;
        font-size: .9rem !important;
        opacity: .8;
        margin-right: 9px !important;
        flex-shrink: 0;
    }

    .sidebar-menu .nav-link.active .nav-icon,
    .sidebar-menu .nav-link:hover .nav-icon {
        opacity: 1;
    }

    .nav-treeview {
        background: rgba(0, 0, 0, .18) !important;
        border-radius: 8px;
        margin: 2px 8px !important;
        padding: 4px 0 !important;
    }

    .nav-treeview .nav-link {
        margin: 0 4px !important;
        padding: 7px 12px 7px 36px !important;
        font-size: .8rem !important;
        color: rgba(255, 255, 255, .72) !important;
        border-radius: 6px !important;
    }

    .nav-treeview .nav-link:hover {
        color: #fff !important;
        background: rgba(255, 255, 255, .08) !important;
    }

    .nav-treeview .nav-link.active {
        color: #fff !important;
        background: rgba(255, 255, 255, .12) !important;
    }

    .sidebar-wrapper::-webkit-scrollbar {
        width: 3px;
    }

    .sidebar-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-wrapper::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .18);
        border-radius: 3px;
    }

    .logout-item .nav-link {
        color: rgba(252, 165, 165, .85) !important;
    }

    .logout-item .nav-link:hover {
        background: rgba(239, 68, 68, .12) !important;
        color: #fca5a5 !important;
    }
</style>

{{-- ══ TOP NAVBAR ══════════════════════════════════════════════════════ --}}
<nav class="app-header navbar navbar-expand">
    <div class="container-fluid px-3">

        <ul class="navbar-nav align-items-center gap-2">
            <li class="nav-item">
                <a class="nav-icon-btn" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-layout-sidebar fs-5"></i>
                </a>
            </li>
            <li class="nav-item d-none d-lg-flex">
                <div class="nav-datetime">
                    <i class="bi bi-calendar3" style="color:#3b82f6;font-size:.8rem;"></i>
                    <span style="color:#6b7280;">{{ $__dayEn }}</span>
                    <span class="dot-sep"></span>
                    <strong style="color:#1e293b;">{{ $__bsToday['day'] }} {{ $__bsToday['month_name'] }}
                        {{ $__bsToday['year'] }}</strong>
                    <span style="color:#6b7280;font-size:.7rem;">B.S.</span>
                    <span class="dot-sep"></span>
                    <i class="bi bi-clock" style="color:#3b82f6;font-size:.75rem;"></i>
                    <span id="hdr-clock" style="font-variant-numeric:tabular-nums;color:#374151;"></span>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto align-items-center gap-1">

            {{-- ── Messages Dropdown ────────────────────────────────────── --}}
            <li class="nav-item dropdown">
                <button class="nav-icon-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Messages"
                    id="msgDropBtn">
                    <i class="bi bi-chat-square-text" style="font-size:1rem;"></i>
                    <span class="nav-badge bg-danger text-white badge-pulse" id="header_msg_badge"
                        style="display:none;">0</span>
                </button>

                <div class="dropdown-menu dropdown-menu-end nav-dropdown-panel" style="width:340px;">
                    <div class="nav-panel-header"
                        style="background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;">
                        <span><i class="bi bi-chat-square-text me-2"></i>Messages</span>
                        <span id="header_msg_count_text"
                            style="font-size:.7rem;font-weight:500;opacity:.85;
                                     text-transform:none;letter-spacing:0;">
                            0 unread
                        </span>
                    </div>
                    <div class="nav-dropdown-scroll" id="header_msg_list">
                        <div class="text-center py-4" style="color:#9ca3af;">
                            <i class="bi bi-hourglass-split"
                                style="font-size:1.5rem;opacity:.5;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:.78rem;">Loading…</div>
                        </div>
                    </div>
                    <a href="{{ url($__prefix . '/chat') }}" class="drop-view-all" style="color:#2563eb;">
                        View all messages <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </li>

            {{-- ── Notifications Dropdown ───────────────────────────────── --}}
            <li class="nav-item dropdown">
                <button class="nav-icon-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    title="Notifications" id="notifDropBtn">
                    <i class="bi bi-bell" style="font-size:1rem;"></i>
                    <span class="nav-badge bg-warning text-dark badge-pulse" id="header_notif_badge"
                        style="display:none;">0</span>
                </button>

                <div class="dropdown-menu dropdown-menu-end nav-dropdown-panel" style="width:330px;">
                    <div class="nav-panel-header"
                        style="background:linear-gradient(135deg,#b45309,#d97706);color:#fff;">
                        <span><i class="bi bi-bell me-2"></i>Notifications</span>
                        <button onclick="markAllNotifsRead()"
                            style="font-size:.7rem;color:rgba(255,255,255,.85);background:none;
                                       border:none;cursor:pointer;padding:0;font-weight:500;">
                            Mark all read
                        </button>
                    </div>
                    <div class="nav-dropdown-scroll" id="header_notif_list">
                        <div class="text-center py-4" style="color:#9ca3af;">
                            <i class="bi bi-hourglass-split"
                                style="font-size:1.5rem;opacity:.5;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:.78rem;">Loading…</div>
                        </div>
                    </div>
                    <a href="{{ url($__prefix . '/my_notice_board') }}" class="drop-view-all" style="color:#d97706;">
                        See all notifications <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </li>

            <li class="nav-item d-none d-md-flex align-items-center" style="height:20px;padding:0 4px;">
                <div style="width:1px;height:100%;background:rgba(0,0,0,.12);"></div>
            </li>

            {{-- ── Profile Dropdown ─────────────────────────────────────── --}}
            <li class="nav-item dropdown">
                <a href="#" class="profile-trigger nav-link" data-bs-toggle="dropdown" role="button">
                    <img src="{{ $__u->getProfile() }}" alt="{{ $__u->name }}"
                        style="border:2px solid {{ $__roleColor }};">
                    <div class="d-none d-md-block" style="line-height:1.3;">
                        <div
                            style="font-size:.78rem;font-weight:600;color:#111827;
                                    max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $__u->name }}
                        </div>
                        <div style="font-size:.67rem;color:#6b7280;">{{ $__roleLabel }}</div>
                    </div>
                    <i class="bi bi-chevron-down d-none d-md-block" style="font-size:.6rem;color:#9ca3af;"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-end nav-dropdown-panel" style="width:244px;">
                    <div class="profile-dropdown-header">
                        <div class="d-flex align-items-center gap-3 position-relative" style="z-index:1;">
                            <img src="{{ $__u->getProfile() }}" alt="{{ $__u->name }}"
                                class="profile-avatar-ring" style="border-color:{{ $__roleColor }}!important;">
                            <div>
                                <div style="font-size:.85rem;font-weight:700;color:#f1f5f9;line-height:1.2;">
                                    {{ $__u->name }} {{ $__u->last_name }}
                                </div>
                                <div class="mt-1">
                                    <span class="user-role-badge"
                                        style="background:{{ $__roleBg }};color:{{ $__roleColor }};">
                                        {{ $__roleShort }}
                                    </span>
                                </div>
                                @if ($__u->email)
                                    <div
                                        style="font-size:.67rem;color:rgba(255,255,255,.7);margin-top:5px;
                                                max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        <i class="bi bi-envelope me-1"></i>{{ $__u->email }}
                                    </div>
                                @endif
                                @if (!empty($__u->mobile_number))
                                    <div style="font-size:.67rem;color:rgba(255,255,255,.6);margin-top:3px;">
                                        <i class="bi bi-telephone me-1"></i>{{ $__u->mobile_number }}
                                    </div>
                                @endif
                                @if ($__type == 3 && !empty($__u->admission_number))
                                    <div style="font-size:.67rem;color:rgba(255,255,255,.6);margin-top:3px;">
                                        <i class="bi bi-person-badge me-1"></i>Admission: {{ $__u->admission_number }}
                                    </div>
                                @elseif($__type == 2 && !empty($__u->qualification))
                                    <div style="font-size:.67rem;color:rgba(255,255,255,.6);margin-top:3px;">
                                        <i
                                            class="bi bi-mortarboard me-1"></i>{{ \Illuminate\Support\Str::limit($__u->qualification, 25) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="py-1">
                        <a href="{{ url($__prefix . '/account') }}" class="profile-menu-item">
                            <span class="pm-icon" style="background:rgba(59,130,246,.1);color:#3b82f6;">
                                <i class="bi bi-person"></i>
                            </span>
                            <div>
                                <div style="font-weight:500;">My Profile</div>
                                <div style="font-size:.7rem;color:#9ca3af;">View & edit account</div>
                            </div>
                        </a>
                        <a href="{{ url($__prefix . '/profile/change_password') }}" class="profile-menu-item">
                            <span class="pm-icon" style="background:rgba(245,158,11,.1);color:#f59e0b;">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <div>
                                <div style="font-weight:500;">Change Password</div>
                                <div style="font-size:.7rem;color:#9ca3af;">Security settings</div>
                            </div>
                        </a>
                        <a href="{{ url($__prefix . '/dashboard') }}" class="profile-menu-item">
                            <span class="pm-icon" style="background:rgba(34,197,94,.1);color:#22c55e;">
                                <i class="bi bi-speedometer2"></i>
                            </span>
                            <div>
                                <div style="font-weight:500;">Dashboard</div>
                                <div style="font-size:.7rem;color:#9ca3af;">Back to home</div>
                            </div>
                        </a>
                        <div style="height:1px;background:rgba(0,0,0,.07);margin:4px 0;"></div>
                        <a href="{{ route('logout') }}" class="profile-menu-item" style="color:#ef4444!important;"
                            onclick="event.preventDefault();document.getElementById('nav-logout-form').submit();">
                            <span class="pm-icon" style="background:rgba(239,68,68,.1);color:#ef4444;">
                                <i class="bi bi-box-arrow-right"></i>
                            </span>
                            <div>
                                <div style="font-weight:600;">Sign Out</div>
                                <div style="font-size:.7rem;color:#9ca3af;">End your session</div>
                            </div>
                        </a>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</nav>

<form id="nav-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

<script>
    (function() {
        /* ── Clock ─────────────────────────────────────────────────────────── */
        function tick() {
            var el = document.getElementById('hdr-clock');
            if (el) el.textContent = new Date().toLocaleTimeString('en-US', {
                timeZone: 'Asia/Kathmandu',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
        }
        tick();
        setInterval(tick, 1000);

        /* ── Unread messages ────────────────────────────────────────────────── */
        function fetchUnreadMessages() {
            fetch('{{ url('chat/global-unread') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    var badge = document.getElementById('header_msg_badge');
                    var text = document.getElementById('header_msg_count_text');
                    var list = document.getElementById('header_msg_list');
                    if (!badge) return;

                    if (res.count > 0) {
                        badge.textContent = res.count > 99 ? '99+' : res.count;
                        badge.style.display = 'flex';
                        text.textContent = res.count + ' unread';
                    } else {
                        badge.style.display = 'none';
                        text.textContent = '0 unread';
                    }
                    list.innerHTML = res.html;
                })
                .catch(() => {});
        }

        /* ── Notifications ──────────────────────────────────────────────────── */
        function fetchNotifications() {
            fetch('{{ url('notifications/global') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    var badge = document.getElementById('header_notif_badge');
                    var list = document.getElementById('header_notif_list');
                    if (!badge) return;

                    if (res.count > 0) {
                        badge.textContent = res.count > 99 ? '99+' : res.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                    list.innerHTML = res.html;
                })
                .catch(() => {});
        }

        window.markAllNotifsRead = function() {
            fetch('{{ url('notifications/mark-read') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(() => fetchNotifications())
                .catch(() => {});
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initial fetch
            fetchUnreadMessages();
            fetchNotifications();

            // Poll messages every 10s, notifications every 30s
            setInterval(fetchUnreadMessages, 10000);
            setInterval(fetchNotifications, 30000);

            // Refresh when dropdown is opened
            var msgBtn = document.getElementById('msgDropBtn');
            var notifBtn = document.getElementById('notifDropBtn');
            if (msgBtn) msgBtn.addEventListener('click', fetchUnreadMessages);
            if (notifBtn) notifBtn.addEventListener('click', fetchNotifications);
        });
    })();
</script>

{{-- ══ SIDEBAR ═════════════════════════════════════════════════════════ --}}
<aside class="app-sidebar shadow" data-bs-theme="dark" style="--role-color:{{ $__roleColor }};">

    {{-- Brand --}}
    <div class="sidebar-brand position-relative overflow-hidden">
        <a href="{{ url($__prefix . '/dashboard') }}" class="brand-link">
            <div class="brand-bg"></div>
            <div class="logo-wrapper">
                <img src="{{ asset('dist/assets/img/school.png') }}" class="brand-image" alt="Logo">
            </div>
            <div style="z-index:1;">
                <div class="sb-school-name">Brain Fart Institute</div>
                <div class="sb-school-sub">School Management System</div>
            </div>
        </a>
    </div>

    {{-- User panel --}}
    <div class="user-panel d-flex align-items-center gap-3">
        <div class="user-bg"></div>
        <div class="position-relative avatar-wrapper">
            <img src="{{ $__u->getProfile() }}" alt="{{ $__u->name }}" class="user-avatar"
                style="border:2px solid {{ $__roleColor }};">
            <span class="online-dot"></span>
        </div>
        <div class="overflow-hidden" style="z-index:1;">
            <a href="{{ url($__prefix . '/account') }}" class="user-name">
                {{ $__u->name }} {{ $__u->last_name }}
            </a>
            <div class="mt-1">
                <span class="user-role-badge" style="background:{{ $__roleBg }};color:{{ $__roleColor }};">
                    {{ $__roleShort }}
                </span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="sidebar-wrapper">
        <nav class="mt-1 pb-4">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview"
                style="--role-color:{{ $__roleColor }};">

                {{-- ══ ADMIN (1) ══ --}}
                @if ($__type == 1)
                    <li class="nav-header">Main</li>
                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}"
                            class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-header">Users</li>
                    @foreach ([['admin/admin/list', 'bi-person-badge', 'Admins', 'admin/admin*'], ['admin/student/list', 'bi-people-fill', 'Students', 'admin/student*'], ['admin/parent/list', 'bi-house-heart-fill', 'Parents', 'admin/parent*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    @php $sActive=request()->is(['admin/teacher*','admin/accountant*','admin/librarian*']); @endphp
                    <li class="nav-item {{ $sActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $sActive ? 'active' : '' }}"><i
                                class="nav-icon bi bi-people"></i>
                            <p>Staff <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/teacher/list', 'bi-person-video3', 'Teachers'], ['admin/accountant/list', 'bi-calculator', 'Accountants'], ['admin/librarian/list', 'bi-book', 'Librarians']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-header">Academic</li>
                    @php $acaA=request()->is(['admin/class*','admin/section*','admin/subject*','admin/assign_subject*','admin/assign_class_teacher*','admin/class_timetable*']); @endphp
                    <li class="nav-item {{ $acaA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $acaA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-mortarboard-fill"></i>
                            <p>Academic <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/class/list', 'bi-building', 'Classes'], ['admin/section/list', 'bi-building', 'Sections'], ['admin/subject/list', 'bi-journal-bookmark-fill', 'Subjects'], ['admin/assign_subject/list', 'bi-pencil-square', 'Assign Subject'], ['admin/assign_class_teacher/list', 'bi-person-check-fill', 'Class Teacher'], ['admin/class_timetable/list', 'bi-calendar3-week', 'Timetable']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $exA=request()->is(['admin/examination*']); @endphp
                    <li class="nav-item {{ $exA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $exA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-clipboard2-check-fill"></i>
                            <p>Examinations <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/examination/exam/list', 'bi-card-list', 'Exam List'], ['admin/examination/exam_schedule', 'bi-calendar-event-fill', 'Schedule'], ['admin/examination/marks_register', 'bi-table', 'Marks Register'], ['admin/examination/marks_grade/list', 'bi-award-fill', 'Marks Grade']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item"><a href="{{ url('admin/academic_session/list') }}"
                            class="nav-link {{ request()->is('admin/academic_session*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-calendar3-range-fill"></i>
                            <p>Academic Sessions</p>
                        </a></li>
                    <li class="nav-header">Operations</li>
                    @php $lbA=request()->is(['admin/library*']); @endphp
                    <li class="nav-item {{ $lbA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $lbA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-book-fill"></i>
                            <p>Library <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/library/book/list', 'bi-journals', 'Books'], ['admin/library/issue/list', 'bi-journal-arrow-up', 'Issue / Return'], ['admin/library/fine/list', 'bi-cash-coin', 'Fines'], ['admin/library/return_policy', 'bi-journal-text', 'Return Policy']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $atA=request()->is(['admin/attendance*']); @endphp
                    <li class="nav-item {{ $atA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $atA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-person-check"></i>
                            <p>Attendance <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/attendance/student_attendance', 'bi-calendar-check', 'Student Attendance'], ['admin/attendance/attendance_report', 'bi-bar-chart-line-fill', 'Student Report'], ['admin/attendance/teacher_attendance', 'bi-pencil-square', 'Teacher Attendance'], ['admin/attendance/teacher_attendance_report', 'bi-bar-chart-fill', 'Teacher Report']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $cmA=request()->is(['admin/communicate*']); @endphp
                    <li class="nav-item {{ $cmA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $cmA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-megaphone-fill"></i>
                            <p>Communicate <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/communicate/notice_board', 'bi-pin-angle-fill', 'Notice Board'], ['admin/communicate/send_email', 'bi-envelope-fill', 'Send Email']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $hwA=request()->is(['admin/homework*']); @endphp
                    <li class="nav-item {{ $hwA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $hwA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-journal-text"></i>
                            <p>Homework <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/homework/homework', 'bi-pencil-fill', 'Homework'], ['admin/homework/homework_report', 'bi-file-earmark-bar-graph-fill', 'Report']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $feA=request()->is(['admin/fee_type*','admin/fee*','admin/fee_group*']); @endphp
                    <li class="nav-item {{ $feA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $feA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-cash-coin"></i>
                            <p>Fee Management <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/fee_type/list', 'bi-tags-fill', 'Fee Types'], ['admin/fee_group/list', 'bi-collection-fill', 'Fee Groups'], ['admin/fee_group/allocate', 'bi-people-fill', 'Fees Allocation'], ['admin/fee/list', 'bi-receipt-cutoff', 'Student Fees'], ['admin/fee/add', 'bi-plus-circle-fill', 'Assign Fee'], ['admin/fee/payment_report', 'bi-bar-chart-line-fill', 'Payment Report']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $certA=request()->is('admin/certificate*'); @endphp
                    <li class="nav-item {{ $certA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $certA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-patch-check"></i>
                            <p>Certificate <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/certificate/list', 'bi-list-ul', 'Templates'], ['admin/certificate/student-generate', 'bi-mortarboard', 'Student Certificate'], ['admin/certificate/employee-generate', 'bi-person-badge', 'Employee Certificate']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $icA=request()->is('admin/id_card*'); @endphp
                    <li class="nav-item {{ $icA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $icA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-person-badge-fill"></i>
                            <p>ID Cards <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['admin/id_card/list', 'bi-layout-text-sidebar-reverse', 'Templates'], ['admin/id_card/student_generate', 'bi-people-fill', 'Student ID Cards'], ['admin/id_card/staff_generate', 'bi-person-workspace', 'Staff ID Cards']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-header">Communication</li>
                    <li class="nav-item">
                        <a href="{{ url($__prefix . '/chat') }}"
                            class="nav-link {{ request()->is($__prefix . '/chat*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-chat-dots-fill"></i>
                            <p>Messages</p>
                        </a>
                    </li>
                    <li class="nav-header">Account</li>
                    @foreach ([['admin/account', 'bi-person-circle', 'My Account', 'admin/account*'], ['admin/profile/change_password', 'bi-shield-lock-fill', 'Change Password', 'admin/change_password*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                @endif

                {{-- ══ TEACHER (2) ══ --}}
                @if ($__type == 2)
                    <li class="nav-header">Main</li>
                    @foreach ([['teacher/dashboard', 'bi-speedometer2', 'Dashboard', 'teacher/dashboard'], ['teacher/my_student', 'bi-people-fill', 'My Students', 'teacher/my_student*'], ['teacher/my_class_subject', 'bi-journal-bookmark-fill', 'Class & Subjects', 'teacher/my_class_subject*'], ['teacher/my_exam_timetable', 'bi-calendar-event-fill', 'Exam Timetable', 'teacher/my_exam_timetable*'], ['teacher/marks_register', 'bi-clipboard2-data-fill', 'Marks Register', 'teacher/marks_register*'], ['teacher/my_notice_board', 'bi-pin-angle-fill', 'Notice Board', 'teacher/my_notice_board*'], ['teacher/my_calender', 'bi-calendar3', 'My Calendar', 'teacher/my_calender*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    @php $tAtA=request()->is(['teacher/attendance*']); @endphp
                    <li class="nav-item {{ $tAtA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $tAtA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-person-check"></i>
                            <p>Attendance <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['teacher/attendance/student_attendance', 'bi-calendar-check', 'Mark Attendance'], ['teacher/attendance/attendance_report', 'bi-bar-chart-line-fill', 'Report']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    @php $tHwA=request()->is(['teacher/homework*']); @endphp
                    <li class="nav-item {{ $tHwA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $tHwA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-journal-text"></i>
                            <p>Homework <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['teacher/homework/homework', 'bi-pencil-fill', 'Homework'], ['teacher/homework/homework_report', 'bi-file-earmark-bar-graph-fill', 'Report']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-header">Library</li>
                    @foreach ([['teacher/library/my_books', 'bi-book-fill', 'My Books', 'teacher/library/my_books*'], ['teacher/library/my_fines', 'bi-cash-coin', 'Library Fines', 'teacher/library/my_fines*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    <li class="nav-header">Communication</li>
                    <li class="nav-item"><a href="{{ url($__prefix . '/chat') }}"
                            class="nav-link {{ request()->is($__prefix . '/chat*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-chat-dots-fill"></i>
                            <p>Messages</p>
                        </a></li>
                    <li class="nav-header">Account</li>
                    @foreach ([['teacher/account', 'bi-person-circle', 'My Account', 'teacher/account*'], ['teacher/profile/change_password', 'bi-shield-lock-fill', 'Change Password', 'teacher/change_password*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                @endif

                {{-- ══ STUDENT (3) ══ --}}
                @if ($__type == 3)
                    <li class="nav-header">Main</li>
                    @foreach ([['student/dashboard', 'bi-speedometer2', 'Dashboard', 'student/dashboard'], ['student/my_subject', 'bi-journal-bookmark-fill', 'My Subjects', 'student/my_subject*'], ['student/my_timetable', 'bi-calendar3-week', 'My Timetable', 'student/my_timetable*'], ['student/my_exam_timetable', 'bi-calendar-event-fill', 'Exam Timetable', 'student/my_exam_timetable*'], ['student/my_exam_result', 'bi-award-fill', 'Exam Results', 'student/my_exam_result*'], ['student/my_attendance', 'bi-calendar-check', 'My Attendance', 'student/my_attendance*'], ['student/my_notice_board', 'bi-pin-angle-fill', 'Notice Board', 'student/my_notice_board*'], ['student/my_homework', 'bi-pencil-square', 'My Homework', 'student/my_homework*'], ['student/my_submitted_homework', 'bi-check2-square', 'Submitted', 'student/my_submitted_homework*'], ['student/my_fees', 'bi-cash-coin', 'My Fees', 'student/my_fees*'], ['student/my_calender', 'bi-calendar3', 'My Calendar', 'student/my_calender*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    <li class="nav-header">Library</li>
                    @foreach ([['student/library/my_books', 'bi-book-fill', 'My Books', 'student/library/my_books*'], ['student/library/my_fines', 'bi-cash-coin', 'Library Fines', 'student/library/my_fines*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    <li class="nav-header">Communication</li>
                    <li class="nav-item"><a href="{{ url($__prefix . '/chat') }}"
                            class="nav-link {{ request()->is($__prefix . '/chat*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-chat-dots-fill"></i>
                            <p>Messages</p>
                        </a></li>
                    <li class="nav-header">Account</li>
                    @foreach ([['student/account', 'bi-person-circle', 'My Account', 'student/account*'], ['student/profile/change_password', 'bi-shield-lock-fill', 'Change Password', 'student/change_password*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                @endif

                {{-- ══ PARENT (4) ══ --}}
                @if ($__type == 4)
                    <li class="nav-header">Main</li>
                    @foreach ([['parent/dashboard', 'bi-speedometer2', 'Dashboard', 'parent/dashboard'], ['parent/my_student', 'bi-people-fill', 'My Children', 'parent/my_student*'], ['parent/my_notice_board', 'bi-pin-angle-fill', 'Notice Board', 'parent/my_notice_board*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    <li class="nav-header">Communication</li>
                    <li class="nav-item"><a href="{{ url($__prefix . '/chat') }}"
                            class="nav-link {{ request()->is($__prefix . '/chat*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-chat-dots-fill"></i>
                            <p>Messages</p>
                        </a></li>
                    <li class="nav-header">Account</li>
                    @foreach ([['parent/account', 'bi-person-circle', 'My Account', 'parent/account*'], ['parent/profile/change_password', 'bi-shield-lock-fill', 'Change Password', 'parent/change_password*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                @endif

                {{-- ══ ACCOUNTANT (5) ══ --}}
                @if ($__type == 5)
                    <li class="nav-header">Main</li>
                    <li class="nav-item"><a href="{{ url('accountant/dashboard') }}"
                            class="nav-link {{ request()->is('accountant/dashboard*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a></li>
                    <li class="nav-header">Fee Management</li>
                    @foreach ([['accountant/fee/list', 'bi-cash-coin', 'Fee Collection', 'accountant/fee*'], ['accountant/fee/payment_report', 'bi-bar-chart-line-fill', 'Payment Report', 'accountant/fee/payment_report*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                    <li class="nav-header">Communication</li>
                    <li class="nav-item"><a href="{{ url($__prefix . '/chat') }}"
                            class="nav-link {{ request()->is($__prefix . '/chat*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-chat-dots-fill"></i>
                            <p>Messages</p>
                        </a></li>
                    <li class="nav-header">Account</li>
                    @foreach ([['accountant/account', 'bi-person-circle', 'My Account', 'accountant/account*'], ['accountant/profile/change_password', 'bi-shield-lock-fill', 'Change Password', 'accountant/change_password*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                @endif

                {{-- ══ LIBRARIAN (6) ══ --}}
                @if ($__type == 6)
                    <li class="nav-header">Main</li>
                    <li class="nav-item"><a href="{{ url('librarian/dashboard') }}"
                            class="nav-link {{ request()->is('librarian/dashboard*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a></li>
                    <li class="nav-header">Book Management</li>
                    @php $lbBA=request()->is(['librarian/library*']); @endphp
                    <li class="nav-item {{ $lbBA ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $lbBA ? 'active' : '' }}"><i
                                class="nav-icon bi bi-book-fill"></i>
                            <p>Library <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach ([['librarian/library/book/list', 'bi-journals', 'Books'], ['librarian/library/issue/list', 'bi-journal-arrow-up', 'Issue / Return'], ['librarian/library/fine/list', 'bi-cash-coin', 'Fines'], ['librarian/library/return_policy', 'bi-journal-text', 'Return Policy']] as [$u, $i, $l])
                                <li class="nav-item"><a href="{{ url($u) }}"
                                        class="nav-link {{ request()->is($u . '*') ? 'active' : '' }}"><i
                                            class="nav-icon bi {{ $i }}"></i>
                                        <p>{{ $l }}</p>
                                    </a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-header">Communication</li>
                    <li class="nav-item"><a href="{{ url($__prefix . '/chat') }}"
                            class="nav-link {{ request()->is($__prefix . '/chat*') ? 'active' : '' }}"><i
                                class="nav-icon bi bi-chat-dots-fill"></i>
                            <p>Messages</p>
                        </a></li>
                    <li class="nav-header">Account</li>
                    @foreach ([['librarian/account', 'bi-person-circle', 'My Account', 'librarian/account*'], ['librarian/profile/change_password', 'bi-shield-lock-fill', 'Change Password', 'librarian/change_password*']] as [$u, $i, $l, $m])
                        <li class="nav-item"><a href="{{ url($u) }}"
                                class="nav-link {{ request()->is($m) ? 'active' : '' }}"><i
                                    class="nav-icon bi {{ $i }}"></i>
                                <p>{{ $l }}</p>
                            </a></li>
                    @endforeach
                @endif

                {{-- Sign Out --}}
                <li class="nav-item logout-item"
                    style="margin-top:8px;border-top:1px solid rgba(255,255,255,.07);padding-top:6px;">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault();document.getElementById('sidebar-logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Sign Out</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
