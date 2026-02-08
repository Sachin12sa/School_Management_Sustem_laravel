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
                <h3 class="mb-0">Add New Class</h3>
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
                    <div class="card-title">Fill All the Details To Add New Class</div>
                  </div>
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form method="post" action="">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label"> Subject Name</label>
                        <input
                          name="name"
                          required
                          placeholder="Subject name"
                          type="name"
                          class="form-control"
                       
                        />
                      </div>
                      <div class="mb-3">
                        <label class="form-label" required name="status">Subject Type</label>
                        <select name="type" class="form-control" id="">
                          <option value="0">Select Type</option>
                          <option value="0">Theory</option>
                          <option value="1">Practical</option>
                        </select>
                  
                      </div>
                      <div class="mb-3">
                        <label class="form-label" name="status">Status</label>
                        <select name="status" class="form-control" id="">
                          <option value="0">Active</option>
                          <option value="1">InActive</option>
                        </select>
                  
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