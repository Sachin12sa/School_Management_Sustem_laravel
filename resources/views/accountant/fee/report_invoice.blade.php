<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Report — {{ $periodLabel }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            padding: 20px;
        }

        /* ── Action bar ── */
        .action-bar {
            max-width: 900px;
            margin: 0 auto 14px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 18px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-print {
            background: #1a56a0;
            color: #fff;
        }

        .btn-print:hover {
            background: #0d3a7a;
        }

        .btn-back {
            background: #fff;
            color: #374151;
            border: 1.5px solid #d1d5db;
        }

        .btn-back:hover {
            background: #f9fafb;
        }

        /* ── Report shell ── */
        .report {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 30px rgba(0, 0, 0, .12);
        }

        /* ── Header ── */
        .rpt-header {
            background: linear-gradient(135deg, #0f172a 0%, #1a56a0 100%);
            color: #fff;
            padding: 28px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .school-name {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .school-sub {
            font-size: 11px;
            opacity: .65;
            margin-top: 3px;
        }

        .rpt-title-block {
            text-align: right;
        }

        .rpt-title-block h2 {
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .rpt-title-block .rno {
            font-size: 11px;
            opacity: .7;
            margin-top: 4px;
        }

        /* ── Period banner ── */
        .period-banner {
            background: #1e3a5f;
            color: #e0f0ff;
            padding: 10px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
        }

        .period-banner strong {
            color: #fff;
            font-size: 13px;
        }

        /* ── Summary pills ── */
        .summary-row {
            display: flex;
            gap: 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .sum-pill {
            flex: 1;
            padding: 16px 20px;
            text-align: center;
            border-right: 1px solid #e5e7eb;
        }

        .sum-pill:last-child {
            border-right: none;
        }

        .sum-pill .sp-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6b7280;
            font-weight: 700;
        }

        .sum-pill .sp-value {
            font-size: 1.1rem;
            font-weight: 800;
            margin-top: 3px;
        }

        .sum-pill .sp-sub {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── Body ── */
        .rpt-body {
            padding: 24px 36px;
        }

        /* ── Section title ── */
        .sec-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 700;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 6px;
            margin-bottom: 14px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sec-title:first-child {
            margin-top: 0;
        }

        /* ── Method breakdown ── */
        .method-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .method-box {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: center;
        }

        .method-box .mb-icon {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .method-box .mb-name {
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .method-box .mb-amount {
            font-size: 1rem;
            font-weight: 800;
            margin-top: 4px;
        }

        .method-box .mb-pct {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .method-cash {
            border-color: #86efac;
            background: #f0fdf4;
        }

        .method-bank {
            border-color: #93c5fd;
            background: #eff6ff;
        }

        .method-online {
            border-color: #fde68a;
            background: #fefce8;
        }

        /* ── Fee type table ── */
        .ft-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .ft-table thead tr {
            background: #f8faff;
        }

        .ft-table thead th {
            padding: 8px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        .ft-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .ft-table tbody td {
            padding: 8px 12px;
        }

        .ft-table tfoot tr {
            background: #f1f5f9;
        }

        .ft-table tfoot td {
            padding: 8px 12px;
            font-weight: 700;
            border-top: 2px solid #e5e7eb;
        }

        /* Progress bar */
        .prog {
            height: 5px;
            border-radius: 3px;
            background: #e5e7eb;
            overflow: hidden;
            margin-top: 3px;
            width: 100%;
        }

        .prog-fill {
            height: 100%;
            border-radius: 3px;
            background: #1a56a0;
        }

        /* ── Transactions table ── */
        .txn-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .txn-table thead tr {
            background: #0f172a;
            color: #fff;
        }

        .txn-table thead th {
            padding: 9px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .txn-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .txn-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .txn-table tbody td {
            padding: 8px 12px;
        }

        .txn-table tfoot tr {
            background: #e8f4ff;
            font-weight: 700;
        }

        .txn-table tfoot td {
            padding: 9px 12px;
            font-size: 12px;
            border-top: 2px solid #1a56a0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Status badges */
        .badge-paid {
            background: #dcfce7;
            color: #15803d;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
        }

        .badge-partial {
            background: #fef9c3;
            color: #92400e;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
        }

        .mbadge {
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
        }

        .mb-c {
            background: #dcfce7;
            color: #15803d;
        }

        .mb-b {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .mb-o {
            background: #fef9c3;
            color: #92400e;
        }

        /* ── Footer ── */
        .rpt-footer {
            border-top: 1px solid #e5e7eb;
            padding: 14px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 10px;
            color: #9ca3af;
        }

        .sig-line {
            text-align: center;
            border-top: 1px solid #374151;
            padding-top: 4px;
            width: 160px;
            font-size: 10px;
            color: #374151;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .report {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            @page {
                margin: 8mm;
                size: A4;
            }
        }
    </style>
</head>

<body>

    {{-- Action Bar --}}
    <div class="action-bar">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print / Save PDF</button>
        <a href="javascript:window.close()" class="btn btn-back">✕ Close</a>
    </div>

    <div class="report">

        {{-- Header --}}
        <div class="rpt-header">
            <div>
                <div class="school-name">Brain Fart Institute</div>
                <div class="school-sub">School Management System</div>
                <div class="school-sub" style="margin-top:6px;">📞 +977-XXXXXXXXX &nbsp;|&nbsp; 📧 info@school.edu.np
                </div>
            </div>
            <div class="rpt-title-block">
                <h2>Fee Collection Report</h2>
                <div class="rno">Report # {{ $reportNo }}</div>
                <div class="rno" style="margin-top:3px;">Generated: @bsDate($generatedDate)</div>
            </div>
        </div>

        {{-- Period Banner --}}
        <div class="period-banner">
            <div>
                📅 Period: <strong>{{ $periodLabel }}</strong>
                @if ($dateFrom && $dateTo)
                    &nbsp;( @bsDate($dateFrom) — @bsDate($dateTo) )
                @endif
            </div>
            <div>
                @if ($method)
                    💳 Method: <strong>{{ ucfirst($method) }}</strong>
                @else
                    💳 Method: <strong>All Methods</strong>
                @endif
            </div>
        </div>

        {{-- Summary Pills --}}
        <div class="summary-row">
            <div class="sum-pill">
                <div class="sp-label">Total Collected</div>
                <div class="sp-value" style="color:#1a56a0;">Rs. {{ number_format($summary['total'], 2) }}</div>
                <div class="sp-sub">{{ $summary['count'] }} payments</div>
            </div>
            <div class="sum-pill">
                <div class="sp-label">Cash</div>
                <div class="sp-value" style="color:#16a34a;">Rs. {{ number_format($summary['cash'], 2) }}</div>
                @php $cp = $summary['total'] > 0 ? round(($summary['cash']/$summary['total'])*100) : 0; @endphp
                <div class="sp-sub">{{ $cp }}%</div>
            </div>
            <div class="sum-pill">
                <div class="sp-label">Bank Transfer</div>
                <div class="sp-value" style="color:#1d4ed8;">Rs. {{ number_format($summary['bank'], 2) }}</div>
                @php $bp = $summary['total'] > 0 ? round(($summary['bank']/$summary['total'])*100) : 0; @endphp
                <div class="sp-sub">{{ $bp }}%</div>
            </div>
            <div class="sum-pill">
                <div class="sp-label">Online</div>
                <div class="sp-value" style="color:#ca8a04;">Rs. {{ number_format($summary['online'], 2) }}</div>
                @php $op = $summary['total'] > 0 ? round(($summary['online']/$summary['total'])*100) : 0; @endphp
                <div class="sp-sub">{{ $op }}%</div>
            </div>
            <div class="sum-pill">
                <div class="sp-label">Transactions</div>
                <div class="sp-value" style="color:#374151;">{{ number_format($summary['count']) }}</div>
                <div class="sp-sub">total records</div>
            </div>
        </div>

        {{-- Body --}}
        <div class="rpt-body">

            {{-- Payment Method Breakdown --}}
            <div class="sec-title">💳 Payment Method Breakdown</div>
            <div class="method-grid">
                <div class="method-box method-cash">
                    <div class="mb-icon">💵</div>
                    <div class="mb-name">Cash</div>
                    <div class="mb-amount" style="color:#16a34a;">Rs. {{ number_format($summary['cash'], 2) }}</div>
                    <div class="mb-pct">{{ $cp }}% of total &nbsp;·&nbsp;
                        {{ $byMethod->where('payment_method', 'cash')->first()->count ?? 0 }} payments
                    </div>
                </div>
                <div class="method-box method-bank">
                    <div class="mb-icon">🏦</div>
                    <div class="mb-name">Bank Transfer</div>
                    <div class="mb-amount" style="color:#1d4ed8;">Rs. {{ number_format($summary['bank'], 2) }}</div>
                    <div class="mb-pct">{{ $bp }}% of total &nbsp;·&nbsp;
                        {{ $byMethod->where('payment_method', 'bank')->first()->count ?? 0 }} payments
                    </div>
                </div>
                <div class="method-box method-online">
                    <div class="mb-icon">📱</div>
                    <div class="mb-name">eSewa / Khalti / Online</div>
                    <div class="mb-amount" style="color:#92400e;">Rs. {{ number_format($summary['online'], 2) }}</div>
                    <div class="mb-pct">{{ $op }}% of total &nbsp;·&nbsp;
                        {{ $byMethod->where('payment_method', 'online')->first()->count ?? 0 }} payments
                    </div>
                </div>
            </div>

            {{-- Fee Type Breakdown --}}
            <div class="sec-title">🏷️ Collection by Fee Type</div>
            <table class="ft-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fee Type</th>
                        <th class="text-center">Transactions</th>
                        <th class="text-right">Amount Collected</th>
                        <th style="width:120px;">Share</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byFeeType as $i => $ft)
                        @php $ftPct = $summary['total'] > 0 ? round(($ft->total/$summary['total'])*100) : 0; @endphp
                        <tr>
                            <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $ft->fee_type_name }}</td>
                            <td class="text-center">{{ $ft->count }}</td>
                            <td class="text-right" style="font-weight:700;color:#1a56a0;">
                                Rs. {{ number_format($ft->total, 2) }}
                            </td>
                            <td>
                                <div style="font-size:10px;color:#6b7280;margin-bottom:2px;">{{ $ftPct }}%</div>
                                <div class="prog">
                                    <div class="prog-fill" style="width:{{ $ftPct }}%;"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#9ca3af;padding:12px;">No data.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($byFeeType->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align:right;">TOTAL</td>
                            <td class="text-center">{{ $byFeeType->sum('count') }}</td>
                            <td class="text-right" style="color:#1a56a0;">Rs.
                                {{ number_format($byFeeType->sum('total'), 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>

            {{-- All Transactions --}}
            <div class="sec-title">📋 Transaction Detail</div>
            <table class="txn-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Adm. No.</th>
                        <th>Fee Type</th>
                        <th>Method</th>
                        <th>Txn ID</th>
                        <th class="text-right">Paid</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTransactions as $i => $row)
                        <tr>
                            <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $row->student_name }} {{ $row->student_last_name }}</td>
                            <td style="color:#6b7280;">{{ $row->admission_number ?? '—' }}</td>
                            <td>{{ $row->fee_type_name }}</td>
                            <td>
                                @if ($row->payment_method === 'cash')
                                    <span class="mbadge mb-c">💵 Cash</span>
                                @elseif($row->payment_method === 'bank')
                                    <span class="mbadge mb-b">🏦 Bank</span>
                                @elseif($row->payment_method === 'online')
                                    <span class="mbadge mb-o">📱 Online</span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td style="color:#6b7280;font-size:10px;">{{ $row->transaction_id ?? '—' }}</td>
                            <td class="text-right" style="font-weight:700;color:#16a34a;">
                                Rs. {{ number_format($row->paid_amount, 2) }}
                            </td>
                            <td style="white-space:nowrap;">@bsDate($row->payment_date)</td>
                            <td>
                                @if ($row->status === 'paid')
                                    <span class="badge-paid">PAID</span>
                                @else
                                    <span class="badge-partial">PARTIAL</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;color:#9ca3af;padding:16px;">No transactions
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($allTransactions->count() > 0)
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align:right;">GRAND TOTAL ({{ $allTransactions->count() }}
                                transactions)</td>
                            <td class="text-right" style="color:#1a56a0;">
                                Rs. {{ number_format($allTransactions->sum('paid_amount'), 2) }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>

            {{-- Note --}}
            <div
                style="margin-top:20px;border:1px solid #e5e7eb;border-radius:6px;padding:10px 14px;font-size:10px;color:#6b7280;">
                <strong style="color:#374151;">Note:</strong>
                This is a computer-generated report. All amounts are in Nepali Rupees (NPR).
                Dates shown in Bikram Sambat (B.S.).
                For any discrepancy please contact the accounts office.
            </div>

        </div>

        {{-- Footer --}}
        <div class="rpt-footer">
            <div>
                <div>Report # {{ $reportNo }}</div>
                <div style="margin-top:2px;">Brain Fart Institute — Fee Collection Report</div>
                <div style="margin-top:2px;">Generated: @bsDate($generatedDate) by {{ $generatedBy }}</div>
            </div>
            <div class="sig-line">Accountant / Principal Signature</div>
        </div>

    </div>

</body>

</html>
