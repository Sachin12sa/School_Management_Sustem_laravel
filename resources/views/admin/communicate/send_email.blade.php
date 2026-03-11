@extends('layouts.app') 
@section('style')   
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <style type="text/css" >
<style type="text/css">
/* 1. The actual text in the dropdown list */
.select2-container--default .select2-results__option {
    color: #000000 !important;
}

/* 2. Text color when an item is hovered/highlighted (Blue background) */
/* We keep this white so it's readable against the dark blue highlight */
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    color: #ffffff !important; 
}

/* 3. The text inside the input box after you've selected it */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #000000 !important;
}

/* 4. The typing cursor/text in the search field */
.select2-container--default .select2-search--inline .select2-search__field {
    color: #000000 !important;
}

/* 5. Placeholder text */
.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: #6c757d !important;
}
</style>
 </style>
@endsection
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Send Email</h3>
                </div>
            </div>
        </div>
    </div>
    @include('message')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">

                        <div class="card-header">
                            <div class="card-title">Notice Board</div>
                        </div>

                        <form method="POST" action="">
                            @csrf

                            <div class="card-body">

                                <!-- Title -->
                                <div class="mb-3">
                                    <label class="form-label">Subject</label>
                                    <input
                                        name="subject"
                                        value="{{ old('subject') }}"
                                        required
                                        placeholder="Enter subject"
                                        type="text"
                                        class="form-control" />
                                </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User(Student/Parents/Teacher)</label>
                                            <select name="user_id[]" class="form-control select2" multiple ...>
                                                <option selected="selected">Select</option>
                                            </select>
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
                                <button type="submit" class="btn btn-primary">Send Email</button>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    $('.select2').select2({
    ajax: {
        url: "{{ url('admin/communicate/search_user') }}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
    },

    placeholder: "Search User",
    minimumInputLength: 1,
    width: '100%',
    dropdownAutoWidth: true,

    // 👇 Force clean dropdown styling
    dropdownCssClass: "select2-black-text",
    containerCssClass: "select2-black-text"
});

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