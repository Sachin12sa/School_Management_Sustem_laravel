@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;background:rgba(111,66,193,.1);color:#6f42c1;">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Exam List</h4>
                            <span class="text-muted small">
                                <i class="bi bi-collection me-1"></i>{{ $totalExam }} {{ Str::plural('exam', $totalExam) }} total
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/examination/exam/add') }}" class="btn px-4 shadow-sm fw-semibold text-white"
                       style="background:#6f42c1;">
                        <i class="bi bi-plus-circle-fill me-2"></i>Add New Exam
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill" style="color:#6f42c1;"></i>
                    <h6 class="mb-0 fw-semibold">Filter Exams</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="get" action="">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-mortarboard me-1"></i>Exam Name
                                </label>
                                <input type="text" name="name"
                                       value="{{ request('name') }}"
                                       class="form-control" placeholder="Search by name…">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-chat-left-text me-1"></i>Note
                                </label>
                                <input type="text" name="note"
                                       value="{{ request('note') }}"
                                       class="form-control" placeholder="Search by note…">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Date
                                </label>
                                <input type="date" name="date"
                                       value="{{ request('date') ? date('Y-m-d', strtotime(request('date'))) : '' }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn flex-fill fw-semibold text-white" style="background:#6f42c1;">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('admin/examination/exam/list') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2" style="color:#6f42c1;"></i>All Exams
                    </h6>
                    <span class="badge" style="background:rgba(111,66,193,.1);color:#6f42c1;">
                        Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Exam Name</th>
                                    <th>Note</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th class="text-center pe-4" width="160">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:30px;height:30px;font-size:.8rem;background:rgba(111,66,193,.1);color:#6f42c1;">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                </div>
                                                <span class="fw-semibold small text-dark">{{ $value->name }}</span>
                                            </div>
                                        </td>

                                        <td class="text-muted small" style="max-width:200px;">
                                            {{ Str::limit($value->note, 60, '…') ?: '—' }}
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-circle text-muted"></i>
                                                <span class="small">{{ $value->created_name }} {{ $value->created_last_name }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ $value->created_at->format('d M Y') }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ $value->created_at->format('h:i A') }}</div>
                                        </td>

                                        <td class="text-center pe-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <a href="{{ url('admin/examination/exam/edit/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-primary px-3">
                                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                                </a>
                                                <a href="{{ url('admin/examination/exam/delete/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-danger px-2"
                                                   onclick="return confirm('Delete this exam?')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="bi bi-mortarboard d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No exams found</div>
                                            <div class="text-muted" style="font-size:.78rem;">Add a new exam to get started.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="text-muted small">
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }} exams
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

</main>
@endsection