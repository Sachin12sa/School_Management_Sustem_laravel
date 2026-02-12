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
                <h3 class="mb-0">Change Password</h3>
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
                   <div class="card-title">Change Your Password</div>

                  </div>
                  <!--end::Header-->
                  <!--begin::Form-->
                   @include('message')
                  <form method="post" action="{{ url('admin/profile/change_password') }}">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label"> Old Password</label>
                        <input
                          name="old_password"
                          required
                          placeholder="Enter Old Password"
                          type="password"
                          class="form-control"
                       
                        />
                      </div>
                    </div>
                         <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label"> New Password</label>
                        <input
                          name="new_password"
                          required
                          placeholder="Enter New Password"
                          type="password"
                          class="form-control"
                       
                        />
                      </div>
                    </div>
                     <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label"> Confirm Password</label>
                        <input
                          name="confirm_password"
                          required
                          placeholder="Enter Again"
                          type="password"
                          class="form-control"
                       
                        />
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