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
                <h3 class="mb-0">My Account</h3>
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
                     @include('message')
                  <!--begin::Form-->
                  <form method="post" action="">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label">Name</label>
                        <input
                          name="name"
                          value="{{old('name',$getRecord->name)}}"
                          placeholder="Name"
                          type="name"
                          class="form-control"
                       
                        />
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input
                        name="email"
                        value="{{ old('email', $getRecord->email)}}"
                        placeholder="Enter Admin Email"
                        type="email"
                        class="form-control"
                     
                          aria-describedby="emailHelp"
                        />
                        <div style="color: red">{{$errors->first('email')}}</div>
                      </div>
                

                
         
                    </div>
               
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Update</button>
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