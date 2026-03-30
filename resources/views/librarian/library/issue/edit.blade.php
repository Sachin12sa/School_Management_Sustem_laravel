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
                        <a href="{{ url('librarian/library/issue/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-7">

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @php
                            // Safe date strings
                            $issueStr = substr((string) $getRecord->issue_date, 0, 10);
                            $dueStr = substr((string) $getRecord->due_date, 0, 10);
                            $retStr = $getRecord->return_date ? substr((string) $getRecord->return_date, 0, 10) : null;
                            $isReturned = $getRecord->status === 'returned';
                        @endphp

                        {{-- Read-only issue summary --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                Issue Summary (read-only)
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Book</div>
                                        <div class="fw-semibold">{{ $getRecord->book->title }}</div>
                                        <div class="text-muted small">{{ $getRecord->book->author }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Member</div>
                                        <div class="fw-semibold">
                                            {{ $getRecord->member->name }} {{ $getRecord->member->last_name }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $getRecord->member->user_type == 2 ? 'Teacher' : 'Student' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="text-muted small">Issue Date</div>
                                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($issueStr)->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="text-muted small">Status</div>
                                        <div class="fw-semibold">
                                            @if ($getRecord->status === 'returned')
                                                <span class="badge bg-success">Returned</span>
                                            @elseif($getRecord->status === 'overdue')
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-primary">Issued</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($retStr)
                                        <div class="col-sm-3">
                                            <div class="text-muted small">Return Date</div>
                                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($retStr)->format('d M Y') }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (($getRecord->fine_amount ?? 0) > 0)
                                        <div class="col-sm-3">
                                            <div class="text-muted small">Fine Amount</div>
                                            <div class="fw-semibold text-danger">
                                                Rs. {{ number_format($getRecord->fine_amount, 2) }}
                                                <span
                                                    class="badge bg-{{ $getRecord->fine_status === 'paid' ? 'success' : ($getRecord->fine_status === 'waived' ? 'secondary' : 'danger') }} ms-1"
                                                    style="font-size:.65rem;">
                                                    {{ ucfirst($getRecord->fine_status ?? 'none') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Editable fields --}}
                        <div class="card border-0 shadow-sm rounded-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-pencil me-1"></i> Edit Details
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ url('librarian/library/issue/edit/' . $getRecord->id) }}" method="POST">
                                    @csrf

                                    <div class="row g-3">

                                        {{-- Due date (editable only if not returned) --}}
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small">
                                                Due Date
                                                @if ($isReturned)
                                                    <span class="text-muted fw-normal">(locked — book returned)</span>
                                                @else
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="date" name="due_date"
                                                class="form-control {{ $isReturned ? 'bg-light' : '' }}"
                                                value="{{ old('due_date', $dueStr) }}"
                                                {{ $isReturned ? 'readonly' : 'required' }}>
                                            @if (!$isReturned)
                                                <div class="form-text">
                                                    Changing the due date will affect late fine calculation on return.
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Fine per day (editable only if not returned) --}}
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small">
                                                Fine per Day (Rs.)
                                                @if ($isReturned)
                                                    <span class="text-muted fw-normal">(locked)</span>
                                                @endif
                                            </label>
                                            <input type="number" name="fine_per_day"
                                                class="form-control {{ $isReturned ? 'bg-light' : '' }}"
                                                value="{{ old('fine_per_day', $getRecord->fine_per_day ?? 0) }}"
                                                min="0" {{ $isReturned ? 'readonly' : '' }}>
                                        </div>

                                        {{-- Note --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small">Note</label>
                                            <textarea name="note" class="form-control" rows="2" placeholder="Optional note about this issue">{{ old('note', $getRecord->note) }}</textarea>
                                        </div>

                                        {{-- Damage section (only if returned and damage columns exist) --}}
                                        @if ($isReturned && isset($hasDamageCols) && $hasDamageCols)
                                            <div class="col-12">
                                                <hr class="my-1">
                                                <div class="small fw-semibold text-muted text-uppercase mb-2"
                                                    style="font-size:.65rem;letter-spacing:.08em;">
                                                    <i class="bi bi-book me-1"></i> Book Condition (set at return)
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-sm-4">
                                                        <label class="form-label fw-semibold small">Condition</label>
                                                        <select name="book_condition" class="form-select">
                                                            @foreach (['good' => 'Good', 'damaged' => 'Damaged', 'torn' => 'Torn', 'lost' => 'Lost'] as $val => $label)
                                                                <option value="{{ $val }}"
                                                                    {{ old('book_condition', $getRecord->book_condition ?? 'good') === $val ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label fw-semibold small">Damage Charge
                                                            (Rs.)</label>
                                                        <input type="number" name="damage_charge" class="form-control"
                                                            min="0" step="0.01"
                                                            value="{{ old('damage_charge', $getRecord->damage_charge ?? 0) }}">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="form-label fw-semibold small">Damage Note</label>
                                                        <input type="text" name="damage_note" class="form-control"
                                                            value="{{ old('damage_note', $getRecord->damage_note ?? '') }}"
                                                            placeholder="Describe damage...">
                                                    </div>
                                                </div>
                                                <div class="alert alert-warning small py-2 mt-2 mb-0">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Changing condition to <strong>Torn</strong> or <strong>Lost</strong>
                                                    will
                                                    permanently reduce the book's quantity count if it hasn't already been
                                                    adjusted.
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="d-flex gap-2 mt-4">
                                        <a href="{{ url('librarian/library/issue/list') }}"
                                            class="btn btn-outline-secondary flex-grow-1">Cancel</a>
                                        <button type="submit" class="btn btn-primary flex-grow-1 fw-semibold">
                                            <i class="bi bi-save me-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
