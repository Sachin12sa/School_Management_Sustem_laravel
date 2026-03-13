@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-house-heart-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Add New Parent</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/parent/list') }}" class="text-muted text-decoration-none">Back to Parent List</a>
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

            <form method="post" action="" enctype="multipart/form-data" autocomplete="off">
                @csrf
                {{-- Fixed: was user_type=3 on parent form --}}
                <input type="hidden" name="user_type" value="4">
                <input type="hidden" name="is_delete" value="0">

                <div class="row g-4">

                    {{-- ══ LEFT: Profile Photo ══════════════════════════════ --}}
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-image text-danger"></i>
                                <h6 class="mb-0 fw-semibold">Profile Photo</h6>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center justify-content-center gap-3 py-4">
                                <div class="position-relative">
                                    <img id="parentAvatarPreview"
                                         src="{{ asset('upload/profile/user.jpg') }}"
                                         alt="Preview"
                                         class="rounded-circle shadow"
                                         style="width:110px;height:110px;object-fit:cover;border:3px solid #e9ecef;">
                                    <label for="profile_pic"
                                           class="position-absolute bottom-0 end-0 btn btn-danger btn-sm rounded-circle p-1"
                                           style="width:30px;height:30px;cursor:pointer;">
                                        <i class="bi bi-camera-fill" style="font-size:.75rem;"></i>
                                    </label>
                                </div>
                                <div class="text-center">
                                    <div class="small fw-semibold text-dark">Upload Photo</div>
                                    <div class="text-muted" style="font-size:.72rem;">JPG, PNG · Max 2MB</div>
                                </div>
                                <input type="file" id="profile_pic" name="profile_pic"
                                       class="d-none" accept="image/*"
                                       onchange="previewAvatar(this, 'parentAvatarPreview')">
                                @error('profile_pic') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ══ RIGHT: Form Sections ════════════════════════════ --}}
                    <div class="col-lg-9">

                        {{-- ── Section 1: Personal Info ──────────────────── --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-person-lines-fill text-danger"></i>
                                <h6 class="mb-0 fw-semibold">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name') }}" required
                                               placeholder="First name"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                               placeholder="Last name"
                                               class="form-control @error('last_name') is-invalid @enderror">
                                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" required class="form-select @error('gender') is-invalid @enderror">
                                            <option value="">— Select —</option>
                                            <option {{ old('gender') == 'Male'   ? 'selected' : '' }} value="Male">Male</option>
                                            <option {{ old('gender') == 'Female' ? 'selected' : '' }} value="Female">Female</option>
                                            <option {{ old('gender') == 'Other'  ? 'selected' : '' }} value="Other">Other</option>
                                        </select>
                                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-phone text-muted"></i></span>
                                            <input type="text" name="mobile_number" value="{{ old('mobile_number') }}"
                                                   placeholder="e.g. 9800000000"
                                                   class="form-control @error('mobile_number') is-invalid @enderror">
                                        </div>
                                        @error('mobile_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Occupation</label>
                                        <input type="text" name="occupation" value="{{ old('occupation') }}"
                                               placeholder="e.g. Engineer, Teacher…"
                                               class="form-control @error('occupation') is-invalid @enderror">
                                        @error('occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-secondary">Blood Group</label>
                                        <input type="text" name="blood_group" value="{{ old('blood_group') }}"
                                               placeholder="e.g. A+, B-, O+"
                                               class="form-control @error('blood_group') is-invalid @enderror">
                                        @error('blood_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Address</label>
                                        <input type="text" name="address" value="{{ old('address') }}"
                                               placeholder="Street, City…"
                                               class="form-control @error('address') is-invalid @enderror">
                                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold small text-secondary">Status <span class="text-danger">*</span></label>
                                        <select name="status" required class="form-select @error('status') is-invalid @enderror">
                                            <option value="">—</option>
                                            {{-- Fixed: was using old('gender') for status --}}
                                            <option {{ old('status') === '0' ? 'selected' : '' }} value="0">Active</option>
                                            <option {{ old('status') === '1' ? 'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ── Section 2: Login Credentials ───────────────── --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill text-danger"></i>
                                <h6 class="mb-0 fw-semibold">Login Credentials</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-envelope me-1"></i>Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                               placeholder="parent@school.com"
                                               class="form-control @error('email') is-invalid @enderror">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="parentPassword" required
                                                   placeholder="Set a strong password"
                                                   class="form-control @error('password') is-invalid @enderror">
                                            <button type="button" class="btn btn-outline-secondary px-3"
                                                    onclick="togglePassword('parentPassword', this)" tabindex="-1">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/parent/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-danger px-4 fw-semibold">
                                        <i class="bi bi-person-plus-fill me-2"></i>Create Parent
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
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