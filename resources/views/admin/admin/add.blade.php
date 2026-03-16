@extends('layouts.app')
@section('content')
    <main class="app-main">

        {{-- ── Page Header ─────────────────────────────────────────── --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Add New librarian</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('librarian/librarian/list') }}"
                                        class="text-muted text-decoration-none">Back to librarian List</a>
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
                                    <i class="bi bi-image text-danger"></i>
                                    <h6 class="mb-0 fw-semibold">Profile Photo</h6>
                                </div>
                                <div
                                    class="card-body d-flex flex-column align-items-center justify-content-center gap-3 py-4">
                                    <div class="position-relative">
                                        <img id="parentAvatarPreview" src="{{ asset('upload/profile/user.jpg') }}"
                                            alt="Preview" class="rounded-circle shadow"
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
                                    <input type="file" id="profile_pic" name="profile_pic" class="d-none"
                                        accept="image/*" onchange="previewAvatar(this, 'parentAvatarPreview')">
                                    @error('profile_pic')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── Account Credentials Card ──────────────────────── --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-lock-fill text-primary"></i>
                                    <h6 class="mb-0 fw-semibold">Account Credentials</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">

                                        {{-- Name --}}
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-person me-1"></i>Full Name <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                placeholder="Enter librarian full name" required
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-envelope me-1"></i>Email Address <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" value="{{ old('email') }}"
                                                placeholder="librarian@school.com" required
                                                class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Password --}}
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold small text-secondary">
                                                <i class="bi bi-lock me-1"></i>Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="librarianPassword"
                                                    placeholder="Set a strong password" required
                                                    class="form-control @error('password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary px-3"
                                                    onclick="togglePassword('librarianPassword', this)" tabindex="-1">
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
                                        <a href="{{ url('librarian/librarian/list') }}"
                                            class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                            <i class="bi bi-person-plus-fill me-2"></i>Create librarian
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
        // Password Toggle (Your existing code)
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // NEW: Profile Photo Preview
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
