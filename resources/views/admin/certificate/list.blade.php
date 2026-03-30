@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-patch-check me-2 text-primary"></i>Certificate Templates
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/certificate/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Add Certificate
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul text-primary"></i>
                        <span class="fw-semibold">Certificate List</span>
                        <span class="badge bg-primary ms-auto">{{ $getRecord->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Certificate Name</th>
                                        <th>Applicable User</th>
                                        <th>Page Layout</th>
                                        <th>Background Image</th>
                                        <th>Created At</th>
                                        <th width="130" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        <tr>
                                            <td class="text-muted small">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $row->name }}</div>
                                            </td>
                                            <td>
                                                @if ($row->applicable_user === 'student')
                                                    <span class="badge bg-info text-dark">
                                                        <i class="bi bi-mortarboard me-1"></i>Student
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-person-badge me-1"></i>Employee
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="small text-muted">{{ $row->pageLayoutLabel }}</td>
                                            <td>
                                                @if ($row->background_image)
                                                    <img src="{{ asset('storage/' . $row->background_image) }}"
                                                        alt="Background"
                                                        style="width:70px;height:50px;object-fit:cover;border-radius:4px;border:1px solid #e5e7eb;">
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="small text-muted">
                                                {{ $row->created_at ? $row->created_at->format('d.M.Y') : '—' }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ url('admin/certificate/edit/' . $row->id) }}"
                                                    class="btn btn-outline-primary btn-sm me-1" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ url('admin/certificate/delete/' . $row->id) }}"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Delete this certificate template?')"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                No certificate templates found.
                                                <a href="{{ url('admin/certificate/add') }}">Create one</a>.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
