@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Parent Student List
                        <small class="text-muted">({{$getParent->name}} {{$getParent->last_name}})</small>
                    </h3>
                </div>
      
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-search me-2"></i>
                    Search Parent Student
                </h5>
            </div>

            <div class="card-body">
                <form method="GET" action="">
                    
                    <div class="row g-3">

                        <!-- Student ID -->
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold">Student ID</label>
                            <input type="text"
                                   name="student_id"
                                   value="{{ request('student_id') }}"
                                   class="form-control form-control-sm"
                                   placeholder="ID">
                        </div>

                        <!-- First Name -->
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label fw-semibold">First Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ request('name') }}"
                                   class="form-control form-control-sm"
                                   placeholder="First Name">
                        </div>

                        <!-- Last Name -->
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label fw-semibold">Last Name</label>
                            <input type="text"
                                   name="last_name"
                                   value="{{ request('last_name') }}"
                                   class="form-control form-control-sm"
                                   placeholder="Last Name">
                        </div>

                        <!-- Email -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="text"
                                   name="email"
                                   value="{{ request('email') }}"
                                   class="form-control form-control-sm"
                                   placeholder="Email Address">
                        </div>

                        <!-- Buttons -->
                        <div class="col-lg-1 col-md-6 d-flex align-items-end">
                            <div class="w-100 d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i> Search
                                </button>

                                <a href="{{ url('admin/parent/my-student/'.$parent_id) }}"
                                   class="btn btn-outline-secondary btn-sm">
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
    </div>

    <!-- Content -->
 

<div class="app-content">
    <div class="container-fluid">

        @include('message')


        @if(!empty($getSearchStudent) && $getSearchStudent->count())
                    <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Search Results</h5>
                </div>
                <div class="card-body p-0">
                    @if($getSearchStudent->count())
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>S.N</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Email</th>
                                <th>Parent</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getSearchStudent as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $student->profile_pic ? asset('storage/'.$student->profile_pic) : asset('upload/profile/user.jpg') }}"
                                        class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                </td>
                                <td>{{ $student->name }} {{ $student->last_name }}</td>
                                <td>{{ $student->class_name ?? 'N/A' }}</td>
                                <td>{{ $student->email ?? 'N/A' }}</td>
                                <td>{{ $student->parent_name ?? 'N/A' }}</td>
                                <td>{{ date('d-m-Y H:i A', strtotime($student->created_at)) }}</td>
                                <td>
                                    <a href="{{ url('admin/parent/assign_student_parent/'.$parent_id.'/'.$student->id) }}"
                                    class="btn btn-sm btn-success">Assign</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <p class="text-center my-3">No students found.</p>
                    @endif
                </div>
            </div>
        @endif
            <div class="card mb-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">Assigned Students To Parents</h5>
    </div>
    <div class="card-body p-0">
        @if($getRecord->count())
        <table class="table table-hover table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>S.N</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Email</th>
                    <th>Parent</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($getRecord as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{ $student->profile_pic ? asset('storage/'.$student->profile_pic) : asset('upload/profile/user.jpg') }}"
                             class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                    </td>
                    <td>{{ $student->name }} {{ $student->last_name }}</td>
                    <td>{{ $student->class_name ?? 'N/A' }}</td>
                    <td>{{ $student->email ?? 'N/A' }}</td>
                    <td>{{ $student->parent_name ?? 'N/A' }}</td>
                    <td>{{ date('d-m-Y H:i A', strtotime($student->created_at)) }}</td>
                    <td>
                        <a href="{{ url('admin/parent/assign_student_parent_delete/'.$student->id) }}"
                           class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-center my-3">No assigned students.</p>
        @endif
    </div>
</div>
    </div>
</div>


</main>
@endsection
