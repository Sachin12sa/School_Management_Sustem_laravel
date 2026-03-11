@extends('layouts.app') 

@section('style')   
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <style type="text/css">
    .select2-container--default .select2-results__option { color: #000000 !important; }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { color: #ffffff !important; }
    .select2-container--default .select2-selection--multiple .select2-selection__choice { color: #000000 !important; }
    
 </style>
@endsection

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <h3 class="mb-0">Add New Home Work</h3>
        </div>
    </div>

    @include('message')

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3"> 
                            <label>Class <span class="text-danger">*</span></label>
                            <select class="form-control" name="class_id" required id="getClass">
                                <option value="">Select Class</option>
                                @foreach ($getClass as $class) 
                                    <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"> 
                            <label>Subject <span class="text-danger">*</span></label>
                            <select class="form-control" name="subject_id" required id="getSubject">
                                <option value="">Select Subject</option>
                            </select>
                        </div>

                        <div class="row">
                        {{-- HomeWork date --}}
                        <div class="mb-3 col-md-6 position-relative">
                                    <label class="form-label">HomeWork Date</label>
                                    <div class="input-group">
                                    <input
                                        name="homework_date"
                                        id="homework_date"
                                        value="{{ old('homework_date') }}"
                                        required
                                        type="date"
                                        class="form-control" 
                                        />
                                        <span class="input-group-text date-icon" onclick="document.getElementById('homework_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                    </div>
                                </div>

                        {{-- Submission date --}}
                        
                        <div class="mb-3 col-md-6 position-relative">
                                    <label class="form-label">Submission Date</label>
                                    <div class="input-group">
                                    <input
                                        name="submission_date"
                                        id="submission_date"
                                        value="{{ old('submission_date') }}"
                                        required
                                        type="date"
                                        class="form-control" 
                                        />
                                        <span class="input-group-text date-icon" onclick="document.getElementById('submission_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                    </div>
                                </div>

                        <div class="mb-3">
                            <label>Document</label>
                            <input type="file" class="form-control" name="document_file">
                        </div>
                        
                        <div class="mb-3">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea name="message" id="compose_textarea" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit Homework</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    // Summernote Initialization
    $('#compose_textarea').summernote({
        height: 200
    });

    // Ajax Call for Subjects
    $('#getClass').on('change', function() {
        var class_id = $(this).val();
        if(class_id != "") {
            $.ajax({
                type: "POST",
                url: "{{ url('teacher/ajax_get_subject') }}",
                data: {
                    "class_id": class_id,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    $('#getSubject').html(response.success);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        } else {
            $('#getSubject').html('<option value="">Select Subject</option>');
        }
    });
});
</script>
@endsection