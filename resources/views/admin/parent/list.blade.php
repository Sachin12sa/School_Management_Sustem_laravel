@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Parent List
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/parent/add') }}" class="btn btn-primary">
                        + Add New Parent
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Parent</h3>
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
                                        <label class="form-label">Phone Number</label>
                                        <input
                                            type="text"
                                            name="mobile_number"
                                            value="{{ request('mobile_number') }}"
                                            class="form-control"
                                            placeholder="Enter Phone Number"
                                        />
                                    </div>
                              
                                    
                                    

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/parent/list') }}" class="btn btn-success ms-1">
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
                    <h3 class="card-title">Parent List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Mobile Number</th>
                                <th>Gender</th>
                                <th>Occupation</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $key => $value)
                                <tr>
                                    <td>{{ $getRecord->firstItem() + $key }}</td>
                                    {{-- profile --}}
                                            <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                @if(!empty($value->profile_pic))
                                                    <img src="{{ asset('storage/'.$value->profile_pic) }}" alt="user" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('upload/profile/user.jpg') }}" alt="default" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold">{{ $value->name }} {{ $value->last_name }}</h6>
                                                <small class="text-muted">{{ $value->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$value->mobile_number}}</td>
                                    <td>{{ $value->gender }}</td>
                                     <td>{{$value->occupation}}</td>
                                      <td>{{$value->address}}</td>
                                      {{-- status --}}
                                <td>
                                        @if($value->status == 0)
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">Inactive</span>
                                        @endif
                                    </td>
                                 <td>{{ date('d-m-Y H:i A',strtotime($value->created_at)) }}</td>
                                    <td  class="d-flex justify-content-center gap-3">
                                        
                                        <a href="{{ url('admin/parent/edit/' . $value->id) }}" class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                        <a href="{{ url('admin/parent/delete/' . $value->id) }}"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this admin?')">
                                            Delete
                                        </a>
                                        <a href="{{ url('admin/parent/my-student/' . $value->id) }}" class="btn btn-sm btn-primary">
                                            My Student
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        No Parent found.
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
