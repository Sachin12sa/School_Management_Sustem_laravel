@extends('layouts.app')

@section('content')
    <main class="app-main">

        {{-- ── Page Header ─────────────────────────────────────────────────── --}}
        <div class="app-content-header">
            <div class="container-fluid">

                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Assign Class Teacher</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-collection me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/assign_class_teacher/add') }}" class="btn btn-success px-4 shadow-sm">
                            <i class="bi bi-plus-circle-fill me-2"></i>Assign Class Teacher
                        </a>
                    </div>
                </div>

                {{-- ── Search / Filter Card ─────────────────────────────────────── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-success"></i>
                        <h6 class="mb-0 fw-semibold">Filter Assignments</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="get" action="">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-building me-1"></i>Class Name
                                    </label>
                                    <input type="text" name="class_name" value="{{ request('class_name') }}"
                                        class="form-control" placeholder="Search by class…">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-person me-1"></i>Teacher First Name
                                    </label>
                                    <input type="text" name="teacher_name" value="{{ request('teacher_name') }}"
                                        class="form-control" placeholder="Search by first name…">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-person me-1"></i>Teacher Last Name
                                    </label>
                                    <input type="text" name="teacher_last_name"
                                        value="{{ request('teacher_last_name') }}" class="form-control"
                                        placeholder="Search by last name…">
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-success flex-fill">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url('admin/assign_class_teacher/list') }}"
                                        class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </a>
                                </div>

                            </div>
                        </form>
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
                            <i class="bi bi-table me-2 text-success"></i>All Class–Teacher Assignments
                        </h6>
                        <span class="badge bg-success bg-opacity-10 text-success small">
                            Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary"
                                        style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="60">#</th>
                                        <th>Class</th>
                                        <th style="min-width:200px;">Teacher</th>
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
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-semibold">
                                                    <i class="bi bi-building me-1"></i>{{ $value->class_name }}
                                                </span>
                                                <br>
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-semibold">
                                                    <i class="bi bi-diagram-3 me-1"></i>Section: {{ $value->section_name }}
                                                </span>
                                            </td>

                                            {{-- Teacher --}}
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                        style="width:34px;height:34px;font-size:.8rem;">
                                                        {{ strtoupper(substr($value->teacher_name ?? '?', 0, 1)) }}{{ strtoupper(substr($value->teacher_last_name ?? '', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark small">
                                                            {{ $value->teacher_name }} {{ $value->teacher_last_name }}
                                                        </div>
                                                        <div class="text-muted" style="font-size:.72rem;">Class Teacher
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Status — fixed: was using subject_type for label --}}
                                            <td>
                                                @if ($value->status == 0)
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.45rem;vertical-align:middle;"></i>Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.45rem;vertical-align:middle;"></i>Inactive
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
                                                <div class="small text-dark">{{ $value->created_at->format('d M Y') }}
                                                </div>
                                                <div class="text-muted" style="font-size:.72rem;">
                                                    {{ $value->created_at->format('h:i A') }}</div>
                                            </td>

                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ url('admin/assign_class_teacher/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-3" title="Edit All">
                                                        <i class="bi bi-pencil-fill me-1"></i>Edit All
                                                    </a>
                                                    <a href="{{ url('admin/assign_class_teacher/edit_single/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-secondary px-2" title="Edit Single">
                                                        <i class="bi bi-pencil me-1"></i>Single
                                                    </a>
                                                    <a href="{{ url('admin/assign_class_teacher/delete/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-danger px-2" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this assignment?')">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-person-x-fill d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.3;"></i>
                                                    <div class="fw-semibold small">No assignments found</div>
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
                            Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of
                            {{ $getRecord->total() }} assignments
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
