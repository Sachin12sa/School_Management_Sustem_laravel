@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-cash-coin me-2 text-primary"></i>My Fees</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div style="width:48px;height:48px;border-radius:.6rem;display:flex;align-items:center;justify-content:center;font-size:1.3rem;" class="bg-success bg-opacity-15 text-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Paid</div>
                                <div class="fw-bold fs-5 text-success">Rs. {{ number_format($total_paid, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div style="width:48px;height:48px;border-radius:.6rem;display:flex;align-items:center;justify-content:center;font-size:1.3rem;" class="bg-danger bg-opacity-15 text-danger">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Due</div>
                                <div class="fw-bold fs-5 text-danger">Rs. {{ number_format($total_due, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div style="width:48px;height:48px;border-radius:.6rem;display:flex;align-items:center;justify-content:center;font-size:1.3rem;" class="bg-primary bg-opacity-15 text-primary">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Records</div>
                                <div class="fw-bold fs-5">{{ $getRecord->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fee Table --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-transparent fw-semibold small text-muted text-uppercase">Fee Records</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Due Date</th>
                                    <th>Payment Date</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $row->feeType->name ?? '-' }}</td>
                                    <td>Rs. {{ number_format($row->amount, 2) }}</td>
                                    <td class="text-success">Rs. {{ number_format($row->paid_amount, 2) }}</td>
                                    <td class="{{ $row->amount - $row->paid_amount > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                                        Rs. {{ number_format($row->amount - $row->paid_amount, 2) }}
                                    </td>
                                    <td class="{{ $row->due_date < now()->toDateString() && $row->status != 'paid' ? 'text-danger fw-semibold' : '' }}">
                                        {{ $row->due_date }}
                                    </td>
                                    <td>{{ $row->payment_date ?? '-' }}</td>
                                    <td>
                                        @if($row->payment_method)
                                            <span class="badge bg-secondary">{{ ucfirst($row->payment_method) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{!! $row->status_badge !!}</td>
                                </tr>
                                @empty
                                <tr><td colspan="9" class="text-center text-muted py-4">No fee records assigned yet.</td></tr>
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