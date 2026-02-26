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
                <h3 class="mb-0">Edit Marks Grade
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
                    <div class="card-title">Fill the details to Edit Marks Grades</div>
                  </div>
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form method="post" action="">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label"> Grade Name</label>
                        <input
                          name="name"
                          value="{{old('name',$getRecord ->name)}}"
                          required
                          placeholder="Enter the grade name"
                          type="name"
                          class="form-control"
                       
                        />
                      </div>
                           <div class="mb-3">
                        <label  class="form-label">Percent From</label>
                        <input
                          name="percent_from"
                          value="{{old('percent_from',$getRecord ->percent_from)}}"
                          required
                          placeholder="Percent From"
                          type="name"
                          class="form-control"
                       
                        />
                      </div>
                           <div class="mb-3">
                        <label  class="form-label">Percent To</label>
                        <input
                          name="percent_to"
                          value="{{old('percent_to',$getRecord ->percent_to)}}"
                          required
                          placeholder="Percent To"
                          type="name"
                          class="form-control"
                       
                        />
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