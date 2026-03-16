@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-cash-stack me-2 text-success"></i>Collect Payment</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url($prefix.'/fee/list') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7">

                    {{-- Fee Summary Card --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-transparent border-bottom fw-semibold small text-muted text-uppercase">Fee Details</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="text-muted small">Student</div>
                                    <div class="fw-semibold">{{ $getRecord->student->name }} {{ $getRecord->student->last_name }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted small">Fee Type</div>
                                    <div class="fw-semibold">{{ $getRecord->feeType->name }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted small">Total Amount</div>
                                    <div class="fw-semibold text-dark">Rs. {{ number_format($getRecord->amount, 2) }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted small">Already Paid</div>
                                    <div class="fw-semibold text-success">Rs. {{ number_format($getRecord->paid_amount, 2) }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted small">Balance Due</div>
                                    <div class="fw-semibold text-danger fs-5">Rs. {{ number_format($getRecord->amount - $getRecord->paid_amount, 2) }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted small">Due Date</div>
                                    <div class="fw-semibold {{ $getRecord->due_date < now()->toDateString() && $getRecord->status != 'paid' ? 'text-danger' : '' }}">
                                        {{ $getRecord->due_date }}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted small">Current Status</div>
                                    <div>{!! $getRecord->status_badge !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Form --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-transparent border-bottom fw-semibold small text-muted text-uppercase">Record Payment</div>
                        <div class="card-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <form action="{{ url($prefix.'/fee/collect/'.$getRecord->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Amount Paying Now (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" name="paid_amount" class="form-control" step="0.01" min="0.01"
                                           max="{{ $getRecord->amount - $getRecord->paid_amount }}"
                                           value="{{ old('paid_amount', $getRecord->amount - $getRecord->paid_amount) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        <option value="cash" {{ old('payment_method')=='cash'?'selected':'' }}>Cash</option>
                                        <option value="bank" {{ old('payment_method')=='bank'?'selected':'' }}>Bank Transfer</option>
                                        <option value="online" {{ old('payment_method')=='online'?'selected':'' }}>Online / eSewa / Khalti</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->toDateString()) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Transaction ID / Receipt No.</label>
                                    <input type="text" name="transaction_id" class="form-control" placeholder="Optional" value="{{ old('transaction_id') }}">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-semibold">
                                    <i class="bi bi-check-circle me-1"></i> Confirm Payment
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection