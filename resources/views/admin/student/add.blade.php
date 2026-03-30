@extends('layouts.app')
@section('content')
    <style>
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        input[name="admission_number"] {
            letter-spacing: 0.5px;
            font-family: 'Monaco', 'Consolas', monospace;
        }

        .section-badge {
            background: #ede9fe;
            color: #7c3aed;
        }
    </style>

    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Add New Student</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/student/list') }}" class="text-muted text-decoration-none">Back
                                        to Student List</a>
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

                <form method="POST" action="{{ url('admin/student/add') }}" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    @csrf
                    {{-- Session is auto-assigned from current active session in the controller --}}
                    {{-- No dropdown needed — admin should not manually pick a session --}}
                    @php $currentSession = \App\Models\AcademicSessionModel::getCurrent(); @endphp
                    @if ($currentSession)
                        <input type="hidden" name="session_id" value="{{ $currentSession->id }}">
                    @endif

                    <div class="row g-4">

                        {{-- ══ LEFT: Profile Photo ══════════════════════════════ --}}
                        <div class="col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-image text-warning"></i>
                                    <h6 class="mb-0 fw-semibold">Profile Photo</h6>
                                </div>
                                <div
                                    class="card-body d-flex flex-column align-items-center justify-content-center gap-3 py-4">
                                    <div class="position-relative">
                                        <img id="studentAvatarPreview" src="{{ asset('upload/profile/user.jpg') }}"
                                            alt="Preview" class="rounded-circle shadow"
                                            style="width:110px;height:110px;object-fit:cover;border:3px solid #e9ecef;">
                                        <label for="profile_pic"
                                            class="position-absolute bottom-0 end-0 btn btn-warning btn-sm rounded-circle p-1"
                                            style="width:30px;height:30px;cursor:pointer;">
                                            <i class="bi bi-camera-fill" style="font-size:.75rem;"></i>
                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <div class="small fw-semibold text-dark">Upload Photo</div>
                                        <div class="text-muted" style="font-size:.72rem;">JPG, PNG · Max 2MB</div>
                                    </div>
                                    <input type="file" id="profile_pic" name="profile_pic" class="d-none"
                                        accept="image/*" onchange="previewAvatar(this,'studentAvatarPreview')">
                                    @error('profile_pic')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ══ RIGHT: Form Sections ════════════════════════════ --}}
                        <div class="col-lg-9">

                            {{-- ── Section 1: Enrollment Info ─────────────────── --}}
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-mortarboard-fill text-warning"></i>
                                    <h6 class="mb-0 fw-semibold">Enrollment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ old('name') }}" required
                                                placeholder="First name"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Middle Name</label>
                                            <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                                placeholder="Middle name"
                                                class="form-control @error('middle_name') is-invalid @enderror">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                                placeholder="Last name"
                                                class="form-control @error('last_name') is-invalid @enderror">
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Admission Number <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="admission_number"
                                                value="{{ old('admission_number', $suggestedId ?? '') }}" required
                                                placeholder="e.g. ADM-2081-001"
                                                class="form-control @error('admission_number') is-invalid @enderror">
                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Roll Number</label>
                                            <input type="text" name="roll_number" id="roll_number"
                                                value="{{ old('roll_number') }}" placeholder="e.g. 01"
                                                class="form-control @error('roll_number') is-invalid @enderror">
                                            @error('roll_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

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

                                        <div class="col-md-4">
                                            <x-bs-date-input name="admission_date" label="Admission Date"
                                                :value="old('admission_date', '')" :required="true" />
                                            @error('admission_date')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" required
                                                class="form-select @error('status') is-invalid @enderror">

                                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 2: Personal Details ────────────────── --}}
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-person-lines-fill text-warning"></i>
                                    <h6 class="mb-0 fw-semibold">Personal Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Gender <span
                                                    class="text-danger">*</span></label>
                                            <select name="gender" required
                                                class="form-select @error('gender') is-invalid @enderror">
                                                <option value="">— Select —</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <x-bs-date-input name="date_of_birth" label="Date of Birth" :value="old('date_of_birth', '')"
                                                :required="true" />
                                            @error('date_of_birth')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Mobile
                                                Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="bi bi-phone text-muted"></i>
                                                </span>
                                                <input type="text" name="mobile_number"
                                                    value="{{ old('mobile_number') }}" placeholder="e.g. 9800000000"
                                                    class="form-control @error('mobile_number') is-invalid @enderror">
                                            </div>
                                            @error('mobile_number')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary d-block">Blood
                                                Group</label>
                                            @php $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-']; @endphp
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($groups as $group)
                                                    <label class="badge bg-light text-dark border p-2"
                                                        style="cursor:pointer;">
                                                        <input type="radio" name="blood_group"
                                                            value="{{ $group }}"
                                                            {{ old('blood_group') == $group ? 'checked' : '' }}>
                                                        {{ $group }}
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('blood_group')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Religion</label>
                                            @php $religions = ['Hindu','Buddhist','Muslim','Christian','Kirat','Sikh','Jain','Other']; @endphp
                                            <select name="religion"
                                                class="form-select @error('religion') is-invalid @enderror">
                                                <option value="">-- Select Religion --</option>
                                                @foreach ($religions as $religion)
                                                    <option value="{{ $religion }}"
                                                        {{ old('religion') == $religion ? 'selected' : '' }}>
                                                        {{ $religion }}</option>
                                                @endforeach
                                            </select>
                                            @error('religion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold small text-secondary">Height</label>
                                            <input type="text" name="height" value="{{ old('height') }}"
                                                placeholder="cm"
                                                class="form-control @error('height') is-invalid @enderror">
                                            @error('height')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold small text-secondary">Weight</label>
                                            <input type="text" name="weight" value="{{ old('weight') }}"
                                                placeholder="kg"
                                                class="form-control @error('weight') is-invalid @enderror">
                                            @error('weight')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 3: Parent / Guardian ───────────────── --}}
                            <div class="card border-0 shadow-sm mb-4">
                                <div
                                    class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-people-fill text-warning"></i>
                                        <h6 class="mb-0 fw-semibold">Parent / Guardian</h6>
                                        <span class="badge bg-warning text-dark rounded-pill px-2" id="parent-count"
                                            style="font-size:.7rem;">0 assigned</span>
                                    </div>
                                    <a href="{{ url('admin/parent/add') }}" target="_blank"
                                        class="btn btn-sm btn-outline-warning fw-semibold">
                                        <i class="bi bi-person-plus-fill me-1"></i>Add New Parent
                                        <i class="bi bi-box-arrow-up-right ms-1" style="font-size:.7rem;"></i>
                                    </a>
                                </div>

                                <div class="card-body">
                                    <div
                                        class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 py-2 small d-flex align-items-center gap-2 mb-3">
                                        <i class="bi bi-lightbulb-fill text-warning flex-shrink-0"></i>
                                        <span>Search for an existing registered parent below and assign them to this
                                            student.
                                            If the parent isn't registered yet, click <strong>Add New Parent</strong> above,
                                            then use <strong>Refresh List</strong> to find them.</span>
                                    </div>

                                    <div class="input-group mb-3">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" id="parent-search-input"
                                            class="form-control border-start-0"
                                            placeholder="Type name, email or mobile to filter parents…"
                                            autocomplete="off">
                                        <button type="button" class="btn btn-outline-secondary" id="refresh-parent-btn">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh List
                                        </button>
                                    </div>

                                    <div id="parent-table-wrap"
                                        style="max-height:300px;overflow-y:auto;border:1px solid #dee2e6;border-radius:8px;">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light" style="position:sticky;top:0;z-index:1;">
                                                <tr>
                                                    <th class="small text-uppercase text-secondary"
                                                        style="font-size:.7rem;">Name</th>
                                                    <th class="small text-uppercase text-secondary"
                                                        style="font-size:.7rem;">Email</th>
                                                    <th class="small text-uppercase text-secondary"
                                                        style="font-size:.7rem;">Mobile</th>
                                                    <th class="small text-uppercase text-secondary text-end"
                                                        style="font-size:.7rem;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="parent-table-body">
                                                @forelse($getParent as $parent)
                                                    <tr class="parent-row" data-id="{{ $parent->id }}"
                                                        data-name="{{ $parent->name }} {{ $parent->middle_name }} {{ $parent->last_name }}"
                                                        data-email="{{ $parent->email }}"
                                                        data-mobile="{{ $parent->mobile_number ?? '' }}">
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                                    style="width:32px;height:32px;font-size:.72rem;">
                                                                    {{ strtoupper(substr($parent->name, 0, 1)) }}{{ strtoupper(substr($parent->middle_name ?? '', 0, 1) ?? '') }}{{ strtoupper(substr($parent->last_name ?? '', 0, 1)) }}
                                                                </div>
                                                                <div class="fw-semibold small text-dark">
                                                                    {{ $parent->name }}{{ $parent->middle_name }}
                                                                    {{ $parent->last_name }}</div>
                                                            </div>
                                                        </td>
                                                        <td class="small text-muted">{{ $parent->email }}</td>
                                                        <td class="small text-muted">{{ $parent->mobile_number ?? '—' }}
                                                        </td>
                                                        <td class="text-end">
                                                            <button type="button"
                                                                class="btn btn-sm btn-success assign-parent-btn fw-semibold"
                                                                data-id="{{ $parent->id }}"
                                                                data-name="{{ $parent->name }} {{ $parent->middle_name }} {{ $parent->last_name }}"
                                                                data-email="{{ $parent->email }}"
                                                                data-mobile="{{ $parent->mobile_number ?? '' }}">
                                                                <i class="bi bi-plus-circle me-1"></i>Assign
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr id="no-parents-row">
                                                        <td colspan="4" class="text-center py-4 text-muted small">
                                                            <i class="bi bi-people d-block mb-1"
                                                                style="font-size:1.5rem;opacity:.3;"></i>
                                                            No registered parents found.
                                                            <a href="{{ url('admin/parent/add') }}" target="_blank"
                                                                class="ms-1 text-warning fw-semibold">Add one now</a>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="small fw-semibold text-secondary">Assigned Parent(s)</span>
                                            <span class="text-muted" style="font-size:.72rem;" id="assign-hint">
                                                Click Assign on a row above to link a parent
                                            </span>
                                        </div>
                                        <div id="selected-parents-list" class="d-flex flex-wrap gap-2 p-2 rounded-2"
                                            style="min-height:46px;border:1.5px dashed #ced4da;background:#fafafa;">
                                            <span class="text-muted small align-self-center" id="no-parent-msg">
                                                <i class="bi bi-person-x me-1"></i>No parent assigned yet
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 4: Login Credentials ───────────────── --}}
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-lock-fill text-warning"></i>
                                    <h6 class="mb-0 fw-semibold">Login Credentials</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-envelope me-1"></i>Email Address <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" value="{{ old('email') }}" required
                                                placeholder="student@school.com"
                                                class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="studentPassword" required
                                                    placeholder="Set a strong password"
                                                    class="form-control @error('password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary px-3"
                                                    onclick="togglePassword('studentPassword', this)" tabindex="-1">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
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
                                        <a href="{{ url('admin/student/list') }}" class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                            <i class="bi bi-person-plus-fill me-2"></i>Create Student
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /col-lg-9 --}}
                    </div>{{-- /row --}}
                </form>
            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
        /* ── Helpers ─────────────────────────────────────────────────────────── */
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        }

        function previewAvatar(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById(previewId).src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }

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

        /* ── Parent section ──────────────────────────────────────────────────── */
        $(document).ready(function() {

            const assignedIds = new Set();

            function updateCount() {
                const c = assignedIds.size;
                $('#parent-count').text(c + ' assigned');
                if (c === 0) {
                    if ($('#no-parent-msg').length === 0) {
                        $('#selected-parents-list').append(
                            '<span class="text-muted small align-self-center" id="no-parent-msg">' +
                            '<i class="bi bi-person-x me-1"></i>No parent assigned yet</span>'
                        );
                    }
                    $('#assign-hint').show();
                } else {
                    $('#no-parent-msg').remove();
                    $('#assign-hint').hide();
                }
            }

            /* 1. Live filter */
            $('#parent-search-input').on('input', function() {
                const q = $(this).val().trim().toLowerCase();
                let visible = 0;
                $('#parent-table-body .parent-row').each(function() {
                    const match = !q ||
                        ($(this).data('name') || '').toLowerCase().includes(q) ||
                        ($(this).data('email') || '').toLowerCase().includes(q) ||
                        ($(this).data('mobile') || '').toLowerCase().includes(q);
                    $(this).toggle(match);
                    if (match) visible++;
                });
                $('#no-parents-row').toggle(visible === 0 && $('#parent-table-body .parent-row').length >
                    0);
            });

            /* 2. Refresh List */
            $('#refresh-parent-btn').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html(
                    '<i class="bi bi-arrow-clockwise me-1 spin"></i>Refreshing…');

                $.ajax({
                    url: "{{ url('admin/student/search_parent') }}",
                    method: 'GET',
                    data: {
                        search: $('#parent-search-input').val().trim()
                    },
                    success: function(response) {
                        const parents = response.results ?? [];
                        let html = '';
                        if (parents.length === 0) {
                            html = `<tr id="no-parents-row"><td colspan="4" class="text-center py-4 text-muted small">
                            <i class="bi bi-people d-block mb-1" style="font-size:1.5rem;opacity:.3;"></i>
                            No registered parents found.
                            <a href="{{ url('admin/parent/add') }}" target="_blank" class="ms-1 text-warning fw-semibold">Add one now</a>
                        </td></tr>`;
                        } else {
                            parents.forEach(p => {
                                const id = String(p.id);
                                const name = p.text ?? ((p.name ?? '') + ' ' + (p
                                    .middle_name ?? '') + ' ' + (p
                                    .last_name ?? '')).trim();
                                const email = p.email ?? '';
                                const mobile = p.phone ?? p.mobile_number ?? '';
                                const already = assignedIds.has(id);
                                const initials = name.trim().split(' ').map(w => w[0] ||
                                    '').join('').toUpperCase().slice(0, 2);
                                html += `<tr class="parent-row" data-id="${id}" data-name="${name}" data-email="${email}" data-mobile="${mobile}">
                                <td><div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                         style="width:32px;height:32px;font-size:.72rem;">${initials}</div>
                                    <div class="fw-semibold small text-dark">${name}</div>
                                </div></td>
                                <td class="small text-muted">${email}</td>
                                <td class="small text-muted">${mobile||'—'}</td>
                                <td class="text-end">
                                    <button type="button"
                                            class="btn btn-sm fw-semibold assign-parent-btn ${already?'btn-secondary disabled':'btn-success'}"
                                            data-id="${id}" data-name="${name}" data-email="${email}" data-mobile="${mobile}"
                                            ${already?'disabled':''}>
                                        ${already?'<i class="bi bi-check-circle me-1"></i>Assigned':'<i class="bi bi-plus-circle me-1"></i>Assign'}
                                    </button>
                                </td>
                            </tr>`;
                            });
                        }
                        $('#parent-table-body').html(html);
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-check-circle-fill me-1 text-success"></i>Refreshed!'
                        );
                        setTimeout(() => btn.html(
                                '<i class="bi bi-arrow-clockwise me-1"></i>Refresh List')
                            .prop('disabled', false), 1800);
                    },
                    error: function() {
                        btn.prop('disabled', false).html(
                            '<i class="bi bi-arrow-clockwise me-1"></i>Refresh List');
                        alert('Could not refresh. Please check your connection.');
                    }
                });
            });

            /* 3. Assign a parent */
            $(document).on('click', '.assign-parent-btn:not([disabled])', function() {
                const id = String($(this).data('id'));
                const name = $(this).data('name');
                const email = $(this).data('email') || '';
                const mobile = $(this).data('mobile') || '';
                if (assignedIds.has(id)) return;
                assignedIds.add(id);
                updateCount();
                $('#no-parent-msg').remove();
                $('#assign-hint').hide();
                const chip = $(`
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 border border-warning bg-warning bg-opacity-10"
                     id="parent-tag-${id}" style="font-size:.82rem;">
                    <input type="hidden" name="user_id[]" value="${id}">
                    <i class="bi bi-person-fill text-warning"></i>
                    <span class="fw-semibold text-dark">${name}</span>
                    ${email  ? `<span class="text-muted small">&lt;${email}&gt;</span>` : ''}
                    ${mobile ? `<span class="text-muted small">· ${mobile}</span>` : ''}
                    <button type="button" class="btn-close btn-close-sm remove-parent ms-1"
                            data-id="${id}" aria-label="Remove" style="font-size:.65rem;"></button>
                </div>`);
                $('#selected-parents-list').append(chip);
                $(`.assign-parent-btn[data-id="${id}"]`)
                    .addClass('btn-secondary disabled').removeClass('btn-success')
                    .prop('disabled', true).html('<i class="bi bi-check-circle me-1"></i>Assigned');
            });

            /* 4. Remove assigned parent */
            $(document).on('click', '.remove-parent', function() {
                const id = String($(this).data('id'));
                assignedIds.delete(id);
                $(`#parent-tag-${id}`).remove();
                updateCount();
                $(`.assign-parent-btn[data-id="${id}"]`)
                    .removeClass('btn-secondary disabled').addClass('btn-success')
                    .prop('disabled', false).html('<i class="bi bi-plus-circle me-1"></i>Assign');
            });

        });
    </script>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin {
            display: inline-block;
            animation: spin .8s linear infinite;
        }
    </style>
@endsection
