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
                        {{-- <small class="text-muted">(Total : {{ $getRecord->total() }})</small> --}}
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/class/add') }}" class="btn btn-primary">
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
                    <h3 class="card-title">Subject List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                    <tbody>

                    </tbody>
                </table>

                </div>
                {{-- <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div> --}}
                <!-- Pagination -->
                <div class="card-footer text-end">
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
