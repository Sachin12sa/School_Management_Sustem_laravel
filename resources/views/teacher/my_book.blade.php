@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-book-fill me-2 text-primary"></i>My Books</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @php
                $active   = $getRecord->whereIn('status', ['issued','overdue']);
                $returned = $getRecord->where('status', 'returned');
            @endphp

            {{-- Summary --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                        <div class="fw-bold fs-4 text-primary">{{ $active->count() }}</div>
                        <div class="text-muted small">Currently Borrowed</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                        <div class="fw-bold fs-4 text-success">{{ $returned->count() }}</div>
                        <div class="text-muted small">Returned</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 text-center p-3">
                        <div class="fw-bold fs-4 text-danger">{{ $active->where('status','overdue')->count() }}</div>
                        <div class="text-muted small">Overdue</div>
                    </div>
                </div>
            </div>

            {{-- Currently Borrowed --}}
            @if($active->count() > 0)
            <h6 class="fw-semibold mb-2 text-muted text-uppercase small">Currently Borrowed</h6>
            <div class="row g-3 mb-4">
                @foreach($active as $issue)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100
                         {{ $issue->status === 'overdue' ? 'border-danger border' : '' }}">
                        <div class="card-body d-flex gap-3">
                            @if($issue->book->cover_image)
                                <img src="{{ asset('storage/'.$issue->book->cover_image) }}"
                                     class="rounded" style="width:55px;height:75px;object-fit:cover;flex-shrink:0;">
                            @else
                                <div class="rounded bg-secondary bg-opacity-15 d-flex align-items-center justify-content-center"
                                     style="width:55px;height:75px;flex-shrink:0;">
                                    <i class="bi bi-book text-secondary fs-4"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $issue->book->title }}</div>
                                <div class="text-muted small">{{ $issue->book->author }}</div>
                                <div class="mt-2">
                                    <div class="small"><span class="text-muted">Issued:</span> {{ $issue->issue_date }}</div>
                                    <div class="small {{ $issue->status === 'overdue' ? 'text-danger fw-semibold' : '' }}">
                                        <span class="text-muted">Due:</span> {{ $issue->due_date }}
                                        @if($issue->status === 'overdue')
                                            <span class="ms-1 badge bg-danger">Overdue</span>
                                        @endif
                                    </div>
                                    @if($issue->fine_per_day > 0 && $issue->status === 'overdue')
                                        <div class="small text-danger mt-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Fine accruing: Rs.{{ $issue->fine_per_day }}/day
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Returned --}}
            @if($returned->count() > 0)
            <h6 class="fw-semibold mb-2 text-muted text-uppercase small">Return History</h6>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Book</th>
                                <th>Issued</th>
                                <th>Returned</th>
                                <th>Fine Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returned as $i => $issue)
                            <tr>
                                <td class="small text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="small fw-semibold">{{ $issue->book->title }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $issue->book->author }}</div>
                                </td>
                                <td class="small">{{ $issue->issue_date }}</td>
                                <td class="small">{{ $issue->return_date }}</td>
                                <td class="small">
                                    @if($issue->fine_amount > 0)
                                        <span class="text-danger">Rs. {{ number_format($issue->fine_amount, 2) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($getRecord->count() === 0)
            <div class="text-center text-muted py-5">
                <i class="bi bi-book fs-1 d-block mb-3"></i>
                You have no books issued yet.
            </div>
            @endif

        </div>
    </div>
</main>
@endsection