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
                <h3 class="mb-0">Edit Assign Teacher</h3>
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
                    <div class="card-title">Fill  the Details To Edit Assign Teacher</div>
                  </div>
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form method="post" action="">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                         <div class="mb-3">
                        <label  class="form-label">Class Name</label>
                           <select name="class_id" required class="form-control" id="">
                          <option value="">Select Class</option>
                                @foreach($getClass as $class)
                                <option {{ ($getRecord->class_id == $class->id) ? 'selected' : ' '}} value="{{$class->id}}">{{$class->name}}</option>
                                @endforeach
                        </select>

                      </div>

                         <div class="mb-3">
                        <label  class="form-label"> Teacher Name</label>
                       
                        @foreach ($getTeacherClass as $teacher)
                            <div>
                                <label style="font-weight:normal">
                                    <input type="checkbox"
                                        name="teacher_id"
                                        value="{{ $teacher->id }}"
                                        {{ ($getRecord->teacher_id == $teacher->id) ? 'checked' : '' }}>
                                    {{ $teacher->name }} {{ $teacher->last_name }}
                                </label>
                            </div>
                        @endforeach


                      </div>
                      <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" class="form-control" id="">
                          <option {{ ($getRecord->status == 0) ? 'selected' : ' '}} value="0">Active</option>
                          <option {{ ($getRecord->status == 1) ? 'selected' : ' '}} value="1">Inactive</option>
                        </select>

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