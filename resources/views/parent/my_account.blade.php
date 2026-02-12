@extends('layouts.app')    
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">My Account</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mb-4">

                        @include('message')
                        <form method="post" action="" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body">
                                <div class="row">

                                    {{-- First Name --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">First Name <span style="color:red">*</span></label>
                                        <input name="name" value="{{ old('name',$getRecord->name)}}" placeholder="First Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('name') }}</div>
                                    </div>

                                    {{-- Last Name --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Last Name <span style="color:red">*</span></label>
                                        <input name="last_name" value="{{ old('last_name',$getRecord->last_name)}}" placeholder="Last Name" required class="form-control" />
                                        <div style="color:red">{{ $errors->first('last_name') }}</div>

                                    </div>

                        
                                    {{-- Gender --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Gender<span style="color:red">*</span></label>
                                        <select name="gender"   class="form-control">
                                            <option value="">Select</option>
                                            <option {{ (old('gender',$getRecord->gender)=='Male')?'selected' : '' }} value="Male">Male</option>
                                            <option {{ (old('gender',$getRecord->gender)=='Female')?'selected' : '' }} value="Female">Female</option>
                                            <option {{ (old('gender',$getRecord->gender)=='Other')?'selected' : '' }} value="Other">Other</option>
                                        </select>
                                    <div style="color:red">{{ $errors->first('gender') }}</div></div>


                                    {{-- Mobile Number --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Mobile Number</label>
                                        <input name="mobile_number" value="{{ old('mobile_number',$getRecord->mobile_number)}}" placeholder="Enter Number" class="form-control" />
                                    <div style="color:red">{{ $errors->first('mobile_number') }}</div></div>

                                    <div class="form-group col-md-6">
                                        <label class="form-label">Address</label>
                                        <input name="address" value="{{ old('address',$getRecord->address)}}" placeholder="address" class="form-control" />
                                    <div style="color:red">{{ $errors->first('address') }}</div></div>

                                    {{-- Blood Group --}}
                                         <div class="form-group col-md-6">
                                        <label class="form-label">Blood Group</label>
                                        <input name="blood_group" value="{{ old('blood_group',$getRecord->blood_group) }}" class="form-control" />
                                    <div style="color:red">{{ $errors->first('blood_group') }}</div></div>

                                    {{-- Profile Picture --}}
                                       <div class="form-group col-md-6">
                                        <label class="form-label">Profile Picture</label>
                                        <input type="file" value="{{ old('profile_pic')}}" name="profile_pic" class="form-control" />
                                    <div style="color:red">{{ $errors->first('profile_pic') }}</div>
                                    @if(!empty($getRecord -> getProfile()))
                                    <img src="{{$getRecord->getProfile()}}" style="width:65px;height:75px" alt="">@endif
                                </div>
                                    {{-- occupation --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Occupation</label>
                                        <input name="occupation" value="{{ old('occupation',$getRecord->occupation)}}" placeholder="Enter occupation" class="form-control" />
                                    <div style="color:red">{{ $errors->first('occupation') }}</div></div>
                               
                                </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Status<span style="color:red">*</span></label>
                                        <select name="status"  value="{{ old('status',$getRecord->status) }}" class="form-control">
                                            <option value="">Select</option>
                                            <option {{ (old('status',$getRecord->status)=='0')?'selected' : '' }} value="0">Active</option>
                                            <option {{ (old('status',$getRecord->status)=='1')?'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                   <div style="color:red">{{ $errors->first('status') }}</div> </div>
                                      <hr />
                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address<span style="color:red">*</span></label>
                                    <input name="email" value="{{ old('email',$getRecord->email)}}" required placeholder="Email" type="email" class="form-control" />
                                    <div style="color:red">{{ $errors->first('email') }}</div>
                                </div>

                                                         

                                {{-- Hidden Fields --}}
                                <input type="hidden" name="user_type" value="3">
                                <input type="hidden" name="is_delete" value="0">

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
