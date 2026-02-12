@extends('layouts.app')
@section('content')
<main class="app-main">
   <!-- Header -->
   <div class="app-content-header">
   <div class="container-fluid">
      <div class="row align-items-center mb-3">
         <div class="col-sm-6">
            <h3 class="mb-0">
               My Student Subject List
               <br>
               <small class="text-muted" style="color: blue">{{$getUser->name}} {{$getUser->last_name}}</small>
            </h3>
         </div>
      </div>
   </div>
   <!-- Content -->
   <div class="app-content">
      <div class="container-fluid">
         @include('message')
         <div class="card">
            <div class="card-header">
               <h3 class="card-title"> My Student Subject List</h3>
            </div>
            <div class="card-body p-0">
               <table class="table table-striped mb-0">
                  <thead>
                     <tr>
                        <th>S.N</th>
                        <th>Subject Name</th>
                        <th> Subject Type</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($getRecord as $key => $value)
                     <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->subject_name }}</td>
                        <td>{{ $value->subject_type == 0 ? 'Theory' : 'Practical' }}</td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            <div class="card-footer text-end">
            </div>
         </div>
      </div>
   </div>
</main>
@endsection