@extends('layouts.app')
@section('content')
<main class="app-main">

    {{-- ── Page Header ────────────────────────────────────────────────── --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Account</h4>
                            <span class="text-muted small">Update your profile and security settings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Form Content ──────────────────────────────────────────────── --}}
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
                            <div class="card-body d-flex flex-column align-items-center justify-content-center gap-3 py-4">
                                <div class="position-relative">
                                    <img id="avatarPreview"
                                         src="{{ !empty($getRecord->getProfile()) ? $getRecord->getProfile() : asset('upload/profile/user.jpg') }}"
                                         alt="Profile"
                                         class="rounded-circle shadow"
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
                                <input type="file" id="profile_pic" name="profile_pic"
                                       class="d-none" accept="image/*"
                                       onchange="previewAvatar(this, 'avatarPreview')">
                                @error('profile_pic')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ══ RIGHT: Account Details ══════════════════════════ --}}
                    <div class="col-lg-9">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-person-lines-fill text-primary"></i>
                                <h6 class="mb-0 fw-semibold">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">

                                    {{-- Full Name --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Full Name</label>
                                        <input type="text" name="name"
                                               value="{{ old('name', $getRecord->name) }}"
                                               placeholder="Your name"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Email Address --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Email Address</label>
                                        <input type="email" name="email"
                                               value="{{ old('email', $getRecord->email) }}"
                                               class="form-control @error('email') is-invalid @enderror">
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Password Field --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Change Password
                                            <span class="badge bg-light text-muted ms-1" style="font-size:.6rem;">Optional</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="myAccountPassword"
                                                   placeholder="Leave blank to keep current"
                                                   class="form-control @error('password') is-invalid @enderror">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('myAccountPassword', this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex justify-content-end py-3">
                                <button type="submit" class="btn btn-primary px-5 fw-semibold shadow-sm">
                                    <i class="bi bi-check2-circle me-2"></i>Update Account
                                </button>
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
// Toggle Password Visibility
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Preview image immediately after selection
function previewAvatar(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection