@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-collection me-2 text-primary"></i>Fee Groups
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/fee_group/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Add Fee Group
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
                        <span class="fw-semibold">Fee Group List</span>
                        <span class="badge bg-primary ms-auto">{{ $getRecord->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Group Name</th>
                                        <th>Fee Types Included</th>
                                        <th>Description</th>
                                        <th width="90">Status</th>
                                        <th width="120" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $group)
                                        <tr>
                                            <td class="text-muted small">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $group->name }}</div>
                                                <div class="text-muted small">
                                                    {{ $group->items->count() }} fee
                                                    type{{ $group->items->count() !== 1 ? 's' : '' }}
                                                </div>
                                            </td>
                                            <td>
                                                @forelse($group->itemsWithType as $item)
                                                    <span class="badge bg-light text-dark border me-1 mb-1"
                                                        style="font-size:.75rem;">
                                                        {{ $item->feeType->name ?? '—' }}
                                                        <span class="text-success fw-semibold">
                                                            Rs.{{ number_format($item->amount, 2) }}
                                                        </span>
                                                    </span>
                                                @empty
                                                    <span class="text-muted small">—</span>
                                                @endforelse
                                            </td>
                                            <td class="text-muted small">{{ $group->description ?: '—' }}</td>
                                            <td>
                                                @if ($group->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ url('admin/fee_group/edit/' . $group->id) }}"
                                                    class="btn btn-outline-primary btn-sm me-1" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ url('admin/fee_group/delete/' . $group->id) }}"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Delete this fee group?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                No fee groups found. <a href="{{ url('admin/fee_group/add') }}">Create
                                                    one</a>.
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
