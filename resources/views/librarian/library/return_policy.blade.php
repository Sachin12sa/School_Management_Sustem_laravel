@extends('layouts.app')
@section('style')
    <style>
        .policy-section-title {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: .75rem;
        }

        .rule-row {
            padding: .75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .rule-row:last-child {
            border-bottom: none;
        }

        .cond-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .9rem;
            border-radius: 2rem;
            font-size: .8rem;
            font-weight: 600;
            min-width: 100px;
        }

        .impact-chip {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: .3rem;
            font-size: .72rem;
            font-weight: 600;
            margin: .1rem;
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
                            <i class="bi bi-journal-text me-2 text-primary"></i>Library Return Policy
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('librarian/library/fine/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-cash-coin me-1"></i> Manage Fines
                        </a>
                        <a href="{{ url('librarian/library/issue/list') }}" class="btn btn-primary btn-sm ms-1">
                            <i class="bi bi-journal-arrow-up me-1"></i> Issue / Return
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row g-3">

                    {{-- Left — main policy content --}}
                    <div class="col-lg-8">

                        {{-- Overview --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-4">
                                <div class="d-flex gap-3 align-items-start">
                                    <div
                                        style="width:48px;height:48px;border-radius:.6rem;background:rgba(13,110,253,.1);color:#0d6efd;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                                        <i class="bi bi-info-circle-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Return Policy Overview</h5>
                                        <p class="text-muted mb-0" style="font-size:.9rem;">
                                            All borrowed books must be returned by the due date and in the same condition
                                            as when issued. Late returns attract a daily fine. Damaged or lost books attract
                                            an additional charge. Fine payment is a separate step handled in the
                                            <strong>Library Fines</strong> section.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Book condition rules --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-4">
                                <div class="policy-section-title">
                                    <i class="bi bi-book me-1"></i> Book Condition on Return &amp; System Actions
                                </div>

                                {{-- GOOD --}}
                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <span class="cond-pill"
                                        style="background:rgba(25,135,84,.12);color:#198754;flex-shrink:0;">
                                        <i class="bi bi-check-circle-fill"></i> Good
                                    </span>
                                    <div>
                                        <div class="fw-semibold small mb-1">No damage charge — book returns to shelf</div>
                                        <p class="text-muted small mb-2">
                                            Book returned clean and intact with no missing pages, no writing/highlighting,
                                            and no damage to cover or binding. Only a late fine applies if returned after
                                            the due date.
                                        </p>
                                        <div>
                                            <span class="impact-chip" style="background:rgba(25,135,84,.1);color:#198754;">
                                                <i class="bi bi-check me-1"></i>Available count +1
                                            </span>
                                            <span class="impact-chip" style="background:rgba(25,135,84,.1);color:#198754;">
                                                <i class="bi bi-check me-1"></i>Quantity unchanged
                                            </span>
                                            <span class="impact-chip" style="background:rgba(13,110,253,.1);color:#0d6efd;">
                                                <i class="bi bi-cash me-1"></i>Damage charge: Rs. 0
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- DAMAGED --}}
                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <span class="cond-pill"
                                        style="background:rgba(255,193,7,.15);color:#856404;flex-shrink:0;">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Damaged
                                    </span>
                                    <div>
                                        <div class="fw-semibold small mb-1">Partial damage charge — book returns to shelf
                                        </div>
                                        <p class="text-muted small mb-2">
                                            Book has visible but minor damage: torn page, water stain, scribbling,
                                            broken spine, or torn cover still attached. Book is usable but degraded.
                                            Librarian assesses and enters a damage charge at time of return.
                                        </p>
                                        <div class="text-muted small mb-2">
                                            <strong>Examples:</strong> Single torn page, coffee stain, pen marks inside,
                                            cover partially torn.
                                        </div>
                                        <div>
                                            <span class="impact-chip" style="background:rgba(25,135,84,.1);color:#198754;">
                                                <i class="bi bi-check me-1"></i>Available count +1
                                            </span>
                                            <span class="impact-chip" style="background:rgba(25,135,84,.1);color:#198754;">
                                                <i class="bi bi-check me-1"></i>Quantity unchanged
                                            </span>
                                            <span class="impact-chip" style="background:rgba(255,193,7,.15);color:#856404;">
                                                <i class="bi bi-cash me-1"></i>Damage charge set by librarian
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- TORN --}}
                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <span class="cond-pill"
                                        style="background:rgba(220,53,69,.12);color:#dc3545;flex-shrink:0;">
                                        <i class="bi bi-x-circle-fill"></i> Torn
                                    </span>
                                    <div>
                                        <div class="fw-semibold small mb-1 text-danger">Full replacement charge — book
                                            REMOVED from stock</div>
                                        <p class="text-muted small mb-2">
                                            Book is severely damaged and cannot be reissued: cover completely detached,
                                            multiple missing pages, water damage rendering pages unreadable, or otherwise
                                            beyond repair. Member pays full replacement cost.
                                        </p>
                                        <div class="text-muted small mb-2">
                                            <strong>Examples:</strong> Cover torn off, 3+ pages missing, book soaked beyond
                                            drying, spine destroyed.
                                        </div>
                                        <div>
                                            <span class="impact-chip" style="background:rgba(220,53,69,.12);color:#dc3545;">
                                                <i class="bi bi-x me-1"></i>Available count unchanged
                                            </span>
                                            <span class="impact-chip" style="background:rgba(220,53,69,.12);color:#dc3545;">
                                                <i class="bi bi-x me-1"></i>Quantity −1 (permanently)
                                            </span>
                                            <span class="impact-chip" style="background:rgba(220,53,69,.12);color:#dc3545;">
                                                <i class="bi bi-cash me-1"></i>Full replacement cost charged
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- LOST --}}
                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <span class="cond-pill"
                                        style="background:rgba(33,37,41,.1);color:#212529;flex-shrink:0;">
                                        <i class="bi bi-question-circle-fill"></i> Lost
                                    </span>
                                    <div>
                                        <div class="fw-semibold small mb-1">Full replacement + late fine — book REMOVED from
                                            stock</div>
                                        <p class="text-muted small mb-2">
                                            Member cannot locate the book. They must report the loss immediately.
                                            All late fine accumulated from due date to today is charged, plus full
                                            replacement cost. The book is removed from library inventory permanently.
                                        </p>
                                        <div>
                                            <span class="impact-chip" style="background:rgba(33,37,41,.1);color:#212529;">
                                                <i class="bi bi-x me-1"></i>Available count unchanged
                                            </span>
                                            <span class="impact-chip" style="background:rgba(220,53,69,.12);color:#dc3545;">
                                                <i class="bi bi-x me-1"></i>Quantity −1 (permanently)
                                            </span>
                                            <span class="impact-chip" style="background:rgba(33,37,41,.1);color:#212529;">
                                                <i class="bi bi-cash me-1"></i>Full cost + all late fine
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Late fine rules --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-4">
                                <div class="policy-section-title">
                                    <i class="bi bi-clock me-1"></i> Late Return Fine
                                </div>

                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <i class="bi bi-calendar-x text-danger fs-5 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small mb-1">Daily fine from the day after the due date</div>
                                        <p class="text-muted small mb-2">
                                            A fine is charged for each calendar day the book is overdue, including
                                            weekends and public holidays. The rate is set at the time of issue.
                                        </p>
                                        <div class="p-2 rounded-2 bg-light border small font-monospace">
                                            Fine = (Return Date − Due Date) × Fine per Day
                                        </div>
                                    </div>
                                </div>

                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <i class="bi bi-arrow-return-left text-success fs-5 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small mb-1">Return and fine payment are separate steps
                                        </div>
                                        <p class="text-muted small mb-0">
                                            When a book is returned, the system records the fine as <strong>Unpaid</strong>
                                            in <strong>Library → Fines</strong>. The member does not need to pay at the
                                            counter at the exact moment of return — but payment must be made before issuing
                                            additional books.
                                        </p>
                                    </div>
                                </div>

                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <i class="bi bi-slash-circle text-secondary fs-5 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small mb-1">Fine waiver (exceptional circumstances only)
                                        </div>
                                        <p class="text-muted small mb-0">
                                            librarian or librarian may waive a fine due to medical emergency, bereavement,
                                            or librarianistrative error. A reason must be provided and the waiver is recorded
                                            permanently. Waivers cannot be reversed once saved.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment methods --}}
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-4">
                                <div class="policy-section-title">
                                    <i class="bi bi-cash-coin me-1"></i> Fine Payment
                                </div>
                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <i class="bi bi-cash text-success fs-5 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small mb-2">Accepted payment methods</div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-success px-3 py-2"><i
                                                    class="bi bi-cash me-1"></i>Cash</span>
                                            <span class="badge bg-info text-dark px-3 py-2"><i
                                                    class="bi bi-bank me-1"></i>Bank Transfer</span>
                                            <span class="badge bg-warning text-dark px-3 py-2"><i
                                                    class="bi bi-phone me-1"></i>eSewa / Khalti</span>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">
                                            All payments are recorded with date, method, and the staff member who collected
                                            them.
                                            Members can view their fine history via <strong>Library → My Fines</strong>.
                                        </p>
                                    </div>
                                </div>
                                <div class="rule-row d-flex gap-3 align-items-start">
                                    <i class="bi bi-ban text-danger fs-5 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold small mb-1">Unpaid fines block further borrowing</div>
                                        <p class="text-muted small mb-0">
                                            Members with unpaid fines are restricted from borrowing additional books
                                            until all outstanding amounts are cleared.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right — quick reference + flow --}}
                    <div class="col-lg-4">

                        {{-- Quick reference table --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-3">
                                <div class="policy-section-title">
                                    <i class="bi bi-table me-1"></i> Quick Reference
                                </div>
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Condition</th>
                                            <th>Late Fine</th>
                                            <th>Damage</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="badge bg-success">Good</span></td>
                                            <td class="small">Yes (if late)</td>
                                            <td class="small text-muted">None</td>
                                            <td class="small text-success fw-semibold"><i class="bi bi-check"></i> +1</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-warning text-dark">Damaged</span></td>
                                            <td class="small">Yes</td>
                                            <td class="small">Partial</td>
                                            <td class="small text-success fw-semibold"><i class="bi bi-check"></i> +1</td>
                                        </tr>
                                        <tr class="table-danger">
                                            <td><span class="badge bg-danger">Torn</span></td>
                                            <td class="small">Yes</td>
                                            <td class="small">Full cost</td>
                                            <td class="small text-danger fw-semibold"><i class="bi bi-x"></i> Qty −1</td>
                                        </tr>
                                        <tr class="table-dark">
                                            <td><span class="badge bg-dark">Lost</span></td>
                                            <td class="small">Yes</td>
                                            <td class="small">Full cost</td>
                                            <td class="small text-danger fw-semibold"><i class="bi bi-x"></i> Qty −1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Return flow --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div class="card-body p-3">
                                <div class="policy-section-title">
                                    <i class="bi bi-diagram-3 me-1"></i> Return Process (Step by Step)
                                </div>
                                @foreach ([['1', 'primary', 'bi-arrow-return-left', 'Member returns book', 'Librarian opens the issue record and clicks Return'], ['2', 'warning', 'bi-book', 'Condition is assessed', 'Select: Good / Damaged / Torn / Lost'], ['3', 'danger', 'bi-calculator', 'Charges calculated', 'Late fine + any damage charge totalled automatically'], ['4', 'secondary', 'bi-hourglass-split', 'Fine marked as Unpaid', 'Appears in Library → Fines — book return is done'], ['5', 'success', 'bi-cash-coin', 'librarian collects payment', 'Open the Unpaid fine, select method, confirm'], ['6', 'info', 'bi-check-circle', 'Fine marked as Paid', 'Record updated — member\'s account is clear']] as [$n, $col, $ic, $title, $desc])
                                    <div class="d-flex gap-3 align-items-start mb-3">
                                        <div
                                            style="width:26px;height:26px;border-radius:50%;background:rgba(var(--bs-{{ $col }}-rgb),.15);color:var(--bs-{{ $col }});display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0;">
                                            {{ $n }}
                                        </div>
                                        <div>
                                            <div class="small fw-semibold"><i
                                                    class="bi {{ $ic }} me-1"></i>{{ $title }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ $desc }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="policy-section-title">
                                    <i class="bi bi-lightning me-1"></i> Quick Actions
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ url('librarian/library/issue/list') }}"
                                        class="btn btn-outline-primary d-flex align-items-center gap-2">
                                        <i class="bi bi-journal-arrow-up"></i> Issue / Return Books
                                    </a>
                                    <a href="{{ url('librarian/library/fine/list') }}"
                                        class="btn btn-outline-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-cash-coin"></i> Manage Fines
                                    </a>
                                    <a href="{{ url('librarian/library/fine/list?tab=overdue') }}"
                                        class="btn btn-outline-warning d-flex align-items-center gap-2">
                                        <i class="bi bi-exclamation-triangle"></i> View Overdue Books
                                    </a>
                                    <a href="{{ url('librarian/library/fine/report') }}"
                                        class="btn btn-outline-secondary d-flex align-items-center gap-2">
                                        <i class="bi bi-bar-chart-line"></i> Fine Report
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
