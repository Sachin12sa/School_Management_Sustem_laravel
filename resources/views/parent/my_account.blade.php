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
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Account</h4>
                            <span class="text-muted small">
                                <a href="{{ url('parent/dashboard') }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
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
            <div class="row g-4">

                {{-- Sidebar --}}
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body py-4 px-3">
                            @if(!empty($getRecord->getProfile()))
                                <img src="{{ $getRecord->getProfile() }}"
                                     id="avatar-preview"
                                     class="rounded-circle shadow mb-3"
                                     style="width:90px;height:90px;object-fit:cover;border:3px solid rgba(220,53,69,.3);">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow mb-3 mx-auto"
                                     id="avatar-preview-initials"
                                     style="width:90px;height:90px;font-size:2rem;background:rgba(220,53,69,.1);border:3px solid rgba(220,53,69,.25);">
                                    {{ strtoupper(substr($getRecord->name,0,1)) }}{{ strtoupper(substr($getRecord->last_name??'',0,1)) }}
                                </div>
                                <img id="avatar-preview" class="rounded-circle shadow mb-3 d-none"
                                     style="width:90px;height:90px;object-fit:cover;border:3px solid rgba(220,53,69,.3);">
                            @endif
                            <div class="fw-bold text-dark">{{ $getRecord->name }} {{ $getRecord->last_name }}</div>
                            <div class="text-muted small mb-2">{{ $getRecord->email }}</div>
                            <span class="badge px-3 py-1" style="background:rgba(220,53,69,.1);color:#842029;border:1px solid rgba(220,53,69,.25);font-size:.7rem;">
                                <i class="bi bi-house-heart-fill me-1"></i>Parent
                            </span>
                            <hr class="my-3 opacity-10">
                            <div class="text-start small">
                                <div class="d-flex justify-content-between py-1 border-bottom border-light">
                                    <span class="text-muted">Mobile</span>
                                    <span class="fw-semibold text-dark">{{ $getRecord->mobile_number ?? '—' }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom border-light">
                                    <span class="text-muted">Blood Group</span>
                                    <span class="fw-semibold text-dark">{{ $getRecord->blood_group ?? '—' }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted">Occupation</span>
                                    <span class="fw-semibold text-dark text-truncate ms-2" style="max-width:90px;">{{ $getRecord->occupation ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                            <i class="bi bi-pencil-fill text-danger"></i>
                            <h6 class="mb-0 fw-semibold">Edit Profile Details</h6>
                        </div>
                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf
                            {{-- hidden fields --}}
                            <input type="hidden" name="user_type" value="3">
                            <input type="hidden" name="is_delete" value="0">

                            <div class="card-body">
                                <div class="row g-4">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $getRecord->name) }}"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="First Name" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" value="{{ old('last_name', $getRecord->last_name) }}"
                                               class="form-control @error('last_name') is-invalid @enderror"
                                               placeholder="Last Name" required>
                                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $getRecord->email) }}"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="Email" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                            <option value="">— Select —</option>
                                            <option value="Male"   {{ old('gender', $getRecord->gender) == 'Male'   ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender', $getRecord->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other"  {{ old('gender', $getRecord->gender) == 'Other'  ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Mobile Number</label>
                                        <input type="text" name="mobile_number" value="{{ old('mobile_number', $getRecord->mobile_number) }}"
                                               class="form-control @error('mobile_number') is-invalid @enderror"
                                               placeholder="Mobile number">
                                        @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Blood Group</label>
                                        <input type="text" name="blood_group" value="{{ old('blood_group', $getRecord->blood_group) }}"
                                               class="form-control @error('blood_group') is-invalid @enderror"
                                               placeholder="e.g. A+, O-">
                                        @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Occupation</label>
                                        <input type="text" name="occupation" value="{{ old('occupation', $getRecord->occupation) }}"
                                               class="form-control @error('occupation') is-invalid @enderror"
                                               placeholder="Occupation">
                                        @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Address</label>
                                        <input type="text" name="address" value="{{ old('address', $getRecord->address) }}"
                                               class="form-control @error('address') is-invalid @enderror"
                                               placeholder="Address">
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="">— Select —</option>
                                            <option value="0" {{ old('status', $getRecord->status) == '0' ? 'selected' : '' }}>Active</option>
                                            <option value="1" {{ old('status', $getRecord->status) == '1' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Profile Picture</label>
                                        <input type="file" name="profile_pic" id="profilePicInput"
                                               class="form-control @error('profile_pic') is-invalid @enderror"
                                               accept="image/*" onchange="previewProfile(this)">
                                        @error('profile_pic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        @if(!empty($getRecord->getProfile()))
                                            <div class="mt-2 d-flex align-items-center gap-2">
                                                <img src="{{ $getRecord->getProfile() }}" class="rounded-2" style="width:48px;height:48px;object-fit:cover;">
                                                <span class="text-muted small">Current profile photo</span>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <a href="{{ url('parent/dashboard') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-danger px-5 fw-semibold">
                                    <i class="bi bi-check-circle-fill me-2"></i>Save Changes
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
function previewProfile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview  = document.getElementById('avatar-preview');
            var initials = document.getElementById('avatar-preview-initials');
            if (preview)  { preview.src = e.target.result; preview.classList.remove('d-none'); }
            if (initials) initials.classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection