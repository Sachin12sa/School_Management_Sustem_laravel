@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Admin List
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/admin/add') }}" class="btn btn-primary">
                        + Add New Admin
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Admin</h3>
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
                                        <label class="form-label">Email</label>
                                        <input
                                            type="text"
                                            name="email"
                                            value="{{ request('email') }}"
                                            class="form-control"
                                            placeholder="Enter email"
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
                                        <a href="{{ url('admin/admin/list') }}" class="btn btn-success ms-1">
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
                    <h3 class="card-title">Admin List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created Date</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $key => $value)
                                <tr>
                                    <td>{{ $getRecord->firstItem() + $key }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ date('d-m-Y H:i A',strtotime($value->created_at)) }}</td>
                                    <td>
                                        <a href="{{ url('admin/admin/edit/' . $value->id) }}" class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                        <a href="{{ url('admin/admin/delete/' . $value->id) }}"
                                           class="btn btn-icon btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this student?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        No admins found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
