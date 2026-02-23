@extends('layouts.app')
@section('content')
<main class="app-main">
   <!-- Header -->
   <div class="app-content-header">
      <div class="container-fluid">
         <div class="row align-items-center mb-3">
            <div class="col-sm-6">
               <h3 class="mb-0">
               my TimeTable                       
            </div>
         </div>
         <!-- Search Card -->
         <div class="row mb-4">
            <div class="col-md-12">
               <div class="card card-primary card-outline">
                  <div class="card-header">
                     <h3 class="card-title">ClassName: {{$getClass->name}} <br> Subject Name: {{$getSubject->name}} <span style="color:blue;">{{$getUser->name}} {{$getUser->last_name}}</span></h3>
                  </div>
                
                     
                  
               </div>
            </div>
         </div>
         @include('message')
       
         <div class="card">
            <div class="card-header">
               
               <h3 class="card-title"></h3>
              
            <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Room Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeks as $week)
                    <tr>
                        <td>{{ $week['week_name'] }}</td>
                        <td>{{ !empty($week['start_time']) ? date('h:i A', strtotime($week['start_time'])) : '' }}</td>
                        <td>{{ !empty($week['end_time']) ? date('h:i A', strtotime($week['end_time'])) : '' }}</td>
                        <td>{{ $week['room_number'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

      </div>
   </div>
   <!-- Content -->
</main>
@endsection