@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Class List
                        {{-- <small class="text-muted">(Total : {{ $getRecord->total() }})</small> --}}
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/class/add') }}" class="btn btn-primary">
                        + Add New Class
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Class</h3>
                        </div>

                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <div class="col-md-3">
                                        <label class="form-label">Name</label>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{ request('name') }}"
                                            class="form-control"
                                            placeholder="Enter name"
                                        />
                                    </div>

                 
                                     <div class="col-md-3">
                                        <label class="form-label">Date</label>
                                        <input
                                            type="date"
                                            name="date"
                                            value="{{ request('date') }}"
                                            class="form-control"
                                            
                                        />
                                    </div>
                                    
                                    

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/class/list') }}" class="btn btn-success ms-1">
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Class List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                    <tbody>
                        @forelse($getRecord as $key => $value)
                            <tr>
                                {{-- Serial number (pagination-safe) --}}
                                <td>{{ $getRecord->firstItem() + $key }}</td>

                                <td>{{ $value->name }}</td>

                                {{-- Status badge --}}
                                <td>
                                    @if($value->status == 0)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                {{-- Created By --}}
                                <td>{{ $value->created_by_name ?? 'System' }}</td>

                                {{-- Created Date --}}
                                <td>{{ $value->created_at->format('d-m-Y h:i A') }}</td>

                                {{-- Actions --}}
                                <td>
                                    <a href="{{ url('admin/class/edit/' . $value->id) }}"
                                    class="btn btn-sm btn-primary">
                                        Edit
                                    </a>

                                    <a href="{{ url('admin/class/delete/' . $value->id) }}"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this class?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    No admins found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                </div>
                <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
                <!-- Pagination -->
                <div class="card-footer text-end">
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
