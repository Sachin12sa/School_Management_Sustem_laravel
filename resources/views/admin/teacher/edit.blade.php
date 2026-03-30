@extends('layouts.app')
@section('content')
    <main class="app-main">

        {{-- ── Page Header ─────────────────────────────────────────── --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-person-video3"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Edit Teacher</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/teacher/list') }}" class="text-muted text-decoration-none">Back
                                        to Teacher List</a>
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
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold"
                                    style="width:32px;height:32px;font-size:.8rem;">
                                    {{ strtoupper(substr($getRecord->name, 0, 1)) }}{{ strtoupper(substr($getRecord->last_name ?? '', 0, 1)) }}
                                </div>
                            @endif
                            <div class="text-start">
                                <div class="fw-semibold small text-dark lh-1">{{ $getRecord->name }}
                                    {{ $getRecord->last_name }}</div>
                                <div class="text-muted" style="font-size:.72rem;">Teacher</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Form ─────────────────────────────────────────────────── --}}
        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <form method="post" action="" enctype="multipart/form-data" autocomplete="off">
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
                                        <img id="avatarPreview"
                                            src="{{ !empty($getRecord->getProfile()) ? $getRecord->getProfile() : asset('upload/profile/user.jpg') }}"
                                            alt="Profile" class="rounded-circle shadow"
                                            style="width:110px;height:110px;object-fit:cover;border:3px solid #e9ecef;">
                                        <label for="profile_pic"
                                            class="position-absolute bottom-0 end-0 btn btn-warning btn-sm rounded-circle p-1"
                                            style="width:30px;height:30px;cursor:pointer;" title="Change photo">
                                            <i class="bi bi-camera-fill" style="font-size:.75rem;"></i>
                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <div class="small fw-semibold text-dark">Change Photo</div>
                                        <div class="text-muted" style="font-size:.72rem;">JPG, PNG · Max 2MB</div>
                                    </div>
                                    <input type="file" id="profile_pic" name="profile_pic" class="d-none"
                                        accept="image/*" onchange="previewAvatar(this, 'avatarPreview')">
                                    @error('profile_pic')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ══ RIGHT: Form Fields ═══════════════════════════════ --}}
                        <div class="col-lg-9">

                            {{-- ── Section 1: Personal Info ──────────────────── --}}
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-person-lines-fill text-warning"></i>
                                    <h6 class="mb-0 fw-semibold">Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ old('name', $getRecord->name) }}"
                                                required placeholder="First name"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">Middle Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="middle_name"
                                                value="{{ old('middle_name', $getRecord->middle_name) }}"
                                                placeholder="Middle name"
                                                class="form-control @error('middle_name') is-invalid @enderror">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
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
                                            <label class="form-label fw-semibold small text-secondary">Gender <span
                                                    class="text-danger">*</span></label>
                                            <select name="gender" required
                                                class="form-select @error('gender') is-invalid @enderror">
                                                <option value="">— Select —</option>
                                                <option {{ old('gender', $getRecord->gender) == 'Male' ? 'selected' : '' }}
                                                    value="Male">Male</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}
                                                    value="Female">Female</option>
                                                <option
                                                    {{ old('gender', $getRecord->gender) == 'Other' ? 'selected' : '' }}
                                                    value="Other">Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Date of Birth <span
                                                    class="text-danger">*</span></label>
                                            <x-bs-date-input name="date_of_birth"
                                                value="{{ old('date_of_birth', $getRecord->date_of_birth) }}"
                                                class="form-control @error('date_of_birth') is-invalid @enderror" />
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Marital
                                                Status</label>
                                            <select name="marital_status"
                                                class="form-select @error('marital_status') is-invalid @enderror">
                                                <option
                                                    {{ old('marital_status', $getRecord->marital_status) == 1 ? 'selected' : '' }}
                                                    value="1">Unmarried</option>
                                                <option
                                                    {{ old('marital_status', $getRecord->marital_status) == 0 ? 'selected' : '' }}
                                                    value="0">Married</option>
                                            </select>
                                            @error('marital_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Mobile Number <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i
                                                        class="bi bi-phone text-muted"></i></span>
                                                <input type="text" name="mobile_number"
                                                    value="{{ old('mobile_number', $getRecord->mobile_number) }}" required
                                                    class="form-control @error('mobile_number') is-invalid @enderror">
                                            </div>
                                            @error('mobile_number')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Date of Joining
                                                <span class="text-danger">*</span></label>
                                            <x-bs-date-input name="date_of_joining"
                                                value="{{ old('date_of_joining', $getRecord->admission_date) }}"
                                                class="form-control @error('date_of_joining') is-invalid @enderror" />
                                            @error('date_of_joining')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" required
                                                class="form-select @error('status') is-invalid @enderror">
                                                <option value="">— Select —</option>
                                                <option {{ old('status', $getRecord->status) == '0' ? 'selected' : '' }}
                                                    value="0">Active</option>
                                                <option {{ old('status', $getRecord->status) == '1' ? 'selected' : '' }}
                                                    value="1">Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 2: Address & Qualifications ────────── --}}
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-warning"></i>
                                    <h6 class="mb-0 fw-semibold">Address &amp; Qualifications</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">Current
                                                Address</label>
                                            <textarea name="current_address" rows="3" class="form-control @error('current_address') is-invalid @enderror">{{ old('current_address', $getRecord->address) }}</textarea>
                                            @error('current_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">Permanent
                                                Address</label>
                                            <textarea name="permanent_address" rows="3"
                                                class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address', $getRecord->permanent_address) }}</textarea>
                                            @error('permanent_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label
                                                class="form-label fw-semibold small text-secondary">Qualification</label>
                                            <textarea name="qualification" rows="2" class="form-control @error('qualification') is-invalid @enderror">{{ old('qualification', $getRecord->qualification) }}</textarea>
                                            @error('qualification')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Work
                                                Experience</label>
                                            <textarea name="work_experience" rows="2" class="form-control @error('work_experience') is-invalid @enderror">{{ old('work_experience', $getRecord->work_experience) }}</textarea>
                                            @error('work_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">Note</label>
                                            <textarea name="note" rows="2" class="form-control @error('note') is-invalid @enderror">{{ old('note', $getRecord->note) }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{-- ── Section 3: Login Credentials ───────────────── --}}
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
                                                <input type="password" name="password" id="teacherEditPassword"
                                                    placeholder="Leave blank to keep current"
                                                    class="form-control @error('password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary px-3"
                                                    onclick="togglePassword('teacherEditPassword', this)" tabindex="-1">
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
                                        <a href="{{ url('admin/teacher/list') }}" class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                            <i class="bi bi-floppy-fill me-2"></i>Update Teacher
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end col-lg-9 --}}
                    </div>{{-- end row --}}
                </form>

            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
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
    </script>
@endsection
