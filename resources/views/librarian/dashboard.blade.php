@extends('layouts.app')

@section('style')
    <style>
        .stat-card {
            border: none;
            border-radius: .75rem;
            transition: transform .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .4rem 1rem rgba(0, 0, 0, .1) !important;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .book-row:hover {
            background: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-book-fill me-2 text-primary"></i>Library Dashboard
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end d-flex gap-2 justify-content-end">
                        <a href="{{ url('librarian/library/book/add') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Book
                        </a>
                        <a href="{{ url('librarian/library/issue/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-journal-arrow-up me-1"></i> Issue Book
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- ── SUMMARY CARDS ───────────────────────────────────── --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-xl-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-primary bg-opacity-15 text-primary">
                                    <i class="bi bi-journals"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Total Books</div>
                                    <div class="fw-bold fs-4 text-primary">{{ $summary['total_books'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ $summary['total_copies'] }} total
                                        copies</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-success bg-opacity-15 text-success">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Available</div>
                                    <div class="fw-bold fs-4 text-success">{{ $summary['available_copies'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">copies on shelf</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-info bg-opacity-15 text-info">
                                    <i class="bi bi-journal-arrow-up"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Currently Issued</div>
                                    <div class="fw-bold fs-4 text-info">{{ $summary['total_issued'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">active issues</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card stat-card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div class="stat-icon bg-danger bg-opacity-15 text-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Overdue</div>
                                    <div class="fw-bold fs-4 text-danger">{{ $summary['overdue'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">past due date</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">

                    {{-- ── OVERDUE BOOKS ───────────────────────────────── --}}
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small">
                                    <i class="bi bi-exclamation-circle-fill text-danger me-1"></i>
                                    Overdue Books
                                </span>
                                <a href="{{ url('librarian/library/issue/list?status=overdue') }}"
                                    class="btn btn-outline-danger btn-sm" style="font-size:.72rem;">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Book</th>
                                            <th>Member</th>
                                            <th>Due</th>
                                            <th>Days Late</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($overdueBooks as $issue)
                                            <tr class="book-row">
                                                <td class="small fw-semibold">{{ Str::limit($issue->book->title, 20) }}</td>
                                                <td class="small">{{ $issue->member->name }}
                                                    {{ $issue->member->last_name }}</td>
                                                <td class="small text-danger fw-semibold">
                                                    {{ \Carbon\Carbon::parse($issue->due_date)->format('d M') }}
                                                </td>
                                                <td>
                                                    @php $days = \Carbon\Carbon::parse($issue->due_date)->diffInDays(now()); @endphp
                                                    <span class="badge bg-danger">{{ $days }}d</span>
                                                </td>
                                                <td>
                                                    <a href="{{ url('librarian/library/issue/return/' . $issue->id) }}"
                                                        class="btn btn-sm btn-success py-0 px-2" style="font-size:.7rem;">
                                                        Return
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3 small">
                                                    <i class="bi bi-check-circle text-success me-1"></i>No overdue books!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ── RECENTLY ISSUED ─────────────────────────────── --}}
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div
                                class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small">
                                    <i class="bi bi-clock-history text-primary me-1"></i>
                                    Recently Issued
                                </span>
                                <a href="{{ url('librarian/library/issue/list') }}" class="btn btn-outline-primary btn-sm"
                                    style="font-size:.72rem;">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Book</th>
                                            <th>Member</th>
                                            <th>Issued</th>
                                            <th>Due</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentIssues as $issue)
                                            <tr class="book-row">
                                                <td class="small fw-semibold">{{ Str::limit($issue->book->title, 20) }}
                                                </td>
                                                <td class="small">{{ $issue->member->name }}</td>
                                                <td class="small">
                                                    {{ \Carbon\Carbon::parse($issue->issue_date)->format('d M') }}</td>
                                                <td
                                                    class="small {{ \Carbon\Carbon::parse($issue->due_date)->isPast() && $issue->status !== 'returned' ? 'text-danger fw-semibold' : '' }}">
                                                    {{ \Carbon\Carbon::parse($issue->due_date)->format('d M') }}
                                                </td>
                                                <td>{!! $issue->status_badge !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-3 small">No recent
                                                    issues.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row g-3">

                    {{-- ── MOST BORROWED BOOKS ─────────────────────────── --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-transparent border-bottom">
                                <span class="fw-semibold small">
                                    <i class="bi bi-trophy-fill text-warning me-1"></i>
                                    Most Borrowed Books
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th class="text-center">Times</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($popularBooks as $i => $book)
                                            <tr>
                                                <td class="text-muted small">{{ $i + 1 }}</td>
                                                <td class="small fw-semibold">{{ Str::limit($book->title, 25) }}</td>
                                                <td class="small text-muted">{{ Str::limit($book->author, 18) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ $book->issues_count }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3 small">No data yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ── BOOKS BY CATEGORY ───────────────────────────── --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-transparent border-bottom">
                                <span class="fw-semibold small">
                                    <i class="bi bi-tags-fill text-info me-1"></i>
                                    Books by Category
                                </span>
                            </div>
                            <div class="card-body">
                                @if ($byCategory->count())
                                    <div style="position:relative;height:200px;">
                                        <canvas id="categoryChart"></canvas>
                                    </div>
                                @else
                                    <p class="text-muted small text-center py-4">No categories assigned yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── QUICK ACTIONS ───────────────────────────────── --}}
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-transparent border-bottom">
                                <span class="fw-semibold small">
                                    <i class="bi bi-lightning-fill text-warning me-1"></i>
                                    Quick Actions
                                </span>
                            </div>
                            <div class="card-body d-flex flex-column gap-2">
                                <a href="{{ url('librarian/library/book/add') }}"
                                    class="btn btn-outline-primary d-flex align-items-center gap-2">
                                    <i class="bi bi-plus-circle"></i> Add New Book
                                </a>
                                <a href="{{ url('librarian/library/issue/add') }}"
                                    class="btn btn-outline-success d-flex align-items-center gap-2">
                                    <i class="bi bi-journal-arrow-up"></i> Issue Book
                                </a>
                                <a href="{{ url('librarian/library/issue/list?status=issued') }}"
                                    class="btn btn-outline-info d-flex align-items-center gap-2">
                                    <i class="bi bi-list-ul"></i> All Active Issues
                                </a>
                                <a href="{{ url('librarian/library/issue/list?status=overdue') }}"
                                    class="btn btn-outline-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle"></i> View Overdue
                                </a>
                                <a href="{{ url('librarian/library/book/list') }}"
                                    class="btn btn-outline-secondary d-flex align-items-center gap-2">
                                    <i class="bi bi-journals"></i> All Books
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        @if ($byCategory->count())
            var catLabels = @json($byCategory->pluck('category'));
            var catValues = @json($byCategory->pluck('total'));

            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catValues,
                        backgroundColor: [
                            '#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0',
                            '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#d63384'
                        ],
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 11
                                },
                                padding: 8,
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endsection
