@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                     My Submitted Home Work
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
                    <h3 class="card-title">Search Submitted Home Work</h3>
                </div>
                <form method="GET" action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Subject</label>
                                <input type="text" class="form-control" name="subject_name" value="{{ Request::get('subject_name') }}" placeholder="Subject Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Homework Date</label>
                                <input type="date" class="form-control" name="homework_date" value="{{ Request::get('homework_date') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Submission Date</label>
                                <input type="date" class="form-control" name="submission_date" value="{{ Request::get('submission_date') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label style="display: block;">&nbsp;</label>
                                <button class="btn btn-primary" type="submit">Search</button>
                                <a href="{{ url('student/my_submitted_homework') }}" class="btn btn-success">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Submitted Home Work List</h3>
                </div>

                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Homework Date</th>
                                <th>Submission Date</th>
                                <th>Description</th>
                                <th>Teacher Document</th>
                                <th>Your Document</th>
                                <th>Submitted Description</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
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
            @php $daysLate = $deadline->diffInDays($submittedAt); @endphp
            <span class="badge bg-danger">Late by {{ $daysLate }} {{ $daysLate == 1 ? 'Day' : 'Days' }}</span>
        @else
            <span class="badge bg-success">On Time</span>
        @endif
    </div>

    {{-- Resubmit Logic --}}
    @if(!$isPastDeadline)
        <a href="{{ url('student/homework/edit_submit/'.$value->homework_id) }}" 
           class="btn btn-primary btn-sm">
           <i class="fas fa-sync"></i> Resubmit
        </a>
        <small class="text-muted d-block mt-1">Deadline: {{ $deadline->format('d-m-Y') }}</small>
    @else
        <span class="text-muted small"><i class="fas fa-lock"></i> Locked (Deadline Passed)</span>
    @endif
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