@extends('layouts.app')    
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Add New Parent</h3>
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
                            <div class="card-title">Fill Details To Add New Parent</div>
                        </div>

                        <form method="post" action="" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">

                                    {{-- First Name --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">First Name <span style="color:red">*</span></label>
                                        <input name="name" value="{{ old('name') }}" placeholder="First Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('name') }}</div>
                                    </div>

                                    {{-- Last Name --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Last Name <span style="color:red">*</span></label>
                                        <input name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('last_name') }}</div>

                                    </div>

                        
                                    {{-- Gender --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Gender<span style="color:red">*</span></label>
                                        <select name="gender" required  class="form-control">
                                            <option value="">Select</option>
                                            <option {{ (old('gender')=='Male')?'selected' : '' }} value="Male">Male</option>
                                            <option {{ (old('gender')=='Female')?'selected' : '' }} value="Female">Female</option>
                                            <option {{ (old('gender')=='Other')?'selected' : '' }} value="Other">Other</option>
                                        </select>
                                    <div style="color:red">{{ $errors->first('gender') }}</div></div>

                        

                                  
                                    {{-- Mobile Number --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Mobile Number</label>
                                        <input name="mobile_number" value="{{ old('mobile_number') }}" placeholder="Enter Number" class="form-control" />
                                    <div style="color:red">{{ $errors->first('mobile_number') }}</div></div>

                                    <div class="form-group col-md-6">
                                        <label class="form-label">Address</label>
                                        <input name="address" value="{{ old('address') }}" placeholder="address" class="form-control" />
                                    <div style="color:red">{{ $errors->first('address') }}</div></div>

                                    {{-- Blood Group --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Blood Group</label>
                                        <input name="blood_group" value="{{ old('blood_group') }}" placeholder="Blood Group" class="form-control" />
                                    <div style="color:red">{{ $errors->first('blood_group') }}</div></div>

                                    {{-- Profile Picture --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Profile Picture</label>
                                        <input type="file" value="{{ old('profile_pic') }}" name="profile_pic" class="form-control" />
                                    <div style="color:red">{{ $errors->first('profile_pic') }}</div></div>
                                    {{-- occupation --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Occupation</label>
                                        <input name="occupation" value="{{ old('occupation') }}" placeholder="Enter occupation" class="form-control" />
                                    <div style="color:red">{{ $errors->first('occupation') }}</div></div>
                               
                                </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Status<span style="color:red">*</span></label>
                                        <select name="status" required value="{{ old('status') }}" class="form-control">
                                            <option value="">Select</option>
                                            <option {{ (old('gender')=='0')?'selected' : '' }} value="0">Active</option>
                                            <option {{ (old('gender')=='1')?'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                   <div style="color:red">{{ $errors->first('status') }}</div> </div>
                                      <hr />
                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address<span style="color:red">*</span></label>
                                    <input name="email" value="{{ old('email') }}" required placeholder="Email" type="email" class="form-control" />
                                    <div style="color:red">{{ $errors->first('email') }}</div>
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label">Password<span style="color:red">*</span></label>
                                    <input type="password" name="password" placeholder="Password" required class="form-control" />
                                <div style="color:red">{{ $errors->first('password') }}</div></div>

                                {{-- Hidden Fields --}}
                                <input type="hidden" name="user_type" value="3">
                                <input type="hidden" name="is_delete" value="0">

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
