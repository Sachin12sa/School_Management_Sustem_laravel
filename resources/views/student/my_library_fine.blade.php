@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-cash-coin me-2 text-danger"></i>My Library Fines
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- Summary --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-danger">Rs. {{ number_format($totalUnpaid, 2) }}</div>
                            <div class="text-muted small">Unpaid Fines</div>
                            @if ($totalUnpaid > 0)
                                <div class="text-danger mt-1" style="font-size:.72rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>Please pay at the library office
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-warning">Rs. {{ number_format($totalAccruing, 2) }}</div>
                            <div class="text-muted small">Accruing (Overdue Books)</div>
                            @if ($totalAccruing > 0)
                                <div class="text-warning mt-1" style="font-size:.72rem;">
                                    <i class="bi bi-arrow-up me-1"></i>Increasing daily — return books soon
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-success">
                                Rs. {{ number_format($getRecord->where('fine_status', 'paid')->sum('fine_amount'), 2) }}
                            </div>
                            <div class="text-muted small">Total Paid</div>
                        </div>
                    </div>
                </div>

                @if ($totalUnpaid > 0 || $totalAccruing > 0)
                    <div class="alert alert-warning d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="fw-semibold">You have outstanding library fines</div>
                            <div class="small mt-1">
                                Please visit the library office to pay your fines.
                                Unpaid fines may affect your ability to borrow new books.
                                You can pay via <strong>Cash</strong>, <strong>Bank Transfer</strong>, or
                                <strong>Online</strong>.
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Fine Records --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-bottom fw-semibold small text-muted text-uppercase">
                        Fine History
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Days Late</th>
                                        <th>Fine Amount</th>
                                        <th>Status</th>
                                        <th>Paid On</th>
                                        <th>Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $issue)
                                        @php
                                            $liveFine =
                                                $issue->status === 'overdue' && $issue->fine_per_day > 0
                                                    ? \Carbon\Carbon::parse($issue->due_date)->diffInDays(now()) *
                                                        $issue->fine_per_day
                                                    : $issue->fine_amount;

                                            $daysLate = $issue->return_date
                                                ? max(
                                                    0,
                                                    \Carbon\Carbon::parse($issue->due_date)->diffInDays(
                                                        \Carbon\Carbon::parse($issue->return_date),
                                                        false,
                                                    ) * -1,
                                                )
                                                : ($issue->status === 'overdue'
                                                    ? \Carbon\Carbon::parse($issue->due_date)->diffInDays(now())
                                                    : 0);
                                            // Simplified: just check if returned after due
                                            $daysLate = \Carbon\Carbon::parse($issue->due_date)->isPast()
                                                ? ($issue->return_date
                                                    ? \Carbon\Carbon::parse($issue->due_date)->diffInDays(
                                                        \Carbon\Carbon::parse($issue->return_date),
                                                    )
                                                    : \Carbon\Carbon::parse($issue->due_date)->diffInDays(now()))
                                                : 0;
                                        @endphp
                                        <tr
                                            class="{{ ($issue->fine_status ?? 'none') === 'unpaid' ? 'table-warning' : '' }}">
                                            <td class="small text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="small fw-semibold">{{ $issue->book->title }}</div>
                                                <div class="text-muted" style="font-size:.7rem;">{{ $issue->book->author }}
                                                </div>
                                            </td>
                                            <td class="small text-danger fw-semibold">
                                                {{ \Carbon\Carbon::parse($issue->due_date)->format('d M Y') }}
                                            </td>
                                            <td class="small">
                                                @if ($issue->return_date)
                                                    {{ \Carbon\Carbon::parse($issue->return_date)->format('d M Y') }}
                                                @else
                                                    <span class="badge bg-danger">Not Returned</span>
                                                @endif
                                            </td>
                                            <td class="small text-center">
                                                <span class="badge bg-danger bg-opacity-15 text-warning">{{ $daysLate }}
                                                    days</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-bold {{ ($issue->fine_status ?? 'none') === 'unpaid' ? 'text-danger' : '' }}">
                                                    Rs. {{ number_format($liveFine, 2) }}
                                                </span>
                                                @if ($issue->status === 'overdue')
                                                    <div class="text-muted" style="font-size:.65rem;">
                                                        +Rs.{{ $issue->fine_per_day }}/day</div>
                                                @endif
                                            </td>
                                            <td>{!! $issue->fine_status_badge !!}</td>
                                            <td class="small">
                                                {{ $issue->fine_paid_at ? \Carbon\Carbon::parse($issue->fine_paid_at)->format('d M Y') : '—' }}
                                            </td>
                                            <td class="small">
                                                @if ($issue->fine_payment_method)
                                                    <span
                                                        class="badge bg-secondary">{{ ucfirst($issue->fine_payment_method) }}</span>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-5">
                                                <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                                                No fines on your record.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
