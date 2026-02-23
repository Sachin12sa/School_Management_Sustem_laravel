@extends('layouts.app')    
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Teacher</h3>
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
                            <div class="card-title">Fill Details To Add New Teacher</div>
                        </div>

                        <form method="post" action="" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    {{-- First Name --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">First Name <span style="color:red">*</span></label>
                                        <input name="name" value="{{ old('name') }}" placeholder="First Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('name') }}</div>
                                    </div>

                                    {{-- Last Name --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Last Name <span style="color:red">*</span></label>
                                        <input name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('last_name') }}</div>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Gender <span style="color:red">*</span></label>
                                        <select name="gender" required class="form-control">
                                            <option value="">Select Gender</option>
                                            <option {{ (old('gender')=='Male')?'selected' : '' }} value="Male">Male</option>
                                            <option {{ (old('gender')=='Female')?'selected' : '' }} value="Female">Female</option>
                                            <option {{ (old('gender')=='Other')?'selected' : '' }} value="Other">Other</option>
                                        </select>
                                        <div style="color:red">{{ $errors->first('gender') }}</div>
                                    </div>

                                    {{-- Date of Birth --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Date of Birth <span style="color:red">*</span></label>
                                        <input type="date". id="date" value="{{ old('date_of_birth') }}" required name="date_of_birth" class="form-control" />
                                        <span  class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        
                                        <div style="color:red">{{ $errors->first('date_of_birth') }}</div>
                                    </div>

                                    {{-- Date of Joining --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Date of Joining <span style="color:red">*</span></label>
                                        <input type="date" id="date" name="date_of_joining" value="{{ old('date_of_joining') }}" required class="form-control" />
                                        <span  class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <div style="color:red">{{ $errors->first('date_of_joining') }}</div>
                                    </div>

                                    {{-- Mobile Number --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Mobile Number <span style="color:red">*</span></label>
                                        <input name="mobile_number" value="{{ old('mobile_number') }}" placeholder="Enter Mobile Number" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('mobile_number') }}</div>
                                    </div>

                                    {{-- Marital Status --}}
                                    <div class="form-group col-md-6 mb-3">
                                    <label class="form-label">Marital Status</label>
                                    <select name="marital_status" class="form-control">
                                        {{-- Set default to 1 (Unmarried) if no old input exists --}}
                                        <option {{ (old('marital_status', 1) == 0) ? 'selected' : '' }} value="0">Married</option>
                                        <option {{ (old('marital_status', 1) == 1) ? 'selected' : '' }} value="1">Unmarried</option>
                                    </select>
                                    <div style="color:red">{{ $errors->first('marital_status') }}</div>
                                </div>

                                    {{-- Profile Picture --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Profile Picture</label>
                                        <input type="file" name="profile_pic" class="form-control" />
                                        <div style="color:red">{{ $errors->first('profile_pic') }}</div>
                                    </div>

                                    {{-- Current Address --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Current Address</label>
                                        <textarea name="current_address" class="form-control" placeholder="Current Address">{{ old('current_address') }}</textarea>
                                        <div style="color:red">{{ $errors->first('current_address') }}</div>
                                    </div>

                                    {{-- Permanent Address --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control" placeholder="Permanent Address">{{ old('permanent_address') }}</textarea>
                                        <div style="color:red">{{ $errors->first('permanent_address') }}</div>
                                    </div>

                                    {{-- Qualification --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Qualification</label>
                                        <textarea name="qualification" class="form-control" placeholder="Qualification">{{ old('qualification') }}</textarea>
                                        <div style="color:red">{{ $errors->first('qualification') }}</div>
                                    </div>

                                    {{-- Work Experience --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Work Experience</label>
                                        <textarea name="work_experience" class="form-control" placeholder="Work Experience">{{ old('work_experience') }}</textarea>
                                        <div style="color:red">{{ $errors->first('work_experience') }}</div>
                                    </div>

                                    {{-- Note --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Note</label>
                                        <textarea name="note" class="form-control" placeholder="Note">{{ old('note') }}</textarea>
                                        <div style="color:red">{{ $errors->first('note') }}</div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="form-group col-md-6 mb-3">
                                        <label class="form-label">Status <span style="color:red">*</span></label>
                                        <select name="status" required class="form-control">
                                            <option value="">Select Status</option>
                                            <option {{ (old('status')=='0')?'selected' : '' }} value="0">Active</option>
                                            <option {{ (old('status')=='1')?'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                        <div style="color:red">{{ $errors->first('status') }}</div>
                                    </div>
                                </div>

                                <hr />

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span style="color:red">*</span></label>
                                    <input name="email" value="{{ old('email') }}" required placeholder="Email" type="email" class="form-control" />
                                    <div style="color:red">{{ $errors->first('email') }}</div>
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label">Password <span style="color:red">*</span></label>
                                    <input type="password" name="password" placeholder="Password" required class="form-control" />
                                    <div style="color:red">{{ $errors->first('password') }}</div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection