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
                        <th>My Class TimeTable</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($getRecord as $key => $value)
                     <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->subject_name }}</td>
                        <td>{{ $value->subject_type == 0 ? 'Theory' : 'Practical' }}</td>
                                      <td>
                                 @php
                                    $ClassSubject = $value->getMyTimeTable($value->class_id,$value->subject_id);
                                 @endphp
                                 <div style="background-color: lightgreen;" >
                                    @if($ClassSubject?->start_time)
                                       {{ date('h:i A', strtotime($ClassSubject->start_time)) }}
                                       to
                                       {{ date('h:i A', strtotime($ClassSubject->end_time)) }}
                                       <br>
                                       Room Number : {{$ClassSubject->room_number}}
                                 </div>
                                    
                                    @else
                                      <div style="background-color: red;">
                                       No Class Today
                                       </div> 
                                    @endif

                                 
                                </td>
                        <td>
                           <a href="{{ url('parent/my_student/subject/class_timetable/'.$value->class_id.'/'.$value->subject_id.'/'.$getUser->id) }}" class="btn btn-primary">
                              Class TimeTable
                           </a>

                        </td>
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