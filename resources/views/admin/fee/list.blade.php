@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-cash-coin me-2 text-primary"></i>Student Fees</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('admin/fee/add') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Assign Fee
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

            {{-- Filter Bar --}}
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body py-2 px-3">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Student</label>
                            <select name="student_id" class="form-select form-select-sm">
                                <option value="">All Students</option>
                                @foreach($getStudents as $s)
                                    <option value="{{ $s->id }}" {{ request('student_id')==$s->id?'selected':'' }}>{{ $s->name }} {{ $s->last_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Student</label>
                            <select name="Class_id" class="form-select form-select-sm">
                                <option value="">All Classes</option>
                                @foreach($getClasses as $s)
                                    <option value="{{ $s->id }}" {{ request('Class_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Fee Type</label>
                            <select name="fee_type_id" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                @foreach($getFeeTypes as $ft)
                                    <option value="{{ $ft->id }}" {{ request('fee_type_id')==$ft->id?'selected':'' }}>{{ $ft->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                                <option value="partial" {{ request('status')=='partial'?'selected':'' }}>Partial</option>
                                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ url('admin/fee/list') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Class Name</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $i => $row)
                                <tr>
                                    <td>{{ $getRecord->firstItem() + $i }}</td>
                                    <td>
                                        <div class="fw-semibold small">{{ $row->student_name }} {{ $row->student_last_name }}</div>
                                        <div class="text-muted" style="font-size:.72rem;">{{ $row->admission_number }}</div>
                                    </td>
                                    <td>{{ $row->class_id }}</td>
                                    <td>{{ $row->fee_type_name }}</td>
                                    <td>Rs. {{ number_format($row->amount, 2) }}</td>
                                    <td class="text-success fw-semibold">Rs. {{ number_format($row->paid_amount, 2) }}</td>
                                    <td class="{{ $row->amount - $row->paid_amount > 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                        Rs. {{ number_format($row->amount - $row->paid_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="{{ $row->due_date < now()->toDateString() && $row->status != 'paid' ? 'text-danger fw-semibold' : '' }}">
                                            {{ $row->due_date }}
                                        </span>
                                    </td>
                                    <td>{!! $row->status_badge !!}</td>
                                    <td>
                                        @if($row->status != 'paid')
                                            <a href="{{ url('admin/fee/collect/'.$row->id) }}" class="btn btn-sm btn-success" title="Collect Payment"><i class="bi bi-cash"></i></a>
                                        @endif
                                        <a href="{{ url('admin/fee/edit/'.$row->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="{{ url('admin/fee/delete/'.$row->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="9" class="text-center text-muted py-4">No fee records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($getRecord->hasPages())
                    <div class="px-3 py-2">{{ $getRecord->withQueryString()->links() }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</main>
@endsection