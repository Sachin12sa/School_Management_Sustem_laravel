@extends('layouts.app')

@section('style')
    <style>
        /* ── Summary stats ────────────────────────────────────────── */
        .fee-stat {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform .15s, box-shadow .15s;
        }

        .fee-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        .fs-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .fs-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            font-weight: 600;
        }

        .fs-value {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 2px;
        }

        /* ── Filter card ──────────────────────────────────────────── */
        .filter-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 16px;
        }

        /* ── Table ────────────────────────────────────────────────── */
        .fee-table-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .fee-table-wrap table {
            margin-bottom: 0;
        }

        .fee-table-wrap thead th {
            background: #f8faff;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 14px;
            white-space: nowrap;
        }

        .fee-table-wrap tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }

        .fee-table-wrap tbody tr:last-child td {
            border-bottom: none;
        }

        .fee-table-wrap tbody tr:hover {
            background: #fafbff;
        }

        /* Row status highlights */
        .fee-table-wrap tbody tr.row-overdue {
            border-left: 3px solid #dc2626;
        }

        .fee-table-wrap tbody tr.row-paid {
            opacity: .75;
        }

        /* Student cell */
        .stu-name {
            font-size: .85rem;
            font-weight: 600;
            color: #111827;
            text-decoration: none;
        }

        .stu-name:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .stu-adm {
            font-size: .68rem;
            color: #9ca3af;
            margin-top: 1px;
        }

        /* Amount display */
        .amt-cell {
            font-variant-numeric: tabular-nums;
        }

        /* Progress bar for payment */
        .pay-progress {
            height: 5px;
            border-radius: 3px;
            background: #e5e7eb;
            margin-top: 4px;
            overflow: hidden;
        }

        .pay-progress-fill {
            height: 100%;
            border-radius: 3px;
            background: #16a34a;
            transition: width .3s;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        .sb-paid {
            background: #dcfce7;
            color: #15803d;
        }

        .sb-partial {
            background: #fef9c3;
            color: #92400e;
        }

        .sb-pending {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Action buttons */
        .act-btn {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            cursor: pointer;
            transition: all .12s;
            text-decoration: none;
        }

        .act-ledger {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .act-ledger:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .act-collect {
            background: #dcfce7;
            border-color: #86efac;
            color: #15803d;
        }

        .act-collect:hover {
            background: #bbf7d0;
            color: #14532d;
        }

        .act-edit {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
        }

        .act-edit:hover {
            background: #e5e7eb;
            color: #111827;
        }

        .act-delete {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #be123c;
        }

        .act-delete:hover {
            background: #ffe4e6;
            color: #9f1239;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state .es-icon {
            font-size: 3rem;
            opacity: .3;
            margin-bottom: 12px;
        }

        .empty-state .es-title {
            font-weight: 600;
            color: #6b7280;
            font-size: .95rem;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-cash-coin me-2 text-primary"></i>Student Fees
                        </h4>
                        <span class="text-muted small">Manage and collect student fee payments</span>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/fee/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Assign Fee
                        </a>
                        <a href="{{ url('admin/fee_group/allocate') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Allocate Fee
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ── Summary Stats ── --}}
                @php
                    $allFees = $getRecord->getCollection();
                    $statTotal = $getRecord->total();
                    $statPending = \App\Models\StudentFee::where('is_delete', 0)->where('status', 'pending')->count();
                    $statPartial = \App\Models\StudentFee::where('is_delete', 0)->where('status', 'partial')->count();
                    $statPaid = \App\Models\StudentFee::where('is_delete', 0)->where('status', 'paid')->count();
                    $statOverdue = \App\Models\StudentFee::where('is_delete', 0)
                        ->where('status', '!=', 'pending')
                        ->where('due_date', '<', now()->toDateString())
                        ->count();
                    $statDue = \App\Models\StudentFee::where('is_delete', 0)
                        ->where('status', '!=', 'paid')
                        ->sum(\DB::raw('amount - paid_amount'));
                    $statCollected = \App\Models\StudentFee::where('is_delete', 0)->sum('paid_amount');
                @endphp
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-2">
                        <a href="{{ url('admin/fee/list') }}" class="text-decoration-none d-block">
                            <div class="fee-stat">
                                <div class="fs-icon" style="background:#eff6ff;color:#1d4ed8;">
                                    <i class="bi bi-list-check"></i>
                                </div>
                                <div>
                                    <div class="fs-label">Total Records</div>
                                    <div class="fs-value text-primary">{{ number_format($statTotal) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-2">
                        <a href="{{ url('admin/fee/list?status=pending') }}" class="text-decoration-none d-block">
                            <div class="fee-stat">
                                <div class="fs-icon" style="background:#fff1f2;color:#be123c;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <div class="fs-label">Pending</div>
                                    <div class="fs-value" style="color:#dc2626;">{{ number_format($statPending) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-2">
                        <a href="{{ url('admin/fee/list?status=partial') }}" class="text-decoration-none d-block">
                            <div class="fee-stat">
                                <div class="fs-icon" style="background:#fefce8;color:#ca8a04;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <div class="fs-label">Partial</div>
                                    <div class="fs-value" style="color:#ca8a04;">{{ number_format($statPartial) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-2">
                        <a href="{{ url('admin/fee/list?status=paid') }}" class="text-decoration-none d-block">
                            <div class="fee-stat">
                                <div class="fs-icon" style="background:#dcfce7;color:#15803d;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div>
                                    <div class="fs-label">Paid</div>
                                    <div class="fs-value" style="color:#16a34a;">{{ number_format($statPaid) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-xl-2">
                        <div class="fee-stat">
                            <div class="fs-icon" style="background:#fee2e2;color:#dc2626;">
                                <i class="bi bi-alarm-fill"></i>
                            </div>
                            <div>
                                <div class="fs-label">Overdue</div>
                                <div class="fs-value" style="color:#dc2626;">{{ number_format($statOverdue) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-2">
                        <div class="fee-stat">
                            <div class="fs-icon" style="background:#dcfce7;color:#15803d;">
                                <i class="bi bi-currency-rupee"></i>
                            </div>
                            <div>
                                <div class="fs-label">Collected</div>
                                <div class="fs-value" style="color:#16a34a;font-size:1rem;">
                                    Rs. {{ number_format($statCollected, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Filters ── --}}
                <div class="filter-card">
                    <form method="GET" class="row g-2 align-items-end">

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">Class</label>
                            <select name="class_id" id="classFilter" class="form-select form-select-sm">
                                <option value="">All Classes</option>
                                @foreach ($getClasses as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ── NEW: Section filter ── --}}
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">Section</label>
                            <select name="section_id" id="sectionFilter" class="form-select form-select-sm">
                                <option value="">All Sections</option>
                                @foreach ($getSections as $sec)
                                    <option value="{{ $sec->id }}" data-class="{{ $sec->class_id }}"
                                        {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">Student</label>
                            {{-- inside the Student <select> --}}
                            <select name="student_id" class="form-select form-select-sm">
                                <option value="">All Students</option>
                                @foreach ($getStudents as $s)
                                    <option value="{{ $s->id }}" data-class="{{ $s->class_id }}"
                                        {{ request('student_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }} {{ $s->last_name }}
                                        @if ($s->admission_number)
                                            ({{ $s->admission_number }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">Fee Type</label>
                            <select name="fee_type_id" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                @foreach ($getFeeTypes as $ft)
                                    <option value="{{ $ft->id }}"
                                        {{ request('fee_type_id') == $ft->id ? 'selected' : '' }}>
                                        {{ $ft->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🔴 Pending
                                </option>
                                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>🟡 Partial
                                </option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>🟢 Paid
                                </option>
                                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>🚨 Overdue
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ url('admin/fee/list') }}" class="btn btn-outline-secondary btn-sm"
                                title="Reset">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        </div>

                        @if (request()->hasAny(['class_id', 'section_id', 'student_id', 'fee_type_id', 'status']))
                            <div class="col-12">
                                <div style="font-size:.72rem;color:#6b7280;">
                                    <i class="bi bi-funnel-fill text-primary me-1"></i>Filters active —
                                    @if (request('status'))
                                        <span class="badge bg-primary">{{ ucfirst(request('status')) }}</span>
                                    @endif
                                    @if (request('class_id'))
                                        <span class="badge bg-secondary">Class filtered</span>
                                    @endif
                                    @if (request('section_id'))
                                        <span class="badge bg-secondary">Section filtered</span>
                                    @endif
                                    @if (request('student_id'))
                                        <span class="badge bg-secondary">Student filtered</span>
                                    @endif
                                    @if (request('fee_type_id'))
                                        <span class="badge bg-secondary">Fee type filtered</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </form>
                </div>

                {{-- ── Table ── --}}
                <div class="fee-table-wrap">
                    {{-- Table header with count --}}
                    <div
                        style="padding:12px 16px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;background:#fff;">
                        <span style="font-size:.78rem;color:#6b7280;font-weight:500;">
                            Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }}
                            of {{ number_format($getRecord->total()) }} records
                        </span>
                        <div style="font-size:.72rem;color:#9ca3af;display:flex;gap:16px;">
                            <span><span
                                    style="width:8px;height:8px;background:#dc2626;border-radius:50%;display:inline-block;margin-right:4px;"></span>Overdue</span>
                            <span><span
                                    style="width:8px;height:8px;background:#f59e0b;border-radius:50%;display:inline-block;margin-right:4px;"></span>Partial</span>
                            <span><span
                                    style="width:8px;height:8px;background:#16a34a;border-radius:50%;display:inline-block;margin-right:4px;"></span>Paid</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Fee Type</th>
                                    <th class="text-end">Amount</th>
                                    <th>Payment</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $i => $row)
                                    @php
                                        $bal = $row->amount - $row->paid_amount;
                                        $isOverdue = $row->status !== 'paid' && $row->due_date < now()->toDateString();
                                        $pct =
                                            $row->amount > 0
                                                ? min(100, round(($row->paid_amount / $row->amount) * 100))
                                                : 0;
                                    @endphp
                                    <tr
                                        class="{{ $isOverdue ? 'row-overdue' : ($row->status === 'paid' ? 'row-paid' : '') }}">
                                        <td class="small text-muted" style="width:40px;">
                                            {{ $getRecord->firstItem() + $i }}
                                        </td>

                                        {{-- Student --}}
                                        <td>
                                            <a href="{{ url('admin/fee/student/' . $row->student_id) }}"
                                                class="stu-name">
                                                {{ $row->student_name }} {{ $row->student_last_name }}
                                            </a>
                                            <div class="stu-adm">{{ $row->admission_number }}</div>
                                        </td>

                                        {{-- Class --}}
                                        <td class="small text-muted" style="white-space:nowrap;">
                                            {{ $row->class_name }}
                                            <br>
                                            <div class="stu-adm"> {{ $row->section_name }}</div>


                                        </td>

                                        {{-- Fee type --}}
                                        <td class="small fw-semibold" style="white-space:nowrap;">
                                            {{ $row->fee_type_name }}
                                        </td>

                                        {{-- Amount --}}
                                        <td class="text-end amt-cell" style="white-space:nowrap;">
                                            <div class="small fw-bold">Rs. {{ number_format($row->amount, 2) }}</div>
                                            @if ($bal > 0)
                                                <div class="small text-danger">−Rs. {{ number_format($bal, 2) }}</div>
                                            @endif
                                        </td>

                                        {{-- Payment progress --}}
                                        <td style="min-width:130px;">
                                            <div class="d-flex justify-content-between"
                                                style="font-size:.68rem;color:#6b7280;">
                                                <span>Rs. {{ number_format($row->paid_amount, 2) }}</span>
                                                <span>{{ $pct }}%</span>
                                            </div>
                                            <div class="pay-progress">
                                                <div class="pay-progress-fill"
                                                    style="width:{{ $pct }}%;background:{{ $pct == 100 ? '#16a34a' : ($pct > 0 ? '#f59e0b' : '#dc2626') }};">
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Due date --}}
                                        <td style="white-space:nowrap;">
                                            <div
                                                class="small {{ $isOverdue ? 'text-danger fw-semibold' : 'text-muted' }}">
                                                @bsDate($row->due_date)
                                            </div>
                                            @if ($isOverdue)
                                                <div style="font-size:.62rem;color:#dc2626;font-weight:700;">OVERDUE</div>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            @if ($row->status === 'paid')
                                                <span class="status-badge sb-paid">
                                                    <i class="bi bi-check-circle-fill"></i> Paid
                                                </span>
                                            @elseif($row->status === 'partial')
                                                <span class="status-badge sb-partial">
                                                    <i class="bi bi-clock-history"></i> Partial
                                                </span>
                                            @else
                                                <span class="status-badge sb-pending">
                                                    <i class="bi bi-hourglass-split"></i> Pending
                                                </span>
                                                @if ($isOverdue)
                                                    <span class="status-badge sb-overdue">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> Overdue
                                                    </span>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- Method --}}
                                        <td>
                                            @if ($row->payment_method === 'cash')
                                                <span class="status-badge" style="background:#dcfce7;color:#15803d;">💵
                                                    Cash</span>
                                            @elseif($row->payment_method === 'bank')
                                                <span class="status-badge" style="background:#dbeafe;color:#1d4ed8;">🏦
                                                    Bank</span>
                                            @elseif($row->payment_method === 'online')
                                                <span class="status-badge" style="background:#fef9c3;color:#92400e;">📱
                                                    Online</span>
                                            @else
                                                <span class="small text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <a href="{{ url('admin/fee/student/' . $row->student_id) }}"
                                                    class="act-btn act-ledger" title="Student Ledger">
                                                    <i class="bi bi-person-lines-fill"></i>
                                                </a>

                                                @if ($row->status !== 'paid')
                                                    <a href="{{ url('admin/fee/collect/' . $row->id) }}"
                                                        class="act-btn act-collect" title="Collect Payment">
                                                        <i class="bi bi-cash-stack"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ url('admin/fee/edit/' . $row->id) }}"
                                                    class="act-btn act-edit" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ url('admin/fee/delete/' . $row->id) }}"
                                                    class="act-btn act-delete" title="Delete"
                                                    onclick="return confirm('Delete this fee record?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">
                                            <div class="empty-state">
                                                <div class="es-icon"><i class="bi bi-inbox"></i></div>
                                                <div class="es-title">No fee records found</div>
                                                <div class="small mt-1">Try adjusting your filters or
                                                    <a href="{{ url('admin/fee/add') }}">assign a new fee</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($getRecord->hasPages())
                        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">
                            {{ $getRecord->withQueryString()->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        const classFilter = document.getElementById('classFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const studentSelect = document.querySelector('select[name="student_id"]');

        // Store all student options on load
        const allStudentOptions = [...studentSelect.options].map(opt => ({
            value: opt.value,
            text: opt.text,
            cls: opt.dataset.class ?? '',
        }));

        // ── Filter sections by class ───────────────────────────────────────
        function filterSections(clsId) {
            [...sectionFilter.options].forEach(opt => {
                if (!opt.value) return;
                opt.hidden = clsId ? opt.dataset.class !== clsId : false;
            });
            // If current selection no longer belongs to class, reset it
            const sel = sectionFilter.options[sectionFilter.selectedIndex];
            if (sel.value && clsId && sel.dataset.class !== clsId) {
                sectionFilter.value = '';
            }
        }

        // ── Filter students by class ───────────────────────────────────────
        function filterStudents(clsId) {
            const current = studentSelect.value;
            // Rebuild options
            studentSelect.innerHTML = '<option value="">All Students</option>';
            allStudentOptions.forEach(s => {
                if (!s.value) return;
                if (clsId && s.cls !== clsId) return; // hide other classes
                const opt = document.createElement('option');
                opt.value = s.value;
                opt.text = s.text;
                opt.dataset.class = s.cls;
                if (s.value === current && (!clsId || s.cls === clsId)) opt.selected = true;
                studentSelect.appendChild(opt);
            });
        }

        // ── On class change ────────────────────────────────────────────────
        classFilter.addEventListener('change', function() {
            filterSections(this.value);
            filterStudents(this.value);
            sectionFilter.value = '';
            studentSelect.value = '';
        });

        // ── On page load — apply saved filter values from URL ─────────────
        (function init() {
            const clsId = classFilter.value;
            if (clsId) {
                filterSections(clsId);
                filterStudents(clsId);
            }
        })();
    </script>
@endsection
