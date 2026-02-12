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
                                        <input name="name" value="{{ old('name',$getRecord->name) }}" placeholder="First Name"  class="form-control" />
                                        <div style="color:red">{{ $errors->first('name') }}</div>
                                    </div>

                                    {{-- Last Name --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Last Name <span style="color:red">*</span></label>
                                        <input name="last_name" value="{{ old('last_name',$getRecord->last_name) }}" placeholder="Last Name"  class="form-control" />
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

                                    {{-- Date of Birth --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Date of Birth<span style="color:red">*</span></label>
                                        <input type="date" value="{{ old('date_of_birth' ,$getRecord->date_of_birth )}}"  name="date_of_birth" class="form-control" />
                                    <div style="color:red">{{ $errors->first('name') }}</div></div>
                                      

                                   
                                    {{-- Mobile Number --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Mobile Number</label>
                                        <input name="mobile_number" value="{{ old('mobile_number' ,$getRecord->mobile_number)}}" placeholder="Enter Number" class="form-control" />
                                    <div style="color:red">{{ $errors->first('mobile_number') }}</div></div>

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
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Religion</label>
                                        <input name="religion" value="{{ old('religion',$getRecord->religion) }}" placeholder="Enter religion" class="form-control" />
                                    <div style="color:red">{{ $errors->first('religion') }}</div></div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Height</label>
                                        <input name="height" value="{{ old('height',$getRecord->height) }}" placeholder="Height" class="form-control" />
                                    <div style="color:red">{{ $errors->first('height') }}</div></div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Weight</label>
                                        <input name="weight" value="{{ old('weight',$getRecord->weight) }}" placeholder="Weight" class="form-control" />
                                    <div style="color:red">{{ $errors->first('weight') }}</div></div>

                                </div>
                      
                                      <hr />
                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address<span style="color:red">*</span></label>
                                    <input name="email" value="{{ old('email',$getRecord->email) }}"  placeholder="Email" type="email" class="form-control" />
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
