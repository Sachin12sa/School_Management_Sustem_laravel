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
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Change Password</h4>
                                <span class="text-muted small">Keep your account secure</span>
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
                            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                                <i class="bi bi-shield-lock-fill text-danger"></i>
                                <h6 class="mb-0 fw-semibold">Update Password</h6>
                            </div>

                            {{-- Fixed: multiple separate card-body blocks for each field (broken structure) --}}
                            {{-- Fixed: card-footer was inside card-body --}}
                            <form method="post" action="{{ url('student/profile/change_password') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Current Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="old_password" id="old_password" required
                                                    placeholder="Enter your current password"
                                                    class="form-control @error('old_password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('old_password', this)">
                                                    <i class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                            @error('old_password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                New Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="new_password" id="new_password" required
                                                    placeholder="Enter a new password"
                                                    class="form-control @error('new_password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('new_password', this)">
                                                    <i class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                            @error('new_password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Confirm New Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="confirm_password" id="confirm_password"
                                                    required placeholder="Re-enter your new password"
                                                    class="form-control @error('confirm_password') is-invalid @enderror">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="togglePassword('confirm_password', this)">
                                                    <i class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                            @error('confirm_password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Password strength hints --}}
                                        <div class="col-12">
                                            <div class="rounded-2 bg-light border p-3" style="font-size:.78rem;">
                                                <div class="fw-semibold small text-muted mb-1"><i
                                                        class="bi bi-lightbulb me-1"></i>Password tips</div>
                                                <ul class="mb-0 ps-3 text-muted">
                                                    <li>Use at least 8 characters</li>
                                                    <li>Mix uppercase, lowercase, numbers &amp; symbols</li>
                                                    <li>Don't reuse your current password</li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div
                                    class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span
                                            class="text-danger">*</span> are required.</span>
                                    <button type="submit" class="btn btn-danger px-5 fw-semibold">
                                        <i class="bi bi-shield-check me-2"></i>Update Password
                                    </button>
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
        function togglePassword(fieldId, btn) {
            const field = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye-slash';
            }
        }
    </script>
@endsection
