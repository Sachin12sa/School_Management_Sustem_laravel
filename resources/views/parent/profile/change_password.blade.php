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
                            <span class="text-muted small">
                                <a href="{{ url('parent/account') }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Back to My Account
                                </a>
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
                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm mb-4"
                         style="border-left:4px solid rgba(220,53,69,.5)!important;border-radius:.75rem;">
                        <div class="card-body py-3 px-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-lightbulb-fill text-warning"></i>
                                <h6 class="mb-0 fw-semibold small">Password Tips</h6>
                            </div>
                            <ul class="text-muted mb-0 ps-3" style="font-size:.78rem;line-height:1.9;">
                                <li>Use at least 8 characters</li>
                                <li>Mix uppercase, lowercase, numbers &amp; symbols</li>
                                <li>Don't reuse passwords from other sites</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                            <i class="bi bi-key-fill text-danger"></i>
                            <h6 class="mb-0 fw-semibold">Update Password</h6>
                        </div>
                        <form method="POST" action="change_password">
                            @csrf
                            <div class="card-body">
                                <div class="row g-4">

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">Current Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="old_password" id="oldPass"
                                                   class="form-control @error('old_password') is-invalid @enderror"
                                                   placeholder="Enter current password" required>
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="togglePw('oldPass',this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @error('old_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="new_password" id="newPass"
                                                   class="form-control @error('new_password') is-invalid @enderror"
                                                   placeholder="Enter new password" required>
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="togglePw('newPass',this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mt-2">
                                            <div class="progress" style="height:4px;border-radius:2px;">
                                                <div id="strength-bar" class="progress-bar" style="width:0%;transition:width .3s,background .3s;"></div>
                                            </div>
                                            <div id="strength-label" class="mt-1 text-muted" style="font-size:.7rem;"></div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">Confirm New Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_password" id="confirmPass"
                                                   class="form-control @error('confirm_password') is-invalid @enderror"
                                                   placeholder="Repeat new password" required>
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="togglePw('confirmPass',this)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @error('confirm_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div id="match-msg" class="mt-1" style="font-size:.72rem;"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <a href="{{ url('parent/account') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-danger px-5 fw-semibold">
                                    <i class="bi bi-shield-lock-fill me-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script>
function togglePw(id, btn) {
    var i = document.getElementById(id);
    var ic = btn.querySelector('i');
    if (i.type === 'password') { i.type = 'text'; ic.classList.replace('bi-eye','bi-eye-slash'); }
    else { i.type = 'password'; ic.classList.replace('bi-eye-slash','bi-eye'); }
}
document.getElementById('newPass').addEventListener('input', function () {
    var v = this.value, s = 0;
    if (v.length >= 8) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    var m = [['0%','',''],['25%','bg-danger','Weak'],['50%','bg-warning','Fair'],['75%','bg-info','Good'],['100%','bg-success','Strong']][s];
    var bar = document.getElementById('strength-bar');
    bar.style.width = m[0]; bar.className = 'progress-bar ' + m[1];
    document.getElementById('strength-label').textContent = m[2] ? 'Strength: ' + m[2] : '';
    checkMatch();
});
function checkMatch() {
    var np = document.getElementById('newPass').value;
    var cp = document.getElementById('confirmPass').value;
    var el = document.getElementById('match-msg');
    if (!cp) { el.textContent = ''; return; }
    if (np === cp) { el.textContent = '✓ Passwords match'; el.className = 'text-success mt-1'; }
    else           { el.textContent = '✗ Passwords do not match'; el.className = 'text-danger mt-1'; }
}
document.getElementById('confirmPass').addEventListener('input', checkMatch);
</script>
@endsection