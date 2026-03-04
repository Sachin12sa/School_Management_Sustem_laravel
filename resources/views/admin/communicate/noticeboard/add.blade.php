@extends('layouts.app')    

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Notice Board</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">

                        <div class="card-header">
                            <div class="card-title">Notice Board</div>
                        </div>

                        <form method="POST" action="{{url('admin/communicate/notice_board/add')}}">
                            @csrf

                            <div class="card-body">

                                <!-- Title -->
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input
                                        name="title"
                                        value="{{ old('title') }}"
                                        required
                                        placeholder="Enter Title"
                                        type="text"
                                        class="form-control" />
                                </div>
                                {{-- notice date --}}
                                <div class="mb-3 col-md-6 position-relative">
                                    <label class="form-label">Notice Date</label>
                                    <div class="input-group">
                                    <input
                                        name="notice_date"
                                        id="notice_date"
                                        value="{{ old('notice_date') }}"
                                        required
                                        type="date"
                                        class="form-control" 
                                        />
                                        <span class="input-group-text date-icon" onclick="document.getElementById('notice_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                    </div>
                                </div>
                                {{-- Publish date --}}
                    
                                <div class="mb-3 col-md-6 position-relative">
                                    <label class="form-label">Publish Date</label>
                                    <div class="input-group">
                                    <input
                                        name="publish_date"
                                        id="publish_date"
                                        value="{{ old('publish_date') }}"
                                        required
                                        type="date"
                                        class="form-control" 
                                        />
                                        <span class="input-group-text date-icon" onclick="document.getElementById('publish_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="mb-3">
                                    <label class="form-label">Message To</label><br>
                                    <label for=""><input type="checkbox" name="message_to[]" value="3" id="">Student</label>
                                    <label for=""><input type="checkbox" name="message_to[]" value="2" id="">Parent</label>
                                    <label for=""><input type="checkbox" name="message_to[]" value="4" id="">Teacher</label>
                                </div>


                                <!-- Message -->
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea 
                                        name="message"
                                        id="compose_textarea"
                                        class="form-control"
                                        required
                                    >{{ old('message') }}</textarea>
                                </div>

                                

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

@section('script')

<!-- jQuery (Required) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS (Required for some themes) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#compose_textarea').summernote({
            height: 300,
            placeholder: 'Write your notice here...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>

@endsection