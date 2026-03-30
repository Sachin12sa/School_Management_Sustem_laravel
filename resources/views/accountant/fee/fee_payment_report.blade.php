@extends('layouts.app')

@section('style')
    <style>
        /* ── Period pills ─────────────────────────────────────── */
        .period-pill {
            border: 1.5px solid #e5e7eb;
            border-radius: 20px;
            padding: .3rem .9rem;
            background: #f9fafb;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            color: #4b5563;
            display: inline-block;
            white-space: nowrap;
        }

        .period-pill:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            color: #111827;
        }

        .period-pill.active {
            background: #1a56a0;
            border-color: #1a56a0;
            color: #fff;
        }

        /* ── Stat cards ───────────────────────────────────────── */
        .rpt-stat {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform .15s, box-shadow .15s;
            height: 100%;
        }

        .rpt-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        }

        .rs-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .rs-label {
            font-size: .67rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b7280;
            font-weight: 700;
        }

        .rs-value {
            font-size: 1.25rem;
            font-weight: 800;
            margin-top: 2px;
        }

        .rs-sub {
            font-size: .68rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Section cards ────────────────────────────────────── */
        .section-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
        }

        .section-card .card-head {
            padding: 12px 18px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-card .card-head-title {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #374151;
        }

        /* ── Table ────────────────────────────────────────────── */
        .rpt-table {
            margin-bottom: 0;
        }

        .rpt-table thead th {
            background: #f8faff;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            padding: 9px 14px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .rpt-table tbody td {
            padding: 10px 14px;
            font-size: .82rem;
            border-bottom: 1px solid #f9fafb;
            vertical-align: middle;
        }

        .rpt-table tbody tr:last-child td {
            border-bottom: none;
        }

        .rpt-table tbody tr:hover {
            background: #fafbff;
        }

        .rpt-table tfoot td {
            padding: 10px 14px;
            font-size: .82rem;
            background: #f1f5f9;
            font-weight: 700;
            border-top: 2px solid #e5e7eb;
        }

        /* ── Method badges ────────────────────────────────────── */
        .mbadge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 9px;
            border-radius: 20px;
            font-size: .66rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .mb-cash {
            background: #dcfce7;
            color: #15803d;
        }

        .mb-bank {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .mb-online {
            background: #fef9c3;
            color: #92400e;
        }

        /* ── Status badges ────────────────────────────────────── */
        .sbadge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .64rem;
            font-weight: 700;
        }

        .sb-paid {
            background: #dcfce7;
            color: #15803d;
        }

        .sb-partial {
            background: #fef9c3;
            color: #92400e;
        }

        /* ── Receipt btn ──────────────────────────────────────── */
        .receipt-link {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            text-decoration: none;
            transition: all .12s;
        }

        .receipt-link:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        /* ── Progress bar ─────────────────────────────────────── */
        .mini-bar {
            height: 4px;
            border-radius: 3px;
            background: #e5e7eb;
            margin-top: 3px;
            overflow: hidden;
        }

        .mini-bar-fill {
            height: 100%;
            border-radius: 3px;
        }

        /* ── Print-report button ──────────────────────────────── */
        .btn-print-report {
            background: linear-gradient(135deg, #1a56a0, #0d3a7a);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity .15s;
        }

        .btn-print-report:hover {
            opacity: .9;
            color: #fff;
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
                            <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Payment Report
                        </h4>
                        @if ($dateFrom && $dateTo)
                            <span class="text-muted small">
                                @bsDate($dateFrom) — @bsDate($dateTo)
                            </span>
                        @endif
                    </div>
                    <div class="col-sm-6 text-sm-end d-flex gap-2 justify-content-sm-end flex-wrap">
                        {{-- Dynamic print invoice for current filters --}}
                        <a href="{{ url($prefix . '/fee/report-invoice?' . http_build_query(array_filter(['period' => $period, 'payment_method' => $method, 'date_from' => $dateFrom, 'date_to' => $dateTo]))) }}"
                            target="_blank" class="btn-print-report">
                            🖨️ Print Report Invoice
                        </a>
                        <a href="{{ url($prefix . '/fee/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- ── Filter Bar ── --}}
                <div class="section-card mb-3">
                    <div class="card-body p-3">
                        <form method="GET" id="filterForm">
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="small fw-bold text-muted">Period:</span>
                                @foreach (['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'quarterly' => 'This Quarter', 'yearly' => 'This Year', 'custom' => 'Custom'] as $val => $label)
                                    <a href="javascript:void(0)" onclick="setPeriod('{{ $val }}')"
                                        class="period-pill {{ $period === $val ? 'active' : '' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                                <input type="hidden" name="period" id="periodInput" value="{{ $period }}">

                                {{-- Custom date range --}}
                                <span id="customRange" class="d-flex gap-2 align-items-center"
                                    style="{{ $period === 'custom' ? '' : 'display:none!important;' }}">
                                    <input type="text" name="date_from" id="date_from"
                                        class="nepali-date form-control form-control-sm" value="{{ $dateFrom }}"
                                        style="width:140px;" placeholder="From" autocomplete="off">
                                    <span class="text-muted small">to</span>
                                    <input type="text" name="date_to" id="date_to"
                                        class="nepali-date form-control form-control-sm" value="{{ $dateTo }}"
                                        style="width:140px;" placeholder="To" autocomplete="off">
                                    <button type="submit" class="btn btn-primary btn-sm px-3">Apply</button>
                                </span>

                                <div class="ms-auto d-flex gap-2 align-items-center">
                                    <select name="payment_method" class="form-select form-select-sm" style="width:160px;"
                                        onchange="this.form.submit()">
                                        <option value="" {{ $method === '' ? 'selected' : '' }}>All Methods</option>
                                        <option value="cash" {{ $method === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                        <option value="bank" {{ $method === 'bank' ? 'selected' : '' }}>🏦 Bank Transfer
                                        </option>
                                        <option value="online" {{ $method === 'online' ? 'selected' : '' }}>📱 Online</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── Summary Stats ── --}}
                @php
                    $periodLabel =
                        [
                            'daily' => 'Today',
                            'weekly' => 'This Week',
                            'monthly' => 'This Month',
                            'quarterly' => 'This Quarter',
                            'yearly' => 'This Year',
                            'custom' => 'Selected Period',
                        ][$period] ?? ucfirst($period);
                @endphp
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="rpt-stat">
                            <div class="rs-icon" style="background:#eff6ff;color:#1d4ed8;">
                                <i class="bi bi-currency-rupee"></i>
                            </div>
                            <div>
                                <div class="rs-label">Total Collected</div>
                                <div class="rs-value" style="color:#1d4ed8;">
                                    Rs. {{ number_format($summary['total'], 2) }}
                                </div>
                                <div class="rs-sub">{{ $summary['count'] }} payments · {{ $periodLabel }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-stat">
                            <div class="rs-icon" style="background:#dcfce7;color:#16a34a;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div>
                                <div class="rs-label">Cash</div>
                                <div class="rs-value" style="color:#16a34a;">
                                    Rs. {{ number_format($summary['cash'], 2) }}
                                </div>
                                @php $cashPct = $summary['total'] > 0 ? round(($summary['cash']/$summary['total'])*100) : 0; @endphp
                                <div class="rs-sub">{{ $cashPct }}% of total</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-stat">
                            <div class="rs-icon" style="background:#dbeafe;color:#1d4ed8;">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <div>
                                <div class="rs-label">Bank Transfer</div>
                                <div class="rs-value" style="color:#1d4ed8;">
                                    Rs. {{ number_format($summary['bank'], 2) }}
                                </div>
                                @php $bankPct = $summary['total'] > 0 ? round(($summary['bank']/$summary['total'])*100) : 0; @endphp
                                <div class="rs-sub">{{ $bankPct }}% of total</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="rpt-stat">
                            <div class="rs-icon" style="background:#fef9c3;color:#92400e;">
                                <i class="bi bi-phone-fill"></i>
                            </div>
                            <div>
                                <div class="rs-label">Online / eSewa / Khalti</div>
                                <div class="rs-value" style="color:#92400e;">
                                    Rs. {{ number_format($summary['online'], 2) }}
                                </div>
                                @php $onlinePct = $summary['total'] > 0 ? round(($summary['online']/$summary['total'])*100) : 0; @endphp
                                <div class="rs-sub">{{ $onlinePct }}% of total</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Charts Row ── --}}
                <div class="row g-3 mb-3">
                    {{-- Trend chart --}}
                    <div class="col-lg-8">
                        <div class="section-card h-100">
                            <div class="card-head">
                                <span class="card-head-title">
                                    <i class="bi bi-graph-up me-1 text-primary"></i>Daily Collection Trend
                                </span>
                                <span class="text-muted" style="font-size:.7rem;">
                                    @bsDate($trendFrom) — @bsDate($trendTo)
                                </span>
                            </div>
                            <div class="card-body p-3">
                                <div style="position:relative;height:220px;">
                                    <canvas id="trendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Method donut --}}
                    <div class="col-lg-4">
                        <div class="section-card h-100">
                            <div class="card-head">
                                <span class="card-head-title">
                                    <i class="bi bi-pie-chart-fill me-1 text-primary"></i>By Method
                                </span>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center p-3">
                                @if ($summary['total'] > 0)
                                    <div style="position:relative;height:150px;margin-bottom:12px;">
                                        <canvas id="methodChart"></canvas>
                                    </div>
                                    @foreach ($byMethod as $m)
                                        @php
                                            $mPct =
                                                $summary['total'] > 0
                                                    ? round(($m->total / $summary['total']) * 100)
                                                    : 0;
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                @if ($m->payment_method === 'cash')
                                                    <span class="mbadge mb-cash">💵 Cash</span>
                                                @elseif($m->payment_method === 'bank')
                                                    <span class="mbadge mb-bank">🏦 Bank</span>
                                                @else
                                                    <span class="mbadge mb-online">📱 Online</span>
                                                @endif
                                                <span class="text-muted ms-1"
                                                    style="font-size:.68rem;">{{ $m->count }} txns</span>
                                            </div>
                                            <div class="text-end">
                                                <div style="font-size:.78rem;font-weight:700;">Rs.
                                                    {{ number_format($m->total, 2) }}</div>
                                                <div style="font-size:.65rem;color:#9ca3af;">{{ $mPct }}%</div>
                                            </div>
                                        </div>
                                        <div class="mini-bar mb-2">
                                            <div class="mini-bar-fill"
                                                style="width:{{ $mPct }}%;background:{{ $m->payment_method === 'cash' ? '#16a34a' : ($m->payment_method === 'bank' ? '#1d4ed8' : '#ca8a04') }};">
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-4" style="font-size:.82rem;">
                                        <i class="bi bi-inbox d-block mb-2" style="font-size:1.5rem;opacity:.3;"></i>
                                        No payments in this period.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Fee Type Breakdown + Transactions ── --}}
                <div class="row g-3 mb-3">

                    {{-- By Fee Type --}}
                    <div class="col-lg-4">
                        <div class="section-card h-100">
                            <div class="card-head">
                                <span class="card-head-title">
                                    <i class="bi bi-tags-fill me-1 text-primary"></i>By Fee Type
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table rpt-table">
                                    <thead>
                                        <tr>
                                            <th>Fee Type</th>
                                            <th class="text-center">Txns</th>
                                            <th class="text-end">Collected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($byFeeType as $ft)
                                            @php
                                                $ftPct =
                                                    $summary['total'] > 0
                                                        ? round(($ft->total / $summary['total']) * 100)
                                                        : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div style="font-size:.8rem;font-weight:600;">{{ $ft->fee_type_name }}
                                                    </div>
                                                    <div class="mini-bar" style="width:80px;">
                                                        <div class="mini-bar-fill"
                                                            style="width:{{ $ftPct }}%;background:#1a56a0;"></div>
                                                    </div>
                                                </td>
                                                <td class="text-center small">{{ $ft->count }}</td>
                                                <td class="text-end small fw-semibold" style="color:#1d4ed8;">
                                                    Rs. {{ number_format($ft->total, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4 small">No data for
                                                    this period.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($byFeeType->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <td>Total</td>
                                                <td class="text-center">{{ $byFeeType->sum('count') }}</td>
                                                <td class="text-end" style="color:#1d4ed8;">
                                                    Rs. {{ number_format($byFeeType->sum('total'), 2) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Transactions --}}
                    <div class="col-lg-8">
                        <div class="section-card">
                            <div class="card-head">
                                <span class="card-head-title">
                                    <i class="bi bi-list-ul me-1 text-primary"></i>Transactions
                                </span>
                                <span
                                    style="background:#e5e7eb;color:#374151;font-size:.68rem;font-weight:700;
                                         padding:3px 10px;border-radius:20px;">
                                    {{ $transactions->total() }} records
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table rpt-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student</th>
                                            <th>Fee Type</th>
                                            <th>Method</th>
                                            <th class="text-end">Paid</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $i => $row)
                                            <tr>
                                                <td class="text-muted" style="font-size:.72rem;">
                                                    {{ $transactions->firstItem() + $i }}
                                                </td>
                                                <td>
                                                    <a href="{{ url($prefix . '/fee/student/' . $row->student_id) }}"
                                                        class="fw-semibold text-decoration-none text-dark"
                                                        style="font-size:.82rem;">
                                                        {{ $row->student_name }} {{ $row->student_last_name }}
                                                    </a>
                                                    <div style="font-size:.65rem;color:#9ca3af;">
                                                        {{ $row->admission_number }}</div>
                                                </td>
                                                <td style="font-size:.8rem;font-weight:600;">{{ $row->fee_type_name }}
                                                </td>
                                                <td>
                                                    @if ($row->payment_method === 'cash')
                                                        <span class="mbadge mb-cash">💵 Cash</span>
                                                    @elseif($row->payment_method === 'bank')
                                                        <span class="mbadge mb-bank">🏦 Bank</span>
                                                    @elseif($row->payment_method === 'online')
                                                        <span class="mbadge mb-online">📱 Online</span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold" style="color:#16a34a;font-size:.82rem;">
                                                    Rs. {{ number_format($row->paid_amount, 2) }}
                                                </td>
                                                <td style="white-space:nowrap;">
                                                    <div style="font-size:.78rem;">@bsDate($row->payment_date)</div>
                                                </td>
                                                <td>
                                                    @if ($row->status === 'paid')
                                                        <span class="sbadge sb-paid"><i
                                                                class="bi bi-check-circle-fill"></i> Paid</span>
                                                    @else
                                                        <span class="sbadge sb-partial"><i
                                                                class="bi bi-clock-history"></i> Partial</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Per-row receipt link --}}
                                                    <a href="{{ url($prefix . '/fee/receipt/' . $row->student_id) }}"
                                                        class="receipt-link" title="View/Print Receipt" target="_blank">
                                                        <i class="bi bi-receipt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox d-block mb-2"
                                                        style="font-size:2rem;opacity:.3;"></i>
                                                    No transactions found for this period.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($transactions->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end">Period Total:</td>
                                                <td class="text-end" style="color:#16a34a;">
                                                    Rs. {{ number_format($transactions->sum('paid_amount'), 2) }}
                                                </td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                            @if ($transactions->hasPages())
                                <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">
                                    {{ $transactions->withQueryString()->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
        <script>
            // ── Period filter ──────────────────────────────────────────────────────────
            function setPeriod(val) {
                document.getElementById('periodInput').value = val;
                document.getElementById('customRange').style.display = (val === 'custom') ? '' : 'none';
                if (val !== 'custom') document.getElementById('filterForm').submit();
            }

            // ── Build daily trend labels from PHP data ─────────────────────────────────
            var trendRaw = @json($dailyTrend->toArray());
            var trendFrom = '{{ $trendFrom }}';
            var trendTo = '{{ $trendTo }}';

            function nextDate(str) {
                var p = str.split('-');
                var d = new Date(+p[0], +p[1] - 1, +p[2] + 1);
                return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2,
                    '0');
            }

            var labels = [],
                values = [],
                cur = trendFrom;
            while (cur <= trendTo) {
                labels.push(cur.slice(5));
                values.push(trendRaw[cur] ? parseFloat(trendRaw[cur].total) : 0);
                cur = nextDate(cur);
            }

            // ── Trend bar chart ────────────────────────────────────────────────────────
            new Chart(document.getElementById('trendChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Collected (Rs.)',
                        data: values,
                        backgroundColor: function(ctx) {
                            var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                            g.addColorStop(0, 'rgba(26,86,160,0.85)');
                            g.addColorStop(1, 'rgba(26,86,160,0.25)');
                            return g;
                        },
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => 'Rs. ' + ctx.parsed.y.toLocaleString('en-IN', {
                                    minimumFractionDigits: 2
                                })
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,.04)'
                            },
                            ticks: {
                                callback: v => v >= 1000 ? 'Rs.' + (v / 1000).toFixed(0) + 'k' : 'Rs.' + v,
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 20,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // ── Method donut chart ─────────────────────────────────────────────────────
            @if ($summary['total'] > 0)
                new Chart(document.getElementById('methodChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Cash', 'Bank', 'Online'],
                        datasets: [{
                            data: [{{ $summary['cash'] }}, {{ $summary['bank'] }}, {{ $summary['online'] }}],
                            backgroundColor: ['#16a34a', '#1d4ed8', '#ca8a04'],
                            borderWidth: 3,
                            borderColor: '#fff',
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ctx.label + ': Rs. ' + ctx.parsed.toLocaleString('en-IN', {
                                        minimumFractionDigits: 2
                                    })
                                }
                            }
                        }
                    }
                });
            @endif

            // ── Nepali date picker ─────────────────────────────────────────────────────
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.nepali-date').forEach(function(el) {
                    el.NepaliDatePicker({
                        ndpYear: true,
                        ndpMonth: true,
                        readOnlyInput: true
                    });
                });
            });
        </script>
    </main>
@endsection
