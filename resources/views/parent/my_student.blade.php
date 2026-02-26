@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        My Student
                    </h3>
                </div>
      
            </div>
        </div>
    </div>

    <!-- Content -->
 

<div class="app-content">
    <div class="container-fluid">

        @include('message')

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
                    <th style="min-width: 250px;">Student Name</th>
                    <th>Admission No</th>
                    <th>Admission Date</th>
                    <th>Roll Number</th>
                    <th>Class</th>
                    <th>Gender</th>
                    <th>Date Of Birth</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Created Date</th>
                    <th>Action </th>
                </tr>
                         <tbody>
                            @forelse($getRecord as $key => $value)
                                <tr>
                                    <td>{{ $value-> id }}</td>
                                  
                                    
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
                                      <td>{{ $value->roll_number }}</td> 
                                    
                                    <td>{{ $value->class_name }}</td> 
                                    
                                    <td>{{ $value->gender }}</td>
                                    <td>{{ $value->date_of_birth }}</td>
                                    <td>{{ $value->height }}</td>
                                    <td>{{ $value->weight }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ date('d-m-Y', strtotime($value->created_at)) }}</span>
                                            <small class="text-muted">{{ date('h:i A', strtotime($value->created_at)) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                         <a href="{{ url('parent/my_student/subject/'.$value->id) }}"
                                            class="btn btn-sm btn-success">
                                                View Subject
                                            </a>
                                             <a href="{{ url('parent/my_student/exam_timetable/'.$value->id) }}"
                                            class="btn btn-sm btn-primary">
                                                View Timetable
                                            </a>
                                            <a href="{{ url('parent/my_student/my_exam_result/'.$value->id) }}"
                                            class="btn btn-sm btn-primary">
                                                 Result
                                            </a>
                                            <a href="{{ url('parent/my_student/calendar/'.$value->id) }}"
                                            class="btn btn-sm btn-warning">
                                                 Calendar
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
        @else
            <p class="text-center my-3">No assigned students.</p>
        @endif
    </div>
</div>
    </div>
</div>


</main>
@endsection
