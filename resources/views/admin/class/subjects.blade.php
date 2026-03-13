@extends('layouts.app')

@section('content')
<main class="app-main">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Assign Subject To {{ $getClass->name }}</h4>
                            <span class="text-muted small">
                                <i class="bi bi-collection me-1"></i>
                                {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/class/assign_subject/' . $getClass->id) }}" class="btn btn-info text-white px-4 shadow-sm">
                        <i class="bi bi-plus-circle-fill me-2"></i>Assign New Subject
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Table ────────────────────────────────────────────────────────── --}}
    <div class="app-content">
        <div class="container-fluid">

            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2 text-info"></i>All Assigned Subjects
                    </h6>
                    <span class="badge bg-info bg-opacity-10 text-info small">
                        Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Subject Type</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th class="text-center pe-4" width="220">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                        {{-- Class --}}
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-semibold">
                                                <i class="bi bi-building me-1"></i>{{ $value->class_name }}
                                            </span>
                                        </td>

                                        {{-- Subject Name --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-2 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:30px;height:30px;font-size:.8rem;">
                                                    <i class="bi bi-journal-text"></i>
                                                </div>
                                                <span class="fw-semibold text-dark small">{{ $value->subject_name }}</span>
                                            </div>
                                        </td>

                                        {{-- Subject Type --}}
                                        <td>
                                            @if($value->subject_type == 0)
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                                    <i class="bi bi-book me-1"></i>Theory
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">
                                                    <i class="bi bi-eyedropper me-1"></i>Practical
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Status — fixed: was using subject_type for label --}}
                                        <td>
                                            @if($value->status == 0)
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Active
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Inactive
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-circle text-muted"></i>
                                                <span class="small">{{ $value->created_by_name ?? 'System' }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ $value->created_at->format('d M Y') }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ $value->created_at->format('h:i A') }}</div>
                                        </td>

                                        <td class="text-center pe-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <a href="{{ url('admin/class/assign_subject/' . $getClass->id) }}" 
                                                   class="btn btn-sm btn-outline-primary px-3"
                                                   title="Edit All">
                                                    <i class="bi bi-pencil-fill me-1"></i>Edit All
                                                </a>
                                          
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-journal-x d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                                                <div class="fw-semibold small">No assigned subjects found</div>
                                                <div style="font-size:.78rem;">Try adjusting your search filters.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="text-muted small">
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }} assignments
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

</main>
@endsection