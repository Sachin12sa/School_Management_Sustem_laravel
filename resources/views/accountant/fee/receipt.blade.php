<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt #{{ $receiptNo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* ── Action bar ── */
        .action-bar {
            width: 100%;
            max-width: 720px;
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 9px 20px;
            border-radius: 6px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-print {
            background: #1a56a0;
            color: #fff;
        }

        .btn-print:hover {
            background: #0d3a7a;
        }

        .btn-ledger {
            background: #fff;
            color: #1a56a0;
            border: 2px solid #1a56a0;
        }

        .btn-ledger:hover {
            background: #e8f0fe;
        }

        .btn-grey {
            background: #6c757d;
            color: #fff;
        }

        .btn-grey:hover {
            background: #495057;
        }

        /* ── Receipt shell ── */
        .receipt {
            width: 100%;
            max-width: 720px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .12);
            position: relative;
        }

        /* ── Header ── */
        .receipt-header {
            background: linear-gradient(135deg, #1a56a0 0%, #0d3a7a 100%);
            color: #fff;
            padding: 26px 36px 22px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .school-name {
            font-size: 18px;
            font-weight: 700;
        }

        .school-sub {
            font-size: 11px;
            opacity: .7;
            margin-top: 2px;
        }

        .receipt-label h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .receipt-label .rno {
            font-size: 11px;
            opacity: .75;
            margin-top: 4px;
        }

        /* ── Status stamp ── */
        .stamp-bar {
            padding: 10px 36px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
        }

        .stamp-bar.paid {
            background: #dcfce7;
            color: #15803d;
            border-bottom: 1px solid #bbf7d0;
        }

        .stamp-bar.partial {
            background: #fef9c3;
            color: #92400e;
            border-bottom: 1px solid #fde68a;
        }

        /* ── Student band ── */
        .student-band {
            background: #f8faff;
            border-bottom: 1px solid #e0e7ff;
            padding: 14px 36px;
            display: flex;
            gap: 36px;
            flex-wrap: wrap;
        }

        .info-block .lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            font-weight: 600;
        }

        .info-block .val {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            margin-top: 1px;
        }

        /* ── Body ── */
        .receipt-body {
            padding: 22px 36px;
        }

        /* Amount paid hero box */
        .amount-hero {
            background: linear-gradient(135deg, #16a34a, #15803d);
            border-radius: 10px;
            padding: 16px 22px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .ah-left .lbl {
            font-size: 11px;
            opacity: .8;
        }

        .ah-left .val {
            font-size: 30px;
            font-weight: 800;
            line-height: 1.1;
            margin-top: 2px;
        }

        .ah-right {
            text-align: right;
        }

        .ah-right .sub {
            font-size: 11px;
            opacity: .75;
            margin-top: 4px;
        }

        /* Method badge */
        .method-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .method-cash {
            background: #dcfce7;
            color: #15803d;
        }

        .method-bank {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .method-online {
            background: #fef9c3;
            color: #92400e;
        }

        /* Fee table */
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .fee-table thead tr {
            background: #1a56a0;
            color: #fff;
        }

        .fee-table thead th {
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .fee-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .fee-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .fee-table tbody td {
            padding: 9px 12px;
        }

        .fee-table tfoot tr {
            background: #f1f5f9;
            font-weight: 700;
        }

        .fee-table tfoot td {
            padding: 9px 12px;
            font-size: 13px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Single-fee detail table */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .detail-table tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-table tr:last-child {
            border-bottom: none;
        }

        .detail-table td {
            padding: 8px 4px;
            font-size: 13px;
        }

        .detail-table td:first-child {
            color: #6b7280;
            width: 42%;
        }

        .detail-table td:last-child {
            font-weight: 600;
            color: #111827;
            text-align: right;
        }

        /* Summary box */
        .summary-box {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            float: right;
            width: 260px;
        }

        .summary-box tr td {
            padding: 8px 14px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
        }

        .summary-box tr:last-child td {
            border-bottom: none;
        }

        .summary-box .total-row {
            background: #1a56a0;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
        }

        .clearfix::after {
            content: '';
            display: table;
            clear: both;
        }

        /* Balance remaining */
        .balance-box {
            border: 1.5px solid #fca5a5;
            border-radius: 8px;
            padding: 12px 16px;
            background: #fff5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .balance-box.cleared {
            border-color: #86efac;
            background: #f0fdf4;
        }

        .balance-box .bl-lbl {
            font-size: 12px;
            color: #6b7280;
        }

        .balance-box .bl-val {
            font-size: 17px;
            font-weight: 700;
        }

        .balance-box.cleared .bl-val {
            color: #16a34a;
        }

        .balance-box:not(.cleared) .bl-val {
            color: #dc2626;
        }

        /* Note */
        .receipt-note {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Footer */
        .receipt-footer {
            border-top: 1px solid #e5e7eb;
            padding: 14px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 11px;
            color: #9ca3af;
        }

        .sig-line {
            text-align: center;
            border-top: 1px solid #374151;
            padding-top: 4px;
            width: 150px;
            font-size: 11px;
            color: #374151;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 110px;
            font-weight: 900;
            opacity: .04;
            color: #16a34a;
            pointer-events: none;
            z-index: 0;
            letter-spacing: .1em;
        }

        /* Status badges in table */
        .badge-paid {
            background: #dcfce7;
            color: #15803d;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        .badge-partial {
            background: #fef9c3;
            color: #92400e;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .receipt {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .watermark {
                opacity: .06;
            }

            @page {
                margin: 8mm;
            }
        }
    </style>
</head>

<body>

    @php
        $firstFee = $fees->first();
        $paymentDate = $firstFee->payment_date ?? now()->toDateString();
        $paymentMethod = $firstFee->payment_method ?? '';
        $transactionId = $firstFee->transaction_id ?? null;
        $isFullyCleared = $totalBalance <= 0;

        $methodLabel =
            ['cash' => '💵 Cash', 'bank' => '🏦 Bank Transfer', 'online' => '📱 eSewa / Khalti'][$paymentMethod] ??
            ucfirst($paymentMethod);
        $methodClass =
            ['cash' => 'method-cash', 'bank' => 'method-bank', 'online' => 'method-online'][$paymentMethod] ??
            'method-cash';
    @endphp

    @if ($isFullyCleared)
        <div class="watermark">PAID</div>
    @endif

    {{-- ── Action Bar ── --}}
    <div class="action-bar">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print Receipt</button>
        <a href="{{ url($prefix . '/fee/student/' . $student->id) }}" class="btn btn-ledger">
            📋 View Ledger
        </a>
        <a href="{{ url($prefix . '/fee/invoice/' . $student->id) }}" class="btn btn-ledger" target="_blank">
            📄 Full Invoice
        </a>
        <a href="{{ url($prefix . '/fee/list') }}" class="btn btn-grey">← Back to List</a>
    </div>

    {{-- ── Receipt ── --}}
    <div class="receipt">

        {{-- Header --}}
        <div class="receipt-header">
            <div>
                <div class="school-name">Brain Fart Institute</div>
                <div class="school-sub">School Management System</div>
                <div class="school-sub" style="margin-top:5px;">📞 +977-XXXXXXXXX</div>
            </div>
            <div class="receipt-label" style="text-align:right;">
                <h2>Receipt</h2>
                <div class="rno"># {{ $receiptNo }}</div>
                <div class="rno" style="margin-top:3px;">Date: @bsDate($paymentDate)</div>
            </div>
        </div>

        {{-- Status stamp --}}
        @php
            $overallBalance = $fees->sum(fn($f) => $f->amount - $f->paid_amount);
        @endphp
        @if ($overallBalance <= 0)
            <div class="stamp-bar paid">
                <span>✓</span>
                <span>PAYMENT RECEIVED — ALL SELECTED FEES CLEARED</span>
            </div>
        @else
            <div class="stamp-bar partial">
                <span>⚠</span>
                <span>
                    PARTIAL PAYMENT — OUTSTANDING BALANCE:
                    Rs. {{ number_format($overallBalance, 2) }}
                </span>
            </div>
        @endif

        {{-- Student info --}}
        <div class="student-band">
            <div class="info-block">
                <div class="lbl">Student Name</div>
                <div class="val">{{ $student->name }} {{ $student->last_name }}</div>
            </div>
            @if ($student->admission_number)
                <div class="info-block">
                    <div class="lbl">Admission No.</div>
                    <div class="val">{{ $student->admission_number }}</div>
                </div>
            @endif
            @if ($studentClass)
                <div class="info-block">
                    <div class="lbl">Class</div>
                    <div class="val">{{ $studentClass }}</div>
                </div>
            @endif
            <div class="info-block">
                <div class="lbl">Payment Date</div>
                <div class="val">@bsDate($paymentDate)</div>
            </div>
        </div>

        {{-- Body --}}
        <div class="receipt-body">

            {{-- Amount hero --}}
            <div class="amount-hero">
                <div class="ah-left">
                    <div class="lbl">Total Amount Received</div>
                    <div class="val">Rs. {{ number_format($amountPaidNow, 2) }}</div>
                </div>
                <div class="ah-right">
                    <span class="method-badge {{ $methodClass }}">{{ $methodLabel }}</span>
                    @if ($transactionId)
                        <div class="sub">Ref: {{ $transactionId }}</div>
                    @endif
                </div>
            </div>

            {{-- ════ SINGLE FEE: detailed breakdown ════ --}}
            @if ($isSingleFee)
                @php
                    $fee = $fees->first();
                    $bal = $fee->amount - $fee->paid_amount;
                @endphp

                <table class="detail-table">
                    <tr>
                        <td>Fee Type</td>
                        <td>{{ $fee->feeType->name }}</td>
                    </tr>
                    <tr>
                        <td>Fee Amount</td>
                        <td>Rs. {{ number_format($fee->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Previously Paid</td>
                        <td>Rs. {{ number_format($fee->paid_amount - $amountPaidNow, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Paid This Transaction</td>
                        <td style="color:#16a34a;">Rs. {{ number_format($amountPaidNow, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Paid to Date</td>
                        <td style="color:#16a34a;font-size:14px;">Rs. {{ number_format($fee->paid_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Due Date</td>
                        <td>@bsDate($fee->due_date)</td>
                    </tr>
                    @if ($fee->transaction_id)
                        <tr>
                            <td>Transaction ID</td>
                            <td>{{ $fee->transaction_id }}</td>
                        </tr>
                    @endif
                    @if ($fee->remarks)
                        <tr>
                            <td>Remarks</td>
                            <td style="font-weight:400;">{{ $fee->remarks }}</td>
                        </tr>
                    @endif
                    @if ($collectorName)
                        <tr>
                            <td>Collected By</td>
                            <td>{{ $collectorName }}</td>
                        </tr>
                    @endif
                </table>

                <div class="balance-box {{ $bal <= 0 ? 'cleared' : '' }}">
                    <div>
                        <div class="bl-lbl">Remaining Balance for this Fee</div>
                        <div class="bl-val">Rs. {{ number_format($bal, 2) }}</div>
                    </div>
                    <div style="font-size:12px;text-align:right;">
                        @if ($bal <= 0)
                            <span style="color:#16a34a;font-weight:700;">✓ FULLY PAID</span>
                        @else
                            <span style="color:#dc2626;">Due: @bsDate($fee->due_date)</span>
                        @endif
                    </div>
                </div>

                {{-- ════ MULTIPLE FEES: table of all paid items ════ --}}
            @else
                <table class="fee-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fee Type</th>
                            <th class="text-right">Fee Amount</th>
                            <th class="text-right">Paid Now</th>
                            <th class="text-right">Total Paid</th>
                            <th class="text-right">Balance</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fees as $i => $fee)
                            @php
                                $fBal = $fee->amount - $fee->paid_amount;
                                // per-fee amount paid now = pro-rated share if we can't know exactly;
                                // but since each fee tracks its own paid_amount we show the total paid
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $fee->feeType->name }}</strong>
                                    @if ($fee->remarks)
                                        <br><span style="font-size:10px;color:#6b7280;">{{ $fee->remarks }}</span>
                                    @endif
                                </td>
                                <td class="text-right">Rs. {{ number_format($fee->amount, 2) }}</td>
                                <td class="text-right" style="color:#16a34a;">
                                    Rs. {{ number_format($fee->paid_amount, 2) }}
                                </td>
                                <td class="text-right" style="color:#16a34a;">
                                    Rs. {{ number_format($fee->paid_amount, 2) }}
                                </td>
                                <td class="text-right {{ $fBal > 0 ? '' : '' }}"
                                    style="color:{{ $fBal > 0 ? '#dc2626' : '#16a34a' }};font-weight:600;">
                                    Rs. {{ number_format($fBal, 2) }}
                                </td>
                                <td class="text-center">
                                    @if ($fee->status === 'paid')
                                        <span class="badge-paid">PAID</span>
                                    @else
                                        <span class="badge-partial">PARTIAL</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right">TOTALS</td>
                            <td class="text-right">Rs. {{ number_format($totalFeeAmount, 2) }}</td>
                            <td class="text-right" style="color:#16a34a;">Rs. {{ number_format($amountPaidNow, 2) }}
                            </td>
                            <td class="text-right" style="color:#16a34a;">Rs. {{ number_format($totalPaid, 2) }}</td>
                            <td class="text-right" style="color:{{ $totalBalance > 0 ? '#dc2626' : '#16a34a' }};">
                                Rs. {{ number_format($totalBalance, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Summary --}}
                <div class="clearfix">
                    <table class="summary-box">
                        <tr>
                            <td>Total Fee Assigned</td>
                            <td class="text-right"><strong>Rs. {{ number_format($totalFeeAmount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Paid This Session</td>
                            <td class="text-right" style="color:#16a34a;">
                                <strong>Rs. {{ number_format($amountPaidNow, 2) }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Paid to Date</td>
                            <td class="text-right" style="color:#16a34a;">
                                <strong>Rs. {{ number_format($totalPaid, 2) }}</strong>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <td>Outstanding Balance</td>
                            <td class="text-right">Rs. {{ number_format($totalBalance, 2) }}</td>
                        </tr>
                    </table>
                </div>

                @if ($collectorName)
                    <p style="font-size:12px;color:#6b7280;margin-bottom:12px;">
                        Collected by: <strong>{{ $collectorName }}</strong>
                    </p>
                @endif

            @endif

            {{-- Note --}}
            <div class="receipt-note">
                <strong style="color:#374151;">Note:</strong>
                This is a computer-generated receipt. Please retain for your records.
                For any discrepancy, contact the school accounts office.
            </div>

        </div>

        {{-- Footer --}}
        <div class="receipt-footer">
            <div>
                <div>Receipt # {{ $receiptNo }}</div>
                <div style="margin-top:2px;">Brain Fart Institute — Official Payment Receipt</div>
                <div style="margin-top:2px;">Printed: @bsDate(now()->toDateString())</div>
            </div>
            <div class="sig-line">Authorized Signatory</div>
        </div>

    </div>

</body>

</html>
