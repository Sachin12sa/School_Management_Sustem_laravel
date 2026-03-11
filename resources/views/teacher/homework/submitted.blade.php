@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">Submitted Homework List</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Search Submissions</h3>
                </div>
                <form method="GET" action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Student Name</label>
                                <input type="text" class="form-control" name="student_name" value="{{ Request::get('student_name') }}" placeholder="First or Last Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Submitted Date</label>
                                <input type="date" class="form-control" name="created_at" value="{{ Request::get('created_at') }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label style="display: block;">&nbsp;</label>
                                <button class="btn btn-primary" type="submit">Search</button>
                                <a href="{{ url(Request::url()) }}" class="btn btn-success">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Submissions</h3>
                </div>
                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Student Name</th>
                                <th>Submitted Document</th>
                                <th>Student Description</th>
                                <th>Submitted Date</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->student_name }} {{ $value->student_last_name }}</td>
                                    
                                    {{-- Student's File --}}
                                    <td>
                                        @if(!empty($value->document_file))
                                            <a href="{{ asset('upload/homework/'.$value->document_file) }}" class="btn btn-sm btn-outline-primary" target="_blank">View File</a>
                                            <a href="{{ asset('upload/homework/'.$value->document_file) }}" class="btn btn-sm btn-primary" download=""><i class="fas fa-download"></i></a>
                                        @else
                                            <span class="badge bg-secondary">No File</span>
                                        @endif
                                    </td>

                                    <td>{!! $value->description !!}</td>
                                    <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($value->getHomework->submission_date)) }}</td>

                                    {{-- Status Logic --}}
                                    <td>
                                     
    @php
        $deadline = \Carbon\Carbon::parse($value->getHomework->submission_date)->endOfDay();
        $submittedAt = \Carbon\Carbon::parse($value->created_at);
    @endphp

    @if($submittedAt->gt($deadline))
        @php $daysLate = $deadline->diffInDays($submittedAt); @endphp
        <span class="badge bg-danger">Late </span>
    @else
        <span class="badge bg-success">On Time</span>
    @endif
</td>
   
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No student has submitted this homework yet.</td>
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