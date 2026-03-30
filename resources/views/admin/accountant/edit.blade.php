@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-calculator-fill me-2 text-primary"></i>Edit Accountant
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

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ url('admin/accountant/edit/' . $getRecord->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- ── Personal Info ── --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-person-fill me-1"></i> Personal Information
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-3 text-center">
                                            <label class="form-label fw-semibold d-block">Profile Photo</label>
                                            <img id="preview" src="{{ $getRecord->getProfile() }}"
                                                class="rounded-circle border mb-2"
                                                style="width:90px;height:90px;object-fit:cover;">
                                            <input type="file" name="profile_pic" id="profile_pic"
                                                class="form-control form-control-sm" accept="image/*"
                                                onchange="previewImg(this)">
                                            <div class="form-text">Leave blank to keep current photo.</div>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">First Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $getRecord->name) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Middle Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="middle_name" class="form-control"
                                                        value="{{ old('middle_name', $getRecord->middle_name) }}" >
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Last Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="last_name" class="form-control"
                                                        value="{{ old('last_name', $getRecord->last_name) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Gender <span
                                                            class="text-danger">*</span></label>
                                                    <select name="gender" class="form-select" required>
                                                        <option value="">-- Select --</option>
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
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Date of Birth</label>
                                                    <x-bs-date-input name="date_of_birth" id="date_of_birth" required
                                                        value="{{ old('date_of_birth', $getRecord->date_of_birth) }}">
                                                    </x-bs-date-input>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Marital Status</label>
                                                    <select name="marital_status" class="form-select">
                                                        <option value="">-- Select --</option>
                                                        <option value="0"
                                                            {{ old('marital_status', $getRecord->marital_status) == '0' ? 'selected' : '' }}>
                                                            Single</option>
                                                        <option value="1"
                                                            {{ old('marital_status', $getRecord->marital_status) == '1' ? 'selected' : '' }}>
                                                            Married</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Mobile Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="mobile_number" class="form-control"
                                                        value="{{ old('mobile_number', $getRecord->mobile_number) }}"
                                                        required>
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
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-briefcase-fill me-1"></i> Professional Information
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Date of Joining</label>
                                            <x-bs-date-input name="date_of_joining" id="date_of_joining" required
                                                value="{{ old('date_of_joining', $getRecord->date_of_joining) }}">

                                            </x-bs-date-input>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Qualification</label>
                                            <input type="text" name="qualification" class="form-control"
                                                value="{{ old('qualification', $getRecord->qualification) }}"
                                                placeholder="e.g. B.Com, MBS">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Work Experience</label>
                                            <input type="text" name="work_experience" class="form-control"
                                                value="{{ old('work_experience', $getRecord->work_experience) }}"
                                                placeholder="e.g. 3 years">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Status <span
                                                    class="text-danger">*</span></label>
                                            <select name="status" class="form-select" required>
                                                <option value="1"
                                                    {{ old('status', $getRecord->status) == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0"
                                                    {{ old('status', $getRecord->status) == 0 ? 'selected' : '' }}>Inactive
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Note</label>
                                            <textarea name="note" class="form-control" rows="2">{{ old('note', $getRecord->note) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Address ── --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Address
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Current Address</label>
                                            <textarea name="current_address" class="form-control" rows="2">{{ old('current_address', $getRecord->address) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Permanent Address</label>
                                            <textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address', $getRecord->permanent_address) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Login Credentials ── --}}
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Login Credentials
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email', $getRecord->email) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">New Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Leave blank to keep current password">
                                            <div class="form-text">Only fill this if you want to change the password.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 text-end pb-3">
                            <a href="{{ url('admin/accountant/list') }}"
                                class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Update Accountant
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </main>
@endsection

@section('script')
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
