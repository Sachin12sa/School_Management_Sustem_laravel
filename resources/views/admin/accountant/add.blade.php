@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold text-dark">
                        <i class="bi bi-calculator-fill me-2 text-primary"></i>Add New Accountant
                    </h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('admin/accountant/list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ url('admin/accountant/add') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- ── Personal Info ── --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-person-fill me-1"></i> Personal Information
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-3 text-center">
                                        <label class="form-label fw-semibold d-block">Profile Photo</label>
                                        <img id="preview" src="{{ asset('dist/assets/img/default-profile.png') }}"
                                             class="rounded-circle border mb-2"
                                             style="width:90px;height:90px;object-fit:cover;">
                                        <input type="file" name="profile_pic" id="profile_pic"
                                               class="form-control form-control-sm" accept="image/*"
                                               onchange="previewImg(this)">
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control"
                                                       value="{{ old('name') }}" placeholder="First name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="last_name" class="form-control"
                                                       value="{{ old('last_name') }}" placeholder="Last name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                                <select name="gender" class="form-select" required>
                                                    <option value="">-- Select --</option>
                                                    <option value="Male"   {{ old('gender')=='Male'   ? 'selected':'' }}>Male</option>
                                                    <option value="Female" {{ old('gender')=='Female' ? 'selected':'' }}>Female</option>
                                                    <option value="Other"  {{ old('gender')=='Other'  ? 'selected':'' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Date of Birth</label>
                                                <input type="date" name="date_of_birth" class="form-control"
                                                       value="{{ old('date_of_birth') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Marital Status</label>
                                                <select name="marital_status" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    <option value="0" {{ old('marital_status')==='0' ? 'selected':'' }}>Single</option>
                                                    <option value="1" {{ old('marital_status')==='1' ? 'selected':'' }}>Married</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                                                <input type="text" name="mobile_number" class="form-control"
                                                       value="{{ old('mobile_number') }}" placeholder="98XXXXXXXX" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Professional Info ── --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-briefcase-fill me-1"></i> Professional Information
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Date of Joining</label>
                                        <input type="date" name="date_of_joining" class="form-control"
                                               value="{{ old('date_of_joining') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Qualification</label>
                                        <input type="text" name="qualification" class="form-control"
                                               value="{{ old('qualification') }}" placeholder="e.g. B.Com, MBS">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Work Experience</label>
                                        <input type="text" name="work_experience" class="form-control"
                                               value="{{ old('work_experience') }}" placeholder="e.g. 3 years">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="1" {{ old('status','1')=='1' ? 'selected':'' }}>Active</option>
                                            <option value="0" {{ old('status')=='0' ? 'selected':'' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Note</label>
                                        <textarea name="note" class="form-control" rows="2"
                                                  placeholder="Any additional notes">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Address ── --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-geo-alt-fill me-1"></i> Address
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Current Address</label>
                                        <textarea name="current_address" class="form-control" rows="2"
                                                  placeholder="Current address">{{ old('current_address') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control" rows="2"
                                                  placeholder="Permanent address">{{ old('permanent_address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Login Credentials ── --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-shield-lock-fill me-1"></i> Login Credentials
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                               value="{{ old('email') }}" placeholder="email@example.com" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="Minimum 5 characters" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 text-end pb-3">
                        <a href="{{ url('admin/accountant/list') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Save Accountant
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection