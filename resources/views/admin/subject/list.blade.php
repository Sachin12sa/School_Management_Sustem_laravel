@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Subject List
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/subject/add') }}" class="btn btn-primary">
                        + Add New Subject
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Subject</h3>
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
                                            <label class="form-label" required name="status">Subject Type</label>
                                            <select name="type" class="form-control" id="">
                                            <option value="0">Select Type</option>
                                            <option value="0">Theory</option>
                                            <option value="1">Practical</option>
                                            </select>
                                    
                                        </div>

                 
                                     <div class="col-md-3">
                                        <label class="form-label">Date</label>
                                        <div class="input-group">
                                        <input type="date" id="date" class="form-control" 
                                                value="{{ request('date') ? date('Y-m-d', strtotime(request('date'))) : '' }}">
                                        <span class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                    </div>
                                    
                                    

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/subject/list') }}" class="btn btn-success ms-1">
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
                    <h3 class="card-title">Subject List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Subject Name</th>
                        <th>Status</th>
                        <th> Subject Type</th>
                   
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                    <tbody>
                    @foreach($getRecord as $key => $value)
                        <tr>
                            <td>{{ $getRecord->firstItem() + $key }}</td>
                            <td>{{ $value->name }}</td>
                        <td>
                            <span class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $value->status == 0 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                            <td>{{ $value->type ==0 ? 'Theory':'Practical' }}</td>
                            <td>{{ $value->created_by_name }}</td>
                            <td>{{ $value->created_at->format('d-m-Y h:i A') }}</td>
                            <td>
                                <a href="{{ url('admin/subject/edit/'.$value->id) }}"
                                class="btn btn-sm btn-primary">
                                    Edit
                                </a>

                                <a href="{{ url('admin/subject/delete/'.$value->id) }}"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this subject?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        
                    @endforeach
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
