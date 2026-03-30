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
                        <a href="{{ url('librarian/library/fine/report') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-bar-chart-line me-1"></i> Fine Report
                        </a>
                        <a href="{{ url('librarian/library/return_policy') }}"
                            class="btn btn-outline-secondary btn-sm ms-1">
                            <i class="bi bi-journal-text me-1"></i> Return Policy
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

                {{-- Summary Cards --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(220,53,69,.12);color:#dc3545;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Unpaid Fines</div>
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
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(255,193,7,.12);color:#b89200;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
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
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(25,135,84,.12);color:#198754;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
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
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(108,117,125,.12);color:#6c757d;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
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

                @php
                    $activeTab = $tab ?? 'unpaid';
                    $hasDmg = isset($hasDamageCols) && $hasDamageCols;
                @endphp

                <div class="card border-0 shadow-sm rounded-3">

                    {{-- Tabs --}}
                    <div class="card-header bg-white border-bottom p-0">
                        <ul class="nav nav-tabs border-0 px-3 pt-2">
                            @php
                                $tabs = [
                                    'unpaid' => [
                                        'label' => 'Unpaid',
                                        'icon' => 'bi-hourglass-split',
                                        'count' => $fineSummary['unpaid_count'],
                                        'badge' => 'bg-danger',
                                    ],
                                    'overdue' => [
                                        'label' => 'Accruing',
                                        'icon' => 'bi-arrow-up-circle',
                                        'count' => $fineSummary['accruing_count'],
                                        'badge' => 'bg-warning text-dark',
                                    ],
                                    'paid' => [
                                        'label' => 'Collected',
                                        'icon' => 'bi-check-circle',
                                        'count' => null,
                                        'badge' => 'bg-success',
                                    ],
                                    'waived' => [
                                        'label' => 'Waived',
                                        'icon' => 'bi-slash-circle',
                                        'count' => null,
                                        'badge' => 'bg-secondary',
                                    ],
                                ];
                            @endphp
                            @foreach ($tabs as $tabKey => $tabInfo)
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab === $tabKey ? 'active fw-semibold' : '' }}"
                                        href="{{ url()->current() }}?tab={{ $tabKey }}">
                                        <i class="bi {{ $tabInfo['icon'] }} me-1"></i>{{ $tabInfo['label'] }}
                                        @if (($tabInfo['count'] ?? 0) > 0)
                                            <span
                                                class="badge {{ $tabInfo['badge'] }} ms-1">{{ $tabInfo['count'] }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Filter --}}
                    <div class="px-3 py-2 border-bottom bg-light">
                        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                            <input type="hidden" name="tab" value="{{ $activeTab }}">
                            <input type="text" name="member_name" class="form-control form-control-sm"
                                style="width:200px;" placeholder="Search member..." value="{{ request('member_name') }}">
                            <select name="member_type" class="form-select form-select-sm" style="width:140px;">
                                <option value="">All Members</option>
                                <option value="2" {{ request('member_type') === '2' ? 'selected' : '' }}>Teachers
                                </option>
                                <option value="3" {{ request('member_type') === '3' ? 'selected' : '' }}>Students
                                </option>
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
                                        @if (in_array($activeTab, ['unpaid', 'overdue']))
                                            <th class="text-center">Days Late</th>
                                            <th>Rate/Day</th>
                                        @endif
                                        @if ($hasDmg && in_array($activeTab, ['unpaid', 'paid', 'waived']))
                                            <th>Condition</th>
                                        @endif
                                        <th>Fine</th>
                                        @if ($hasDmg && in_array($activeTab, ['unpaid', 'paid', 'waived']))
                                            <th>Damage</th>
                                        @endif
                                        <th>Status</th>
                                        @if ($activeTab === 'paid')
                                            <th>Paid On</th>
                                            <th>Method</th>
                                        @endif
                                        @if ($activeTab === 'waived')
                                            <th>Waived By</th>
                                            <th>Reason</th>
                                        @endif
                                        @if (in_array($activeTab, ['unpaid', 'overdue']))
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        @php
                                            // ── Safe dates — substr works on Carbon, datetime, and date strings ──
                                            $dueDateStr = substr((string) $row->due_date, 0, 10);
                                            $retDateStr = $row->return_date
                                                ? substr((string) $row->return_date, 0, 10)
                                                : null;
                                            $paidDateStr = $row->fine_paid_at
                                                ? substr((string) $row->fine_paid_at, 0, 10)
                                                : null;

                                            // days_late computed by DB DATEDIFF in controller select
                                            $daysLate = (int) ($row->days_late ?? 0);

                                            // Display fine
                                            if ($activeTab === 'overdue' && (int) ($row->fine_per_day ?? 0) > 0) {
                                                $displayFine = $daysLate * (int) $row->fine_per_day;
                                            } else {
                                                $displayFine = (float) ($row->fine_amount ?? 0);
                                            }

                                            // Badges — inline match(), NO accessor on stdClass
                                            $fsBadge = match ($row->fine_status ?? 'none') {
                                                'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
                                                'paid' => '<span class="badge bg-success">Paid</span>',
                                                'waived' => '<span class="badge bg-secondary">Waived</span>',
                                                default
                                                    => '<span class="badge bg-light text-muted border">None</span>',
                                            };
                                            $methodClass = match ($row->fine_payment_method ?? '') {
                                                'cash' => 'bg-success',
                                                'bank' => 'bg-info text-dark',
                                                'online' => 'bg-warning text-dark',
                                                default => 'bg-secondary',
                                            };
                                            $condition = $hasDmg ? $row->book_condition ?? 'good' : 'good';
                                            $condBadge = match ($condition) {
                                                'damaged' => '<span class="badge bg-warning text-dark">Damaged</span>',
                                                'torn' => '<span class="badge bg-danger">Torn</span>',
                                                'lost' => '<span class="badge bg-dark">Lost</span>',
                                                default
                                                    => '<span class="badge bg-success bg-opacity-15 text-success">Good</span>',
                                            };
                                            $damageCharge = $hasDmg ? (float) ($row->damage_charge ?? 0) : 0;
                                            $fineColour = match ($activeTab) {
                                                'paid' => 'text-success',
                                                'waived' => 'text-secondary',
                                                default => 'text-danger',
                                            };
                                            // Late return but fine_per_day was never set — still shows unpaid
                                            $zeroRateLate =
                                                $activeTab === 'unpaid' &&
                                                $daysLate > 0 &&
                                                (int) ($row->fine_per_day ?? 0) === 0 &&
                                                $displayFine == 0 &&
                                                $damageCharge == 0;
                                        @endphp
                                        <tr class="{{ $activeTab === 'overdue' ? 'table-warning' : '' }}">
                                            <td class="small text-muted">{{ $getRecord->firstItem() + $i }}</td>

                                            <td>
                                                <div class="fw-semibold small">{{ $row->member_name }}
                                                    {{ $row->member_last_name }}</div>
                                                <span class="badge  bg-opacity-15 text-primary" style="font-size:.65rem;">
                                                    {{ ($row->member_type ?? 0) == 2 ? 'Teacher' : 'Student' }}
                                                </span>
                                                @if ($row->admission_number ?? false)
                                                    <div class="text-muted" style="font-size:.63rem;">
                                                        {{ $row->admission_number }}</div>
                                                @endif
                                            </td>

                                            <td class="small fw-semibold">{{ $row->book_title }}</td>

                                            <td class="small text-danger fw-semibold">
                                                {{ \Carbon\Carbon::parse($dueDateStr)->format('d M Y') }}
                                            </td>

                                            <td class="small">
                                                @if ($retDateStr)
                                                    {{ \Carbon\Carbon::parse($retDateStr)->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">Not returned</span>
                                                @endif
                                            </td>

                                            @if (in_array($activeTab, ['unpaid', 'overdue']))
                                                <td class="text-center">
                                                    @if ($daysLate > 0)
                                                        <span class="badge bg-danger">{{ $daysLate }} days</span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td class="small">
                                                    @if ((int) ($row->fine_per_day ?? 0) > 0)
                                                        Rs. {{ $row->fine_per_day }}/day
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endif

                                            @if ($hasDmg && in_array($activeTab, ['unpaid', 'paid', 'waived']))
                                                <td>{!! $condBadge !!}</td>
                                            @endif

                                            <td>
                                                @if ($zeroRateLate)
                                                    <span class="text-muted small">Rs. 0.00</span>
                                                    <div class="text-warning fw-semibold" style="font-size:.65rem;">
                                                        <i
                                                            class="bi bi-exclamation-triangle me-1"></i>{{ $daysLate }}d
                                                        late, no rate set
                                                    </div>
                                                @else
                                                    <span class="fw-bold {{ $fineColour }}">
                                                        Rs. {{ number_format($displayFine, 2) }}
                                                    </span>
                                                    @if ($activeTab === 'overdue')
                                                        <div class="text-muted" style="font-size:.63rem;">increasing daily
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>

                                            @if ($hasDmg && in_array($activeTab, ['unpaid', 'paid', 'waived']))
                                                <td class="small">
                                                    @if ($damageCharge > 0)
                                                        <span class="fw-semibold text-danger">Rs.
                                                            {{ number_format($damageCharge, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endif

                                            <td>{!! $fsBadge !!}</td>

                                            @if ($activeTab === 'paid')
                                                <td class="small">
                                                    {{ $paidDateStr ? \Carbon\Carbon::parse($paidDateStr)->format('d M Y') : '—' }}
                                                </td>
                                                <td>
                                                    @if ($row->fine_payment_method ?? false)
                                                        <span
                                                            class="badge {{ $methodClass }}">{{ ucfirst($row->fine_payment_method) }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endif

                                            @if ($activeTab === 'waived')
                                                <td class="small">{{ trim($row->collector_name ?? '') ?: '—' }}</td>
                                                <td class="small text-muted" style="max-width:200px;">
                                                    {{ $row->fine_note ?? '—' }}</td>
                                            @endif

                                            @if (in_array($activeTab, ['unpaid', 'overdue']))
                                                <td>
                                                    <a href="{{ url('librarian/library/fine/collect/' . $row->id) }}"
                                                        class="btn btn-sm btn-success mb-1">
                                                        <i class="bi bi-cash me-1"></i>Collect
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="showWaive({{ $row->id }},'{{ addslashes(($row->member_name ?? '') . ' ' . ($row->member_last_name ?? '')) }}',{{ round($displayFine, 2) }})">
                                                        <i class="bi bi-slash-circle me-1"></i>Waive
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center text-muted py-5">
                                                @if ($activeTab === 'paid')
                                                    <i
                                                        class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>No
                                                    collected fines yet.
                                                @elseif($activeTab === 'waived')
                                                    <i class="bi bi-slash-circle fs-3 d-block mb-2"></i>No waived fines.
                                                @elseif($activeTab === 'overdue')
                                                    <i
                                                        class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>No
                                                    overdue books.
                                                @else
                                                    <i
                                                        class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>No
                                                    unpaid fines — all clear!
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

    {{-- Waive modal --}}
    <div id="waiveOverlay"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
        <div
            style="background:#fff;border-radius:.75rem;padding:1.5rem;width:100%;max-width:420px;margin:1rem;box-shadow:0 1rem 3rem rgba(0,0,0,.2);">
            <h6 class="fw-semibold mb-1">Waive Fine</h6>
            <p class="text-muted small mb-3" id="waiveDesc"></p>
            <form method="POST" id="waiveForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Reason <span class="text-danger">*</span></label>
                    <input type="text" name="reason" class="form-control"
                        placeholder="e.g. Medical reason, librarian discretion..." required>
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
            var fmt = 'Rs. ' + parseFloat(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 2
            });
            document.getElementById('waiveDesc').textContent = 'Waive ' + fmt + ' fine for ' + name + '?';
            document.getElementById('waiveForm').action = '/librarian/library/fine/waive/' + id;
            document.getElementById('waiveOverlay').style.display = 'flex';
        }

        function hideWaive() {
            document.getElementById('waiveOverlay').style.display = 'none';
        }
    </script>
@endsection
