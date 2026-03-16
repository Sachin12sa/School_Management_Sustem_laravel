@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold"><i class="bi bi-journal-arrow-up me-2 text-primary"></i>Book Issues</h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('librarian/library/issue/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Issue a Book
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button
                            type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button"
                            class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif

                {{-- Summary --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-primary">{{ $summary['total_books'] }}</div>
                            <div class="text-muted small">Total Books</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-success">{{ $summary['available_books'] }}</div>
                            <div class="text-muted small">Available</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-info">{{ $summary['total_issued'] }}</div>
                            <div class="text-muted small">Currently Issued</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                            <div class="fw-bold fs-4 text-danger">{{ $summary['overdue'] }}</div>
                            <div class="text-muted small">Overdue</div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body py-2 px-3">
                        <form method="GET" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <input type="text" name="member_name" class="form-control form-control-sm"
                                    placeholder="Search member..." value="{{ request('member_name') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="book_id" class="form-select form-select-sm">
                                    <option value="">All Books</option>
                                    @foreach ($getBooks as $b)
                                        <option value="{{ $b->id }}" {{ request('book_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued
                                    </option>
                                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue
                                    </option>
                                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary"><i
                                        class="bi bi-funnel me-1"></i>Filter</button>
                                <a href="{{ url('librarian/library/issue/list') }}"
                                    class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Book</th>
                                        <th>Member</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Fine</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        <tr class="{{ $row->status === 'overdue' ? 'table-danger' : '' }}">
                                            <td class="small text-muted">{{ $getRecord->firstItem() + $i }}</td>
                                            <td>
                                                <div class="fw-semibold small">{{ $row->book_title }}</div>
                                                <div class="text-muted" style="font-size:.72rem;">{{ $row->book_author }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold">{{ $row->member_name }}
                                                    {{ $row->member_last_name }}</div>
                                                <div class="text-muted" style="font-size:.68rem;">
                                                    {{ $row->member_type == 2 ? 'Teacher' : 'Student' }}
                                                    @if ($row->admission_number)
                                                        · {{ $row->admission_number }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="small">{{ $row->issue_date }}</td>
                                            <td
                                                class="small {{ $row->status !== 'returned' && $row->due_date < now()->toDateString() ? 'text-danger fw-semibold' : '' }}">
                                                {{ $row->due_date }}
                                            </td>
                                            <td class="small">{{ $row->return_date ?? '—' }}</td>
                                            <td class="small">
                                                @if ($row->fine_amount > 0)
                                                    <span class="text-danger fw-semibold">Rs.
                                                        {{ number_format($row->fine_amount, 2) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{!! $row->status_badge !!}</td>
                                            <td>
                                                @if ($row->status !== 'returned')
                                                    <a href="{{ url('librarian/library/issue/return/' . $row->id) }}"
                                                        class="btn btn-sm btn-success" title="Return Book">
                                                        <i class="bi bi-arrow-return-left"></i> Return
                                                    </a>
                                                @endif
                                                @if ($row->status === 'returned')
                                                    <a href="{{ url('librarian/library/issue/delete/' . $row->id) }}"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this record?')" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-5">
                                                <i class="bi bi-journal-x fs-3 d-block mb-2"></i>No issue records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($getRecord->hasPages())
                            <div class="px-3 py-2">{{ $getRecord->withQueryString()->links() }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
