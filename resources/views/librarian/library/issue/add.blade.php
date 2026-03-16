@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold"><i class="bi bi-journal-arrow-up me-2 text-primary"></i>Issue Book</h4>
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
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div
                                class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-book me-1"></i> Issue Details
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ url('librarian/library/issue/add') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Book <span
                                                class="text-danger">*</span></label>
                                        <select name="book_id" id="bookSelect" class="form-select" required>
                                            <option value="">-- Select a book --</option>
                                            @foreach ($getBooks as $book)
                                                <option value="{{ $book->id }}" data-available="{{ $book->available }}"
                                                    data-qty="{{ $book->quantity }}"
                                                    {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                                    {{ $book->title }} — {{ $book->author }}
                                                    ({{ $book->available }}/{{ $book->quantity }} available)
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="bookInfo" class="mt-2" style="display:none;">
                                            <span class="badge bg-success" id="availBadge"></span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Issue To <span
                                                class="text-danger">*</span></label>
                                        <select name="member_id" class="form-select" required>
                                            <option value="">-- Select student or teacher --</option>
                                            <optgroup label="Teachers">
                                                @foreach ($getMembers->where('user_type', 2) as $m)
                                                    <option value="{{ $m->id }}"
                                                        {{ old('member_id') == $m->id ? 'selected' : '' }}>
                                                        {{ $m->name }} {{ $m->last_name }} (Teacher)
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Students">
                                                @foreach ($getMembers->where('user_type', 3) as $m)
                                                    <option value="{{ $m->id }}"
                                                        {{ old('member_id') == $m->id ? 'selected' : '' }}>
                                                        {{ $m->name }} {{ $m->last_name }}
                                                        @if ($m->admission_number)
                                                            ({{ $m->admission_number }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Issue Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="issue_date" class="form-control"
                                                value="{{ old('issue_date', now()->toDateString()) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Due Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="due_date" class="form-control"
                                                value="{{ old('due_date', now()->addDays(14)->toDateString()) }}" required>
                                            <div class="form-text">Default: 14 days from today.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Fine per Day (Rs.)</label>
                                        <input type="number" name="fine_per_day" class="form-control"
                                            value="{{ old('fine_per_day', 5) }}" min="0" placeholder="0">
                                        <div class="form-text">Amount charged per day if book is returned late.</div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Note</label>
                                        <textarea name="note" class="form-control" rows="2" placeholder="Optional note">{{ old('note') }}</textarea>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ url('librarian/library/issue/list') }}"
                                            class="btn btn-outline-secondary flex-grow-1">Cancel</a>
                                        <button type="submit" class="btn btn-primary flex-grow-1 fw-semibold">
                                            <i class="bi bi-journal-arrow-up me-1"></i> Issue Book
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

    <script>
        document.getElementById('bookSelect').addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            var avail = opt.getAttribute('data-available');
            var qty = opt.getAttribute('data-qty');
            var info = document.getElementById('bookInfo');
            var badge = document.getElementById('availBadge');
            if (avail !== null) {
                badge.textContent = avail + ' of ' + qty + ' copies available';
                badge.className = 'badge ' + (avail > 0 ? 'bg-success' : 'bg-danger');
                info.style.display = '';
            } else {
                info.style.display = 'none';
            }
        });
    </script>
@endsection
