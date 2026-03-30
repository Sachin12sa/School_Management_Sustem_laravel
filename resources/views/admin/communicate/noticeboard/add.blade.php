@extends('layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Add New Notice</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/communicate/notice_board') }}"
                                        class="text-muted text-decoration-none">Back to Notice Board</a>
                                </span>
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
                        <i class="bi bi-megaphone-fill text-warning"></i>
                        <h6 class="mb-0 fw-semibold">Notice Details</h6>
                    </div>

                    <form method="POST" action="{{ url('admin/communicate/notice_board/add') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row g-4">

                                {{-- Title --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}" required
                                        placeholder="Enter notice title…"
                                        class="form-control @error('title') is-invalid @enderror">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Notice Date / Publish Date --}}
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Notice Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="notice_date" id="notice_date"
                                        value="{{ old('notice_date') }}" required
                                        class="nepali-date form-control @error('notice_date') is-invalid @enderror">
                                    @error('notice_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Publish Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="publish_date" id="publish_date"
                                        value="{{ old('publish_date') }}" required
                                        class="nepali-date form-control @error('publish_date') is-invalid @enderror">
                                    @error('publish_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Recipients --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary d-block mb-2">
                                        Message To <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @php
                                            $recipients = [2 => 'Teacher', 3 => 'Student', 4 => 'Parent'];
                                            $colors = [2 => 'success', 3 => 'warning', 4 => 'danger'];
                                        @endphp
                                        @foreach ($recipients as $val => $label)
                                            <label
                                                class="recipient-label d-flex align-items-center gap-2 px-4 py-2 rounded-pill border"
                                                style="cursor:pointer;transition:all .15s ease;">
                                                <input type="checkbox" name="message_to[]" value="{{ $val }}"
                                                    class="recipient-check d-none" data-color="{{ $colors[$val] }}"
                                                    {{ in_array($val, (array) old('message_to', [])) ? 'checked' : '' }}>
                                                <i class="bi bi-person-fill"></i>
                                                {{ $label }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Message --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">
                                        Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="message" id="compose_textarea" class="form-control" required>{{ old('message') }}</textarea>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span
                                    class="text-danger">*</span> are required.</span>
                            <div class="d-flex gap-2">
                                <a href="{{ url('admin/communicate/notice_board') }}"
                                    class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                    <i class="bi bi-megaphone-fill me-2"></i>Publish Notice
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </main>

    <style>
        .recipient-label {
            background: #f8f9fa;
            color: #555;
            user-select: none;
        }

        .recipient-label:hover {
            filter: brightness(.96);
        }

        .recipient-label.checked-success {
            background: rgba(25, 135, 84, .12) !important;
            border-color: rgba(25, 135, 84, .5) !important;
            color: #198754 !important;
            font-weight: 600;
        }

        .recipient-label.checked-warning {
            background: rgba(255, 193, 7, .18) !important;
            border-color: rgba(255, 193, 7, .6) !important;
            color: #856404 !important;
            font-weight: 600;
        }

        .recipient-label.checked-danger {
            background: rgba(220, 53, 69, .12) !important;
            border-color: rgba(220, 53, 69, .5) !important;
            color: #dc3545 !important;
            font-weight: 600;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    <script>
        $(document).ready(function() {

            // Recipient pill toggle
            function syncRecipient($chk) {
                const color = $chk.data('color');
                const $lbl = $chk.closest('.recipient-label');
                if ($chk.is(':checked')) {
                    $lbl.addClass('checked-' + color);
                } else {
                    $lbl.removeClass('checked-' + color);
                }
            }

            // Init state
            $('.recipient-check').each(function() {
                syncRecipient($(this));
            });

            // On click label, toggle checkbox
            $('.recipient-label').on('click', function() {
                const $chk = $(this).find('.recipient-check');
                $chk.prop('checked', !$chk.prop('checked'));
                syncRecipient($chk);
            });

            // Summernote
            $('#compose_textarea').summernote({
                height: 280,
                placeholder: 'Write your notice here…',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var dateInputs = document.querySelectorAll('.nepali-date');
            dateInputs.forEach(function(input) {
                input.NepaliDatePicker({
                    ndpYear: true,
                    ndpMonth: true,
                    readOnlyInput: true
                });
            });
        });
    </script>
@endsection
