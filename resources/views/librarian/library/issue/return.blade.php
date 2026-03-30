@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-arrow-return-left me-2 text-success"></i>Return Book
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('librarian/library/issue/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-7">

                        @php
                            // Safe date strings — works with Carbon, datetime, or date
                            $dueDateStr = substr((string) $getRecord->due_date, 0, 10);
                            $issueDateStr = substr((string) $getRecord->issue_date, 0, 10);
                            $isOverdue = $dueDateStr < now()->toDateString();
                        @endphp

                        {{-- Issue summary --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                Issue Details
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Book</div>
                                        <div class="fw-semibold">{{ $getRecord->book->title }}</div>
                                        <div class="text-muted small">{{ $getRecord->book->author }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Member</div>
                                        <div class="fw-semibold">{{ $getRecord->member->name }}
                                            {{ $getRecord->member->last_name }}</div>
                                        <div class="text-muted small">
                                            {{ $getRecord->member->user_type == 2 ? 'Teacher' : 'Student' }}</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Issue Date</div>
                                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($issueDateStr)->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Due Date</div>
                                        <div class="fw-semibold {{ $isOverdue ? 'text-danger' : 'text-success' }}">
                                            {{ \Carbon\Carbon::parse($dueDateStr)->format('d M Y') }}
                                            @if ($isOverdue)
                                                <span class="badge bg-danger ms-1">Overdue</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Fine / Day</div>
                                        <div class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info notice --}}
                        <div class="alert alert-info d-flex gap-2 align-items-start mb-3">
                            <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                            <div class="small">
                                This form records the physical return only.
                                Any fine generated will appear in <strong>Library → Fines</strong> for separate payment
                                collection.
                                @if ($hasDamageCols)
                                    If the book is torn or lost, it will be <strong>removed from stock permanently</strong>.
                                @endif
                            </div>
                        </div>

                        <form action="{{ url('librarian/library/issue/return/' . $getRecord->id) }}" method="POST">
                            @csrf

                            {{-- Return details --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    Return Details
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small">
                                                Return Date <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="return_date" id="returnDate" class="form-control"
                                                value="{{ now()->toDateString() }}" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small">Note</label>
                                            <input type="text" name="note" class="form-control"
                                                placeholder="Optional note">
                                        </div>
                                    </div>

                                    {{-- Fine preview --}}
                                    <div id="fineBox" class="mt-3 p-3 rounded-3"
                                        style="display:none;background:#fff3cd;border:1px solid #ffecb5;">
                                        <div class="fw-semibold mb-1" style="color:#856404;">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Late return — fine will be generated
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span style="color:#856404;">Days late</span>
                                            <span class="fw-semibold" id="daysLate">0</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mt-1">
                                            <span style="color:#856404;">Fine per day</span>
                                            <span class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                            <span class="fw-semibold" style="color:#856404;">Late fine</span>
                                            <span class="fw-bold fs-6" style="color:#dc3545;" id="lateFineAmt">Rs. 0</span>
                                        </div>
                                        <div class="small mt-1" style="color:#856404;">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Collect fine separately from Library → Fines.
                                        </div>
                                    </div>
                                    <div id="noFineBox" class="alert alert-success mt-3 mb-0">
                                        <i class="bi bi-check-circle me-2"></i>No late fine — returned on time.
                                    </div>
                                </div>
                            </div>

                            {{-- Book condition (only shown if migration has run) --}}
                            @if ($hasDamageCols)
                                <div class="card border-0 shadow-sm rounded-3 mb-3">
                                    <div
                                        class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                        <i class="bi bi-book me-1"></i> Book Condition
                                    </div>
                                    <div class="card-body">

                                        {{-- Condition radio buttons --}}
                                        <label class="form-label fw-semibold small mb-2">
                                            Select condition <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex gap-3 flex-wrap mb-3">
                                            @foreach ([['good', 'Good', 'success', 'bi-check-circle-fill', 'Book is clean and intact.'], ['damaged', 'Damaged', 'warning', 'bi-exclamation-triangle-fill', 'Book has minor damage but is usable.'], ['torn', 'Torn', 'danger', 'bi-x-circle-fill', 'Book is severely damaged — will be removed from stock.'], ['lost', 'Lost', 'dark', 'bi-question-circle-fill', 'Book cannot be found — will be removed from stock.']] as [$val, $label, $color, $icon, $hint])
                                                <div>
                                                    <input type="radio" class="btn-check" name="book_condition"
                                                        id="cond_{{ $val }}" value="{{ $val }}"
                                                        onchange="handleCondition('{{ $val }}')"
                                                        {{ $val === 'good' ? 'checked' : '' }}>
                                                    <label
                                                        class="btn btn-outline-{{ $color }} d-flex flex-column align-items-center px-3 py-2"
                                                        for="cond_{{ $val }}"
                                                        style="min-width:90px;cursor:pointer;">
                                                        <i class="bi {{ $icon }} mb-1"
                                                            style="font-size:1.2rem;"></i>
                                                        <span class="small fw-semibold">{{ $label }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Condition hint --}}
                                        <div id="condHint" class="small text-muted mb-3">
                                            Book is clean and intact.
                                        </div>

                                        {{-- Torn / lost warning --}}
                                        <div id="tornLostAlert" class="alert alert-danger small py-2 mb-3"
                                            style="display:none;">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            <strong>Warning:</strong> This book will be
                                            <strong>permanently removed from library stock</strong>.
                                            The total quantity will be reduced by 1.
                                        </div>

                                        {{-- Damage charge (shown for damaged / torn / lost) --}}
                                        <div id="damageSection" style="display:none;">
                                            <div class="p-3 rounded-3 border"
                                                style="background:rgba(220,53,69,.04);border-color:rgba(220,53,69,.2)!important;">
                                                <div class="fw-semibold small text-danger mb-3">
                                                    <i class="bi bi-cash-coin me-1"></i> Damage / Loss Charge
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-sm-5">
                                                        <label class="form-label fw-semibold small">Charge Amount
                                                            (Rs.)</label>
                                                        <input type="number" name="damage_charge" id="damageCharge"
                                                            class="form-control" min="0" step="0.01"
                                                            value="0" placeholder="0.00" oninput="updateTotal()">
                                                        <div class="form-text">Enter 0 to waive the charge.</div>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <label class="form-label fw-semibold small">Damage Notes</label>
                                                        <textarea name="damage_note" class="form-control" rows="2" placeholder="Describe the damage..."></textarea>
                                                    </div>
                                                </div>

                                                {{-- Total summary --}}
                                                <div class="mt-3 p-2 rounded-2"
                                                    style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.15);">
                                                    <div class="d-flex justify-content-between small text-muted">
                                                        <span>Late fine</span>
                                                        <span id="totalLateFine">Rs. 0.00</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between small text-muted mt-1">
                                                        <span>Damage charge</span>
                                                        <span id="totalDamage">Rs. 0.00</span>
                                                    </div>
                                                    <div
                                                        class="d-flex justify-content-between fw-bold border-top mt-2 pt-2">
                                                        <span class="text-danger">Total Fine</span>
                                                        <span class="text-danger fs-6" id="grandTotal">Rs. 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
                                <i class="bi bi-arrow-return-left me-1"></i> Confirm Return
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        var DUE_DATE = '{{ $dueDateStr }}';
        var FINE_PER_DAY = {{ (int) $getRecord->fine_per_day }};
        var lateFineVal = 0;

        var condHints = {
            good: 'Book is clean and intact.',
            damaged: 'Book has minor damage but is still usable.',
            torn: 'Book is severely damaged and will be removed from stock.',
            lost: 'Book cannot be located and will be removed from stock.',
        };

        function calcFine(dateStr) {
            if (!dateStr) return;
            var dp = DUE_DATE.split('-'),
                rp = dateStr.split('-');
            var due = new Date(+dp[0], dp[1] - 1, +dp[2]);
            var ret = new Date(+rp[0], rp[1] - 1, +rp[2]);
            var days = Math.max(0, Math.round((ret - due) / 86400000));

            lateFineVal = days * FINE_PER_DAY;
            document.getElementById('daysLate').textContent = days;
            document.getElementById('lateFineAmt').textContent = fmt(lateFineVal);
            document.getElementById('fineBox').style.display = days > 0 ? '' : 'none';
            document.getElementById('noFineBox').style.display = days > 0 ? 'none' : '';
            updateTotal();
        }

        function handleCondition(val) {
            var hint = document.getElementById('condHint');
            var dmgSection = document.getElementById('damageSection');
            var tornAlert = document.getElementById('tornLostAlert');

            if (hint) hint.textContent = condHints[val] || '';
            if (dmgSection) dmgSection.style.display = (val !== 'good') ? '' : 'none';
            if (tornAlert) tornAlert.style.display = (val === 'torn' || val === 'lost') ? '' : 'none';

            if (val === 'good' && document.getElementById('damageCharge')) {
                document.getElementById('damageCharge').value = 0;
            }
            updateTotal();
        }

        function updateTotal() {
            var dmgEl = document.getElementById('damageCharge');
            var dmgVal = dmgEl ? (parseFloat(dmgEl.value) || 0) : 0;
            var grand = lateFineVal + dmgVal;
            if (document.getElementById('totalLateFine')) {
                document.getElementById('totalLateFine').textContent = fmt(lateFineVal);
                document.getElementById('totalDamage').textContent = fmt(dmgVal);
                document.getElementById('grandTotal').textContent = fmt(grand);
            }
        }

        function fmt(n) {
            return 'Rs. ' + Number(n).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        document.getElementById('returnDate').addEventListener('change', function() {
            calcFine(this.value);
        });
        calcFine(document.getElementById('returnDate').value);
    </script>
@endsection
