@extends('layouts.app')
@section('content')
<main class="app-main">

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-person-video3"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Add New Teacher</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/teacher/list') }}" class="text-muted text-decoration-none">Back to Teacher List</a>
                            </span>
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
                                <i class="bi bi-image text-success"></i>
                                <h6 class="mb-0 fw-semibold">Profile Photo</h6>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center justify-content-center gap-3 py-4">
                                <div class="position-relative">
                                    <img id="avatarPreview"
                                         src="{{ asset('upload/profile/user.jpg') }}"
                                         alt="Preview"
                                         class="rounded-circle shadow"
                                         style="width:110px;height:110px;object-fit:cover;border:3px solid #e9ecef;">
                                    <label for="profile_pic"
                                           class="position-absolute bottom-0 end-0 btn btn-success btn-sm rounded-circle p-1"
                                           style="width:30px;height:30px;cursor:pointer;" title="Upload photo">
                                        <i class="bi bi-camera-fill" style="font-size:.75rem;"></i>
                                    </label>
                                </div>
                                <div class="text-center">
                                    <div class="small fw-semibold text-dark">Upload Photo</div>
                                    <div class="text-muted" style="font-size:.72rem;">JPG, PNG · Max 2MB</div>
                                </div>
                                <input type="file" id="profile_pic" name="profile_pic"
                                       class="d-none @error('profile_pic') is-invalid @enderror"
                                       accept="image/*"
                                       onchange="previewAvatar(this, 'avatarPreview')">
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
                                <i class="bi bi-person-lines-fill text-success"></i>
                                <h6 class="mb-0 fw-semibold">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            First Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                               value="{{ old('name') }}" required
                                               placeholder="First name"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Last Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="last_name"
                                               value="{{ old('last_name') }}" required
                                               placeholder="Last name"
                                               class="form-control @error('last_name') is-invalid @enderror">
                                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Gender <span class="text-danger">*</span>
                                        </label>
                                        <select name="gender" required
                                                class="form-select @error('gender') is-invalid @enderror">
                                            <option value="">— Select —</option>
                                            <option {{ old('gender') == 'Male'   ? 'selected' : '' }} value="Male">Male</option>
                                            <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">Female</option>
                                            <option {{ old('gender') == 'Other'  ? 'selected' : '' }} value="Other">Other</option>
                                        </select>
                                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    
                                        <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                   Date of Birth <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="date"
                                           class="form-control"
                                           name="date_of_birth"
                                           id="date_of_birth"
                                           value="{{ Request::get('date_of_birth', date('Y-m-d')) }}"
                                           required>
                                    <span class="input-group-text" onclick="document.getElementById('date_of_birth').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Marital Status
                                        </label>
                                        <select name="marital_status"
                                                class="form-select @error('marital_status') is-invalid @enderror">
                                            <option {{ old('marital_status', 1) == 1 ? 'selected' : '' }} value="1">Unmarried</option>
                                            <option {{ old('marital_status', 1) == 0 ? 'selected' : '' }} value="0">Married</option>
                                        </select>
                                        @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Mobile Number <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-phone text-muted"></i></span>
                                            <input type="text" name="mobile_number"
                                                   value="{{ old('mobile_number') }}" required
                                                   placeholder="e.g. 9800000000"
                                                   class="form-control @error('mobile_number') is-invalid @enderror">
                                        </div>
                                        @error('mobile_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                   Date of Joining <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="date"
                                           class="form-control"
                                           name="date_of_joining"
                                           id="date_of_joining"
                                           value="{{ Request::get('attendance_date', date('Y-m-d')) }}"
                                           required>
                                    <span class="input-group-text" onclick="document.getElementById('date_of_joining').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>

                                    

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" required
                                                class="form-select @error('status') is-invalid @enderror">
                                            <option value="">— Select —</option>
                                            <option {{ old('status') === '0' ? 'selected' : '' }} value="0">Active</option>
                                            <option {{ old('status') === '1' ? 'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ── Section 2: Address & Qualifications ────────── --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-success"></i>
                                <h6 class="mb-0 fw-semibold">Address &amp; Qualifications</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Current Address</label>
                                        <textarea name="current_address" rows="3"
                                                  placeholder="Street, City, State…"
                                                  class="form-control @error('current_address') is-invalid @enderror">{{ old('current_address') }}</textarea>
                                        @error('current_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Permanent Address</label>
                                        <textarea name="permanent_address" rows="3"
                                                  placeholder="Permanent address if different…"
                                                  class="form-control @error('permanent_address') is-invalid @enderror">{{ old('permanent_address') }}</textarea>
                                        @error('permanent_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Qualification</label>
                                        <textarea name="qualification" rows="2"
                                                  placeholder="e.g. B.Ed, M.Sc…"
                                                  class="form-control @error('qualification') is-invalid @enderror">{{ old('qualification') }}</textarea>
                                        @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Work Experience</label>
                                        <textarea name="work_experience" rows="2"
                                                  placeholder="e.g. 5 years at City School…"
                                                  class="form-control @error('work_experience') is-invalid @enderror">{{ old('work_experience') }}</textarea>
                                        @error('work_experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Note</label>
                                        <textarea name="note" rows="2"
                                                  placeholder="Any additional notes…"
                                                  class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                        @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ── Section 3: Login Credentials ───────────────── --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill text-success"></i>
                                <h6 class="mb-0 fw-semibold">Login Credentials</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email"
                                               value="{{ old('email') }}" required
                                               placeholder="teacher@school.com"
                                               class="form-control @error('email') is-invalid @enderror">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password"
                                                   id="teacherPassword"
                                                   placeholder="Set a strong password" required
                                                   class="form-control @error('password') is-invalid @enderror">
                                            <button type="button" class="btn btn-outline-secondary px-3"
                                                    onclick="togglePassword('teacherPassword', this)" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.
                                </span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/teacher/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success px-4 fw-semibold">
                                        <i class="bi bi-person-plus-fill me-2"></i>Create Teacher
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
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
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