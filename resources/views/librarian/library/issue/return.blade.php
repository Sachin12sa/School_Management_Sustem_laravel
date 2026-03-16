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
                    <div class="col-lg-6">

                        {{-- Issue Summary --}}
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
                                            {{ $getRecord->member->user_type == 2 ? 'Teacher' : 'Student' }}</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Issue Date</div>
                                        {{-- Use format() to avoid showing time from Carbon cast --}}
                                        <div class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($getRecord->issue_date)->format('d M Y') }}</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="text-muted small">Due Date</div>
                                        @php $dueDateStr = \Carbon\Carbon::parse($getRecord->due_date)->format('Y-m-d'); @endphp
                                        <div
                                            class="fw-semibold {{ $dueDateStr < now()->toDateString() ? 'text-danger' : 'text-success' }}">
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

                                <form action="{{ url('librarian/library/issue/return/' . $getRecord->id) }}"
                                    method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Return Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="return_date" id="returnDate" class="form-control"
                                            value="{{ now()->toDateString() }}" required>
                                    </div>

                                    {{-- Fine preview --}}
                                    <div id="fineBox" class="card border-0 bg-danger bg-opacity-10 rounded-3 p-3 mb-3"
                                        style="display:none;">
                                        <div class="fw-semibold text-danger mb-2">
                                            <i class="bi bi-exclamation-triangle me-1"></i> Late Return Fine
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="small text-muted">Days overdue</span>
                                            <span class="fw-semibold" id="daysLate">0</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <span class="small text-muted">Fine per day</span>
                                            <span class="fw-semibold">Rs. {{ $getRecord->fine_per_day }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                            <span class="fw-semibold">Total Fine</span>
                                            <span class="fw-bold text-danger fs-5" id="totalFine">Rs. 0</span>
                                        </div>
                                    </div>

                                    <div id="noFineBox" class="alert alert-success mb-3">
                                        <i class="bi bi-check-circle me-2"></i>No fine — returned on time.
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Note</label>
                                        <textarea name="note" class="form-control" rows="2" placeholder="Optional note"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
                                        <i class="bi bi-arrow-return-left me-1"></i> Confirm Return
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
        // ── Use toDateString() from PHP to get clean YYYY-MM-DD — no time, no timezone issue
        var DUE_DATE = '{{ \Carbon\Carbon::parse($getRecord->due_date)->toDateString() }}';
        var FINE_PER_DAY = {{ (int) $getRecord->fine_per_day }};

        function calcFine(returnDateStr) {
            if (!returnDateStr) return;

            // Parse as local date by splitting manually — avoids UTC timezone shift
            var dueParts = DUE_DATE.split('-');
            var retParts = returnDateStr.split('-');

            var due = new Date(
                parseInt(dueParts[0]),
                parseInt(dueParts[1]) - 1,
                parseInt(dueParts[2])
            );
            var ret = new Date(
                parseInt(retParts[0]),
                parseInt(retParts[1]) - 1,
                parseInt(retParts[2])
            );

            var msPerDay = 24 * 60 * 60 * 1000;
            var days = Math.max(0, Math.round((ret - due) / msPerDay));

            var fineBox = document.getElementById('fineBox');
            var noFineBox = document.getElementById('noFineBox');

            if (days > 0) {
                document.getElementById('daysLate').textContent = days;
                document.getElementById('totalFine').textContent = 'Rs. ' + (days * FINE_PER_DAY).toLocaleString('en-IN');
                fineBox.style.display = '';
                noFineBox.style.display = 'none';
            } else {
                fineBox.style.display = 'none';
                noFineBox.style.display = '';
            }
        }

        document.getElementById('returnDate').addEventListener('change', function() {
            calcFine(this.value);
        });

        // Run immediately on page load
        calcFine(document.getElementById('returnDate').value);
    </script>
@endsection
