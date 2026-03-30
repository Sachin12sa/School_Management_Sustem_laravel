@extends('layouts.app')

@section('style')
    <style>
        @media print {

            .app-header,
            .app-sidebar,
            .app-footer,
            .no-print,
            .btn,
            .breadcrumb {
                display: none !important;
            }

            .app-main {
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background: #fff !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }

        .fee-row-overdue {
            background: #fff5f5;
        }

        .fee-row-paid {
            background: #f0fff4;
        }

        .summary-pill {
            border-radius: .5rem;
            padding: .75rem 1rem;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        {{-- Header --}}
        <div class="app-content-header no-print">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                            Fee Ledger
                        </h4>
                        <span class="text-muted small">
                            {{ $student->name }} {{ $student->last_name }}
                            @if ($student->admission_number)
                                &nbsp;·&nbsp; {{ $student->admission_number }}
                            @endif
                        </span>
                    </div>
                    <div class="col-sm-6 text-sm-end d-flex gap-2 justify-content-sm-end flex-wrap">
                        <a href="{{ url('admin/fee/invoice/' . $student->id) }}" class="btn btn-success btn-sm"
                            target="_blank">
                            <i class="bi bi-printer me-1"></i> Print Invoice
                        </a>
                        <a href="{{ url('admin/fee/bulk-collect/' . $student->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-cash-stack me-1"></i> Collect Payment
                        </a>
                        <a href="{{ url('admin/fee/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show no-print">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Student Info Card --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body py-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <img src="{{ $student->getProfile() }}" alt="{{ $student->name }}"
                                    class="rounded-circle border" style="width:52px;height:52px;object-fit:cover;">
                            </div>
                            <div class="col">
                                <div class="fw-bold fs-6">{{ $student->name }} {{ $student->last_name }}</div>
                                <div class="text-muted small">
                                    @if ($student->admission_number)
                                        <i class="bi bi-hash me-1"></i>{{ $student->admission_number }}
                                        &nbsp;&middot;&nbsp;
                                    @endif
                                    @if (isset($student->class_name))
                                        <i class="bi bi-building me-1"></i>{{ $student->class_name }}
                                    @endif
                                </div>
                            </div>
                            {{-- Summary Pills --}}
                            <div class="col-auto d-flex gap-2 flex-wrap">
                                <div class="summary-pill bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                    <div class="text-muted" style="font-size:.68rem;">Total Assigned</div>
                                    <div class="fw-bold text-primary">Rs. {{ number_format($totalAmount, 2) }}</div>
                                </div>
                                <div class="summary-pill bg-success bg-opacity-10 border border-success border-opacity-25">
                                    <div class="text-muted" style="font-size:.68rem;">Total Paid</div>
                                    <div class="fw-bold text-success">Rs. {{ number_format($totalPaid, 2) }}</div>
                                </div>
                                <div
                                    class="summary-pill {{ $totalBalance > 0 ? 'bg-danger bg-opacity-10 border border-danger border-opacity-25' : 'bg-secondary bg-opacity-10 border border-secondary border-opacity-25' }}">
                                    <div class="text-muted" style="font-size:.68rem;">Balance Due</div>
                                    <div class="fw-bold {{ $totalBalance > 0 ? 'text-danger' : 'text-secondary' }} fs-6">
                                        Rs. {{ number_format($totalBalance, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats Row --}}
                <div class="row g-2 mb-3 no-print">
                    @php
                        $overdueCount = $fees
                            ->where('status', '!=', 'paid')
                            ->where('due_date', '<', now()->toDateString())
                            ->count();
                        $pendingCount = $fees->whereIn('status', ['pending', 'partial'])->count();
                        $paidCount = $fees->where('status', 'paid')->count();
                    @endphp
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body p-3 text-center">
                                <div class="fs-3 fw-bold text-warning">{{ $fees->count() }}</div>
                                <div class="text-muted small">Total Fees</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body p-3 text-center">
                                <div class="fs-3 fw-bold text-danger">{{ $pendingCount }}</div>
                                <div class="text-muted small">Unpaid / Partial</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body p-3 text-center">
                                <div class="fs-3 fw-bold text-success">{{ $paidCount }}</div>
                                <div class="text-muted small">Fully Paid</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body p-3 text-center">
                                <div class="fs-3 fw-bold text-danger">{{ $overdueCount }}</div>
                                <div class="text-muted small">Overdue</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Fee Table --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div
                        class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-2">
                        <span class="fw-semibold small text-muted text-uppercase">
                            <i class="bi bi-list-check me-1"></i>Fee Records
                        </span>
                        <a href="{{ url('admin/fee/add') }}?student={{ $student->id }}"
                            class="btn btn-outline-primary btn-sm no-print">
                            <i class="bi bi-plus-circle me-1"></i>Assign New Fee
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fee Type</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Paid</th>
                                        <th class="text-end">Balance</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Paid On</th>
                                        <th>Method</th>
                                        <th class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fees as $i => $row)
                                        @php
                                            $isOverdue =
                                                $row->status !== 'paid' && $row->due_date < now()->toDateString();
                                            $balance = $row->amount - $row->paid_amount;
                                        @endphp
                                        <tr
                                            class="{{ $row->status === 'paid' ? 'fee-row-paid' : ($isOverdue ? 'fee-row-overdue' : '') }}">
                                            <td class="small text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold small">{{ $row->feeType->name ?? '—' }}</div>
                                                @if ($row->remarks)
                                                    <div class="text-muted" style="font-size:.68rem;">
                                                        {{ Str::limit($row->remarks, 40) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-end small">Rs. {{ number_format($row->amount, 2) }}</td>
                                            <td class="text-end small text-success fw-semibold">Rs.
                                                {{ number_format($row->paid_amount, 2) }}</td>
                                            <td
                                                class="text-end small {{ $balance > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                                                Rs. {{ number_format($balance, 2) }}
                                            </td>
                                            <td class="small {{ $isOverdue ? 'text-danger fw-semibold' : '' }}">
                                                @bsDate($row->due_date)
                                                @if ($isOverdue)
                                                    <span class="badge bg-danger ms-1"
                                                        style="font-size:.6rem;">Overdue</span>
                                                @endif
                                            </td>
                                            <td>{!! $row->status_badge !!}</td>
                                            <td class="small text-muted">
                                                {{ $row->payment_date ? \App\Helpers\NepaliCalendar::format($row->payment_date, 'd M Y') : '—' }}
                                            </td>
                                            <td class="small">
                                                @if ($row->payment_method === 'cash')
                                                    <span class="badge bg-success">Cash</span>
                                                @elseif($row->payment_method === 'bank')
                                                    <span class="badge bg-info text-dark">Bank</span>
                                                @elseif($row->payment_method === 'online')
                                                    <span class="badge bg-warning text-dark">Online</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="no-print">
                                                <div class="d-flex gap-1">
                                                    @if ($row->status !== 'paid')
                                                        <a href="{{ url('admin/fee/collect/' . $row->id) }}"
                                                            class="btn btn-sm btn-success" title="Collect">
                                                            <i class="bi bi-cash"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ url('admin/fee/edit/' . $row->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ url('admin/fee/delete/' . $row->id) }}"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this fee record?')"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox d-block mb-2"
                                                    style="font-size:2rem;opacity:.3;"></i>
                                                No fees assigned yet.
                                                <a href="{{ url('admin/fee/add') }}" class="d-block mt-1 small">Assign a
                                                    fee now</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($fees->count() > 0)
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td colspan="2" class="text-end small text-muted">Totals:</td>
                                            <td class="text-end">Rs. {{ number_format($totalAmount, 2) }}</td>
                                            <td class="text-end text-success">Rs. {{ number_format($totalPaid, 2) }}</td>
                                            <td class="text-end {{ $totalBalance > 0 ? 'text-danger' : 'text-success' }}">
                                                Rs. {{ number_format($totalBalance, 2) }}
                                            </td>
                                            <td colspan="5"></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
