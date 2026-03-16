@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-tags-fill me-2 text-primary"></i>Fee Types</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('admin/fee_type/add') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Fee Type
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Fee Name</th>
                                <th>Default Amount</th>
                                <th>Frequency</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="fw-semibold">{{ $row->name }}</td>
                                <td>Rs. {{ number_format($row->amount, 2) }}</td>
                                <td><span class="badge bg-info text-dark">{{ $row->frequency_label }}</span></td>
                                <td>
                                    @if($row->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('admin/fee_type/edit/'.$row->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="{{ url('admin/fee_type/delete/'.$row->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this fee type?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No fee types found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection