@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Issue Record
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/library/issue/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        {{-- Quick Info Card --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3 bg-light">
                            <div class="card-body py-2">
                                <div class="row small">
                                    <div class="col-sm-6">
                                        <strong>Book:</strong> {{ $getRecord->book->title }}
                                        ({{ $getRecord->book->author }})
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Member:</strong> {{ $getRecord->member->name }}
                                        {{ $getRecord->member->last_name }}
                                        <span
                                            class="badge bg-secondary ms-1">{{ $getRecord->member->user_type == 2 ? 'Teacher' : 'Student' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ url('admin/library/issue/edit/' . $getRecord->id) }}" method="POST">
                            @csrf

                            {{-- Core Issue Details --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    Core Details
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-semibold small">Issue Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="issue_date" class="form-control"
                                                value="{{ substr((string) $getRecord->issue_date, 0, 10) }}" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label fw-semibold small">Due Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="due_date" class="form-control"
                                                value="{{ substr((string) $getRecord->due_date, 0, 10) }}" required>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label fw-semibold small">Fine / Day (Rs.) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="fine_per_day" class="form-control"
                                                value="{{ $getRecord->fine_per_day }}" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small">Issue Note</label>
                                            <input type="text" name="note" class="form-control"
                                                value="{{ $getRecord->note }}" placeholder="Optional note">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Return Details (Only visible if already returned) --}}
                            @if ($getRecord->status === 'returned')
                                <div class="alert alert-warning small d-flex gap-2 align-items-start mb-3">
                                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                                    <div>
                                        <strong>Caution:</strong> This book is marked as returned. Editing conditions here
                                        (like switching to 'Lost') will <strong>not</strong> automatically update the
                                        library stock quantities. Please adjust stock manually if necessary.
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm rounded-3 mb-3">
                                    <div
                                        class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                        Post-Return Details
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold small">Return Date</label>
                                                <input type="date" name="return_date" class="form-control"
                                                    value="{{ substr((string) $getRecord->return_date, 0, 10) }}">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold small">Total Fine Amount (Rs.)</label>
                                                <input type="number" step="0.01" name="fine_amount" class="form-control"
                                                    value="{{ $getRecord->fine_amount }}">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="form-label fw-semibold small">Fine Status</label>
                                                <select name="fine_status" class="form-select">
                                                    <option value="none"
                                                        {{ $getRecord->fine_status === 'none' ? 'selected' : '' }}>None
                                                    </option>
                                                    <option value="unpaid"
                                                        {{ $getRecord->fine_status === 'unpaid' ? 'selected' : '' }}>Unpaid
                                                    </option>
                                                    <option value="paid"
                                                        {{ $getRecord->fine_status === 'paid' ? 'selected' : '' }}>Paid
                                                    </option>
                                                </select>
                                            </div>

                                            @if ($hasDamageCols)
                                                <div class="col-sm-4">
                                                    <label class="form-label fw-semibold small">Book Condition</label>
                                                    <select name="book_condition" class="form-select">
                                                        <option value="good"
                                                            {{ $getRecord->book_condition === 'good' ? 'selected' : '' }}>
                                                            Good</option>
                                                        <option value="damaged"
                                                            {{ $getRecord->book_condition === 'damaged' ? 'selected' : '' }}>
                                                            Damaged</option>
                                                        <option value="torn"
                                                            {{ $getRecord->book_condition === 'torn' ? 'selected' : '' }}>
                                                            Torn</option>
                                                        <option value="lost"
                                                            {{ $getRecord->book_condition === 'lost' ? 'selected' : '' }}>
                                                            Lost</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label fw-semibold small">Damage Charge (Rs.)</label>
                                                    <input type="number" step="0.01" name="damage_charge"
                                                        class="form-control" value="{{ $getRecord->damage_charge }}">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="form-label fw-semibold small">Damage Note</label>
                                                    <input type="text" name="damage_note" class="form-control"
                                                        value="{{ $getRecord->damage_note }}">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url('admin/library/issue/list') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary fw-semibold px-4">
                                    <i class="bi bi-save me-1"></i> Update Record
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
