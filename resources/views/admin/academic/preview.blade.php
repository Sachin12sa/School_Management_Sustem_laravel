@extends('layouts.app')

@section('style')
    <style>
        /* ── Class tab pills ──────────────────────────────────────── */
        .class-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: all .12s;
            text-decoration: none;
            color: #374151;
            border: 1.5px solid transparent;
            font-size: .82rem;
        }

        .class-tab:hover {
            background: #f0fdf4;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .class-tab.active {
            background: #d1fae5;
            border-color: #059669;
            color: #065f46;
            font-weight: 700;
        }

        .class-tab .ct-badge {
            font-size: .62rem;
            padding: 1px 7px;
            border-radius: 20px;
            margin-left: auto;
            flex-shrink: 0;
        }

        .class-tab.promoted-done {
            opacity: .65;
        }

        /* ── Step bar ─────────────────────────────────────────────── */
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
                                <i class="bi bi-eye-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Academic Upgrade — Preview</h4>
                                <span class="text-muted small">
                                    <strong>{{ $fromSession->name }}</strong>
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <strong>{{ $toSession->name }}</strong>
                                    @if ($filterClassId)
                                        · Showing class filter
                                    @else
                                        · All classes · {{ $students->count() }} students
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ url('admin/academic/setup') . '?' . http_build_query(['from_session_id' => $fromSession->id, 'to_session_id' => $toSession->id]) }}"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back to Setup
                        </a>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <div class="step-pill done"><i class="bi bi-check-lg me-1"></i>Setup Rules</div>
                    <div class="step-pill active"><span class="me-1">②</span>Preview Students</div>
                    <div class="step-pill todo"><span class="me-1">③</span>Review &amp; Confirm</div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <div class="row g-3">

                    {{-- ── LEFT: Class tabs ── --}}
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-transparent border-bottom py-2">
                                <span class="fw-semibold small text-muted text-uppercase" style="letter-spacing:.06em;">
                                    <i class="bi bi-list-ul me-1"></i>Classes
                                </span>
                            </div>
                            <div class="card-body p-2">
                                {{-- All classes link --}}
                                <a href="{{ url('admin/academic/preview') . '?' . http_build_query(['from' => $fromSession->id, 'to' => $toSession->id]) }}"
                                    class="class-tab {{ !$filterClassId ? 'active' : '' }} mb-1 d-flex">
                                    <i class="bi bi-people-fill"></i>
                                    <span>All Classes</span>
                                    <span
                                        class="ct-badge bg-primary bg-opacity-10 text-primary">{{ $students->count() }}</span>
                                </a>

                                @foreach ($getClass as $class)
                                    @php
                                        $classStudents = $students->where('class_id', $class->id);
                                        $promoStatus = $classPromotionStatus->get($class->id);
                                        $isPromoted = $promoStatus && $promoStatus->total > 0;
                                        $isConfirmed =
                                            $promoStatus &&
                                            $promoStatus->confirmed == $promoStatus->total &&
                                            $isPromoted;
                                        $hasRule = $rules->has($class->id);
                                    @endphp
                                    @if ($classStudents->count() > 0 || $hasRule)
                                        <a href="{{ url('admin/academic/preview') . '?' . http_build_query(['from' => $fromSession->id, 'to' => $toSession->id, 'class_id' => $class->id]) }}"
                                            class="class-tab {{ $filterClassId == $class->id ? 'active' : '' }} {{ $isConfirmed ? 'promoted-done' : '' }} mb-1">
                                            @if ($isConfirmed)
                                                <i class="bi bi-check-circle-fill text-primary"></i>
                                            @elseif($isPromoted)
                                                <i class="bi bi-hourglass-split text-warning"></i>
                                            @elseif($hasRule)
                                                <i class="bi bi-circle text-success"></i>
                                            @else
                                                <i class="bi bi-exclamation-circle text-warning"></i>
                                            @endif
                                            <span>{{ $class->name }}</span>
                                            <span
                                                class="ct-badge
                                    {{ $isConfirmed ? 'bg-primary bg-opacity-10 text-primary' : ($isPromoted ? 'bg-warning bg-opacity-20 text-warning' : 'bg-secondary bg-opacity-10 text-secondary') }}">
                                                {{ $classStudents->count() }}
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ── RIGHT: Student table ── --}}
                    <div class="col-lg-9">

                        {{-- Summary stat cards --}}
                        @php
                            $promotedCount = $students
                                ->filter(
                                    fn($s) => !$rules->get($s->class_id)?->is_final_class &&
                                        $s->promotion_status !== 'failed',
                                )
                                ->count();
                            $failedCount = $students->where('promotion_status', 'failed')->count();
                            $graduatedCount = $students
                                ->filter(fn($s) => $rules->get($s->class_id)?->is_final_class)
                                ->count();
                            $noRuleCount = $students->filter(fn($s) => !$rules->has($s->class_id))->count();
                        @endphp
                        <div class="row g-2 mb-3">
                            <div class="col-3">
                                <div class="card border-0 shadow-sm text-center py-2">
                                    <div class="fw-bold fs-5 text-primary">{{ $students->count() }}</div>
                                    <div class="text-muted" style="font-size:.68rem;">Total</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card border-0 shadow-sm text-center py-2">
                                    <div class="fw-bold fs-5 text-success">{{ $promotedCount }}</div>
                                    <div class="text-muted" style="font-size:.68rem;">Promote</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card border-0 shadow-sm text-center py-2">
                                    <div class="fw-bold fs-5 text-danger">{{ $failedCount }}</div>
                                    <div class="text-muted" style="font-size:.68rem;">Kept Back</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card border-0 shadow-sm text-center py-2">
                                    <div class="fw-bold fs-5 text-warning">{{ $graduatedCount }}</div>
                                    <div class="text-muted" style="font-size:.68rem;">Graduate</div>
                                </div>
                            </div>
                        </div>

                        @if ($noRuleCount > 0)
                            <div class="alert alert-warning d-flex align-items-center gap-2 mb-3 py-2 small">
                                <i class="bi bi-exclamation-triangle-fill text-warning flex-shrink-0"></i>
                                <span>
                                    <strong>{{ $noRuleCount }} student(s)</strong> are in classes with no rule —
                                    they'll be <strong>skipped</strong>.
                                    <a href="{{ url('admin/academic/setup') . '?' . http_build_query(['from_session_id' => $fromSession->id, 'to_session_id' => $toSession->id]) }}"
                                        class="alert-link">Set rules for all classes.</a>
                                </span>
                            </div>
                        @endif

                        @if ($students->isEmpty())
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body text-center py-5 text-muted">
                                    <i class="bi bi-people d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                                    No students found{{ $filterClassId ? ' in this class' : '' }}.
                                    Make sure students are assigned to session <strong>{{ $fromSession->name }}</strong>.
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ url('admin/academic/run') }}" id="run-form">
                                @csrf
                                <input type="hidden" name="from_session_id" value="{{ $fromSession->id }}">
                                <input type="hidden" name="to_session_id" value="{{ $toSession->id }}">
                                <input type="hidden" name="from_class_id" value="{{ $filterClassId ?: '' }}">

                                <div class="card border-0 shadow-sm rounded-3">
                                    <div
                                        class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-2">
                                        <h6 class="mb-0 fw-semibold small">
                                            <i class="bi bi-people-fill me-1 text-success"></i>
                                            Students
                                            @if ($filterClassId)
                                                — {{ $getClass->firstWhere('id', $filterClassId)?->name ?? '' }}
                                            @endif
                                        </h6>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="$('.student-check:not([disabled])').prop('checked',true)">
                                                <i class="bi bi-check-all"></i> All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="$('.student-check:not([disabled])').prop('checked',false)">
                                                <i class="bi bi-x-circle"></i> None
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr
                                                        style="font-size:.68rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;">
                                                        <th class="ps-3" width="36">
                                                            <input type="checkbox" id="check-all"
                                                                class="form-check-input"
                                                                onchange="$('.student-check:not([disabled])').prop('checked',$(this).is(':checked'))">
                                                        </th>
                                                        <th style="min-width:180px;">Student</th>
                                                        <th>Class</th>
                                                        <th>Section</th>
                                                        <th>Promotes To</th>
                                                        <th style="min-width:120px;">Result Override</th>
                                                        <th style="min-width:130px;">Target Section</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $currentClassId = null; @endphp
                                                    @foreach ($students as $student)
                                                        @php
                                                            $rule = $rules->get($student->class_id);
                                                            $hasRule = $rule !== null;
                                                            $isFinal = $rule?->is_final_class;
                                                            $isFailed = $student->promotion_status === 'failed';
                                                            $defaultResult = $isFinal
                                                                ? 'graduated'
                                                                : ($isFailed
                                                                    ? 'failed'
                                                                    : 'promoted');
                                                            $toClass = $isFinal
                                                                ? null
                                                                : ($isFailed
                                                                    ? $student->class_name
                                                                    : $rule?->toClass?->name ?? '—');
                                                        @endphp

                                                        {{-- Class separator row --}}
                                                        @if (!$filterClassId && $currentClassId !== $student->class_id)
                                                            @php $currentClassId = $student->class_id; @endphp
                                                            <tr style="background:#f8faff;">
                                                                <td colspan="7"
                                                                    class="ps-3 py-2 fw-semibold small text-secondary border-bottom-0">
                                                                    <i
                                                                        class="bi bi-building me-1 text-success"></i>{{ $student->class_name }}
                                                                    @if (!$hasRule)
                                                                        <span
                                                                            class="badge bg-warning text-dark ms-2 fw-normal">No
                                                                            rule — skipped</span>
                                                                    @elseif($isFinal)
                                                                        <span
                                                                            class="badge bg-warning text-dark ms-2 fw-normal">🎓
                                                                            Graduates</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        <tr class="{{ !$hasRule ? 'opacity-50' : '' }}">
                                                            <td class="ps-3">
                                                                <input type="checkbox" name="student_ids[]"
                                                                    value="{{ $student->id }}"
                                                                    class="form-check-input student-check"
                                                                    {{ $hasRule ? 'checked' : 'disabled' }}>
                                                            </td>
                                                            <td>
                                                                <div class="fw-semibold small text-dark">
                                                                    {{ $student->name }} {{ $student->last_name }}</div>
                                                                <div class="text-muted" style="font-size:.68rem;">
                                                                    {{ $student->admission_number }}</div>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $student->class_name }}</span>
                                                            </td>
                                                            <td class="small text-muted">
                                                                {{ $student->section_name ?? '—' }}</td>
                                                            <td>
                                                                @if (!$hasRule)
                                                                    <span class="text-muted small">Skipped</span>
                                                                @elseif($isFinal)
                                                                    <span class="badge bg-warning text-dark px-2"><i
                                                                            class="bi bi-mortarboard-fill me-1"></i>Graduated</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-success bg-opacity-10 text-success px-2">
                                                                        <i
                                                                            class="bi bi-arrow-right me-1"></i>{{ $toClass }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($hasRule)
                                                                    <select name="result_override[{{ $student->id }}]"
                                                                        class="form-select form-select-sm">
                                                                        <option value="promoted"
                                                                            {{ $defaultResult === 'promoted' ? 'selected' : '' }}>
                                                                            ✅ Promoted</option>
                                                                        <option value="failed"
                                                                            {{ $defaultResult === 'failed' ? 'selected' : '' }}>
                                                                            🔴 Failed</option>
                                                                        <option value="graduated"
                                                                            {{ $defaultResult === 'graduated' ? 'selected' : '' }}>
                                                                            🎓 Graduated</option>
                                                                    </select>
                                                                @else
                                                                    <span class="text-muted small">—</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($hasRule && !$isFinal && $rule?->to_class_id)
                                                                    @php
                                                                        $sections = \App\Models\ClassSectionModel::getSectionsByClass(
                                                                            $rule->to_class_id,
                                                                        );
                                                                    @endphp

                                                                    <select name="to_section_id[{{ $student->id }}]"
                                                                        class="form-select form-select-sm">

                                                                        <option value="">— Auto —</option>

                                                                        @foreach ($sections as $sec)
                                                                            <option value="{{ $sec->id }}"
                                                                                {{ $student->section_name == $sec->name ? 'selected' : '' }}>

                                                                                Section {{ $sec->name }}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                @else
                                                                    <span class="text-muted small">—</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div
                                        class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                        <div class="text-muted small">
                                            <i class="bi bi-shield-check me-1 text-success"></i>
                                            Old records are never deleted. Each student gets a new record for
                                            <strong>{{ $toSession->name }}</strong>.
                                        </div>
                                        <button type="button" class="btn px-5 fw-semibold text-white"
                                            style="background:#059669;" onclick="confirmRun()">
                                            <i class="bi bi-play-circle-fill me-2"></i>Run Upgrade
                                            @if ($filterClassId)
                                                for {{ $getClass->firstWhere('id', $filterClassId)?->name ?? 'Class' }}
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        function confirmRun() {
            var selected = $('.student-check:checked').length;
            if (selected === 0) {
                alert('Select at least one student.');
                return;
            }
            if (confirm('Run academic upgrade for ' + selected +
                    ' student(s)?\n\nNew records will be created for {{ $toSession->name }}.\nYou can review and rollback before confirming.'
                )) {
                $('#run-form').submit();
            }
        }
    </script>
@endsection
