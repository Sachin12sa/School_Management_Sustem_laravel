@extends('layouts.app')
@section('content')
    <style>
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
                                <h4 class="mb-0 fw-semibold text-dark">Edit Student</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/student/list') }}" class="text-muted text-decoration-none">Back
                                        to Student List</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <div class="d-inline-flex align-items-center gap-2 bg-light rounded-3 px-3 py-2">
                            @if (!empty($getRecord->getProfile()))
                                <img src="{{ $getRecord->getProfile() }}" class="rounded-circle"
                                    style="width:32px;height:32px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold"
                                    style="width:32px;height:32px;font-size:.8rem;">
                                    {{ strtoupper(substr($getRecord->name, 0, 1)) }}{{ strtoupper(substr($getRecord->last_name ?? '', 0, 1)) }}
                                </div>
                            @endif
                            <div class="text-start">
                                <div class="fw-semibold small text-dark lh-1">{{ $getRecord->name }}
                                    {{ $getRecord->last_name }}</div>
                                <div class="text-muted" style="font-size:.72rem;">
                                    {{ $getRecord->admission_number ?? 'Student' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <form method="POST" action="{{ url('admin/student/edit/' . $getRecord->id) }}"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf

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
                                        <img id="studentEditAvatar"
                                            src="{{ !empty($getRecord->getProfile()) ? $getRecord->getProfile() : asset('upload/profile/user.jpg') }}"
                                            alt="Profile" class="rounded-circle shadow"
                                            style="width:110px;height:110px;object-fit:cover;border:3px solid #e9ecef;">
                                        <label for="profile_pic"
                                            class="position-absolute bottom-0 end-0 btn btn-warning btn-sm rounded-circle p-1"
                                            style="width:30px;height:30px;cursor:pointer;">
                                            <i class="bi bi-camera-fill" style="font-size:.75rem;"></i>
                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <div class="small fw-semibold text-dark">Change Photo</div>
                                        <div class="text-muted" style="font-size:.72rem;">JPG, PNG · Max 2MB</div>
                                    </div>
                                    <input type="file" id="profile_pic" name="profile_pic" class="d-none"
                                        accept="image/*" onchange="previewAvatar(this,'studentEditAvatar')">
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
                                            <input type="text" name="name"
                                                value="{{ old('name', $getRecord->name) }}" required
                                                placeholder="First name"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Middle Name</label>
                                            <input type="text" name="middle_name"
                                                value="{{ old('middle_name', $getRecord->middle_name) }}"
                                                placeholder="Middle name"
                                                class="form-control @error('middle_name') is-invalid @enderror">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="last_name"
                                                value="{{ old('last_name', $getRecord->last_name) }}" required
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
                                                value="{{ old('admission_number', $getRecord->admission_number) }}"
                                                required
                                                class="form-control @error('admission_number') is-invalid @enderror">
                                            @error('admission_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Roll Number</label>
                                            <input type="text" name="roll_number"
                                                value="{{ old('roll_number', $getRecord->roll_number) }}"
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
                                                        {{ old('class_id', $getRecord->class_id) == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Section — server-rendered for current class, reloaded via AJAX on class change --}}
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-diagram-3 me-1" style="color:#7c3aed;"></i>Section
                                            </label>
                                            <select name="section_id" id="section_id"
                                                class="form-select @error('section_id') is-invalid @enderror">
                                                <option value="">— No Section / General —</option>
                                                {{-- Pre-populated from controller's $getSections --}}
                                                @foreach ($getSections as $sec)
                                                    <option value="{{ $sec->id }}"
                                                        {{ old('section_id', $getRecord->section_id) == $sec->id ? 'selected' : '' }}>
                                                        Section {{ $sec->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text text-muted small" id="section-hint">
                                                @if ($getSections->count())
                                                    <i class="bi bi-diagram-3 me-1"
                                                        style="color:#7c3aed;"></i>{{ $getSections->count() }} section(s)
                                                    available.
                                                @else
                                                    <i class="bi bi-info-circle me-1"></i>No sections for this class yet.
                                                @endif
                                            </div>
                                            @error('section_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Admission Date <span
                                                    class="text-danger">*</span></label>
                                            <x-bs-date-input name="admission_date" :value="old('admission_date', $getRecord->admission_date)" :required="true" />
                                            @error('admission_date')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" required
                                                class="form-select @error('status') is-invalid @enderror">
                                                <option value="">— Select —</option>
                                                <option value="0"
                                                    {{ old('status', $getRecord->status) == '0' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="1"
                                                    {{ old('status', $getRecord->status) == '1' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- Academic Session — read-only display, never editable --}}
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-calendar3-range me-1" style="color:#d97706;"></i>Academic
                                                Session
                                            </label>
                                            @php
                                                $studentSession = \App\Models\AcademicSessionModel::getSingle(
                                                    $getRecord->session_id,
                                                );
                                            @endphp
                                            @if ($studentSession)
                                                <div class="form-control bg-light d-flex align-items-center gap-2"
                                                    style="cursor:default;">
                                                    <span class="fw-semibold"
                                                        style="color:#d97706;">{{ $studentSession->name }}</span>
                                                    @if ($studentSession->is_current)
                                                        <span class="badge bg-success ms-1"
                                                            style="font-size:.65rem;">Current</span>
                                                    @else
                                                        <span class="badge bg-secondary ms-1"
                                                            style="font-size:.65rem;">Archived</span>
                                                    @endif
                                                </div>
                                                <div class="form-text text-muted small">Session cannot be changed manually.
                                                </div>
                                            @else
                                                <div class="form-control bg-light text-muted" style="cursor:default;">Not
                                                    assigned</div>
                                                <div class="form-text text-warning small">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Run the <a href="{{ url('admin/academic_session/list') }}">session
                                                        assignment</a> first.
                                                </div>
                                            @endif
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
                                                <option value="Male"
                                                    {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}>
                                                    Male</option>
                                                <option value="Female"
                                                    {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                                <option value="Other"
                                                    {{ old('gender', $getRecord->gender) == 'Other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Date of Birth <span
                                                    class="text-danger">*</span></label>
                                            <x-bs-date-input name="date_of_birth" :value="old('date_of_birth', $getRecord->date_of_birth)" :required="true" />
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
                                                    value="{{ old('mobile_number', $getRecord->mobile_number) }}"
                                                    placeholder="e.g. 9800000000"
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
                                                            {{ old('blood_group', $getRecord->blood_group) == $group ? 'checked' : '' }}>
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
                                                <option value="">— Select Religion —</option>
                                                @foreach ($religions as $religion)
                                                    <option value="{{ $religion }}"
                                                        {{ old('religion', $getRecord->religion) == $religion ? 'selected' : '' }}>
                                                        {{ $religion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('religion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold small text-secondary">Height</label>
                                            <input type="text" name="height"
                                                value="{{ old('height', $getRecord->height) }}" placeholder="cm"
                                                class="form-control @error('height') is-invalid @enderror">
                                            @error('height')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold small text-secondary">Weight</label>
                                            <input type="text" name="weight"
                                                value="{{ old('weight', $getRecord->weight) }}" placeholder="kg"
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
                                            style="font-size:.7rem;">
                                            {{ $getStudentParent->count() }} assigned
                                        </span>
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
                                        <span>Currently assigned parents are shown in the chips below. Use the search table
                                            to add more, or click <strong>✕</strong> on a chip to remove a parent.</span>
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
                                                    @php $alreadyAssigned = $getStudentParent->contains('id', $parent->id); @endphp
                                                    <tr class="parent-row" data-id="{{ $parent->id }}"
                                                        data-name="{{ $parent->name }} {{ $parent->last_name }}"
                                                        data-email="{{ $parent->email }}"
                                                        data-mobile="{{ $parent->mobile_number ?? '' }}">
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                                    style="width:32px;height:32px;font-size:.72rem;">
                                                                    {{ strtoupper(substr($parent->name, 0, 1)) }}{{ strtoupper(substr($parent->last_name ?? '', 0, 1)) }}
                                                                </div>
                                                                <div class="fw-semibold small text-dark">
                                                                    {{ $parent->name }} {{ $parent->last_name }}</div>
                                                            </div>
                                                        </td>
                                                        <td class="small text-muted">{{ $parent->email }}</td>
                                                        <td class="small text-muted">{{ $parent->mobile_number ?? '—' }}
                                                        </td>
                                                        <td class="text-end">
                                                            <button type="button"
                                                                class="btn btn-sm fw-semibold assign-parent-btn {{ $alreadyAssigned ? 'btn-secondary disabled' : 'btn-success' }}"
                                                                data-id="{{ $parent->id }}"
                                                                data-name="{{ $parent->name }} {{ $parent->last_name }}"
                                                                data-email="{{ $parent->email }}"
                                                                data-mobile="{{ $parent->mobile_number ?? '' }}"
                                                                {{ $alreadyAssigned ? 'disabled' : '' }}>
                                                                @if ($alreadyAssigned)
                                                                    <i class="bi bi-check-circle me-1"></i>Assigned
                                                                @else
                                                                    <i class="bi bi-plus-circle me-1"></i>Assign
                                                                @endif
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
                                            <span class="text-muted" style="font-size:.72rem;" id="assign-hint"
                                                {{ $getStudentParent->isNotEmpty() ? 'style=display:none' : '' }}>
                                                Click Assign on a row above to link a parent
                                            </span>
                                        </div>
                                        <div id="selected-parents-list" class="d-flex flex-wrap gap-2 p-2 rounded-2"
                                            style="min-height:46px;border:1.5px dashed #ced4da;background:#fafafa;">
                                            @if ($getStudentParent->isNotEmpty())
                                                @foreach ($getStudentParent as $sp)
                                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 border border-warning bg-warning bg-opacity-10"
                                                        id="parent-tag-{{ $sp->id }}" style="font-size:.82rem;">
                                                        <input type="hidden" name="user_id[]"
                                                            value="{{ $sp->id }}">
                                                        <i class="bi bi-person-fill text-warning"></i>
                                                        <span class="fw-semibold text-dark">{{ $sp->name }}
                                                            {{ $sp->last_name }}</span>
                                                        @if ($sp->email)
                                                            <span
                                                                class="text-muted small">&lt;{{ $sp->email }}&gt;</span>
                                                        @endif
                                                        @if ($sp->mobile_number)
                                                            <span class="text-muted small">·
                                                                {{ $sp->mobile_number }}</span>
                                                        @endif
                                                        <button type="button"
                                                            class="btn-close btn-close-sm remove-parent ms-1"
                                                            data-id="{{ $sp->id }}" aria-label="Remove"
                                                            style="font-size:.65rem;"></button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted small align-self-center" id="no-parent-msg">
                                                    <i class="bi bi-person-x me-1"></i>No parent assigned yet
                                                </span>
                                            @endif
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
                                            <input type="email" name="email"
                                                value="{{ old('email', $getRecord->email) }}" required
                                                class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-lock me-1"></i>New Password
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1"
                                                    style="font-size:.65rem;">Optional</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="studentEditPw"
                                                    placeholder="Leave blank to keep current"
                                                    class="form-control @error('password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary px-3"
                                                    onclick="togglePassword('studentEditPw', this)" tabindex="-1">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <div class="form-text text-muted">
                                                <i class="bi bi-info-circle me-1"></i>Leave blank to keep the current
                                                password.
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
                                            <i class="bi bi-floppy-fill me-2"></i>Update Student
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
        // The currently saved section_id (from PHP) — used to keep selection when
        // the page first loads; cleared if admin switches class.
        const savedSectionId = "{{ old('section_id', $getRecord->section_id ?? '') }}";

        function loadSections(class_id, preselectId) {
            const $sel = $('#section_id');
            const $hint = $('#section-hint');

            if (!class_id) {
                $sel.html('<option value="">— Select Class First —</option>');
                $hint.html('<i class="bi bi-info-circle me-1"></i>Select a class to see available sections.');
                return;
            }

            $sel.html('<option value="">Loading…</option>').prop('disabled', true);

            $.get("{{ url('admin/student/get_sections') }}", {
                class_id
            }, function(sections) {
                let html = '<option value="">— No Section / General —</option>';
                if (sections.length === 0) {
                    $hint.html(
                        '<i class="bi bi-exclamation-circle me-1 text-warning"></i>No active sections for this class. <a href="{{ url('admin/class_section/add') }}" target="_blank" style="color:#7c3aed;">Add one</a>'
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
            });
        }

        /* When class changes → reload sections (no preselect — user changed class) */
        $('#class_id').on('change', function() {
            loadSections($(this).val(), null);
        });

        /* On page load: the section dropdown is already server-rendered correctly.
           We only call loadSections if the class was changed after a validation
           failure (old() class differs from saved class). */
        $(document).ready(function() {
            const renderedClass = "{{ $getRecord->class_id }}";
            const oldClass = "{{ old('class_id', $getRecord->class_id) }}";

            // If validation failed and admin changed the class, reload via AJAX
            if (oldClass && oldClass !== renderedClass) {
                const oldSection = "{{ old('section_id') }}";
                loadSections(oldClass, oldSection);
            }
        });

        /* ── Parent section ──────────────────────────────────────────────────── */
        $(document).ready(function() {

            const assignedIds = new Set();
            $('#selected-parents-list [name="user_id[]"]').each(function() {
                assignedIds.add(String($(this).val()));
            });

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
                    $('#assign-hint').text('Click Assign on a row above to link a parent').show();
                } else {
                    $('#no-parent-msg').remove();
                    $('#assign-hint').text('').hide();
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
                                    .last_name ?? '')).trim();
                                const email = p.email ?? '';
                                const mobile = p.phone ?? p.mobile_number ?? '';
                                const already = assignedIds.has(id);
                                const initials = name.split(' ').map(w => w[0] ?? '')
                                    .join('').toUpperCase().slice(0, 2);
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
                        btn.html(
                            '<i class="bi bi-check-circle-fill me-1 text-success"></i>Refreshed!'
                        );
                        setTimeout(() => btn.html(
                                '<i class="bi bi-arrow-clockwise me-1"></i>Refresh List')
                            .prop('disabled', false), 1800);
                    },
                    error: function() {
                        btn.html('<i class="bi bi-arrow-clockwise me-1"></i>Refresh List').prop(
                            'disabled', false);
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

            /* 4. Remove an assigned parent */
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
