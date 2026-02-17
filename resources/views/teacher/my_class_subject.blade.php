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
                  {{-- <small class="text-muted">(Total : {{ $getRecord->total() }})</small> --}}
               </h3>
            </div>
            <div class="col-sm-6 text-end">
               <a href="{{ url('admin/assign_class_teacher/add') }}" class="btn btn-primary">
               + Add New Assign Class Teacher 
               </a>
            </div>
         </div>
         <!-- Search Card -->

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
                        <th>Subject Name</th>
                        <th>Subject Type</th>
                        <th>Created Date</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if($getRecord && $getRecord->count())
                        @foreach($getRecord as $key => $value)
                            <tr>
                                <td>{{ $key +1  }}</td>
                                <td>{{ $value->class_name }}</td>
                                <td>{{ $value ->subject_name}} </td>
                                <td>{{ $value ->subject_type}} </td>
                                <td>{{ $value->created_at->format('d-m-Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No record found</td>
                        </tr>
                    @endif

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