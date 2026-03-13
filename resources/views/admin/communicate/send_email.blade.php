@extends('layouts.app')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<style>
{{-- Fixed: malformed nested <style> tags in original --}}
.select2-container--default .select2-results__option { color: #000 !important; }
.select2-container--default .select2-results__option--highlighted[aria-selected] { color: #fff !important; }
.select2-container--default .select2-selection--multiple .select2-selection__choice { color: #000 !important; }
.select2-container--default .select2-search--inline .select2-search__field { color: #000 !important; }
.select2-container--default .select2-selection--multiple .select2-selection__placeholder { color: #6c757d !important; }
.select2-container--bootstrap-5 .select2-selection { border: 1px solid #dee2e6; }

.recipient-label { background:#f8f9fa; color:#555; user-select:none; cursor:pointer; transition:all .15s ease; }
.recipient-label:hover { filter:brightness(.96); }
.recipient-label.checked-success { background:rgba(25,135,84,.12)!important; border-color:rgba(25,135,84,.5)!important; color:#198754!important; font-weight:600; }
.recipient-label.checked-warning { background:rgba(255,193,7,.18)!important; border-color:rgba(255,193,7,.6)!important; color:#856404!important; font-weight:600; }
.recipient-label.checked-danger  { background:rgba(220,53,69,.12)!important; border-color:rgba(220,53,69,.5)!important; color:#dc3545!important; font-weight:600; }
</style>
@endsection

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Send Email</h4>
                            <span class="text-muted small">Compose and send email to users</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-envelope-fill text-primary"></i>
                    {{-- Fixed: card title said "Notice Board" on the Send Email page --}}
                    <h6 class="mb-0 fw-semibold">Compose Email</h6>
                </div>

                <form method="POST" action="">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">

                            {{-- Subject --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">
                                    Subject <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="subject"
                                       value="{{ old('subject') }}" required
                                       placeholder="Enter email subject…"
                                       class="form-control @error('subject') is-invalid @enderror">
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Recipient users (Select2 AJAX) --}}
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-people me-1"></i>Specific Users (Student / Parent / Teacher)
                                </label>
                                {{-- Fixed: had malformed `...` in the <select> attribute list and value="Select" on a default option --}}
                                <select name="user_id[]" class="form-control select2" multiple
                                        style="width:100%;">
                                </select>
                                <div class="text-muted mt-1" style="font-size:.75rem;">
                                    <i class="bi bi-info-circle me-1"></i>Type at least 1 character to search
                                </div>
                            </div>

                            {{-- Message To groups --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary d-block mb-2">
                                    Message To (Groups)
                                </label>
                                <div class="d-flex flex-wrap gap-3">
                                    @php
                                        $recipients = [3 => 'Student', 2 => 'Parent', 4 => 'Teacher'];
                                        $colors     = [3 => 'warning', 2 => 'danger', 4 => 'success'];
                                    @endphp
                                    @foreach($recipients as $val => $label)
                                    <label class="recipient-label d-flex align-items-center gap-2 px-4 py-2 rounded-pill border">
                                        <input type="checkbox" name="message_to[]"
                                               value="{{ $val }}" class="recipient-check d-none"
                                               data-color="{{ $colors[$val] }}"
                                               {{ in_array($val, (array) old('message_to', [])) ? 'checked' : '' }}>
                                        <i class="bi bi-person-fill"></i>
                                        {{ $label }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Message body --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">
                                    Message <span class="text-danger">*</span>
                                </label>
                                <textarea name="message" id="compose_textarea"
                                          class="form-control" required>{{ old('message') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                        <button type="submit" class="btn btn-primary px-5 fw-semibold">
                            <i class="bi bi-send-fill me-2"></i>Send Email
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
$(document).ready(function () {

    // Recipient pill toggles
    function syncRecipient($chk) {
        const $lbl = $chk.closest('.recipient-label');
        const color = $chk.data('color');
        $lbl.toggleClass('checked-' + color, $chk.is(':checked'));
    }
    $('.recipient-check').each(function () { syncRecipient($(this)); });
    $('.recipient-label').on('click', function () {
        const $chk = $(this).find('.recipient-check');
        $chk.prop('checked', !$chk.prop('checked'));
        syncRecipient($chk);
    });

    // Select2 AJAX user search
    $('.select2').select2({
        ajax: {
            url: "{{ url('admin/communicate/search_user') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) { return { search: params.term }; },
            processResults: function (response) { return { results: response }; },
            cache: true
        },
        placeholder: 'Search by name…',
        minimumInputLength: 1,
        width: '100%'
    });

    // Summernote editor
    $('#compose_textarea').summernote({
        height: 280,
        placeholder: 'Write your email message here…',
        toolbar: [
            ['style',   ['style']],
            ['font',    ['bold', 'underline', 'italic', 'clear']],
            ['fontsize',['fontsize']],
            ['color',   ['color']],
            ['para',    ['ul', 'ol', 'paragraph']],
            ['insert',  ['link', 'picture']],
            ['view',    ['fullscreen', 'codeview']]
        ]
    });
});
</script>
@endsection