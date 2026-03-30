@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#ede9fe;color:#7c3aed;">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Add New Section</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/section/list') }}" class="text-muted text-decoration-none">Back
                                        to Section List</a>
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
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-diagram-3-fill" style="color:#7c3aed;"></i>
                                <h6 class="mb-0 fw-semibold">Section Details</h6>
                            </div>

                            <form method="POST" action="{{ url('admin/section/insert') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">

                                        {{-- Class --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Class <span class="text-danger">*</span>
                                            </label>
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

                                        {{-- Section Name --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Section Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" value="{{ old('name') }}" required
                                                placeholder="e.g. A, B, Science, Morning…"
                                                class="form-control @error('name') is-invalid @enderror">
                                            <div class="form-text text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Section name must be unique within the selected class.
                                            </div>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">Status</label>
                                            <select name="status"
                                                class="form-select @error('status') is-invalid @enderror">
                                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Existing sections preview --}}
                                        <div class="col-12" id="existing-sections-wrap" style="display:none;">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-eye me-1"></i>Existing Sections for this Class
                                            </label>
                                            <div id="existing-sections-list"
                                                class="d-flex flex-wrap gap-2 p-2 rounded-2 border bg-light">
                                                <span class="text-muted small">Loading…</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div
                                    class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                    <span class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>Fields marked <span
                                            class="text-danger">*</span> are required.
                                    </span>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/section/list') }}" class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn px-4 fw-semibold text-white"
                                            style="background:#7c3aed;">
                                            <i class="bi bi-plus-circle-fill me-2"></i>Create Section
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
        /* Show existing sections when a class is selected */
        $('#class_id').on('change', function() {
            const class_id = $(this).val();
            const wrap = $('#existing-sections-wrap');
            const list = $('#existing-sections-list');

            if (!class_id) {
                wrap.hide();
                return;
            }

            $.get("{{ url('admin/section/get_sections') }}", {
                class_id
            }, function(sections) {
                if (sections.length === 0) {
                    list.html(
                        '<span class="text-muted small">No sections yet — this will be the first!</span>'
                    );
                } else {
                    let html = '';
                    sections.forEach(s => {
                        html += `<span class="badge px-3 py-2 fw-semibold" style="background:#ede9fe;color:#7c3aed;font-size:.8rem;">
                        <i class="bi bi-diagram-3 me-1"></i>Section ${s.name}
                    </span>`;
                    });
                    list.html(html);
                }
                wrap.show();
            });
        });

        // Trigger on page load if old value exists (after validation failure)
        if ($('#class_id').val()) {
            $('#class_id').trigger('change');
        }
    </script>
@endsection
