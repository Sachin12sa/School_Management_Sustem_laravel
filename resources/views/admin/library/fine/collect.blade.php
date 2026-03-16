@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-cash-coin me-2 text-success"></i>Collect Fine Payment
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/library/fine/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">

                        {{-- Fine Summary --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                Fine Details
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Member</div>
                                        <div class="fw-semibold">{{ $getRecord->member->name }}
                                            {{ $getRecord->member->last_name }}</div>
                                        <div class="text-muted small">
                                            {{ $getRecord->member->user_type == 2 ? 'Teacher' : 'Student' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Book</div>
                                        <div class="fw-semibold">{{ $getRecord->book->title }}</div>
                                        <div class="text-muted small">{{ $getRecord->book->author }}</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Due Date</div>
                                        <div class="fw-semibold text-danger">
                                            {{ \Carbon\Carbon::parse($getRecord->due_date)->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Return Date</div>
                                        <div class="fw-semibold">
                                            {{ $getRecord->return_date ? \Carbon\Carbon::parse($getRecord->return_date)->format('d M Y') : 'Not returned yet' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Fine / Day</div>
                                        <div class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</div>
                                    </div>
                                </div>

                                {{-- Fine amount box --}}
                                @php
                                    $liveFine =
                                        $getRecord->status === 'overdue' && $getRecord->fine_per_day > 0
                                            ? \Carbon\Carbon::parse($getRecord->due_date)->diffInDays(now()) *
                                                $getRecord->fine_per_day
                                            : $getRecord->fine_amount;
                                    $daysLate =
                                        $getRecord->status === 'overdue'
                                            ? \Carbon\Carbon::parse($getRecord->due_date)->diffInDays(now())
                                            : \Carbon\Carbon::parse($getRecord->due_date)->diffInDays(
                                                \Carbon\Carbon::parse($getRecord->return_date),
                                            );
                                @endphp
                                <div
                                    class="mt-3 p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small">Days overdue</span>
                                        <span class="fw-semibold">{{ $daysLate }} days</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted small">Fine per day</span>
                                        <span class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-1">
                                        <span class="fw-bold">Total Fine</span>
                                        <span class="fw-bold fs-4 text-danger">Rs. {{ number_format($liveFine, 2) }}</span>
                                    </div>
                                    @if ($getRecord->status === 'overdue')
                                        <div class="text-danger small mt-1">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Fine will be locked at this amount once the book is returned.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Payment Form --}}
                        <div class="card border-0 shadow-sm rounded-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-cash me-1"></i> Record Payment
                            </div>
                            <div class="card-body p-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                                @endif

                                <form action="{{ url('admin/library/fine/collect/' . $getRecord->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payment Method <span
                                                class="text-danger">*</span></label>
                                        <select name="fine_payment_method" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            <option value="cash"
                                                {{ old('fine_payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank"
                                                {{ old('fine_payment_method') === 'bank' ? 'selected' : '' }}>Bank Transfer
                                            </option>
                                            <option value="online"
                                                {{ old('fine_payment_method') === 'online' ? 'selected' : '' }}>Online / eSewa /
                                                Khalti</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Payment Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="fine_paid_at" class="form-control"
                                            value="{{ old('fine_paid_at', now()->toDateString()) }}" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Note</label>
                                        <input type="text" name="fine_note" class="form-control"
                                            value="{{ old('fine_note') }}" placeholder="Optional note e.g. Receipt no.">
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Confirm — Rs. {{ number_format($liveFine, 2) }} Received
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
