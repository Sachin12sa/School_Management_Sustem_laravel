@extends('layouts.app')

@section('style')
    <style>
        /* ── Class status grid ─────────────────────────────────────── */
        .class-card {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 14px;
            cursor: pointer;
            transition: all .15s;
            position: relative;
            background: #fff;
        }

        .class-card:hover {
            border-color: #059669;
            box-shadow: 0 2px 12px rgba(5, 150, 105, .12);
        }

        .class-card.selected {
            border-color: #059669;
            background: #f0fdf4;
            box-shadow: 0 2px 12px rgba(5, 150, 105, .18);
        }

        .class-card.has-rule {
            border-color: #10b981;
        }

        .class-card.confirmed {
            border-color: #3b82f6;
            background: #eff6ff;
            cursor: default;
        }

        .class-card.confirmed:hover {
            border-color: #3b82f6;
            box-shadow: none;
        }

        .cc-name {
            font-size: .82rem;
            font-weight: 700;
            color: #111827;
        }

        .cc-status {
            font-size: .68rem;
            margin-top: 3px;
        }

        .cc-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
        }

        /* ── Rule editor panel ─────────────────────────────────────── */
        #rule-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        #rule-panel .rp-head {
            background: linear-gradient(135deg, #065f46, #059669);
            color: #fff;
            padding: 14px 18px;
        }

        #rule-panel .rp-head h6 {
            font-size: .85rem;
            font-weight: 700;
            margin: 0;
        }

        #rule-panel .rp-head p {
            font-size: .72rem;
            opacity: .8;
            margin: 2px 0 0;
        }

        /* ── Progress stepper ──────────────────────────────────────── */
        .step-bar {
            display: flex;
            gap: 6px;
        }

        .step-pill {
            flex: 1;
            text-align: center;
            padding: 8px 4px;
            border-radius: 8px;
            font-size: .72rem;
            font-weight: 600;
        }

        .step-pill.active {
            color: #fff;
            background: #059669;
        }

        .step-pill.done {
            color: #059669;
            background: #d1fae5;
        }

        .step-pill.todo {
            color: #6b7280;
            background: #f3f4f6;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-3">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#d1fae5;color:#059669;">
                                <i class="bi bi-arrow-up-circle-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Academic Upgrade</h4>
                                <span class="text-muted small">Set promotion rules class by class, then preview and run each
                                    one</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/academic_session/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Sessions
                        </a>
                    </div>
                </div>
                {{-- Step bar --}}
                <div class="step-bar mb-3">
                    <div class="step-pill active"><span class="me-1">①</span>Setup Rules</div>
                    <div class="step-pill todo"><span class="me-1">②</span>Preview Students</div>
                    <div class="step-pill todo"><span class="me-1">③</span>Review &amp; Confirm</div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @include('message')

                {{-- ── SECTION 1: Session pickers ── --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-transparent border-bottom py-2 d-flex align-items-center gap-2">
                        <i class="bi bi-calendar3-range text-success"></i>
                        <span class="fw-semibold small text-uppercase text-muted" style="letter-spacing:.06em;">Step 1 —
                            Select Sessions</span>
                    </div>
                    <div class="card-body">
                        <form method="GET" id="session-form" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">From Session (Current Year) <span
                                        class="text-danger">*</span></label>
                                <select name="from_session_id" id="from_session_id" class="form-select" required
                                    onchange="sessionChanged()">
                                    <option value="">— Select —</option>
                                    @foreach ($getSessions as $session)
                                        <option value="{{ $session->id }}"
                                            {{ old('from_session_id') == $session->id || $session->is_current ? 'selected' : '' }}>
                                            {{ $session->name }}
                                            @if ($session->is_current)
                                                (Current)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 text-center pt-3">
                                <i class="bi bi-arrow-right-circle-fill text-success" style="font-size:1.5rem;"></i>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">To Session (New Year) <span
                                        class="text-danger">*</span></label>
                                <select name="to_session_id" id="to_session_id" class="form-select" required
                                    onchange="sessionChanged()">
                                    <option value="">— Select —</option>
                                    @foreach ($getSessions as $s)
                                        <option value="{{ $s->id }}"
                                            {{ request('to_session_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }} {{ $s->is_current ? '(Current)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    <a href="{{ url('admin/academic_session/add') }}" target="_blank"
                                        class="text-success fw-semibold">
                                        <i class="bi bi-plus-circle me-1"></i>Create new session
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn w-100 fw-semibold text-white" style="background:#059669;">
                                    <i class="bi bi-arrow-right-circle me-1"></i>Load Classes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if (request('from_session_id') && request('to_session_id'))

                    <div class="row g-3">

                        {{-- ── LEFT: Class status grid ── --}}
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div
                                    class="card-header bg-transparent border-bottom py-2 d-flex align-items-center justify-content-between">
                                    <span class="fw-semibold small text-uppercase text-muted" style="letter-spacing:.06em;">
                                        <i class="bi bi-grid-3x3-gap me-1 text-success"></i>Step 2 — Pick a Class to Set
                                        Rule
                                    </span>
                                    @php
                                        $doneCount = $classStatuses->where('has_rule', true)->count();
                                        $totalCount = $classStatuses->count();
                                    @endphp
                                    <span
                                        class="badge {{ $doneCount === $totalCount && $totalCount > 0 ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $doneCount }}/{{ $totalCount }} rules set
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div
                                        class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 py-2 small mb-3">
                                        <i class="bi bi-info-circle-fill text-info me-1"></i>
                                        Click a class below to configure where its students promote to.
                                        Each class can be promoted independently.
                                    </div>

                                    <div class="row g-2" id="class-grid">
                                        @foreach ($classStatuses as $cs)
                                            @php
                                                $cardClass = $cs->is_confirmed
                                                    ? 'confirmed'
                                                    : ($cs->has_rule
                                                        ? 'has-rule'
                                                        : '');
                                                $isSelected = request('class_id') == $cs->id;
                                                if ($isSelected) {
                                                    $cardClass .= ' selected';
                                                }
                                            @endphp
                                            <div class="col-6">
                                                <div class="class-card {{ $cardClass }}"
                                                    data-class-id="{{ $cs->id }}"
                                                    onclick="selectClass({{ $cs->id }}, '{{ addslashes($cs->name) }}')"
                                                    title="{{ $cs->name }}">
                                                    {{-- Status dot --}}
                                                    <div
                                                        class="cc-badge
                                            @if ($cs->is_confirmed) bg-primary text-white
                                            @elseif($cs->is_pending) bg-warning text-dark
                                            @elseif($cs->has_rule) bg-success text-white
                                            @else bg-light text-muted border @endif">
                                                        @if ($cs->is_confirmed)
                                                            <i class="bi bi-check-lg"></i>
                                                        @elseif($cs->is_pending)
                                                            <i class="bi bi-hourglass-split"></i>
                                                        @elseif($cs->has_rule)
                                                            <i class="bi bi-check"></i>
                                                        @else
                                                            <i class="bi bi-dash"></i>
                                                        @endif
                                                    </div>

                                                    <div class="cc-name">{{ $cs->name }}</div>
                                                    <div class="cc-status">
                                                        @if ($cs->is_confirmed)
                                                            <span class="text-primary">✓ Confirmed
                                                                ({{ $cs->promoted_count }} students)
                                                            </span>
                                                        @elseif($cs->is_pending)
                                                            <span class="text-warning">⏳ Pending review
                                                                ({{ $cs->promoted_count }})</span>
                                                        @elseif($cs->has_rule)
                                                            @if ($cs->is_final)
                                                                <span class="text-success">🎓 Final — Graduates</span>
                                                            @else
                                                                <span class="text-success">→
                                                                    {{ $cs->to_class_name ?? 'No change' }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">No rule set yet</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if ($classStatuses->isEmpty())
                                            <div class="col-12 text-center text-muted py-4 small">
                                                <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                                                No classes found. Add classes first.
                                            </div>
                                        @endif
                                    </div>

                                    @if ($classStatuses->where('has_rule', true)->count() > 0)
                                        <div class="mt-3 pt-3 border-top">
                                            <a href="{{ url('admin/academic/preview') . '?' . http_build_query(['from' => request('from_session_id'), 'to' => request('to_session_id')]) }}"
                                                class="btn w-100 fw-semibold text-white" style="background:#059669;">
                                                <i class="bi bi-eye-fill me-2"></i>Preview All Students
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ── RIGHT: Rule editor ── --}}
                        <div class="col-lg-7">
                            @if (isset($selectedClass) && $selectedClass)
                                <div id="rule-panel">
                                    <div class="rp-head">
                                        <h6><i class="bi bi-pencil-square me-2"></i>Setting rule for:
                                            {{ $selectedClass->name }}</h6>
                                        <p>
                                            Define where students from <strong>{{ $selectedClass->name }}</strong>
                                            should go in the new session.
                                        </p>
                                    </div>

                                    <div class="card-body p-4">

                                        @if (isset($existingRule) && $existingRule)
                                            <div
                                                class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 py-2 small mb-3 d-flex align-items-center justify-content-between">
                                                <span>
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    Rule already saved —
                                                    @if ($existingRule->is_final_class)
                                                        students <strong>graduate</strong> (final class).
                                                    @else
                                                        students promote to
                                                        <strong>{{ $existingRule->toClass?->name ?? '—' }}</strong>.
                                                    @endif
                                                    You can update it below.
                                                </span>
                                                <form method="POST" action="{{ url('admin/academic/delete_rule') }}"
                                                    onsubmit="return confirm('Remove this rule?')"
                                                    style="flex-shrink:0;margin-left:10px;">
                                                    @csrf
                                                    <input type="hidden" name="from_session_id"
                                                        value="{{ request('from_session_id') }}">
                                                    <input type="hidden" name="to_session_id"
                                                        value="{{ request('to_session_id') }}">
                                                    <input type="hidden" name="from_class_id"
                                                        value="{{ $selectedClass->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash me-1"></i>Remove Rule
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ url('admin/academic/save_rules') }}">
                                            @csrf
                                            <input type="hidden" name="from_session_id"
                                                value="{{ request('from_session_id') }}">
                                            <input type="hidden" name="to_session_id"
                                                value="{{ request('to_session_id') }}">
                                            <input type="hidden" name="from_class_id" value="{{ $selectedClass->id }}">

                                            {{-- Final class toggle --}}
                                            <div class="mb-4 p-3 rounded-3"
                                                style="background:#fafafa;border:1px solid #e5e7eb;">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_final_class"
                                                        value="1" id="final-check"
                                                        {{ $existingRule?->is_final_class ? 'checked' : '' }}
                                                        onchange="toggleFinal(this.checked)">
                                                    <label class="form-check-label fw-semibold" for="final-check">
                                                        🎓 This is the Final Class (students graduate)
                                                    </label>
                                                </div>
                                                <div class="text-muted small mt-1">
                                                    When checked, students in {{ $selectedClass->name }} will be marked as
                                                    <strong>Graduated</strong>
                                                    and won't be assigned to a new class.
                                                </div>
                                            </div>

                                            {{-- Promote To --}}
                                            <div id="promote-to-wrap"
                                                {{ $existingRule?->is_final_class ? 'style=display:none;' : '' }}>
                                                <label class="form-label fw-semibold small">Promotes To <span
                                                        class="text-danger">*</span></label>
                                                <select name="to_class_id" id="to_class_id" class="form-select mb-1">
                                                    <option value="">— Select destination class —</option>
                                                    @foreach ($getClass as $c)
                                                        @if ($c->id !== $selectedClass->id)
                                                            <option value="{{ $c->id }}"
                                                                {{ $existingRule?->to_class_id == $c->id ? 'selected' : '' }}>
                                                                {{ $c->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted small">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Students who <strong>fail</strong> will automatically stay in
                                                    {{ $selectedClass->name }}.
                                                    Only passed students move to the selected class.
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                                <button type="submit" class="btn fw-semibold text-white px-5"
                                                    style="background:#059669;">
                                                    <i class="bi bi-floppy-fill me-2"></i>
                                                    {{ $existingRule ? 'Update Rule' : 'Save Rule' }}
                                                </button>
                                                @if (isset($existingRule) && $existingRule && !$existingRule->is_final_class)
                                                    <a href="{{ url('admin/academic/preview') . '?' . http_build_query(['from' => request('from_session_id'), 'to' => request('to_session_id'), 'class_id' => $selectedClass->id]) }}"
                                                        class="btn btn-outline-success px-4">
                                                        <i class="bi bi-eye me-1"></i>Preview {{ $selectedClass->name }}
                                                        Students
                                                    </a>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                {{-- No class selected yet --}}
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center py-5"
                                        style="min-height:300px;">
                                        <i class="bi bi-cursor-fill d-block mb-3 text-success"
                                            style="font-size:2.5rem;opacity:.3;"></i>
                                        <div class="fw-semibold text-dark mb-1">Click a Class Card</div>
                                        <p class="text-muted small mb-0">
                                            Select a class from the grid on the left to set or edit its promotion rule.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                @else
                    {{-- No sessions selected yet --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-calendar3-range d-block mb-3 text-success"
                                style="font-size:2.5rem;opacity:.3;"></i>
                            <div class="fw-semibold text-dark mb-1">Select Sessions Above</div>
                            <p class="text-muted small mb-0">
                                Choose a <strong>From Session</strong> and <strong>To Session</strong> to begin
                                configuring promotion rules.
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        var fromSession = '{{ request('from_session_id') }}';
        var toSession = '{{ request('to_session_id') }}';

        function selectClass(classId, className) {
            // Navigate to setup page with this class selected
            var url = new URL(window.location.href);
            url.searchParams.set('from_session_id', fromSession);
            url.searchParams.set('to_session_id', toSession);
            url.searchParams.set('class_id', classId);
            window.location.href = url.toString();
        }

        function sessionChanged() {
            var from = document.getElementById('from_session_id').value;
            var to = document.getElementById('to_session_id').value;
            if (from && to && from === to) {
                alert('From Session and To Session must be different.');
                document.getElementById('to_session_id').value = '';
            }
        }

        function toggleFinal(checked) {
            var wrap = document.getElementById('promote-to-wrap');
            if (wrap) {
                wrap.style.display = checked ? 'none' : '';
                var sel = document.getElementById('to_class_id');
                if (sel) sel.required = !checked;
            }
        }

        // Highlight currently selected class card
        $(document).ready(function() {
            var selectedId = '{{ request('class_id') }}';
            if (selectedId) {
                $('[data-class-id="' + selectedId + '"]').addClass('selected');
            }
        });
    </script>
@endsection
