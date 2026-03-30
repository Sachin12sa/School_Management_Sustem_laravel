@extends('layouts.app')

@section('style')
    <style>
        /* ── Stat cards ─────────────────────────────────────── */
        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform .15s, box-shadow .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        .sc-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .sc-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            font-weight: 600;
        }

        .sc-value {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .sc-sub {
            font-size: .68rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Section cards ──────────────────────────────────── */
        .section-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        .section-card .sc-head {
            padding: 12px 18px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }

        .section-card .sc-head-title {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #374151;
        }

        /* ── Table inside cards ─────────────────────────────── */
        .inner-table {
            margin-bottom: 0;
        }

        .inner-table thead th {
            background: #f8faff;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            padding: 8px 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .inner-table tbody td {
            padding: 10px 14px;
            font-size: .82rem;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        .inner-table tbody tr:last-child td {
            border-bottom: none;
        }

        .inner-table tbody tr:hover {
            background: #fafbff;
        }

        /* ── Status badges ──────────────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .03em;
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

        /* ── Action buttons ─────────────────────────────────── */
        .act-btn {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            cursor: pointer;
            transition: all .12s;
            text-decoration: none;
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

        .act-ledger {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .act-ledger:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ── Progress bar ───────────────────────────────────── */
        .pay-progress {
            height: 4px;
            border-radius: 3px;
            background: #e5e7eb;
            margin-top: 3px;
            overflow: hidden;
        }

        .pay-progress-fill {
            height: 100%;
            border-radius: 3px;
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
                            <i class="bi bi-calculator-fill me-2 text-primary"></i>Accountant Dashboard
                        </h4>
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            @php $bsToday = \App\Helpers\NepaliCalendar::today(); @endphp
                            {{ $bsToday['day'] }} {{ $bsToday['month_name'] }} {{ $bsToday['year'] }} B.S.
                        </span>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('accountant/fee/list') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-cash-stack me-1"></i> Fee Collection
                        </a>
                        <a href="{{ url('accountant/fee/payment_report') }}" class="btn btn-outline-secondary btn-sm ms-1">
                            <i class="bi bi-bar-chart-line me-1"></i> Report
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
                    $statPending = \App\Models\StudentFee::where('is_delete', 0)->where('status', 'pending')->count();
                    $statPartial = \App\Models\StudentFee::where('is_delete', 0)->where('status', 'partial')->count();
                    $statPaid = \App\Models\StudentFee::where('is_delete', 0)->where('status', 'paid')->count();
                    $statOverdue = \App\Models\StudentFee::where('is_delete', 0)
                        ->where('status', '!=', 'paid')
                        ->where('due_date', '<', now()->toDateString())
                        ->count();
                    $statCollected = \App\Models\StudentFee::where('is_delete', 0)->sum('paid_amount');
                    $statDue = \App\Models\StudentFee::where('is_delete', 0)
                        ->where('status', '!=', 'paid')
                        ->sum(\DB::raw('amount - paid_amount'));
                    $todayCollected = \App\Models\StudentFee::where('is_delete', 0)
                        ->whereDate('payment_date', today())
                        ->sum('paid_amount');
                @endphp

                <div class="row g-3 mb-4">
                    {{-- Total Collected --}}
                    <div class="col-6 col-xl-2">
                        <div class="stat-card">
                            <div class="sc-icon" style="background:#dcfce7;color:#16a34a;">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div>
                                <div class="sc-label">Total Collected</div>
                                <div class="sc-value" style="color:#16a34a;font-size:1rem;">
                                    Rs. {{ number_format($statCollected, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Today --}}
                    <div class="col-6 col-xl-2">
                        <div class="stat-card">
                            <div class="sc-icon" style="background:#eff6ff;color:#1d4ed8;">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <div class="sc-label">Today's Collection</div>
                                <div class="sc-value" style="color:#1d4ed8;font-size:1rem;">
                                    Rs. {{ number_format($todayCollected, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pending --}}
                    <div class="col-6 col-xl-2">
                        <a href="{{ url('accountant/fee/list?status=pending') }}" class="text-decoration-none d-block">
                            <div class="stat-card">
                                <div class="sc-icon" style="background:#fff1f2;color:#be123c;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <div class="sc-label">Pending</div>
                                    <div class="sc-value" style="color:#dc2626;">{{ number_format($statPending) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Partial --}}
                    <div class="col-6 col-xl-2">
                        <a href="{{ url('accountant/fee/list?status=partial') }}" class="text-decoration-none d-block">
                            <div class="stat-card">
                                <div class="sc-icon" style="background:#fefce8;color:#ca8a04;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div>
                                    <div class="sc-label">Partial</div>
                                    <div class="sc-value" style="color:#ca8a04;">{{ number_format($statPartial) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    {{-- Overdue --}}
                    <div class="col-6 col-xl-2">
                        <div class="stat-card">
                            <div class="sc-icon" style="background:#fee2e2;color:#dc2626;">
                                <i class="bi bi-alarm-fill"></i>
                            </div>
                            <div>
                                <div class="sc-label">Overdue</div>
                                <div class="sc-value" style="color:#dc2626;">{{ number_format($statOverdue) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Due --}}
                    <div class="col-6 col-xl-2">
                        <div class="stat-card">
                            <div class="sc-icon" style="background:#fef3c7;color:#b45309;">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </div>
                            <div>
                                <div class="sc-label">Total Due</div>
                                <div class="sc-value" style="color:#b45309;font-size:1rem;">
                                    Rs. {{ number_format($statDue, 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Main content row ── --}}
                <div class="row g-3">

                    {{-- Recent Fees --}}
                    <div class="col-lg-7">
                        <div class="section-card">
                            <div class="sc-head">
                                <span class="sc-head-title">
                                    <i class="bi bi-clock-history me-1 text-primary"></i>Recent Fee Records
                                </span>
                                <a href="{{ url('accountant/fee/list') }}" class="btn btn-outline-primary btn-sm"
                                    style="font-size:.72rem;">
                                    View All →
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table inner-table">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Fee Type</th>
                                            <th class="text-end">Amount</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentFees as $fee)
                                            @php
                                                $pct =
                                                    $fee->amount > 0
                                                        ? min(100, round(($fee->paid_amount / $fee->amount) * 100))
                                                        : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <a href="{{ url('accountant/fee/student/' . $fee->student_id) }}"
                                                        class="fw-semibold text-decoration-none text-dark"
                                                        style="font-size:.82rem;">
                                                        {{ $fee->student->name ?? '—' }}
                                                        {{ $fee->student->last_name ?? '' }}
                                                    </a>
                                                    <div style="font-size:.65rem;color:#9ca3af;">
                                                        {{ $fee->student->admission_number ?? '' }}
                                                    </div>
                                                </td>
                                                <td class="small fw-semibold">{{ $fee->feeType->name ?? '—' }}</td>
                                                <td class="text-end small">Rs. {{ number_format($fee->amount, 2) }}</td>
                                                <td style="min-width:100px;">
                                                    <div
                                                        style="font-size:.65rem;color:#6b7280;display:flex;justify-content:space-between;">
                                                        <span>Rs. {{ number_format($fee->paid_amount, 2) }}</span>
                                                        <span>{{ $pct }}%</span>
                                                    </div>
                                                    <div class="pay-progress">
                                                        <div class="pay-progress-fill"
                                                            style="width:{{ $pct }}%;background:{{ $pct == 100 ? '#16a34a' : ($pct > 0 ? '#f59e0b' : '#dc2626') }};">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($fee->status === 'paid')
                                                        <span class="status-badge sb-paid"><i
                                                                class="bi bi-check-circle-fill"></i> Paid</span>
                                                    @elseif($fee->status === 'partial')
                                                        <span class="status-badge sb-partial"><i
                                                                class="bi bi-clock-history"></i> Partial</span>
                                                    @else
                                                        <span class="status-badge sb-pending"><i
                                                                class="bi bi-hourglass-split"></i> Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ url('accountant/fee/student/' . $fee->student_id) }}"
                                                            class="act-btn act-ledger" title="Ledger">
                                                            <i class="bi bi-person-lines-fill"></i>
                                                        </a>
                                                        @if ($fee->status !== 'paid')
                                                            <a href="{{ url('accountant/fee/collect/' . $fee->id) }}"
                                                                class="act-btn act-collect" title="Collect">
                                                                <i class="bi bi-cash-stack"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4"
                                                    style="font-size:.82rem;">
                                                    No recent records.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Overdue Fees --}}
                    <div class="col-lg-5">
                        <div class="section-card">
                            <div class="sc-head">
                                <span class="sc-head-title">
                                    <i class="bi bi-alarm-fill me-1 text-danger"></i>Overdue Fees
                                </span>
                                <a href="{{ url('accountant/fee/list?status=pending') }}"
                                    class="btn btn-outline-danger btn-sm" style="font-size:.72rem;">
                                    View All →
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table inner-table">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Fee Type</th>
                                            <th>Due Date</th>
                                            <th class="text-end">Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($overdueFees as $fee)
                                            <tr>
                                                <td>
                                                    <a href="{{ url('accountant/fee/student/' . $fee->student_id) }}"
                                                        class="fw-semibold text-decoration-none text-dark"
                                                        style="font-size:.82rem;">
                                                        {{ $fee->student->name ?? '—' }}
                                                        {{ $fee->student->last_name ?? '' }}
                                                    </a>
                                                </td>
                                                <td class="small">{{ $fee->feeType->name ?? '—' }}</td>
                                                <td>
                                                    <div class="small text-danger fw-semibold">
                                                        @bsDate($fee->due_date)
                                                    </div>
                                                    <div style="font-size:.62rem;color:#dc2626;font-weight:700;">OVERDUE
                                                    </div>
                                                </td>
                                                <td class="text-end small text-danger fw-semibold">
                                                    Rs. {{ number_format($fee->amount - $fee->paid_amount, 2) }}
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ url('accountant/fee/student/' . $fee->student_id) }}"
                                                            class="act-btn act-ledger" title="Ledger">
                                                            <i class="bi bi-person-lines-fill"></i>
                                                        </a>
                                                        <a href="{{ url('accountant/fee/collect/' . $fee->id) }}"
                                                            class="act-btn act-collect" title="Collect">
                                                            <i class="bi bi-cash-stack"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4" style="font-size:.82rem;">
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    No overdue fees. All clear!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection
