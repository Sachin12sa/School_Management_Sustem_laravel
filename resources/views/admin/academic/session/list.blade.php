@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#fef3c7;color:#d97706;">
                                <i class="bi bi-calendar3-range-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Academic Sessions</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-collection me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('session', $getRecord->total()) }}
                                    @if ($getCurrent)
                                        · Active: <strong class="text-success">{{ $getCurrent->name }}</strong>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ url('admin/academic/setup') }}" class="btn btn-success px-4 shadow-sm fw-semibold">
                            <i class="bi bi-arrow-up-circle-fill me-2"></i>Academic Upgrade
                        </a>
                        <a href="{{ url('admin/academic_session/add') }}" class="btn px-4 shadow-sm fw-semibold text-white"
                            style="background:#d97706;">
                            <i class="bi bi-plus-circle-fill me-2"></i>New Session
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary"
                                        style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="60">#</th>
                                        <th>Session</th>
                                        <th>Duration</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th class="text-center pe-4" width="220">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $key => $value)
                                        <tr class="{{ $value->is_current ? 'table-success bg-opacity-25' : '' }}">
                                            <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-2 d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                        style="width:36px;height:36px;font-size:.78rem;background:#fef3c7;color:#d97706;">
                                                        {{ substr($value->name, -2) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ $value->name }}</div>
                                                        @if ($value->label)
                                                            <div class="text-muted" style="font-size:.75rem;">
                                                                {{ $value->label }}</div>
                                                        @endif
                                                    </div>
                                                    @if ($value->is_current)
                                                        <span class="badge bg-success ms-1">Current</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="small text-muted">
                                                @if ($value->start_date && $value->end_date)
                                                    {{ date('d M Y', strtotime($value->start_date)) }}
                                                    — {{ date('d M Y', strtotime($value->end_date)) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                @php $count = \App\Models\AcademicSessionModel::getStudentCount($value->id); @endphp
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                    <i class="bi bi-people me-1"></i>{{ $count }}
                                                </span>
                                            </td>

                                            <td>
                                                @if ($value->status == 0)
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">Active</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-1">Archived</span>
                                                @endif
                                            </td>

                                            <td class="small">{{ $value->created_by_name ?? '—' }}</td>

                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    @if (!$value->is_current)
                                                        <a href="{{ url('admin/academic_session/set_current/' . $value->id) }}"
                                                            class="btn btn-sm btn-outline-success px-2"
                                                            onclick="return confirm('Set {{ $value->name }} as the active session?')"
                                                            title="Set as Current">
                                                            <i class="bi bi-check2-circle me-1"></i>Activate
                                                        </a>
                                                    @endif
                                                    <a href="{{ url('admin/academic_session/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-2">
                                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                                    </a>
                                                    @if (!$value->is_current)
                                                        <a href="{{ url('admin/academic_session/delete/' . $value->id) }}"
                                                            class="btn btn-sm btn-outline-danger px-2"
                                                            onclick="return confirm('Delete this session?')">
                                                            <i class="bi bi-trash3-fill"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="bi bi-calendar-x d-block mb-2"
                                                    style="font-size:2.5rem;opacity:.3;"></i>
                                                <div class="fw-semibold small">No academic sessions yet</div>
                                                <a href="{{ url('admin/academic_session/add') }}"
                                                    class="btn btn-sm btn-warning mt-2">
                                                    Create First Session
                                                </a>
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
                            {{ $getRecord->total() }}
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
