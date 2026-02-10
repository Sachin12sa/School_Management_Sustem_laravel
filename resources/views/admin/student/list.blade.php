@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Student List
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/student/add') }}" class="btn btn-primary">
                        + Add New Student
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Student</h3>
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
                                        <label class="form-label">Admission Number</label>
                                        <input
                                            type="text"
                                            name="admission_number"
                                            value="{{ request('admission_number') }}"
                                            class="form-control"
                                            placeholder="Enter admission number"
                                        />
                                    </div>
                                    

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/student/list') }}" class="btn btn-success ms-1">
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

    <div class="app-content">
    <div class="container-fluid">
        @include('message')

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h3 class="card-title mb-0">Student List</h3>
                <a href="{{ url('admin/student/add') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th>S.N</th>
                                <th style="min-width: 250px;">Student Name</th>
                               
                                <th>Admission No</th>
                                 <th>Admission Date</th>
                                <th>Class</th>
                                <th>Gender</th>
                                <th>Parent/Contact</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th class="text-end" width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $key => $value)
                                <tr>
                                    <td>{{ $getRecord->firstItem() + $key }}</td>
                                  
                                    
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

                                    <td class="fw-bold text-dark">{{ $value->admission_number }}</td>
                                      <td>{{ $value->admission_date }}</td> 
                                    
                                    <td>{{ $value->class_name }}</td> 
                                    
                                    <td>{{ $value->gender }}</td>
                                    
                                    <td>
                                        @if(!empty($value->mobile_number))
                                            {{ $value->mobile_number }}
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($value->status == 0)
                                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Active</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ date('d-m-Y', strtotime($value->created_at)) }}</span>
                                            <small class="text-muted">{{ date('h:i A', strtotime($value->created_at)) }}</small>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ url('admin/student/edit/' . $value->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ url('admin/student/delete/' . $value->id) }}"
                                           class="btn btn-icon btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this student?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                                            <p>No students found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


</main>
@endsection
