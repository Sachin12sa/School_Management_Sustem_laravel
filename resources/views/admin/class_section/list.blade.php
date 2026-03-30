@extends('layouts.app')

@section('content')
    <main class="app-main">

        {{-- ── Page Header ─────────────────────────────────────────────────── --}}
        <div class="app-content-header">
            <div class="container-fluid">

                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-purple bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#ede9fe;color:#7c3aed;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Class Section List</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-collection me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/section/add') }}" class="btn px-4 shadow-sm text-white fw-semibold"
                            style="background:#7c3aed;">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add New Section
                        </a>
                    </div>
                </div>

                {{-- ── Filter Card ──────────────────────────────────────────────── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill" style="color:#7c3aed;"></i>
                        <h6 class="mb-0 fw-semibold">Filter Sections</h6>
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
                                        <i class="bi bi-diagram-3 me-1"></i>Section Name
                                    </label>
                                    <input type="text" name="section_name" value="{{ request('section_name') }}"
                                        class="form-control" placeholder="e.g. A, B, Science…">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>Created Date
                                    </label>
                                    <input type="date" name="date"
                                        value="{{ request('date') ? date('Y-m-d', strtotime(request('date'))) : '' }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn flex-fill text-white fw-semibold"
                                        style="background:#7c3aed;">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url('admin/section/list') }}" class="btn btn-outline-secondary flex-fill">
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
                            <i class="bi bi-table me-2" style="color:#7c3aed;"></i>All Sections
                        </h6>
                        <span class="badge bg-opacity-10 small px-3 py-2" style="background:#ede9fe;color:#7c3aed;">
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
                                        <th>Section Name</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th class="text-center pe-4" width="200">Actions</th>
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
                                            </td>

                                            {{-- Section Name --}}
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-2 d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                        style="width:32px;height:32px;font-size:.8rem;background:#ede9fe;color:#7c3aed;">

                                                        {{ strtoupper(substr($value->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Student count --}}
                                            <td>
                                                <a href="{{ url('admin/section/students/' . $value->id) }}"
                                                    class="badge bg-info bg-opacity-10 text-info px-2 py-1 text-decoration-none">
                                                    <i class="bi bi-people-fill me-1"></i>
                                                    {{ $value->students_count ?? '—' }} students
                                                </a>
                                            </td>

                                            {{-- Status --}}
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
                                                    <a href="{{ url('admin/section/students/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-info px-2" title="View Students">
                                                        <i class="bi bi-people-fill me-1"></i>Students
                                                    </a>
                                                    <a href="{{ url('admin/section/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-2" title="Edit">
                                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                                    </a>
                                                    <a href="{{ url('admin/section/delete/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-danger px-2" title="Delete"
                                                        onclick="return confirm('Delete this section? Students assigned to it will lose their section assignment.')">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-diagram-3 d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.3;"></i>
                                                    <div class="fw-semibold small">No sections found</div>
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
                            {{ $getRecord->total() }} sections
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
