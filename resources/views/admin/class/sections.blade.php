@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#ede9fe;color:#7c3aed;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Sections of {{ $getClass->name }}</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-collection me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('section', $getRecord->total()) }} found
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end">
                        <a href="{{ url('admin/section/add') . '?class_id=' . $getClass->id }}"
                            class="btn px-4 shadow-sm text-white fw-semibold" style="background:#7c3aed;">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add Section
                        </a>
                        <a href="{{ url('admin/class/list') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>Back to Classes
                        </a>
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
                            <i class="bi bi-table me-2" style="color:#7c3aed;"></i>
                            All Sections — {{ $getClass->name }}
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary"
                                        style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="60">#</th>
                                        <th>Section Name</th>
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

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-2 d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                        style="width:34px;height:34px;font-size:.85rem;background:#ede9fe;color:#7c3aed;">
                                                        {{ strtoupper(substr($value->name, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-semibold text-dark">Section {{ $value->name }}</span>
                                                </div>
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
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a href="{{ url('admin/section/students/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-info px-2">
                                                        <i class="bi bi-people-fill me-1"></i>Students
                                                    </a>
                                                    <a href="{{ url('admin/section/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-2">
                                                        <i class="bi bi-pencil-fill me-1"></i>Edit
                                                    </a>
                                                    <a href="{{ url('admin/section/delete/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-danger px-2"
                                                        onclick="return confirm('Delete this section?')">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-diagram-3 d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.3;"></i>
                                                    <div class="fw-semibold small">No sections yet for
                                                        {{ $getClass->name }}</div>
                                                    <div style="font-size:.78rem;">
                                                        <a href="{{ url('admin/section/add') }}" style="color:#7c3aed;">Add
                                                            the first section</a>
                                                    </div>
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
                            Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }}
                            of {{ $getRecord->total() }} sections
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
