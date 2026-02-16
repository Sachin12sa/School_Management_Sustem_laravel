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
                <h3 class="mb-0">Add New  Assign Class Teacher </h3>
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
                    <div class="card-title">Fill All the Details To Add New  Assign Class to Teacher </div>
                  </div>
                  @include('message')
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form method="post" action="">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label"> Assigned Class Name</label>
                           <select name="class_id" required class="form-control" id="">
                          <option value="">Select Class</option>
                                @foreach($getClass as $class)
                                <option value="{{$class->id}}">{{$class->name}}</option>
                                @endforeach
                        </select>

                      </div>
                      <div class="mb-3">
                        <label  class="form-label"> Assign Teacher Name</label>
                                @foreach($getTeacherClass as $teacher)
                                <div>
                                     <label style="font-weight: normal;">
                                  <input type="checkbox" value="{{$teacher->id}}"  name="teacher_id[]" id="">{{$teacher->name}} {{$teacher->last_name}}
                                </input>
                                </div>
                               
                                @endforeach
                        </select>

                      </div>

                      <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control" id="">
                          <option value="0">Active</option>
                          <option value="1">Inactive</option>
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