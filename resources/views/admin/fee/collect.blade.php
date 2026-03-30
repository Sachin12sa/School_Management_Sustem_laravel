@extends('layouts.app')

@section('style')
    <style>
        /* ── Page shell ──────────────────────────────── */
        .collect-wrap {
            max-width: 860px;
            margin: 0 auto;
        }

        /* ── Student hero card ───────────────────────── */
        .student-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            border-radius: 14px;
            padding: 20px 24px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .student-hero::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .student-hero::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: 80px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
        }

        .sh-avatar {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, .25);
            object-fit: cover;
            flex-shrink: 0;
        }

        .sh-name {
            font-size: 1.05rem;
            font-weight: 700;
        }

        .sh-meta {
            font-size: .75rem;
            opacity: .65;
            margin-top: 2px;
        }

        .sh-pills {
            margin-left: auto;
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .sh-pill {
            text-align: center;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 10px;
            padding: 8px 14px;
        }

        .sh-pill .sp-label {
            font-size: .65rem;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .sh-pill .sp-value {
            font-size: .95rem;
            font-weight: 700;
            margin-top: 1px;
        }

        .sh-pill.danger .sp-value {
            color: #fca5a5;
        }

        .sh-pill.success .sp-value {
            color: #86efac;
        }

        /* ── Section card ────────────────────────────── */
        .section-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .section-card .sc-header {
            padding: 12px 18px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }

        .section-card .sc-title {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-card .sc-body {
            padding: 18px;
        }

        /* ── Payment method tiles ────────────────────── */
        .method-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .method-tile {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
            position: relative;
        }

        .method-tile:hover {
            border-color: #6b7280;
            background: #f9fafb;
        }

        .method-tile.active {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .method-tile .mt-icon {
            font-size: 1.5rem;
            margin-bottom: 4px;
        }

        .method-tile .mt-label {
            font-size: .75rem;
            font-weight: 600;
            color: #374151;
        }

        .method-tile.active .mt-label {
            color: #15803d;
        }

        .method-tile .mt-check {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 18px;
            height: 18px;
            background: #16a34a;
            border-radius: 50%;
            color: #fff;
            font-size: .6rem;
            display: none;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .method-tile.active .mt-check {
            display: flex;
        }

        /* ── Fee rows ────────────────────────────────── */
        .fee-row {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 10px;
            background: #fff;
            transition: border-color .15s, background .15s;
            position: relative;
        }

        .fee-row.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .fee-row.overdue {
            border-left: 3px solid #dc2626;
        }

        .fee-row.fee-paid {
            border-color: #86efac;
            background: #f0fdf4;
            opacity: .7;
        }

        .fee-row-top {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .fee-check-wrap {
            padding-top: 2px;
            flex-shrink: 0;
        }

        .fee-check-wrap input[type=checkbox] {
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .fee-name {
            font-size: .88rem;
            font-weight: 600;
            color: #111827;
        }

        .fee-meta {
            font-size: .7rem;
            color: #9ca3af;
            margin-top: 2px;
        }

        .fee-badges {
            display: flex;
            gap: 5px;
            margin-top: 4px;
            flex-wrap: wrap;
        }

        .fee-amounts {
            margin-left: auto;
            display: flex;
            gap: 14px;
            flex-shrink: 0;
            align-items: flex-start;
        }

        .fa-block {
            text-align: right;
        }

        .fa-block .fa-lbl {
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #9ca3af;
        }

        .fa-block .fa-val {
            font-size: .85rem;
            font-weight: 600;
        }

        /* Pay input row */
        .fee-pay-row {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #e5e7eb;
            display: none;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .fee-pay-row.show {
            display: flex;
        }

        .pay-input-wrap {
            flex: 1;
            min-width: 140px;
        }

        .pay-input-wrap label {
            font-size: .7rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
            display: block;
        }

        .pay-full-btn {
            font-size: .72rem;
            padding: 3px 10px;
            border-radius: 6px;
            border: 1px solid #3b82f6;
            background: #eff6ff;
            color: #1d4ed8;
            cursor: pointer;
            font-weight: 600;
            white-space: nowrap;
            transition: all .12s;
        }

        .pay-full-btn:hover {
            background: #3b82f6;
            color: #fff;
        }

        /* Discount per row */
        .disc-row-wrap {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .disc-type-sel {
            font-size: .72rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 8px;
            background: #fff;
            cursor: pointer;
            color: #374151;
        }

        .disc-val-inp {
            width: 90px;
            font-size: .78rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 8px;
        }

        .disc-preview {
            font-size: .7rem;
            padding: 3px 8px;
            border-radius: 6px;
            background: #fef9c3;
            color: #92400e;
            font-weight: 600;
            display: none;
        }

        /* ── Summary bar ─────────────────────────────── */
        .summary-bar {
            background: linear-gradient(135deg, #1a56a0 0%, #0d47a1 100%);
            border-radius: 12px;
            padding: 16px 20px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .sb-item {
            flex: 1;
            min-width: 100px;
        }

        .sb-label {
            font-size: .65rem;
            opacity: .75;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .sb-value {
            font-size: 1.15rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .sb-divider {
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, .2);
        }

        .sb-confirm {
            padding: 10px 24px;
            background: #16a34a;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 700;
            font-size: .9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background .15s;
            white-space: nowrap;
        }

        .sb-confirm:hover {
            background: #15803d;
        }

        .sb-confirm:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* ── Badge helpers ───────────────────────────── */
        .badge-overdue {
            background: #fee2e2;
            color: #dc2626;
            font-size: .62rem;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 700;
        }

        .badge-partial {
            background: #fef9c3;
            color: #92400e;
            font-size: .62rem;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 700;
        }

        .badge-pending {
            background: #fee2e2;
            color: #dc2626;
            font-size: .62rem;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 700;
        }

        .badge-paid {
            background: #dcfce7;
            color: #15803d;
            font-size: .62rem;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 700;
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
                            <i class="bi bi-cash-stack me-2 text-success"></i>Collect Payment
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url($prefix . '/fee/student/' . $student->id) }}"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Ledger
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="collect-wrap">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Student Hero --}}
                    <div class="student-hero">
                        <img src="{{ $student->getProfile() }}" alt="{{ $student->name }}" class="sh-avatar">
                        <div>
                            <div class="sh-name">{{ $student->name }} {{ $student->last_name }}</div>
                            <div class="sh-meta">
                                @if ($student->admission_number)
                                    {{ $student->admission_number }} &nbsp;·&nbsp;
                                @endif
                                @if ($studentClass)
                                    {{ $studentClass }}
                                @endif
                            </div>
                        </div>
                        <div class="sh-pills">
                            <div class="sh-pill danger">
                                <div class="sp-label">Total Due</div>
                                <div class="sp-value">Rs.
                                    {{ number_format($fees->sum(fn($f) => $f->amount - $f->paid_amount), 2) }}</div>
                            </div>
                            <div class="sh-pill success">
                                <div class="sp-label">Total Paid</div>
                                <div class="sp-value">Rs. {{ number_format($fees->sum('paid_amount'), 2) }}</div>
                            </div>
                        </div>
                    </div>

                    @if ($fees->isEmpty())
                        <div class="section-card">
                            <div class="sc-body text-center py-5 text-muted">
                                <i class="bi bi-check-circle-fill text-success d-block mb-2" style="font-size:2.5rem;"></i>
                                <div class="fw-semibold">All fees cleared!</div>
                                <div class="small mt-1">No pending or partial fees for this student.</div>
                                <a href="{{ url($prefix . '/fee/student/' . $student->id) }}"
                                    class="btn btn-outline-primary btn-sm mt-3">View Ledger</a>
                            </div>
                        </div>
                    @else
                        <form action="{{ url($prefix . '/fee/bulk-collect/' . $student->id) }}" method="POST"
                            id="collectForm">
                            @csrf

                            {{-- ── STEP 1: Payment Details ──────────────────── --}}
                            <div class="section-card">
                                <div class="sc-header">
                                    <span class="sc-title">
                                        <span
                                            style="background:#3b82f6;color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;">1</span>
                                        Payment Details
                                    </span>
                                </div>
                                <div class="sc-body">
                                    <div class="row g-3">

                                        {{-- Method --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small mb-2">
                                                Payment Method <span class="text-danger">*</span>
                                            </label>
                                            <div class="method-grid">
                                                @foreach (['cash' => ['💵', 'Cash'], 'bank' => ['🏦', 'Bank Transfer'], 'online' => ['📱', 'eSewa / Khalti']] as $val => [$icon, $lbl])
                                                    <div class="method-tile {{ old('payment_method') === $val ? 'active' : '' }}"
                                                        id="mt-{{ $val }}"
                                                        onclick="pickMethod('{{ $val }}')">
                                                        <div class="mt-check">✓</div>
                                                        <div class="mt-icon">{{ $icon }}</div>
                                                        <div class="mt-label">{{ $lbl }}</div>
                                                        <input type="radio" name="payment_method"
                                                            value="{{ $val }}" class="d-none method-radio"
                                                            id="mr-{{ $val }}"
                                                            {{ old('payment_method') === $val ? 'checked' : '' }}>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Date & Txn --}}
                                        <div class="col-md-5">
                                            <x-bs-date-input name="payment_date" label="Payment Date" :value="old('payment_date', now()->toDateString())"
                                                :required="true" />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small">Transaction ID / Ref</label>
                                            <input type="text" name="transaction_id" class="form-control"
                                                placeholder="Bank ref, eSewa ref…" value="{{ old('transaction_id') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold small">Remarks</label>
                                            <input type="text" name="remarks" class="form-control"
                                                placeholder="Optional note" value="{{ old('remarks') }}">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ── STEP 2: Fees to Pay ──────────────────────── --}}
                            <div class="section-card">
                                <div class="sc-header">
                                    <span class="sc-title">
                                        <span
                                            style="background:#3b82f6;color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;">2</span>
                                        Select Fees &amp; Amounts
                                    </span>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="selectAll()">Select All</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="payAllFull()">Pay All Full</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            onclick="clearAll()">Clear</button>
                                    </div>
                                </div>
                                <div class="sc-body" style="padding-bottom: 4px;">

                                    @foreach ($fees as $i => $fee)
                                        @php
                                            $bal = $fee->amount - $fee->paid_amount;
                                            $isOverdue = $fee->due_date < now()->toDateString();
                                            $isPaid = $fee->status === 'paid';
                                        @endphp

                                        <div class="fee-row {{ $isPaid ? 'fee-paid' : ($isOverdue ? 'overdue' : '') }}"
                                            id="frow-{{ $i }}">

                                            {{-- ═══ HIDDEN SUBMIT FIELDS — always present, never disabled ═══ --}}
                                            {{-- These are what actually gets submitted. JS writes values into them. --}}
                                            <input type="hidden" name="payments[{{ $i }}][fee_id]"
                                                value="{{ $fee->id }}">
                                            <input type="hidden" name="payments[{{ $i }}][paid_amount]"
                                                id="h-amt-{{ $i }}" value="{{ $isPaid ? 0 : $bal }}">
                                            <input type="hidden" name="payments[{{ $i }}][discount_type]"
                                                id="h-dtype-{{ $i }}" value="none">
                                            <input type="hidden" name="payments[{{ $i }}][discount_value]"
                                                id="h-dval-{{ $i }}" value="0">

                                            <div class="fee-row-top">
                                                {{-- Checkbox --}}
                                                <div class="fee-check-wrap">
                                                    <input type="checkbox" class="fee-chk" id="fchk-{{ $i }}"
                                                        data-idx="{{ $i }}"
                                                        data-balance="{{ $bal }}"
                                                        data-fee-id="{{ $fee->id }}"
                                                        {{ $isPaid ? 'disabled' : 'checked' }}
                                                        onchange="onCheck({{ $i }}, this.checked)">
                                                </div>

                                                {{-- Fee info --}}
                                                <div style="flex:1;min-width:0;">
                                                    <div class="fee-name">{{ $fee->feeType->name }}</div>
                                                    <div class="fee-meta">Due: @bsDate($fee->due_date)</div>
                                                    <div class="fee-badges">
                                                        @if ($isPaid)
                                                            <span class="badge-paid">PAID</span>
                                                        @else
                                                            @if ($isOverdue)
                                                                <span class="badge-overdue">OVERDUE</span>
                                                            @endif
                                                            @if ($fee->status === 'partial')
                                                                <span class="badge-partial">PARTIAL</span>
                                                            @endif
                                                            @if ($fee->status === 'pending')
                                                                <span class="badge-pending">PENDING</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Amounts --}}
                                                <div class="fee-amounts">
                                                    <div class="fa-block">
                                                        <div class="fa-lbl">Fee</div>
                                                        <div class="fa-val">Rs. {{ number_format($fee->amount, 2) }}</div>
                                                    </div>
                                                    <div class="fa-block">
                                                        <div class="fa-lbl">Paid</div>
                                                        <div class="fa-val text-success">Rs.
                                                            {{ number_format($fee->paid_amount, 2) }}</div>
                                                    </div>
                                                    <div class="fa-block">
                                                        <div class="fa-lbl">Balance</div>
                                                        <div
                                                            class="fa-val {{ $isPaid ? 'text-success' : 'text-danger' }}">
                                                            Rs. {{ number_format($bal, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Pay + Discount row (shown when checked) --}}
                                            @if (!$isPaid)
                                                <div class="fee-pay-row show" id="fpay-{{ $i }}">

                                                    {{-- Amount input --}}
                                                    <div class="pay-input-wrap">
                                                        <label>Paying Now (Rs.)</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light">Rs.</span>
                                                            <input type="number" id="pamt-{{ $i }}"
                                                                class="form-control text-end fw-semibold pay-amt"
                                                                data-idx="{{ $i }}"
                                                                data-balance="{{ $bal }}" step="0.01"
                                                                min="0" max="{{ $bal }}"
                                                                value="{{ $bal }}"
                                                                oninput="onAmtChange({{ $i }})">
                                                        </div>
                                                    </div>

                                                    {{-- Pay full quick --}}
                                                    <div style="padding-top:18px;">
                                                        <button type="button" class="pay-full-btn"
                                                            onclick="setFull({{ $i }}, {{ $bal }})">
                                                            Pay Full
                                                        </button>
                                                    </div>

                                                    {{-- Discount --}}
                                                    <div class="pay-input-wrap">
                                                        <label>Discount</label>
                                                        <div class="disc-row-wrap">
                                                            <select class="disc-type-sel disc-type"
                                                                id="dtype-{{ $i }}"
                                                                data-idx="{{ $i }}"
                                                                onchange="onDiscChange({{ $i }})">
                                                                <option value="none">None</option>
                                                                <option value="percent">%</option>
                                                                <option value="flat">Rs.</option>
                                                            </select>
                                                            <input type="number" class="disc-val-inp disc-val"
                                                                id="dval-{{ $i }}"
                                                                data-idx="{{ $i }}" step="0.01"
                                                                min="0" value="0" style="display:none;"
                                                                oninput="onDiscChange({{ $i }})">
                                                            <span class="disc-preview"
                                                                id="dpreview-{{ $i }}"></span>
                                                        </div>
                                                    </div>

                                                    {{-- Net for this row --}}
                                                    <div class="pay-input-wrap">
                                                        <label>Net Paying</label>
                                                        <div style="font-size:1rem;font-weight:700;color:#1d4ed8;padding:5px 0;"
                                                            id="pnet-{{ $i }}">
                                                            Rs. {{ number_format($bal, 2) }}
                                                        </div>
                                                    </div>

                                                </div>
                                            @endif

                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            {{-- ── Summary bar + Submit ─────────────────────── --}}
                            <div class="summary-bar">
                                <div class="sb-item">
                                    <div class="sb-label">Fees Selected</div>
                                    <div class="sb-value" id="sb-count">0</div>
                                </div>
                                <div class="sb-divider d-none d-md-block"></div>
                                <div class="sb-item">
                                    <div class="sb-label">Gross Amount</div>
                                    <div class="sb-value" id="sb-gross">Rs. 0.00</div>
                                </div>
                                <div class="sb-divider d-none d-md-block"></div>
                                <div class="sb-item">
                                    <div class="sb-label">Total Discount</div>
                                    <div class="sb-value" id="sb-disc" style="color:#fde68a;">Rs. 0.00</div>
                                </div>
                                <div class="sb-divider d-none d-md-block"></div>
                                <div class="sb-item">
                                    <div class="sb-label">Net Payable</div>
                                    <div class="sb-value" id="sb-net" style="color:#86efac;font-size:1.35rem;">Rs.
                                        0.00</div>
                                </div>
                                <button type="submit" class="sb-confirm ms-auto" id="sb-btn" disabled>
                                    <i class="bi bi-check-circle"></i>
                                    Confirm &amp; Receipt
                                </button>
                            </div>

                        </form>
                    @endif

                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // ── State ─────────────────────────────────────────────────────────────────────
        var balances = {}; // idx → original balance (unpaid fees only)

        @foreach ($fees as $i => $fee)
            @if ($fee->status !== 'paid')
                balances[{{ $i }}] = {{ $fee->amount - $fee->paid_amount }};
            @endif
        @endforeach

        // ── Sync visible → hidden helpers ─────────────────────────────────────────────
        // All form submission goes through the hidden inputs (h-amt, h-dtype, h-dval).
        // Visible inputs are UI only — no name attribute, never submitted directly.
        // This means disabled/unchecked state NEVER causes a missing POST key.

        function syncHidden(idx) {
            var visAmt = parseFloat(document.getElementById('pamt-' + idx)?.value) || 0;
            var visType = document.getElementById('dtype-' + idx)?.value || 'none';
            var visDisc = parseFloat(document.getElementById('dval-' + idx)?.value) || 0;

            var chk = document.getElementById('fchk-' + idx);
            var active = chk && chk.checked && !chk.disabled;

            // If row is unchecked → submit 0 so controller skips it
            var submitAmt = active ? visAmt : 0;
            var submitType = active ? visType : 'none';
            var submitDisc = active ? visDisc : 0;

            var hAmt = document.getElementById('h-amt-' + idx);
            var hType = document.getElementById('h-dtype-' + idx);
            var hDval = document.getElementById('h-dval-' + idx);

            if (hAmt) hAmt.value = submitAmt;
            if (hType) hType.value = submitType;
            if (hDval) hDval.value = submitDisc;
        }

        // ── Method selection ──────────────────────────────────────────────────────────
        function pickMethod(val) {
            document.querySelectorAll('.method-tile').forEach(function(t) {
                t.classList.remove('active');
            });
            var tile = document.getElementById('mt-' + val);
            if (tile) tile.classList.add('active');
            var radio = document.getElementById('mr-' + val);
            if (radio) radio.checked = true;
            updateSummary();
        }

        // ── Checkbox toggle ───────────────────────────────────────────────────────────
        function onCheck(idx, checked) {
            var row = document.getElementById('frow-' + idx);
            var pay = document.getElementById('fpay-' + idx);
            var amt = document.getElementById('pamt-' + idx);

            if (checked) {
                row.classList.add('selected');
                if (pay) pay.classList.add('show');
                if (amt) amt.value = balances[idx] || 0;
            } else {
                row.classList.remove('selected');
                if (pay) pay.classList.remove('show');
                if (amt) amt.value = 0;
            }
            syncHidden(idx);
            recalcNet(idx);
            updateSummary();
        }

        // ── Amount change ─────────────────────────────────────────────────────────────
        function onAmtChange(idx) {
            syncHidden(idx);
            recalcNet(idx);
            updateSummary();
        }

        // ── Discount change ───────────────────────────────────────────────────────────
        function onDiscChange(idx) {
            var dtype = document.getElementById('dtype-' + idx);
            var dval = document.getElementById('dval-' + idx);

            if (dtype.value !== 'none') {
                dval.style.display = '';
            } else {
                dval.style.display = 'none';
                dval.value = 0;
            }
            syncHidden(idx);
            recalcNet(idx);
            updateSummary();
        }

        // ── Recalculate net display for one row ───────────────────────────────────────
        function recalcNet(idx) {
            var amt = parseFloat(document.getElementById('pamt-' + idx)?.value) || 0;
            var dtype = document.getElementById('dtype-' + idx)?.value || 'none';
            var dval = parseFloat(document.getElementById('dval-' + idx)?.value) || 0;
            var preview = document.getElementById('dpreview-' + idx);
            var net = document.getElementById('pnet-' + idx);

            var disc = 0;
            if (dtype === 'percent' && dval > 0) disc = Math.min((dval / 100) * amt, amt);
            if (dtype === 'flat' && dval > 0) disc = Math.min(dval, amt);

            var netAmt = Math.max(0, amt - disc);
            if (net) net.textContent = 'Rs. ' + fmt(netAmt);

            if (preview) {
                if (disc > 0) {
                    preview.style.display = 'inline';
                    preview.textContent = '−Rs. ' + fmt(disc);
                } else {
                    preview.style.display = 'none';
                }
            }
        }

        // ── Summary bar totals ────────────────────────────────────────────────────────
        function updateSummary() {
            var count = 0,
                gross = 0,
                totalDisc = 0,
                net = 0;

            document.querySelectorAll('.fee-chk').forEach(function(chk) {
                if (!chk.checked || chk.disabled) return;
                var idx = chk.dataset.idx;
                var amt = parseFloat(document.getElementById('pamt-' + idx)?.value) || 0;
                var dtype = document.getElementById('dtype-' + idx)?.value || 'none';
                var dval = parseFloat(document.getElementById('dval-' + idx)?.value) || 0;

                var disc = 0;
                if (dtype === 'percent' && dval > 0) disc = Math.min((dval / 100) * amt, amt);
                if (dtype === 'flat' && dval > 0) disc = Math.min(dval, amt);

                count++;
                gross += amt;
                totalDisc += disc;
                net += Math.max(0, amt - disc);
            });

            document.getElementById('sb-count').textContent = count;
            document.getElementById('sb-gross').textContent = 'Rs. ' + fmt(gross);
            document.getElementById('sb-disc').textContent = 'Rs. ' + fmt(totalDisc);
            document.getElementById('sb-net').textContent = 'Rs. ' + fmt(net);

            var hasMethod = document.querySelector('.method-radio:checked');
            document.getElementById('sb-btn').disabled = (count === 0 || !hasMethod);
        }

        // ── Helpers ───────────────────────────────────────────────────────────────────
        function fmt(n) {
            return n.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function setFull(idx, bal) {
            var inp = document.getElementById('pamt-' + idx);
            if (inp) {
                inp.value = bal;
                onAmtChange(idx);
            }
        }

        function selectAll() {
            document.querySelectorAll('.fee-chk:not(:disabled)').forEach(function(chk) {
                if (!chk.checked) {
                    chk.checked = true;
                    onCheck(chk.dataset.idx, true);
                }
            });
        }

        function clearAll() {
            document.querySelectorAll('.fee-chk:not(:disabled)').forEach(function(chk) {
                if (chk.checked) {
                    chk.checked = false;
                    onCheck(chk.dataset.idx, false);
                }
            });
        }

        function payAllFull() {
            selectAll();
            document.querySelectorAll('.fee-chk:not(:disabled)').forEach(function(chk) {
                setFull(chk.dataset.idx, balances[chk.dataset.idx] || 0);
            });
        }

        // ── Init ──────────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            // Init: mark selected rows, sync all hidden inputs, update summary
            document.querySelectorAll('.fee-chk').forEach(function(chk) {
                var idx = chk.dataset.idx;
                if (chk.checked && !chk.disabled) {
                    document.getElementById('frow-' + idx)?.classList.add('selected');
                }
                // Sync hidden inputs for every row on load
                syncHidden(idx);
            });
            updateSummary();

            // Restore method if old()
            @if (old('payment_method'))
                pickMethod('{{ old('payment_method') }}');
            @endif

            // If arriving from single fee collect button (?focus=ID),
            // deselect all then select only the focused fee
            var focusId = new URLSearchParams(window.location.search).get('focus');
            if (focusId) {
                clearAll();
                document.querySelectorAll('.fee-chk').forEach(function(chk) {
                    if (chk.dataset.feeId == focusId && !chk.disabled) {
                        chk.checked = true;
                        onCheck(chk.dataset.idx, true);
                        // Scroll to it
                        var row = document.getElementById('frow-' + chk.dataset.idx);
                        if (row) setTimeout(function() {
                            row.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }, 300);
                    }
                });
                updateSummary();
            }
        });
    </script>
@endsection
