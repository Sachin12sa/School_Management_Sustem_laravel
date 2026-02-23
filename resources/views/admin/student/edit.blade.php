@extends('layouts.app')    
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Student</h3>
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
                            <div class="card-title">Fill Details To Edit Student</div>
                        </div>

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

                                    {{-- Admission Number --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Admission Number <span style="color:red">*</span></label>
                                        <input name="admission_number" value="{{ old('admission_number',$getRecord->admission_number) }}" placeholder="Admission Number"  class="form-control" />
                                       <div style="color:red">{{ $errors->first('admission_number') }}</div>                                       

                                    </div>

                                    {{-- Roll Number --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Roll Number</label>
                                        <input name="roll_number" value="{{ old('roll_number',$getRecord->roll_number) }}" placeholder="Roll Number" class="form-control" />
                                    <div style="color:red">{{ $errors->first('roll_number') }}</div></div>

                                    {{-- Class --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Class <span style="color:red">*</span></label>
                                        <select name="class_id" class="form-control" >
                                            <option value="">Select Class</option>
                                            @foreach($getClass as $class)
                                                <option {{ (old('class_id',$getRecord->class_id)==$class->id) ? 'selected' : ''}} value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    <div style="color:red">{{ $errors->first('class_id') }}</div></div>

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
                                        <input type="date" id="date" value="{{ old('date_of_birth' ,$getRecord->date_of_birth )}}"  name="date_of_birth" class="form-control" />
                                        <span  class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    <div style="color:red">{{ $errors->first('name') }}</div></div>
                                      

                                    {{-- Admission Date --}}
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Admission Date<span style="color:red">*</span></label>
                                        <input type="date" id="date" name="admission_date" value="{{ old('admission_date',$getRecord->admission_date) }}"  class="form-control" />
                                        <span  class="input-group-text" onclick="document.getElementById('date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    <div style="color:red">{{ $errors->first('admission_date') }}</div></div>

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
                                    <input name="email" value="{{ old('email',$getRecord->email) }}"  placeholder="Email" type="email" class="form-control" />
                                    <div style="color:red">{{ $errors->first('email') }}</div>
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label class="form-label">Password<span style="color:red">*</span></label>
                                    <input type="password" name="password" placeholder="Password"  class="form-control" />
                                    <p>Do you want to change password? Ifso,Please add new password</p>
                                <div style="color:red">{{ $errors->first('password') }}</div></div>
                                

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
