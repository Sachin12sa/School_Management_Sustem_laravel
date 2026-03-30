@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Assign Subjects to Class</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/assign_subject/list') }}"
                                        class="text-muted text-decoration-none">Back to Assign Subject List</a>
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

                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-journal-bookmark-fill text-info"></i>
                                <h6 class="mb-0 fw-semibold">Assignment Details</h6>
                            </div>

                            <form method="post" action="">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-4">
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

                                        {{-- Subjects --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-journal-text me-1"></i>Select Subjects <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="border rounded-3 p-3 bg-light"
                                                style="max-height:280px;overflow-y:auto;">
                                                @forelse($getSubject as $subject)
                                                    <div class="form-check py-1 border-bottom border-light-subtle">
                                                        <input class="form-check-input" type="checkbox" name="subject_id[]"
                                                            value="{{ $subject->id }}" id="subject_{{ $subject->id }}">
                                                        <label class="form-check-label small fw-semibold text-dark"
                                                            for="subject_{{ $subject->id }}">
                                                            {{ $subject->name }}
                                                            <span
                                                                class="badge bg-secondary bg-opacity-10 text-secondary ms-1"
                                                                style="font-size:.65rem;">
                                                                {{ $subject->type == 0 ? 'Theory' : 'Practical' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @empty
                                                    <p class="text-muted small mb-0">No subjects available.</p>
                                                @endforelse
                                            </div>
                                            @error('subject_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">Status</label>
                                            <select name="status"
                                                class="form-select @error('status') is-invalid @enderror">
                                                <option value="0">Active</option>
                                                <option value="1">Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div
                                    class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Select at least one
                                        subject.</span>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/assign_subject/list') }}"
                                            class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-info text-white px-4 fw-semibold">
                                            <i class="bi bi-check-circle-fill me-2"></i>Assign Subjects
                                        </button>
                                    </div>
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
