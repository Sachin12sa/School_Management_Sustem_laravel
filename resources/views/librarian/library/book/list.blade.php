@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold"><i class="bi bi-book-fill me-2 text-primary"></i>Books</h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('librarian/library/book/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New Book
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @include('message')
        <div class="app-content">
            <div class="container-fluid">



                {{-- Summary Cards --}}
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
                                <input type="text" name="title" class="form-control form-control-sm"
                                    placeholder="Search title..." value="{{ request('title') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="author" class="form-control form-control-sm"
                                    placeholder="Search author..." value="{{ request('author') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">All Categories</option>
                                    @foreach ($getCategories as $cat)
                                        <option value="{{ $cat }}"
                                            {{ request('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary"><i
                                        class="bi bi-funnel me-1"></i>Filter</button>
                                <a href="{{ url('librarian/library/book/list') }}"
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
                                        <th style="width:50px;">#</th>
                                        <th style="width:55px;">Cover</th>
                                        <th>Title / Author</th>
                                        <th>ISBN</th>
                                        <th>Category</th>
                                        <th>Rack</th>
                                        <th>Qty</th>
                                        <th>Available</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $book)
                                        <tr>
                                            <td class="text-muted small">{{ $getRecord->firstItem() + $i }}</td>
                                            <td>
                                                @if ($book->cover_image)
                                                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="rounded"
                                                        style="width:38px;height:50px;object-fit:cover;">
                                                @else
                                                    <div class="rounded bg-secondary bg-opacity-15 d-flex align-items-center justify-content-center"
                                                        style="width:38px;height:50px;">
                                                        <i class="bi bi-book text-secondary"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold small">{{ $book->title }}</div>
                                                <div class="text-muted" style="font-size:.72rem;">{{ $book->author }}</div>
                                            </td>
                                            <td class="small">{{ $book->isbn ?? '—' }}</td>
                                            <td>
                                                @if ($book->category)
                                                    <span class="badge"
                                                        style="font-size:.72rem; background-color:blue; color:white; font-weight:bold;">{{ $book->category }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="small">{{ $book->rack_number ?? '—' }}</td>
                                            <td class="small text-center">{{ $book->quantity }}</td>
                                            <td class="small text-center">
                                                <span
                                                    class="{{ $book->available == 0 ? 'text-danger fw-bold' : 'text-success fw-semibold' }}">
                                                    {{ $book->available }}
                                                </span>
                                            </td>
                                            <td>{!! $book->status_badge !!}</td>
                                            <td>
                                                <a href="{{ url('librarian/library/book/edit/' . $book->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ url('librarian/library/book/delete/' . $book->id) }}"
                                                    class="btn btn-sm btn-outline-danger" title="Delete"
                                                    onclick="return confirm('Delete this book?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-5">
                                                <i class="bi bi-book fs-3 d-block mb-2"></i>
                                                No books found. <a href="{{ url('librarian/library/book/add') }}">Add one
                                                    now</a>.
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
