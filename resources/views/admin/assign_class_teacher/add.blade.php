@extends('layouts.app')
@section('content')
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Add New Assign Class Teacher </h3>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row g-4">
                    <!--begin::Col-->

                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-12">
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Fill All the Details To Add New Assign Class to Teacher </div>
                            </div>
                            @include('message')
                            <!--end::Header-->
                            <!--begin::Form-->
                            <form method="post" action="">
                                @csrf
                                <!--begin::Body-->
                                <div class="card-body">
                                    {{-- Class --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Class <span
                                                class="text-danger">*</span></label>
                                        <select name="class_id" id="class_id" required
                                            class="form-select @error('class_id') is-invalid @enderror">
                                            <option value="">— Select Class —</option>
                                            @foreach ($getClass as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Section — dynamically loaded when class changes --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-diagram-3 me-1" style="color:#7c3aed;"></i>Section
                                        </label>
                                        <select name="section_id" id="section_id"
                                            class="form-select @error('section_id') is-invalid @enderror" disabled>
                                            <option value="">— Select Class First —</option>
                                        </select>
                                        <div class="form-text text-muted small" id="section-hint">
                                            Select a class to see available sections.
                                        </div>
                                        @error('section_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"> Assign Teacher Name</label>
                                        @foreach ($getTeacherClass as $teacher)
                                            <div>
                                                <label style="font-weight: normal;">
                                                    <input type="checkbox" value="{{ $teacher->id }}" name="teacher_id[]"
                                                        id="">{{ $teacher->name }} {{ $teacher->last_name }}
                                                    </input>
                                            </div>
                                        @endforeach
                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label for="">Status</label>
                                        <select name="status" class="form-control" id="">
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                <!--end::Footer-->
                            </form>
                            <!--end::Form-->
                        </div>

                    </div>

                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content-->
    </main>
@endsection

@section('script')
    <script>
        /* ── Section loader ─────────────────────────────────────────────────── */
        function loadSections(class_id, preselectId) {
            const $sel = $('#section_id');
            const $hint = $('#section-hint');

            if (!class_id) {
                $sel.html('<option value="">— Select Class First —</option>').prop('disabled', true);
                $hint.html('Select a class to see available sections.').removeClass('text-purple');
                return;
            }

            $sel.html('<option value="">Loading sections…</option>').prop('disabled', true);

            $.get("{{ url('admin/section/get_sections') }}", {
                class_id
            }, function(sections) {
                let html = '<option value="">— No Section / General —</option>';
                if (sections.length === 0) {
                    $hint.html(
                        '<i class="bi bi-exclamation-circle me-1 text-warning"></i>No active sections for this class. <a href="{{ url('admin/section/add') }}" target="_blank" style="color:#7c3aed;">Add one</a>'
                    );
                } else {
                    sections.forEach(s => {
                        const sel = preselectId && String(s.id) === String(preselectId) ? 'selected' : '';
                        html += `<option value="${s.id}" ${sel}>Section ${s.name}</option>`;
                    });
                    $hint.html('<i class="bi bi-diagram-3 me-1" style="color:#7c3aed;"></i>' + sections.length +
                        ' section(s) available.');
                }
                $sel.html(html).prop('disabled', false);
            }).fail(function() {
                $sel.html('<option value="">— Error loading sections —</option>').prop('disabled', false);
                $hint.html('<i class="bi bi-exclamation-triangle me-1 text-danger"></i>Could not load sections.');
            });
        }

        /* ── Class change triggers section + roll number ─────────────────────── */
        $('#class_id').on('change', function() {
            const class_id = $(this).val();

            // Load sections
            loadSections(class_id, null);

            // Auto-fill roll number
            if (class_id) {
                $.get("{{ url('admin/student/get_roll_number') }}", {
                    class_id
                }, function(res) {
                    $('#roll_number').val(res.roll_number);
                });
            }
        });

        // Restore state after validation failure (old() values)
        $(document).ready(function() {
            const oldClass = "{{ old('class_id') }}";
            const oldSection = "{{ old('section_id') }}";
            if (oldClass) {
                loadSections(oldClass, oldSection);
            }
        });
    </script>
@endsection
