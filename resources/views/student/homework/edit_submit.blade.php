@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Homework Submission</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-info mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Original Assignment Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Subject:</strong> {{ $getHomework->subject_name }}</p>
                                    <p><strong>Homework Date:</strong> {{ date('d-m-Y', strtotime($getHomework->homework_date)) }}</p>
                                    <p><strong>Submission Deadline:</strong> {{ date('d-m-Y', strtotime($getHomework->submission_date)) }}</p>
                                </div>
                                <div class="col-md-8">
                                    <p><strong>Teacher's Instructions:</strong></p>
                                    <div class="p-2 border bg-light">{!! $getHomework->description !!}</div>
                                    
                                    @if(!empty($getHomework->document_file))
                                        <div class="mt-2">
                                            <a href="{{ asset('upload/homework/'.$getHomework->document_file) }}" class="btn btn-sm btn-info" target="_blank">View Teacher's Document</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Update Your Submission</h3>
                        </div>
                        <form method="POST" action="{{ url('student/homework/edit_submit/'.$getHomework->id) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label>Description / Message <span style="color:red;">*</span></label>
                                    <textarea name="message" id="compose-textarea" class="form-control" style="height: 200px" required>{{ $getSubmission->description }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Upload Document (Optional)</label>
                                    <input type="file" name="document_file" class="form-control">
                                    
                                    @if(!empty($getSubmission->document_file))
                                        <div class="mt-2">
                                            <p class="text-muted small">
                                                <strong>Current File:</strong> 
                                                <a href="{{ asset('upload/homework/'.$getSubmission->document_file) }}" target="_blank">View Previous Submission</a>
                                            </p>
                                            <p class="text-warning small italic">Note: Uploading a new file will replace your old one.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Submission</button>
                                <a href="{{ url('student/my_submitted_homework') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection