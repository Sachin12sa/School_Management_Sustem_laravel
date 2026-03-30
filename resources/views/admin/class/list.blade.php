@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">

                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Class List</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-collection me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ url('admin/section/list') }}" class="btn px-4 shadow-sm text-white fw-semibold"
                            style="background:#7c3aed;">
                            <i class="bi bi-diagram-3-fill me-2"></i>Manage Sections
                        </a>
                        <a href="{{ url('admin/class/add') }}" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add New Class
                        </a>
                    </div>
                </div>

                {{-- Filter Card --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-primary"></i>
                        <h6 class="mb-0 fw-semibold">Filter Classes</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="get" action="">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-building me-1"></i>Class Name
                                    </label>
                                    <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                        placeholder="Search by class name…">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>Created Date
                                    </label>
                                    <input type="date" name="date"
                                        value="{{ request('date') ? date('Y-m-d', strtotime(request('date'))) : '' }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url('admin/class/list') }}" class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
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
                            <i class="bi bi-table me-2 text-primary"></i>All Classes
                        </h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary small">
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
                                        <th>Class Name</th>
                                        <th>Sections</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th class="text-center pe-4" width="280">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $key => $value)
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                        style="width:32px;height:32px;font-size:.85rem;">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                    <span class="fw-semibold text-dark small">{{ $value->name }}</span>
                                                </div>
                                            </td>

                                            {{-- Sections mini-chips --}}
                                            <td>
                                                @if ($value->sections && $value->sections->count())
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach ($value->sections->take(4) as $sec)
                                                            <span class="badge fw-semibold"
                                                                style="background:#ede9fe;color:#7c3aed;font-size:.7rem;">
                                                                {{ $sec->name }}
                                                            </span>
                                                        @endforeach
                                                        @if ($value->sections->count() > 4)
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary"
                                                                style="font-size:.7rem;">
                                                                +{{ $value->sections->count() - 4 }} more
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted small">No sections</span>
                                                @endif
                                            </td>

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
                                                <div
                                                    class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                                    <a href="{{ url('admin/class/students/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-info px-2" title="View Students">
                                                        <i class="bi bi-people-fill me-1"></i>Students
                                                    </a>
                                                    <a href="{{ url('admin/class/sections/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-secondary px-2" title="View Sections"
                                                        style="border-color:#7c3aed;color:#7c3aed;">
                                                        <i class="bi bi-diagram-3-fill me-1"></i>Sections
                                                    </a>
                                                    <a href="{{ url('admin/class/subjects/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-info px-2" title="View Subjects">
                                                        <i class="bi bi-journal-bookmark me-1"></i>Subjects
                                                    </a>
                                                    <a href="{{ url('admin/class/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-2" title="Edit">
                                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                                    </a>
                                                    <a href="{{ url('admin/class/delete/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-danger px-2" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this class?')">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-building d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.3;"></i>
                                                    <div class="fw-semibold small">No classes found</div>
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
                            {{ $getRecord->total() }} classes
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
