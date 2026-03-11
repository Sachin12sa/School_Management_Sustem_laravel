@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Home Work
                        {{-- <small class="text-muted">(Total : {{ $getRecord->total() }})</small> --}}
                    </h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('teacher/homework/homework/add') }}" class="btn btn-primary">
                        + Add New Home Work
                    </a>
                </div>
            </div>

            <!-- Search Card -->
           
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Home Work List</h3>
                </div>
                <div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Search Home Work</h3>
    </div>
    <form method="GET" action="">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-3">
                    <label>Class</label>
                    <input type="text" class="form-control" name="class_name" value="{{ Request::get('class_name') }}" placeholder="Class Name">
                </div>
                <div class="form-group col-md-3">
                    <label>Subject</label>
                    <input type="text" class="form-control" name="subject_name" value="{{ Request::get('subject_name') }}" placeholder="Subject Name">
                </div>
                <div class="form-group col-md-2">
                    <label>Homework Date</label>
                    <input type="date" class="form-control" name="homework_date" value="{{ Request::get('homework_date') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>Submission Date</label>
                    <input type="date" class="form-control" name="submission_date" value="{{ Request::get('submission_date') }}">
                </div>
                <div class="form-group col-md-2">
                    <label style="display: block;">&nbsp;</label>
                    <button class="btn btn-primary" type="submit">Search</button>
                    <a href="{{ url('teacher/homework/homework') }}" class="btn btn-success">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Class</th>
                                <th>Subject </th>
                                <th> HomeWork Date</th>
                                <th> Submission Date</th>
                                <th> Document </th>
                                <th> Description</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <thead>
                                 @forelse($getRecord as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->class_name }}</td>
                                        <td>{{ $value->subject_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->homework_date)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->submission_date)) }}</td>
                                        <td>
                                            @if(!empty($value->document_file)) {{-- Change 'document' to 'document_file' --}}
                                                <a href="{{ asset('upload/homework/'.$value->document_file) }}" 
                                                class="btn btn-sm btn-outline-primary" 
                                                target="_blank">
                                                View File
                                                </a>
                                                <a href="{{ asset('upload/homework/'.$value->document_file) }}" 
                                                class="btn btn-sm btn-primary" 
                                                download="">
                                                Download
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">No File</span>
                                            @endif
                                        </td>
                                        <td>{!! $value->description !!}</td> 
                                        <td>{{ $value->created_by_name }} {{ $value->created_by_last_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                        <td>
                                            <a href="{{ url('teacher/homework/homework/edit/'.$value->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ url('teacher/homework/submitted/'.$value->id) }}" class="btn btn-success btn-sm">Submitted</a>
                                            <a href="{{ url('teacher/homework/homework/delete/'.$value->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No Homework Found.</td>
                                    </tr>
                                @endforelse
                            </thead>
                            
                           
                        </tbody>
                    </table>
                </div>
                  <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>

                <!-- Pagination -->
               
            </div>
        </div>
    </div>

</main>
@endsection
