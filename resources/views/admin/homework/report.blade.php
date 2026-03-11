@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                      Submitted Home Work Report
                        <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Search Submitted Home Work Report</h3>
                </div>
                <form method="GET" action="">
                    <div class="card-body">
                        <div class="row">

                        <div class="form-group col-md-3">
                            <label>Student Name</label>
                            <input type="text" class="form-control" name="student_name"
                                value="{{ Request::get('student_name') }}"
                                placeholder="Enter student name">
                        </div>
                        <div class="form-group col-md-3">
                        <label>Class</label>
                        <select class="form-control" name="class_id">
                            <option value="">Select Class</option>
                            @foreach($getClass as $class)
                                <option value="{{ $class->id }}"
                                    {{ Request::get('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                        <div class="form-group col-md-3">
                            <label>Subject</label>
                            <input type="text" class="form-control" name="subject_name"
                                value="{{ Request::get('subject_name') }}"
                                placeholder="Subject Name">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Homework Date</label>
                            <input type="date" class="form-control" name="from_homework_date"
                                value="{{ Request::get('from_homework_date') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Submission Date</label>
                            <input type="date" class="form-control" name="from_submission_date"
                                value="{{ Request::get('from_submission_date') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label style="display:block;">&nbsp;</label>
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>

                            <a href="{{ url('admin/homework/homework_report') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>

                    </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Submitted Home Work Report List</h3>
                </div>

                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Homework Date</th>
                                <th>Submission Date</th>
                                <th>Description</th>
                                <th>Teacher Document</th>
                                <th>Student Document</th>
                                <th>Submitted Description</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->first_name }} {{ $value->last_name }}</td>
                                    <td>{{ $value->class_name }}</td>
                                    <td>{{ $value->subject_name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($value->getHomework->homework_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($value->getHomework->submission_date)) }}</td>
                                    <td>{!! $value->getHomework->description !!}</td> 
                                    
                                    {{-- Teacher's Original Document --}}
                                    <td>
                                        @if(!empty($value->getHomework->document_file))
                                            <a href="{{ asset('upload/homework/'.$value->getHomework->document_file) }}" class="btn btn-sm btn-outline-primary" target="_blank">View</a>
                                            <a href="{{ asset('upload/homework/'.$value->getHomework->document_file) }}" class="btn btn-sm btn-primary" download=""><i class="fas fa-download"></i></a>
                                        @else
                                            <span class="badge bg-secondary">No File</span>
                                        @endif
                                    </td>

                                    {{-- Student's Submitted Document --}}
                                    <td>
                                        @if(!empty($value->document_file))
                                            <a href="{{ asset('upload/homework/'.$value->document_file) }}" class="btn btn-sm btn-outline-info" target="_blank">View</a>
                                            <a href="{{ asset('upload/homework/'.$value->document_file) }}" class="btn btn-sm btn-info text-white" download=""><i class="fas fa-download"></i></a>
                                        @else
                                            <span class="badge bg-secondary">No File</span>
                                        @endif
                                    </td>

                                    <td>{!! $value->description !!}</td>
                                    <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>                                       
                                    
                                    <td style="min-width: 150px;">
                                        @php
                                            // 1. Setup the dates
                                            $deadline = \Carbon\Carbon::parse($value->getHomework->submission_date)->endOfDay();
                                            $submittedAt = \Carbon\Carbon::parse($value->created_at);
                                            $now = \Carbon\Carbon::now();
                                            
                                            // 2. Determine if the deadline has passed relative to 'now'
                                            $isPastDeadline = $now->gt($deadline);
                                            
                                            // 3. Determine if the specific submission was late
                                            $wasLate = $submittedAt->gt($deadline);
                                        @endphp

                                        {{-- Status Badges --}}
                                    <div class="mb-2">
                                        @if($wasLate)
                                            @php 
                                                $daysLate = ceil($deadline->diffInDays($submittedAt, false));
                                            @endphp
                                            <span class="badge bg-danger">
                                                Late by {{ $daysLate }} {{ $daysLate == 1 ? 'Day' : 'Days' }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">On Time</span>
                                        @endif
                                    </div>

                                        
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No Homework Found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection