@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold">
                        <i class="bi bi-cash-coin me-2 text-primary"></i>
                        Fee Details — {{ $getStudent->name }} {{ $getStudent->last_name }}
                    </h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('parent/my_student') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to My Children
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
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
                                    <td>{!! $row->status_badge !!}</td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">No fee records found for this student.</td></tr>
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