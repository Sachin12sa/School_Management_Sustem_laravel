@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-journal-arrow-up me-2 text-primary"></i>Book Issues
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/library/issue/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Issue a Book
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
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
                            <div class="fw-bold fs-4 text-success">{{ $summary['available_copies'] }}</div>
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
                                        <option value="{{ $b->id }}"
                                            {{ request('book_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->title }}
                                        </option>
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
                                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>
                                        Returned
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                <a href="{{ url('admin/library/issue/list') }}"
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
                                        <th>Condition</th>
                                        <th>Fine</th>
                                        <th>Status</th>
                                        <th>Return Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        @php
                                            // Safe date strings — no model method, no Carbon cast issues
                                            $issueStr = substr((string) $row->issue_date, 0, 10);
                                            $dueStr = substr((string) $row->due_date, 0, 10);
                                            $retStr = $row->return_date
                                                ? substr((string) $row->return_date, 0, 10)
                                                : null;

                                            $isDueOverdue =
                                                $row->status !== 'returned' && $dueStr < now()->toDateString();

                                            // Status badge — inline match, no accessor
                                            $statusBadge = match ($row->status ?? 'issued') {
                                                'returned' => '<span class="badge bg-success">Returned</span>',
                                                'overdue' => '<span class="badge bg-danger">Overdue</span>',
                                                default => '<span class="badge bg-primary">Issued</span>',
                                            };
                                            $returnTypeBadge = match ($row->return_type ?? '-') {
                                                'late' => '<span class="badge bg-danger">Late</span>',
                                                'on_time' => '<span class="badge bg-success">On Time</span>',
                                                default => '<span class="badge bg-success">-</span>',
                                            };

                                            // Condition badge — check column exists first
                                            $condition = $row->book_condition ?? null;
                                            $condBadge = match ($condition) {
                                                'damaged' => '<span class="badge bg-warning text-dark">Damaged</span>',
                                                'torn' => '<span class="badge bg-danger">Torn</span>',
                                                'lost' => '<span class="badge bg-dark">Lost</span>',
                                                'good'
                                                    => '<span class="badge bg-success">Good</span>', // no badge for good — clean
                                                null => '', // migration not run yet
                                                default => '',
                                            };

                                            // Fine status
                                            $fsBadge = match ($row->fine_status ?? 'none') {
                                                'unpaid'
                                                    => '<span class="badge bg-danger ms-1" style="font-size:.6rem;">Unpaid</span>',
                                                'paid'
                                                    => '<span class="badge bg-success ms-1" style="font-size:.6rem;">Paid</span>',
                                                'waived'
                                                    => '<span class="badge bg-secondary ms-1" style="font-size:.6rem;">Waived</span>',
                                                default => '',
                                            };
                                        @endphp
                                        <tr class="{{ $row->status === 'overdue' ? 'table-danger' : '' }}">
                                            <td class="small text-muted">{{ $getRecord->firstItem() + $i }}</td>

                                            {{-- Book --}}
                                            <td>
                                                <div class="fw-semibold small">{{ $row->book_title }}</div>
                                                <div class="text-muted" style="font-size:.72rem;">
                                                    {{ $row->book_author ?? '' }}</div>
                                            </td>

                                            {{-- Member --}}
                                            <td>
                                                <div class="small fw-semibold">
                                                    {{ $row->member_name }} {{ $row->member_last_name }}
                                                </div>
                                                <div class="text-muted" style="font-size:.68rem;">
                                                    <span class="badge bg-primary" style="font-size:.65rem;">
                                                        {{ ($row->member_type ?? 0) == 2 ? 'Teacher' : 'Student' }}
                                                        @if ($row->admission_number ?? false)
                                                            · {{ $row->admission_number }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- Issue date --}}
                                            <td class="small">{{ \Carbon\Carbon::parse($issueStr)->format('d M Y') }}</td>

                                            {{-- Due date --}}
                                            <td class="small {{ $isDueOverdue ? 'text-danger fw-semibold' : '' }}">
                                                {{ \Carbon\Carbon::parse($dueStr)->format('d M Y') }}
                                            </td>

                                            {{-- Return date --}}
                                            <td class="small">
                                                {{ $retStr ? \Carbon\Carbon::parse($retStr)->format('d M Y') : '—' }}
                                            </td>

                                            {{-- Condition --}}
                                            <td>{!! $condBadge !!}</td>

                                            {{-- Fine --}}
                                            <td class="small">
                                                @if (($row->fine_amount ?? 0) > 0)
                                                    <span
                                                        class="fw-semibold {{ ($row->fine_status ?? '') === 'paid' ? 'text-success' : 'text-danger' }}">
                                                        Rs. {{ number_format($row->fine_amount, 2) }}
                                                    </span>
                                                    {!! $fsBadge !!}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            {{-- Status --}}
                                            <td>{!! $statusBadge !!}</td>

                                            {{-- Return Type --}}
                                            <td>{!! $returnTypeBadge !!}</td>

                                            {{-- Actions --}}
                                            <td>
                                                @if ($row->status !== 'returned')
                                                    <a href="{{ url('admin/library/issue/return/' . $row->id) }}"
                                                        class="btn btn-sm btn-success mb-1" title="Return Book">
                                                        <i class="bi bi-arrow-return-left"></i> Return
                                                    </a>
                                                @endif

                                                {{-- Edit: always show for issued/overdue, show for returned too --}}
                                                <a href="{{ url('admin/library/issue/edit/' . $row->id) }}"
                                                    class="btn btn-sm btn-outline-primary mb-1" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                @if ($row->status === 'returned')
                                                    <a href="{{ url('admin/library/issue/delete/' . $row->id) }}"
                                                        class="btn btn-sm btn-outline-danger mb-1"
                                                        onclick="return confirm('Delete this record?')" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-5">
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
