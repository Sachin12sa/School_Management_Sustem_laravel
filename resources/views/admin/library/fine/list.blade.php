@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-cash-coin me-2 text-danger"></i>Library Fines
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/library/fine/report') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-bar-chart-line me-1"></i>Fine Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ── SUMMARY CARDS ───────────────────────────────── --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(220,53,69,.12);
                                 color:#dc3545;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Unpaid</div>
                                    <div class="fw-bold fs-5 text-danger">Rs.
                                        {{ number_format($fineSummary['unpaid_total'], 2) }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $fineSummary['unpaid_count'] }}
                                        records</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(255,193,7,.12);
                                 color:#b89200;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-arrow-up-circle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Accruing (Overdue)</div>
                                    <div class="fw-bold fs-5" style="color:#b89200;">Rs.
                                        {{ number_format($fineSummary['accruing_total'], 2) }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $fineSummary['accruing_count'] }}
                                        overdue books</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(25,135,84,.12);
                                 color:#198754;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Collected</div>
                                    <div class="fw-bold fs-5 text-success">Rs.
                                        {{ number_format($fineSummary['paid_total'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(108,117,125,.12);
                                 color:#6c757d;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-slash-circle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Waived</div>
                                    <div class="fw-bold fs-5 text-secondary">Rs.
                                        {{ number_format($fineSummary['waived_total'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── TABS ──────────────────────────────────────────── --}}
                @php $activeTab = request('tab', 'unpaid'); @endphp

                <div class="card border-0 shadow-sm rounded-3">
                    {{-- Tab header --}}
                    <div class="card-header bg-white border-bottom p-0">
                        <ul class="nav nav-tabs border-0 px-3 pt-2">
                            @foreach ([
            'unpaid' => ['label' => 'Unpaid', 'icon' => 'bi-hourglass-split', 'count' => $fineSummary['unpaid_count'], 'badge' => 'bg-danger'],
            'accruing' => ['label' => 'Accruing', 'icon' => 'bi-arrow-up-circle', 'count' => $fineSummary['accruing_count'], 'badge' => 'bg-warning text-dark'],
            'paid' => ['label' => 'Collected', 'icon' => 'bi-check-circle', 'count' => null, 'badge' => 'bg-success'],
            'waived' => ['label' => 'Waived', 'icon' => 'bi-slash-circle', 'count' => null, 'badge' => 'bg-secondary'],
        ] as $tabKey => $tabInfo)
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab === $tabKey ? 'active fw-semibold' : '' }}"
                                        href="{{ url()->current() }}?tab={{ $tabKey }}">
                                        <i class="bi {{ $tabInfo['icon'] }} me-1"></i>
                                        {{ $tabInfo['label'] }}
                                        @if ($tabInfo['count'] > 0)
                                            <span
                                                class="badge {{ $tabInfo['badge'] }} ms-1">{{ $tabInfo['count'] }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Filter bar --}}
                    <div class="px-3 py-2 border-bottom bg-light">
                        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                            <input type="hidden" name="tab" value="{{ $activeTab }}">
                            <input type="text" name="member_name" class="form-control form-control-sm"
                                style="width:200px;" placeholder="Search member..." value="{{ request('member_name') }}">
                            <select name="member_type" class="form-select form-select-sm" style="width:140px;">
                                <option value="">All Members</option>
                                <option value="2" {{ request('member_type') === '2' ? 'selected' : '' }}>Teachers</option>
                                <option value="3" {{ request('member_type') === '3' ? 'selected' : '' }}>Students</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ url()->current() }}?tab={{ $activeTab }}"
                                class="btn btn-sm btn-outline-secondary">Reset</a>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        @if ($activeTab === 'accruing')
                                            <th class="text-center">Days Overdue</th>
                                            <th>Rate</th>
                                        @endif
                                        <th>Fine Amount</th>
                                        <th>Fine Status</th>
                                        @if ($activeTab === 'paid')
                                            <th>Paid On</th>
                                            <th>Method</th>
                                        @endif
                                        @if ($activeTab === 'waived')
                                            <th>Waived By</th>
                                            <th>Reason</th>
                                        @endif
                                        @if (in_array($activeTab, ['unpaid', 'accruing']))
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        @php
                                            $dueDateStr = \App\Models\BookIssue::safeDate($row->due_date);
                                            $returnDateStr = $row->return_date
                                                ? \App\Models\BookIssue::safeDate($row->return_date)
                                                : null;

                                            // Live fine calculation
                                            if ($row->status === 'overdue' && $row->fine_per_day > 0) {
                                                $daysOverdue = \Carbon\Carbon::createFromFormat('Y-m-d', $dueDateStr)
                                                    ->startOfDay()
                                                    ->diffInDays(now()->startOfDay());
                                                $displayFine = $daysOverdue * $row->fine_per_day;
                                            } else {
                                                $daysOverdue = 0;
                                                $displayFine = $row->fine_amount ?? 0;
                                            }
                                        @endphp
                                        <tr>
                                            <td class="small text-muted">{{ $getRecord->firstItem() + $i }}</td>
                                            <td>
                                                <div class="fw-semibold small">
                                                    {{ $row->member_name }} {{ $row->member_last_name }}
                                                </div>
                                                <span class="badge bg-secondary bg-opacity-15 text-secondary"
                                                    style="font-size:.65rem;">
                                                    {{ $row->member_type == 2 ? 'Teacher' : 'Student' }}
                                                </span>
                                            </td>
                                            <td class="small fw-semibold">{{ $row->book_title }}</td>
                                            <td class="small text-danger fw-semibold">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $dueDateStr)->format('d M Y') }}
                                            </td>
                                            <td class="small">
                                                @if ($returnDateStr)
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $returnDateStr)->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">Not returned</span>
                                                @endif
                                            </td>

                                            @if ($activeTab === 'accruing')
                                                <td class="text-center">
                                                    <span class="badge bg-danger">{{ $daysOverdue }} days</span>
                                                </td>
                                                <td class="small">Rs. {{ $row->fine_per_day }}/day</td>
                                            @endif

                                            <td>
                                                <span
                                                    class="fw-bold {{ in_array($activeTab, ['unpaid', 'accruing']) ? 'text-danger' : ($activeTab === 'paid' ? 'text-success' : 'text-secondary') }}">
                                                    Rs. {{ number_format($displayFine, 2) }}
                                                </span>
                                                @if ($activeTab === 'accruing')
                                                    <div class="text-muted" style="font-size:.65rem;">increasing daily
                                                    </div>
                                                @endif
                                            </td>

                                            <td>{!! $row->fine_status_badge !!}</td>

                                            @if ($activeTab === 'paid')
                                                <td class="small">
                                                    {{ $row->fine_paid_at
                                                        ? \Carbon\Carbon::createFromFormat('Y-m-d', \App\Models\BookIssue::safeDate($row->fine_paid_at))->format('d M Y')
                                                        : '—' }}
                                                </td>
                                                <td>
                                                    @if ($row->fine_payment_method)
                                                        <span
                                                            class="badge
                                                    {{ $row->fine_payment_method === 'cash'
                                                        ? 'bg-success'
                                                        : ($row->fine_payment_method === 'bank'
                                                            ? 'bg-info text-dark'
                                                            : 'bg-warning text-dark') }}">
                                                            {{ ucfirst($row->fine_payment_method) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endif

                                            @if ($activeTab === 'waived')
                                                <td class="small">
                                                    {{ $row->fineCollector ? $row->fineCollector->name : '—' }}
                                                </td>
                                                <td class="small text-muted">{{ $row->fine_note ?? '—' }}</td>
                                            @endif

                                            @if (in_array($activeTab, ['unpaid', 'accruing']))
                                                <td>
                                                    <a href="{{ url('admin/library/fine/collect/' . $row->id) }}"
                                                        class="btn btn-sm btn-success mb-1">
                                                        <i class="bi bi-cash me-1"></i>Collect
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="showWaive({{ $row->id }}, '{{ addslashes($row->member_name . ' ' . $row->member_last_name) }}', {{ $displayFine }})">
                                                        <i class="bi bi-slash-circle me-1"></i>Waive
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-5">
                                                @if ($activeTab === 'paid')
                                                    <i
                                                        class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>No
                                                    collected fines yet.
                                                @elseif($activeTab === 'waived')
                                                    <i class="bi bi-slash-circle fs-3 d-block mb-2"></i>No waived fines.
                                                @elseif($activeTab === 'accruing')
                                                    <i
                                                        class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>No
                                                    overdue books with fines.
                                                @else
                                                    <i
                                                        class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>No
                                                    unpaid fines.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($getRecord->hasPages())
                            <div class="px-3 py-2">{{ $getRecord->withQueryString()->links() }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- Waive Modal --}}
    <div id="waiveOverlay"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;
            align-items:center;justify-content:center;">
        <div
            style="background:#fff;border-radius:.75rem;padding:1.5rem;
                width:100%;max-width:420px;margin:1rem;box-shadow:0 1rem 3rem rgba(0,0,0,.2);">
            <h6 class="fw-semibold mb-1">Waive Fine</h6>
            <p class="text-muted small mb-3" id="waiveDesc"></p>
            <form method="POST" id="waiveForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Reason <span class="text-danger">*</span></label>
                    <input type="text" name="reason" class="form-control"
                        placeholder="e.g. Medical reason, admin discretion..." required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-grow-1"
                        onclick="hideWaive()">Cancel</button>
                    <button type="submit" class="btn btn-warning flex-grow-1 fw-semibold">
                        <i class="bi bi-slash-circle me-1"></i>Confirm Waive
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showWaive(id, name, amount) {
            document.getElementById('waiveOverlay').style.display = 'flex';
            document.getElementById('waiveDesc').textContent =
                'Waive Rs. ' + parseFloat(amount).toLocaleString('en-IN', {
                    minimumFractionDigits: 2
                }) +
                ' fine for ' + name + '?';
            document.getElementById('waiveForm').action = '/admin/library/fine/waive/' + id;
        }

        function hideWaive() {
            document.getElementById('waiveOverlay').style.display = 'none';
        }
    </script>
@endsection
