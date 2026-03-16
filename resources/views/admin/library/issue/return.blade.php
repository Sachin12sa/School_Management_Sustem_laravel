@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-arrow-return-left me-2 text-success"></i>Return Book
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
                    <div class="col-lg-6">

                        {{-- Issue Details --}}
                        <div class="card border-0 shadow-sm rounded-3 mb-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                Issue Details
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
                                        <div class="fw-semibold">{{ $getRecord->member->name }}
                                            {{ $getRecord->member->last_name }}</div>
                                        <div class="text-muted small">
                                            {{ $getRecord->member->user_type == 2 ? 'Teacher' : 'Student' }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Issue Date</div>
                                        <div class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($getRecord->issue_date)->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        @php $dueDateStr = \Carbon\Carbon::parse($getRecord->due_date)->toDateString(); @endphp
                                        <div class="text-muted small">Due Date</div>
                                        <div
                                            class="fw-semibold {{ $dueDateStr < now()->toDateString() ? 'text-danger' : '' }}">
                                            {{ \Carbon\Carbon::parse($getRecord->due_date)->format('d M Y') }}
                                            @if ($dueDateStr < now()->toDateString())
                                                <span class="badge bg-danger ms-1">Overdue</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Fine / Day</div>
                                        <div class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- !! IMPORTANT NOTICE -- fine is separate !! --}}
                        <div class="alert alert-info d-flex gap-3 align-items-start mb-3">
                            <i class="bi bi-info-circle-fill fs-5 flex-shrink-0 mt-1"></i>
                            <div>
                                <div class="fw-semibold">Book return only</div>
                                <div class="small mt-1">
                                    This form only marks the book as returned. If a fine is generated,
                                    it will appear separately in <strong>Library Fines</strong> where
                                    you can collect payment or waive it.
                                </div>
                            </div>
                        </div>

                        {{-- Return Form --}}
                        <div class="card border-0 shadow-sm rounded-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-arrow-return-left me-1"></i> Process Return
                            </div>
                            <div class="card-body p-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                                @endif

                                <form action="{{ url('admin/library/issue/return/' . $getRecord->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Return Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="return_date" id="returnDate" class="form-control"
                                            value="{{ now()->toDateString() }}" required>
                                    </div>

                                    {{-- Fine preview (informational only) --}}
                                    <div id="fineBox" class="rounded-3 p-3 mb-3"
                                        style="display:none;background:#fff3cd;border:1px solid #ffecb5;">
                                        <div class="fw-semibold mb-2" style="color:#856404;">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            A fine will be generated
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span style="color:#856404;">Days late</span>
                                            <span class="fw-semibold" id="daysLate">0</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mt-1">
                                            <span style="color:#856404;">Fine per day</span>
                                            <span class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                            <span class="fw-semibold" style="color:#856404;">Fine amount</span>
                                            <span class="fw-bold fs-5" style="color:#dc3545;" id="totalFine">Rs. 0</span>
                                        </div>
                                        <div class="small mt-2" style="color:#856404;">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Fine will be marked <strong>Unpaid</strong> — collect separately from Library
                                            Fines.
                                        </div>
                                    </div>

                                    <div id="noFineBox" class="alert alert-success mb-3">
                                        <i class="bi bi-check-circle me-2"></i>
                                        No fine — book returned on time.
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Note</label>
                                        <textarea name="note" class="form-control" rows="2" placeholder="Optional note about this return">{{ old('note') }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
                                        <i class="bi bi-arrow-return-left me-1"></i>
                                        Confirm Return (Fine collected separately)
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        var DUE_DATE = '{{ \Carbon\Carbon::parse($getRecord->due_date)->toDateString() }}';
        var FINE_PER_DAY = {{ (int) $getRecord->fine_per_day }};

        function calcFine(returnDateStr) {
            if (!returnDateStr) return;
            var dp = DUE_DATE.split('-');
            var rp = returnDateStr.split('-');
            var due = new Date(+dp[0], dp[1] - 1, +dp[2]);
            var ret = new Date(+rp[0], rp[1] - 1, +rp[2]);
            var days = Math.max(0, Math.round((ret - due) / 86400000));

            if (days > 0) {
                document.getElementById('daysLate').textContent = days;
                document.getElementById('totalFine').textContent = 'Rs. ' + (days * FINE_PER_DAY).toLocaleString('en-IN');
                document.getElementById('fineBox').style.display = '';
                document.getElementById('noFineBox').style.display = 'none';
            } else {
                document.getElementById('fineBox').style.display = 'none';
                document.getElementById('noFineBox').style.display = '';
            }
        }

        document.getElementById('returnDate').addEventListener('change', function() {
            calcFine(this.value);
        });
        calcFine(document.getElementById('returnDate').value);
    </script>
@endsection
