@extends('layouts.app')
@section('content')
<main class="app-main">
   <!-- Header -->
   <div class="app-content-header">
      <div class="container-fluid">
         <div class="row align-items-center mb-3">
            <div class="col-sm-6">
               <h3 class="mb-0">
                  Assign Class Teacher 
                  <small class="text-muted">(Total : {{ $getRecord->total() }})</small>
               </h3>
            </div>
            <div class="col-sm-6 text-end">
               <a href="{{ url('admin/assign_class_teacher/add') }}" class="btn btn-primary">
               + Add New Assign Class Teacher 
               </a>
            </div>
         </div>
         <!-- Search Card -->
         <div class="row mb-4">
            <div class="col-md-12">
               <div class="card card-primary card-outline">
                  <div class="card-header">
                     <h3 class="card-title">Search Assign Class Teacher  </h3>
                  </div>
                  <form method="get" action="">
                     <div class="card-body">
                        <div class="row align-items-end">
                           <div class="col-md-3">
                              <label class="form-label"> Class Name</label>
                              <input
                                 type="text"
                                 name="class_name"
                                 value="{{ request('name') }}"
                                 class="form-control"
                                 placeholder="Enter name"
                                 />
                           </div>
                   <div class="col-md-3">
                     <label class="form-label">Teacher First Name</label>
                     <input
                        type="text"
                        name="teacher_name"
                        value="{{ request('teacher_name') }}"
                        class="form-control"
                        placeholder="Enter first name"
                     />
                  </div>

                  <div class="col-md-3">
                     <label class="form-label">Teacher Last Name</label>
                     <input
                        type="text"
                        name="teacher_last_name"
                        value="{{ request('teacher_last_name') }}"
                        class="form-control"
                        placeholder="Enter last name"
                     />
                  </div>

                           <div class="col-md-3">
                              <button type="submit" class="btn btn-primary">
                              Search
                              </button>
                              <a href="{{ url('admin/assign_class_teacher/list') }}" class="btn btn-success ms-1">
                              Reset
                              </a>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Content -->
   <div class="app-content">
      <div class="container-fluid">
         @include('message')
         <div class="card">
            <div class="card-header">
               <h3 class="card-title"> Assign Class Teacher  List</h3>
            </div>
            <div class="card-body p-0">
               <table class="table table-striped mb-0">
                  <thead>
                     <tr>
                        <th>S.N</th>
                        <th>Assigned class</th>
                        <th>Teacher Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th width="180">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($getRecord as $key => $value)
                     <tr>
                        <td>{{ $getRecord->firstItem() + $key }}</td>
                        <td>{{ $value->class_name }}</td>
                        <td>{{ $value->teacher_name }}  {{ $value->teacher_last_name }}</td>
                        <td>
                           <span class="badge {{ $value->status == 0 ? 'bg-success' : 'bg-danger' }}">
                           {{ $value->subject_type == 0 ? 'Active' : 'Inactive' }}
                           </span>
                        </td>
                        <td>{{ $value->created_by_name }}</td>
                        <td>{{ $value->created_at->format('d-m-Y h:i A') }}</td>
                        <td class="d-flex justify-content-center gap-3">
                           <a href="{{ url('admin/assign_class_teacher/edit/'.$value->id) }}"
                              class="btn btn-sm btn-primary">
                           Edit
                           </a>
                           <a href="{{ url('admin/assign_class_teacher/edit_single/'.$value->id) }}"
                              class="btn btn-sm btn-primary">
                           Edit Single
                           </a>
                           <a href="{{ url('admin/assign_class_teacher/delete/'.$value->id) }}"
                              class="btn btn-sm btn-danger"
                              onclick="return confirm('Are you sure you want to delete this subject?')">
                           Delete
                           </a>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            <div class="card-footer text-end">
               {{ $getRecord->appends(request()->query())->links() }}
            </div>
            <!-- Pagination -->
            <div class="card-footer text-end">
            </div>
         </div>
      </div>
   </div>
</main>
@endsection