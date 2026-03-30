@extends('layouts.app')

@section('content')
    <main class="app-main">

        {{-- Page Header --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-calculator-fill me-2 text-primary"></i>Librarian List
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/librarian/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New Librarian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:50px;">#</th>
                                        <th style="width:60px;">Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Gender</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th style="width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        <tr>
                                            <td class="text-muted small">{{ $i + 1 }}</td>
                                            <td>
                                                <img src="{{ $row->getProfile() }}" alt="{{ $row->name }}"
                                                    class="rounded-circle border"
                                                    style="width:38px;height:38px;object-fit:cover;">
                                            </td>
                                            <td>
                                                <div class="fw-semibold small">{{ $row->name }} {{ $row->middle_name }}
                                                    {{ $row->last_name }}
                                                </div>
                                                @if ($row->qualification)
                                                    <div class="text-muted" style="font-size:.72rem;">
                                                        {{ $row->qualification }}</div>
                                                @endif
                                            </td>
                                            <td class="small">{{ $row->email }}</td>
                                            <td class="small">{{ $row->mobile_number ?? '—' }}</td>
                                            <td class="small">{{ $row->gender ?? '—' }}</td>
                                            <td class="small">
                                                @bs($row->date_of_joining)
                                            </td>
                                            <td>
                                                @if ($row->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('admin/librarian/edit/' . $row->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ url('admin/librarian/delete/' . $row->id) }}"
                                                    class="btn btn-sm btn-outline-danger" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this librarian?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                No librarians found. <a href="{{ url('admin/librarian/add') }}">Add one
                                                    now</a>.
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
