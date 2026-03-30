<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Invoice — {{ $student->name }} {{ $student->last_name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #222;
            background: #f5f5f5;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 2px 20px rgba(0, 0, 0, .1);
        }

        /* ── Header ── */
        .inv-header {
            background: linear-gradient(135deg, #1a56a0 0%, #0d3a7a 100%);
            color: #fff;
            padding: 28px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .school-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .school-sub {
            font-size: 11px;
            opacity: .75;
            margin-top: 2px;
        }

        .inv-title {
            text-align: right;
        }

        .inv-title h2 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .inv-title .inv-no {
            font-size: 11px;
            opacity: .8;
            margin-top: 4px;
        }

        /* ── Student Info band ── */
        .inv-student-band {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            padding: 16px 36px;
            display: flex;
            gap: 40px;
        }

        .inv-info-block .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #6c757d;
        }

        .inv-info-block .value {
            font-size: 13px;
            font-weight: 600;
            color: #212529;
            margin-top: 1px;
        }

        /* ── Status banner ── */
        .status-banner {
            text-align: center;
            padding: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .status-paid {
            background: #d1e7dd;
            color: #0a3622;
        }

        .status-partial {
            background: #fff3cd;
            color: #664d03;
        }

        .status-pending {
            background: #f8d7da;
            color: #58151c;
        }

        /* ── Table ── */
        .inv-body {
            padding: 24px 36px;
        }

        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .fee-table thead tr {
            background: #1a56a0;
            color: #fff;
        }

        .fee-table thead th {
            padding: 9px 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .fee-table tbody tr {
            border-bottom: 1px solid #e9ecef;
        }

        .fee-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .fee-table tbody tr.row-paid {
            background: #f0fff4;
        }

        .fee-table tbody tr.row-overdue td {
            color: #842029;
        }

        .fee-table tbody td {
            padding: 9px 12px;
        }

        .fee-table tfoot tr {
            background: #e9ecef;
            font-weight: 700;
        }

        .fee-table tfoot td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ── Summary box ── */
        .inv-summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }

        .summary-table {
            width: 280px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
        }

        .summary-table tr td {
            padding: 8px 14px;
            font-size: 13px;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
        }

        .summary-table .total-row {
            background: #1a56a0;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
        }

        /* ── Footer ── */
        .inv-footer {
            border-top: 2px solid #e9ecef;
            padding: 16px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 11px;
            color: #6c757d;
        }

        .sig-line {
            width: 160px;
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 4px;
            font-size: 11px;
            color: #333;
            text-align: center;
        }

        /* ── Print button ── */
        .print-actions {
            max-width: 800px;
            margin: 0 auto 10px;
            text-align: right;
        }

        .print-actions button {
            padding: 8px 20px;
            background: #1a56a0;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
        }

        .print-actions button:hover {
            background: #0d3a7a;
        }

        @media print {
            body {
                background: #fff;
            }

            .print-actions {
                display: none;
            }

            .invoice-wrapper {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }

            @page {
                margin: 10mm;
            }
        }

        .badge-paid {
            background: #198754;
            color: #fff;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
        }

        .badge-partial {
            background: #ffc107;
            color: #333;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
        }

        .badge-pending {
            background: #dc3545;
            color: #fff;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="print-actions">
        <button onclick="window.print()">🖨️ Print Invoice</button>
        <button onclick="window.close()" style="background:#6c757d;margin-left:6px;">✕ Close</button>
    </div>

    <div class="invoice-wrapper">

        {{-- Header --}}
        <div class="inv-header">
            <div>
                <div class="school-name">Brain Fart Institute</div>
                <div class="school-sub">School Management System</div>
                <div class="school-sub" style="margin-top:6px;">📞 +977-XXXXXXXXX &nbsp;|&nbsp; 📧 info@school.edu.np
                </div>
            </div>
            <div class="inv-title">
                <h2>Fee Invoice</h2>
                <div class="inv-no">
                    <div>{{ $invoiceNo }}</div>
                    <div style="margin-top:3px;">Date: @bsDate($printDate)</div>
                </div>
            </div>
        </div>

        {{-- Status Banner --}}
        @php
            $overallStatus = $totalBalance <= 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'pending');
        @endphp
        <div class="status-banner status-{{ $overallStatus }}">
            @if ($overallStatus === 'paid')
                ✓ All Fees Cleared
            @elseif($overallStatus === 'partial')
                ⚠ Partially Paid — Balance Due: Rs. {{ number_format($totalBalance, 2) }}
            @else
                ✗ Payment Pending — Total Due: Rs. {{ number_format($totalBalance, 2) }}
            @endif
        </div>

        {{-- Student Info Band --}}
        <div class="inv-student-band">
            <div class="inv-info-block">
                <div class="label">Student Name</div>
                <div class="value">{{ $student->name }} {{ $student->last_name }}</div>
            </div>
            @if ($student->admission_number)
                <div class="inv-info-block">
                    <div class="label">Admission No.</div>
                    <div class="value">{{ $student->admission_number }}</div>
                </div>
            @endif
            @if (isset($student->class_name) || (isset($student->class) && $student->class))
                <div class="inv-info-block">
                    <div class="label">Class</div>
                    <div class="value">{{ $student->class_name ?? ($student->class->name ?? '—') }}</div>
                </div>
            @endif
            <div class="inv-info-block">
                <div class="label">Invoice Date</div>
                <div class="value">@bsDate($printDate)</div>
            </div>
        </div>

        {{-- Fee Table --}}
        <div class="inv-body">

            <table class="fee-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fee Description</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Balance</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Method</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $i => $row)
                        @php
                            $balance = $row->amount - $row->paid_amount;
                            $isOverdue = $row->status !== 'paid' && $row->due_date < now()->toDateString();
                        @endphp
                        <tr class="{{ $row->status === 'paid' ? 'row-paid' : ($isOverdue ? 'row-overdue' : '') }}">
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $row->feeType->name ?? '—' }}</strong>
                                @if ($row->remarks)
                                    <br><span style="font-size:10px;color:#6c757d;">{{ $row->remarks }}</span>
                                @endif
                            </td>
                            <td class="text-center">@bsDate($row->due_date)</td>
                            <td class="text-right">Rs. {{ number_format($row->amount, 2) }}</td>
                            <td class="text-right" style="color:#198754;">Rs. {{ number_format($row->paid_amount, 2) }}
                            </td>
                            <td class="text-right"
                                style="{{ $balance > 0 ? 'color:#dc3545;font-weight:700;' : 'color:#198754;' }}">
                                Rs. {{ number_format($balance, 2) }}
                            </td>
                            <td class="text-center">
                                @if ($row->status === 'paid')
                                    <span class="badge-paid">PAID</span>
                                @elseif($row->status === 'partial')
                                    <span class="badge-partial">PARTIAL</span>
                                @else
                                    <span class="badge-pending">PENDING</span>
                                @endif
                            </td>
                            <td class="text-center" style="font-size:11px;">
                                {{ $row->payment_method ? ucfirst($row->payment_method) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding:20px;color:#6c757d;">
                                No fee records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right">TOTALS</td>
                        <td class="text-right">Rs. {{ number_format($totalAmount, 2) }}</td>
                        <td class="text-right" style="color:#198754;">Rs. {{ number_format($totalPaid, 2) }}</td>
                        <td class="text-right" style="{{ $totalBalance > 0 ? 'color:#dc3545;' : 'color:#198754;' }}">
                            Rs. {{ number_format($totalBalance, 2) }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>

            {{-- Summary Box --}}
            <div class="inv-summary">
                <table class="summary-table">
                    <tr>
                        <td>Total Assigned</td>
                        <td class="text-right"><strong>Rs. {{ number_format($totalAmount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Total Paid</td>
                        <td class="text-right" style="color:#198754;"><strong>Rs.
                                {{ number_format($totalPaid, 2) }}</strong></td>
                    </tr>
                    <tr class="total-row">
                        <td>Balance Due</td>
                        <td class="text-right">Rs. {{ number_format($totalBalance, 2) }}</td>
                    </tr>
                </table>
            </div>

            {{-- Notes --}}
            <div
                style="border:1px solid #dee2e6;border-radius:5px;padding:12px 16px;font-size:11px;color:#6c757d;margin-top:8px;">
                <strong style="color:#333;">Note:</strong>
                This is a computer-generated invoice. Please keep this for your records.
                For any discrepancies, contact the school office.
            </div>

        </div>

        {{-- Footer --}}
        <div class="inv-footer">
            <div>
                <div>Generated on: @bsDate($printDate)</div>
                <div style="margin-top:2px;">Brain Fart Institute — Official Fee Invoice</div>
            </div>
            <div>
                <div class="sig-line">Accountant / Authorized Signatory</div>
            </div>
        </div>

    </div>

</body>

</html>
