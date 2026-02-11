@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Teacher List
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/teacher/add') }}" class="btn btn-primary">
                        + Add New Teacher
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Teacher</h3>
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
                                        <label class="form-label">Mobile Number</label>
                                        <input
                                            type="text"
                                            name="mobile_number"
                                            value="{{ request('mobile_number') }}"
                                            class="form-control"
                                            placeholder="Enter mobile number"
                                        />
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/teacher/list') }}" class="btn btn-success ms-1">
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
                    <h3 class="card-title mb-0">Teacher List</h3>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0" style="min-width: 1500px;">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th>#</th>
                                    <th>Profile Pic</th>
                                    <th>Teacher Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th style="min-width: 100px;">Date of Birth</th>
                                    <th style="min-width: 100px;">Date of Joining</th>
                                    <th>Mobile Number</th>
                                    <th>Marital Status</th>
                                    <th style="min-width: 150px;">Current Address</th>
                                    <th style="min-width: 150px;">Permanent Address</th>
                                    <th>Qualification</th>
                                    <th>Work Exp.</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th style="min-width: 100px;">Created Date</th>
                                    <th class="text-end" width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td>{{ $getRecord->firstItem() + $key }}</td>
                                      
                                        <td>
                                            @if(!empty($value->profile_pic))
                                                <img src="{{ asset('storage/'.$value->profile_pic) }}" alt="user" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('upload/profile/user.jpg') }}" alt="default" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee;">
                                            @endif
                                        </td>

                                        <td class="fw-bold text-dark">{{ $value->name }} {{ $value->last_name }}</td>
                                        
                                        <td>{{ $value->email }}</td>
                                        
                                        <td>{{ $value->gender }}</td>

                                        <td>
                                            @if(!empty($value->date_of_birth))
                                                {{ date('d-m-Y', strtotime($value->date_of_birth)) }}
                                            @endif
                                        </td>

                                        <td>
                                            @if(!empty($value->admission_date))
                                                {{ date('d-m-Y', strtotime($value->admission_date)) }}
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if(!empty($value->mobile_number))
                                                {{ $value->mobile_number }}
                                            @else
                                                --
                                            @endif
                                        </td>

                                        <td>{{ $value->marital_status }}</td>
                                        
                                        <td>{{ $value->address }}</td> <td>{{ $value->permanent_address }}</td>

                                        <td>{{ $value->qualification }}</td>

                                        <td>{{ $value->work_experience }}</td>
                                        
                                        <td>{{ $value->note }}</td>

                                        <td>
                                            @if($value->status == 0)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>

                                        <td class="text-end">
                                            <a href="{{ url('admin/teacher/edit/' . $value->id) }}" class="btn btn-icon btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                            <a href="{{ url('admin/teacher/delete/' . $value->id) }}"
                                           class="btn btn-icon btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this student?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                                                <p>No teachers found.</p>
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