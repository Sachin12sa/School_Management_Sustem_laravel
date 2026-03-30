@extends('layouts.app')
@section('style')
    <style>
        .stat-card {
            border: none;
            border-radius: .75rem;
            transition: transform .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
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
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i>Fine & Damage Report
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/library/fine/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Fines
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- Date filter --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body py-2 px-3">
                        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="small text-muted fw-semibold">Quick:</span>
                            @php
                                use App\Helpers\NepaliCalendar;
                                $bsToday = NepaliCalendar::today();
                                $bsY = $bsToday['year'];
                                $bsM = $bsToday['month'];

                                // This BS month in AD
                                $mStart = NepaliCalendar::bsToAd($bsY, $bsM, 1)->toDateString();
                                $mEnd = now()->toDateString();

                                // This BS year in AD
                                $yStart = NepaliCalendar::startOfBsYear($bsY)->toDateString();
                                $yEnd = now()->toDateString();

                                // Last BS month
                                $prevM = $bsM === 1 ? 12 : $bsM - 1;
                                $prevY = $bsM === 1 ? $bsY - 1 : $bsY;
                                $prevMDays = NepaliCalendar::daysInMonth($prevY, $prevM);
                                $prevStart = NepaliCalendar::bsToAd($prevY, $prevM, 1)->toDateString();
                                $prevEnd = NepaliCalendar::bsToAd($prevY, $prevM, $prevMDays)->toDateString();
                            @endphp

                            <a href="?date_from={{ now()->toDateString() }}&date_to={{ now()->toDateString() }}"
                                class="btn btn-sm btn-outline-secondary">Today</a>

                            <a href="?date_from={{ $mStart }}&date_to={{ $mEnd }}"
                                class="btn btn-sm btn-outline-secondary">
                                This Month ({{ NepaliCalendar::monthNames()[$bsM] }} {{ $bsY }})
                            </a>

                            <a href="?date_from={{ $prevStart }}&date_to={{ $prevEnd }}"
                                class="btn btn-sm btn-outline-secondary">
                                Last Month ({{ NepaliCalendar::monthNames()[$prevM] }})
                            </a>

                            <a href="?date_from={{ $yStart }}&date_to={{ $yEnd }}"
                                class="btn btn-sm btn-outline-secondary">
                                This Year ({{ $bsY }} B.S.)
                            </a>
                            <span class="text-muted small ms-1">Custom:</span>

                            {{-- First Date Input Component --}}
                            <x-bs-date-input name="date_from" id="date_from" class="form-control form-control-sm"
                                style="width:145px;" value="{{ $dateFrom }}" />

                            <span class="text-muted small">to</span>

                            {{-- Second Date Input Component --}}
                            <x-bs-date-input name="date_to" id="date_to" class="form-control form-control-sm"
                                style="width:145px;" value="{{ $dateTo }}" />

                            <button type="submit" class="btn btn-primary btn-sm ms-1">Apply</button>
                        </form>
                    </div>
                </div>

                <p class="text-muted small mb-3">
                    <i class="bi bi-calendar3 me-1"></i>
                    Showing: <strong> @bs($dateFrom) </strong>
                    — <strong> @bs($dateTo) </strong>
                    &nbsp;·&nbsp; {{ $report['totalReturns'] }} returns, {{ $report['total'] }} with fines
                </p>

                {{-- Summary cards --}}
                @php $grandTotal = $report['collected'] + $report['unpaid'] + $report['waived']; @endphp
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon" style="background:rgba(13,110,253,.1);color:#0d6efd;"><i
                                        class="bi bi-receipt-cutoff"></i></div>
                                <div>
                                    <div class="text-muted small">Total Fines</div>
                                    <div class="fw-bold fs-5 text-primary">Rs. {{ number_format($grandTotal, 2) }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $report['total'] }} records</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon" style="background:rgba(25,135,84,.1);color:#198754;"><i
                                        class="bi bi-check-circle-fill"></i></div>
                                <div>
                                    <div class="text-muted small">Collected</div>
                                    <div class="fw-bold fs-5 text-success">Rs. {{ number_format($report['collected'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon" style="background:rgba(220,53,69,.1);color:#dc3545;"><i
                                        class="bi bi-hourglass-split"></i></div>
                                <div>
                                    <div class="text-muted small">Unpaid</div>
                                    <div class="fw-bold fs-5 text-danger">Rs. {{ number_format($report['unpaid'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon" style="background:rgba(108,117,125,.1);color:#6c757d;"><i
                                        class="bi bi-slash-circle"></i></div>
                                <div>
                                    <div class="text-muted small">Waived</div>
                                    <div class="fw-bold fs-5 text-secondary">Rs. {{ number_format($report['waived'], 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">

                    {{-- Breakdown bar --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-pie-chart-fill me-1 text-primary"></i> Breakdown
                            </div>
                            <div class="card-body">
                                @if ($grandTotal > 0)
                                    <div class="d-flex mb-3" style="height:14px;border-radius:7px;overflow:hidden;">
                                        @if ($report['collected'] > 0)
                                            <div
                                                style="width:{{ round(($report['collected'] / $grandTotal) * 100) }}%;background:#198754;">
                                            </div>
                                        @endif
                                        @if ($report['unpaid'] > 0)
                                            <div
                                                style="width:{{ round(($report['unpaid'] / $grandTotal) * 100) }}%;background:#dc3545;">
                                            </div>
                                        @endif
                                        @if ($report['waived'] > 0)
                                            <div
                                                style="width:{{ round(($report['waived'] / $grandTotal) * 100) }}%;background:#6c757d;">
                                            </div>
                                        @endif
                                    </div>
                                    @foreach ([['Collected', $report['collected'], '#198754'], ['Unpaid', $report['unpaid'], '#dc3545'], ['Waived', $report['waived'], '#6c757d']] as [$lbl, $amt, $clr])
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="d-flex align-items-center gap-2 small">
                                                <span
                                                    style="width:12px;height:12px;border-radius:2px;background:{{ $clr }};display:inline-block;"></span>
                                                {{ $lbl }}
                                            </span>
                                            <span class="fw-semibold small" style="color:{{ $clr }};">
                                                Rs. {{ number_format($amt, 2) }}
                                                <span
                                                    class="text-muted fw-normal">({{ round(($amt / $grandTotal) * 100) }}%)</span>
                                            </span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted small text-center py-4 mb-0">No fine data for this period.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Book condition (only if damage columns exist) --}}
                    @if ($hasDamageCols && $report['conditionStats']->count() > 0)
                        <div class="col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-book me-1 text-warning"></i> Book Conditions
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Condition</th>
                                                <th class="text-end">Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($report['conditionStats'] as $cs)
                                                @php
                                                    $condMap = [
                                                        'good' => ['Good', 'bg-success'],
                                                        'damaged' => ['Damaged', 'bg-warning text-dark'],
                                                        'torn' => ['Torn', 'bg-danger'],
                                                        'lost' => ['Lost', 'bg-dark'],
                                                    ];
                                                    [$clbl, $cbadge] = $condMap[$cs->book_condition] ?? [
                                                        ucfirst($cs->book_condition),
                                                        'bg-secondary',
                                                    ];
                                                @endphp
                                                <tr>
                                                    <td><span
                                                            class="badge {{ $cbadge }}">{{ $clbl }}</span>
                                                    </td>
                                                    <td class="text-end fw-semibold small">{{ $cs->cnt }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @if ($report['damageTotal'] > 0)
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td class="small fw-semibold">Damage charges</td>
                                                    <td class="text-end small fw-bold text-danger">Rs.
                                                        {{ number_format($report['damageTotal'], 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- By method --}}
                    <div class="{{ $hasDamageCols && $report['conditionStats']->count() > 0 ? 'col-lg-2' : 'col-lg-4' }}">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-cash-stack me-1 text-success"></i> By Method
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Method</th>
                                            <th class="text-end">Rs.</th>
                                            <th class="text-end">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report['byMethod'] as $m)
                                            @php
                                                $mc = match ($m->fine_payment_method ?? '') {
                                                    'cash' => 'bg-success',
                                                    'bank' => 'bg-info text-dark',
                                                    'online' => 'bg-warning text-dark',
                                                    default => 'bg-secondary',
                                            }; @endphp
                                            <tr>
                                                <td><span
                                                        class="badge {{ $mc }}">{{ ucfirst($m->fine_payment_method ?? 'N/A') }}</span>
                                                </td>
                                                <td class="text-end small fw-semibold">{{ number_format($m->total, 0) }}
                                                </td>
                                                <td class="text-end small text-muted">{{ $m->cnt }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3 small">No payments.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($report['byMethod']->count() > 0)
                                        <tfoot class="table-light">
                                            <tr>
                                                <td class="fw-semibold small">Total</td>
                                                <td class="text-end fw-bold text-success small">
                                                    {{ number_format($report['collected'], 0) }}</td>
                                                <td class="text-end small text-muted">
                                                    {{ $report['byMethod']->sum('cnt') }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Top members --}}
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-person-fill me-1 text-danger"></i> Most Fines
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Member</th>
                                            <th class="text-end">Fine</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report['topMembers'] as $i => $m)
                                            <tr>
                                                <td class="small text-muted">{{ $i + 1 }}</td>
                                                <td>
                                                    <div class="small fw-semibold">{{ $m->member_name }}
                                                        {{ $m->member_last_name }}</div>
                                                    <span class="text-muted" style="font-size:.65rem;">
                                                        {{ $m->member_type == 2 ? 'Teacher' : 'Student' }} ·
                                                        {{ $m->fine_count }}×
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="small fw-semibold text-danger">Rs.
                                                        {{ number_format($m->total_fine, 0) }}</div>
                                                    <div class="text-muted" style="font-size:.65rem;">paid Rs.
                                                        {{ number_format($m->paid_fine, 0) }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3 small">No data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Transactions --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div
                        class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-muted text-uppercase">
                            <i class="bi bi-list-ul me-1 text-primary"></i> All Transactions
                        </span>
                        <span class="badge bg-secondary">{{ $transactions->total() }} records</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Due</th>
                                        <th>Returned</th>
                                        @if ($hasDamageCols)
                                            <th>Condition</th>
                                        @endif
                                        <th>Fine</th>
                                        @if ($hasDamageCols)
                                            <th>Damage</th>
                                        @endif
                                        <th>Status</th>
                                        <th>Method</th>
                                        <th>Paid On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $i => $row)
                                        @php
                                            $dS = substr((string) $row->due_date, 0, 10);
                                            $rS = $row->return_date ? substr((string) $row->return_date, 0, 10) : null;
                                            $pS = $row->fine_paid_at
                                                ? substr((string) $row->fine_paid_at, 0, 10)
                                                : null;
                                            $fsB = match ($row->fine_status ?? 'none') {
                                                'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                                                'paid' => '<span class="badge bg-success">Paid</span>',
                                                'waived' => '<span class="badge bg-secondary">Waived</span>',
                                                default => '<span class="badge bg-light text-muted border">—</span>',
                                            };
                                            $mc = match ($row->fine_payment_method ?? '') {
                                                'cash' => 'bg-success',
                                                'bank' => 'bg-info text-dark',
                                                'online' => 'bg-warning text-dark',
                                                default => 'bg-secondary',
                                            };
                                            $condMap2 = [
                                                'good' => ['Good', 'bg-success bg-opacity-15 text-success'],
                                                'damaged' => ['Damaged', 'bg-warning text-dark'],
                                                'torn' => ['Torn', 'bg-danger'],
                                                'lost' => ['Lost', 'bg-dark'],
                                            ];
                                        @endphp
                                        <tr>
                                            <td class="small text-muted">{{ $transactions->firstItem() + $i }}</td>
                                            <td>
                                                <div class="small fw-semibold">{{ $row->member_name }}
                                                    {{ $row->member_last_name }}</div>
                                                <span class="text-muted"
                                                    style="font-size:.65rem;">{{ $row->member_type == 2 ? 'Teacher' : 'Student' }}</span>
                                            </td>
                                            <td class="small fw-semibold">{{ $row->book_title }}</td>
                                            <td class="small text-danger">
                                                {{ \Carbon\Carbon::parse($dS)->format('d M Y') }}</td>
                                            <td class="small">
                                                {{ $rS ? \Carbon\Carbon::parse($rS)->format('d M Y') : '—' }}</td>
                                            @if ($hasDamageCols)
                                                <td>
                                                    @php [$clbl2,$cbadge2] = $condMap2[$row->book_condition??'good']??['Good','bg-success bg-opacity-15 text-success']; @endphp
                                                    <span class="badge {{ $cbadge2 }}">{{ $clbl2 }}</span>
                                                </td>
                                            @endif
                                            <td class="small fw-bold">
                                                {{ ($row->fine_amount ?? 0) > 0 ? 'Rs.' . number_format($row->fine_amount, 2) : '—' }}
                                            </td>
                                            @if ($hasDamageCols)
                                                <td class="small">
                                                    {{ ($row->damage_charge ?? 0) > 0 ? 'Rs.' . number_format($row->damage_charge, 2) : '—' }}
                                                </td>
                                            @endif
                                            <td>{!! $fsB !!}</td>
                                            <td>
                                                @if ($row->fine_payment_method)
                                                    <span
                                                        class="badge {{ $mc }}">{{ ucfirst($row->fine_payment_method) }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="small">
                                                {{ $pS ? \Carbon\Carbon::parse($pS)->format('d M Y') : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted py-4">No transactions found
                                                for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($transactions->hasPages())
                            <div class="px-3 py-2">{{ $transactions->withQueryString()->links() }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
