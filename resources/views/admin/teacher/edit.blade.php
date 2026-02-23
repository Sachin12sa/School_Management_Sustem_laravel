@extends('layouts.app')    
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Teacher</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">

                        <div class="card-header">
                            <div class="card-title">Edit Details for Teacher: {{ $getRecord->name }}</div>
                        </div>

                        <form method="post" action="" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    {{-- First Name --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">First Name <span style="color:red">*</span></label>
                                        <input name="name" value="{{ old('name', $getRecord->name) }}" placeholder="First Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('name') }}</div>
                                    </div>

                                    {{-- Last Name --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Last Name <span style="color:red">*</span></label>
                                        <input name="last_name" value="{{ old('last_name', $getRecord->last_name) }}" placeholder="Last Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('last_name') }}</div>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Gender <span style="color:red">*</span></label>
                                        <select name="gender" required class="form-control">
                                            <option value="">Select Gender</option>
                                            <option {{ (old('gender', $getRecord->gender) == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                            <option {{ (old('gender', $getRecord->gender) == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                            <option {{ (old('gender', $getRecord->gender) == 'Other') ? 'selected' : '' }} value="Other">Other</option>
                                        </select>
                                        <div style="color:red">{{ $errors->first('gender') }}</div>
                                    </div>

                                    {{-- Date of Birth --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Date of Birth <span style="color:red">*</span></label>
                                        <input type="date" id="date" value="{{ old('date_of_birth', $getRecord->date_of_birth) }}" required name="date_of_birth" class="form-control" />
                                        <span  class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <div style="color:red">{{ $errors->first('date_of_birth') }}</div>
                                    </div>

                                    {{-- Date of Joining (Mapped to admission_date col) --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Date of Joining <span style="color:red">*</span></label>
                                        <input type="date" id="teacher" name="date_of_joining" value="{{ old('date_of_joining', $getRecord->admission_date) }}" required class="form-control" />
                                        <span  class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <div style="color:red">{{ $errors->first('date_of_joining') }}</div>
                                    </div>

                                    {{-- Mobile Number --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Mobile Number <span style="color:red">*</span></label>
                                        <input name="mobile_number" value="{{ old('mobile_number', $getRecord->mobile_number) }}" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('mobile_number') }}</div>
                                    </div>

                                    {{-- Marital Status --}}
                                        <div class="form-group col-md-6 mb-3">
                                            <label class="form-label">Marital Status</label>
                                                <select name="marital_status" class="form-control">
                                                    <option {{ (old('marital_status', $getRecord->marital_status) == 0) ? 'selected' : '' }} value="0">Married</option>
                                                    <option {{ (old('marital_status', $getRecord->marital_status) == 1) ? 'selected' : '' }} value="1">Unmarried</option>
                                                </select>
                                                <div style="color:red">{{ $errors->first('marital_status') }}</div>
                                            </div>

                                    {{-- Profile Picture --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Profile Picture</label>
                                        <input type="file" name="profile_pic" class="form-control" />
                                        <div style="color:red">{{ $errors->first('profile_pic') }}</div>
                                        @if(!empty($getRecord->getProfile()))
                                            <img src="{{ $getRecord->getProfile() }}" style="width:100px; margin-top:10px; border-radius:5px;" alt="Profile">
                                        @endif
                                    </div>

                                    {{-- Addresses --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Current Address</label>
                                        <textarea name="current_address" class="form-control">{{ old('current_address', $getRecord->address) }}</textarea>
                                        <div style="color:red">{{ $errors->first('current_address') }}</div>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control">{{ old('permanent_address', $getRecord->permanent_address) }}</textarea>
                                        <div style="color:red">{{ $errors->first('permanent_address') }}</div>
                                    </div>

                                    {{-- Qualification/Exp --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Qualification</label>
                                        <textarea name="qualification" class="form-control">{{ old('qualification', $getRecord->qualification) }}</textarea>
                                        <div style="color:red">{{ $errors->first('qualification') }}</div>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Work Experience</label>
                                        <textarea name="work_experience" class="form-control">{{ old('work_experience', $getRecord->work_experience) }}</textarea>
                                        <div style="color:red">{{ $errors->first('work_experience') }}</div>
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Note</label>
                                        <textarea name="note" class="form-control">{{ old('note', $getRecord->note) }}</textarea>
                                        <div style="color:red">{{ $errors->first('note') }}</div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Status <span style="color:red">*</span></label>
                                        <select name="status" required class="form-control">
                                            <option value="">Select</option>
                                            <option {{ (old('status', $getRecord->status) == '0') ? 'selected' : '' }} value="0">Active</option>
                                            <option {{ (old('status', $getRecord->status) == '1') ? 'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                        <div style="color:red">{{ $errors->first('status') }}</div>
                                    </div>
                                </div>

                                <hr />

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span style="color:red">*</span></label>
                                    <input name="email" value="{{ old('email', $getRecord->email) }}" required type="email" class="form-control" />
                                    <div style="color:red">{{ $errors->first('email') }}</div>
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" placeholder="Password" class="form-control" />
                                    <small class="text-muted">Do you want to change the password? If so, please enter a new one. Otherwise, leave it blank.</small>
                                    <div style="color:red">{{ $errors->first('password') }}</div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Teacher</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection