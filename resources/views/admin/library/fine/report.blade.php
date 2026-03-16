@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i>Fine Report
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/library/fine/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Fines
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- Date filter --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body py-2 px-3">
                        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="small text-muted fw-semibold">Period:</span>
                            <input type="date" name="date_from" class="form-control form-control-sm" style="width:145px;"
                                value="{{ $dateFrom }}">
                            <span class="text-muted small">to</span>
                            <input type="date" name="date_to" class="form-control form-control-sm" style="width:145px;"
                                value="{{ $dateTo }}">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-funnel me-1"></i>Apply
                            </button>
                            <a href="{{ url('admin/library/fine/report') }}" class="btn btn-sm btn-outline-secondary">
                                Reset
                            </a>
                        </form>
                    </div>
                </div>

                {{-- Period label --}}
                <p class="text-muted small mb-3">
                    <i class="bi bi-calendar3 me-1"></i>
                    Report for: <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong>
                    — <strong>{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong>
                </p>

                {{-- Summary --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-primary">{{ $report['total_records'] }}</div>
                            <div class="text-muted small">Total Fines Generated</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-success">Rs. {{ number_format($report['collected'], 2) }}</div>
                            <div class="text-muted small">Collected</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-danger">Rs. {{ number_format($report['unpaid'], 2) }}</div>
                            <div class="text-muted small">Unpaid</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-secondary">Rs. {{ number_format($report['waived'], 2) }}</div>
                            <div class="text-muted small">Waived</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">

                    {{-- Collection by method --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-cash-stack me-1"></i> By Payment Method
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        @foreach (['cash' => 'Cash', 'bank' => 'Bank Transfer', 'online' => 'Online'] as $key => $label)
                                            <tr>
                                                <td class="ps-3">
                                                    <span
                                                        class="badge {{ $key === 'cash' ? 'bg-success' : ($key === 'bank' ? 'bg-info text-dark' : 'bg-warning text-dark') }} me-2">
                                                        {{ $label }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-3 fw-semibold">
                                                    Rs. {{ number_format($report['by_method'][$key] ?? 0, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td class="ps-3 fw-semibold">Total</td>
                                            <td class="text-end pe-3 fw-bold text-success">
                                                Rs. {{ number_format($report['collected'], 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Top fine payers --}}
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-person-fill me-1"></i> Members with Most Fines
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Member</th>
                                            <th>Type</th>
                                            <th class="text-center">Fines</th>
                                            <th class="text-end">Total Fine</th>
                                            <th class="text-end">Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report['top_members'] as $i => $m)
                                            <tr>
                                                <td class="small text-muted">{{ $i + 1 }}</td>
                                                <td class="small fw-semibold">{{ $m->member_name }}
                                                    {{ $m->member_last_name }}</td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-15 text-secondary"
                                                        style="font-size:.65rem;">
                                                        {{ $m->member_type == 2 ? 'Teacher' : 'Student' }}
                                                    </span>
                                                </td>
                                                <td class="text-center small">{{ $m->fine_count }}</td>
                                                <td class="text-end small fw-semibold text-danger">
                                                    Rs. {{ number_format($m->total_fine, 2) }}
                                                </td>
                                                <td class="text-end small text-success">
                                                    Rs. {{ number_format($m->paid_fine, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3 small">
                                                    No fine records for this period.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Full transactions --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <span class="small fw-semibold text-muted text-uppercase">
                            <i class="bi bi-list-ul me-1"></i>All Fine Transactions
                        </span>
                        <span class="badge bg-secondary">{{ $transactions->total() }} records</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Fine</th>
                                        <th>Status</th>
                                        <th>Method</th>
                                        <th>Paid On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $i => $row)
                                        <tr>
                                            <td class="small text-muted">{{ $transactions->firstItem() + $i }}</td>
                                            <td>
                                                <div class="small fw-semibold">{{ $row->member_name }}
                                                    {{ $row->member_last_name }}</div>
                                                <span class="text-muted" style="font-size:.65rem;">
                                                    {{ $row->member_type == 2 ? 'Teacher' : 'Student' }}
                                                </span>
                                            </td>
                                            <td class="small">{{ $row->book_title }}</td>
                                            <td class="small text-danger">
                                                {{ \Carbon\Carbon::parse($row->due_date)->format('d M Y') }}
                                            </td>
                                            <td class="small">
                                                {{ $row->return_date ? \Carbon\Carbon::parse($row->return_date)->format('d M Y') : '—' }}
                                            </td>
                                            <td class="small fw-bold">Rs. {{ number_format($row->fine_amount, 2) }}</td>
                                            <td>{!! $row->fine_status_badge !!}</td>
                                            <td class="small">
                                                @if ($row->fine_payment_method)
                                                    <span
                                                        class="badge {{ $row->fine_payment_method === 'cash' ? 'bg-success' : ($row->fine_payment_method === 'bank' ? 'bg-info text-dark' : 'bg-warning text-dark') }}">
                                                        {{ ucfirst($row->fine_payment_method) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="small">
                                                {{ $row->fine_paid_at ? \Carbon\Carbon::parse($row->fine_paid_at)->format('d M Y') : '—' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">No transactions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($transactions->hasPages())
                            <div class="px-3 py-2">{{ $transactions->withQueryString()->links() }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
