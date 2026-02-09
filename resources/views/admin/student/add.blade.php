@extends('layouts.app')    
@section('content')
 <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Add New Student</h3>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row g-4">
              <!--begin::Col-->
    
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-md-12">
                <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header">
                    <div class="card-title">Fill Details To Add New Student </div>
                  </div>
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form method="post" action="">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label  class="form-label">First Name <span style="color: red;">*</span></label>
                                <input
                                name="name" value="{{old('name')}}"required placeholder="Enter the New Admin Name"  type="name" class="form-control"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label  class="form-label">Last Name <span style="color: red;">*</span></label>
                                <input
                                name="last_name" value="{{old('last_name')}}"required placeholder="Enter the New Admin Name"  type="name" class="form-control"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label  class="form-label">Admission Number <span style="color: red;">*</span></label>
                                <input
                                name="admission_number" value="{{old('admission_number')}}"required placeholder="Enter the New Admin Name"  type="name" class="form-control"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label  class="form-label">Roll Number <span style="color: red;"></span></label>
                                <input
                                name="roll_number" value="{{old('roll_number')}}" placeholder="Enter the New Admin Name"  type="name" class="form-control"/>
                            </div>

                        </div>
                         
                      <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input
                        name="email"
                        value="{{old('email ')}}"
                        required
                        placeholder="Enter Admin Email"
                        type="email"
                        class="form-control"
                     
                          aria-describedby="emailHelp"
                        />
                        <div style="color: red">{{$errors->first('email')}}</div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required placeholder="Enter the admin password" class="form-control" id="exampleInputPassword1" />
                      </div>
                
         
                    </div>
               
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
                </div>

              </div>
           
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
@endsection