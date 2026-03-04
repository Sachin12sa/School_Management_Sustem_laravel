@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Student Attendance Reports
                        <small style="background-color: skyblue;" class="text-muted">Total : {{ $getRecord->total() }}</small>
                    </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Student Attendance Report</h3>
                        </div>

                        <form method="GET" action="{{ url('admin/attendance/attendance_report') }}">
                            <div class="card-body form-control">
                                <div class="row align-items-end">


                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Choose Class</label>
                                        <select name="class_id" id="getClass"  class="form-control">
                                            <option value="">Select Class</option>
                                            @foreach($getClass as $class)
                                                <option {{ (Request::get('class_id') == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Student Name</label>
                                       <input 
                                                type="text" 
                                                class="form-control" 
                                                name="student_name" 
                                                id="student_name" 
                                                placeholder="Student name"
                                                value="{{ Request::get('student_name') }}" 
                                            >
                                        </select>
                                    </div>
                                   <div class="mb-3 col-md-6 position-relative">
                                        <label for="attendance_date" class="form-label">Start Attendance Date</label>
                                        <div class="input-group">
                                            <input 
                                                type="date" 
                                                class="form-control" 
                                                name="start_attendance_date" 
                                                id="start_attendance_date" 
                                                placeholder="Select date"
                                                value="{{ Request::get('attendance_date') }}" 
                                            >
                                            <span class="input-group-text date-icon" onclick="document.getElementById('start_attendance_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-2 position-relative">
                                        <label for="attendance_date" class="form-label">End Attendance Date</label>
                                        <div class="input-group">
                                            <input 
                                                type="date" 
                                                class="form-control" 
                                                name="end_attendance_date" 
                                                id="end_attendance_date" 
                                                placeholder="Select date"
                                                value="{{ Request::get('attendance_date') }}" 
                                            >
                                            <span class="input-group-text date-icon" onclick="document.getElementById('end_attendance_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Choose Attendance Type</label>
                                        <select name="attendance_type" id="getClass"  class="form-control">
                                            <option value="">Select Attendance Type</option>
                                            <option  {{ (Request::get('attendance_type') == 1) ? 'selected' : '' }} value="1"> Present </option>
                                            <option  {{ (Request::get('attendance_type') == 2) ? 'selected' : '' }} value="2"> Absent </option>
                                            <option  {{ (Request::get('attendance_type') == 3) ? 'selected' : '' }} value="3"> Late </option>
                                            <option  {{ (Request::get('attendance_type') == 4) ? 'selected' : '' }} value="4"> Half Day </option>

                                        </select>
                                    </div>



                                    <div class="col-md-2" style="margin-bottom: 15px;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('admin/attendance/attendance_report') }}" class="btn btn-success ms-1">Reset</a>
                                    </div>

                                </div>
                            </div>
                        </form>
                         @include('message')

                    </div>
                   {{-- @if(!empty(Request::get('class_id')) || !empty(Request::get('student_name')) || !empty(Request::get('attendance_date')) || !empty(Request::get('attendance_type'))) --}}
                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Attendance List</h3>
                    </div>

                    <form action="{{ url('admin/examination/attendance_report') }}" method="post" id="SubmitMarksForm">
                        @csrf
                        

                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Student Id</th>
                                            <th>Student Name</th>
                                            <th>Created Name</th>
                                            <th>Class Name</th>
                                            <th>Attendance</th>
                                            <th>Attendance Date</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>{{ $value->student_name }} {{ $value->student_last_name }}</td>
                                            <td>{{ $value->created_name }} {{ $value->created_last_name }}</td>
                                            <td>{{ $value->class_name }}</td>
                                            <td>
                                                @if($value->attendance_type == 1)
                                                    <div style="color: green;">Present</div>
                                                @elseif($value->attendance_type == 2)
                                                    <div style="color: red;">Absent</div>
                                                @elseif($value->attendance_type == 3)
                                                    <div style="color: orange;">Late</div>
                                                @elseif($value->attendance_type == 4)
                                                    <div style="color: blue;">Half Day</div>
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                                            <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7">Record Not Found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            
                            <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div> 
                        </div>
                    </form>
                    </div>
                {{-- @endif --}}
            
                </div>
            </div>
        </div>
    </div>
    <div id="ajax-response-message" 
     class="alert" 
     style="display:none;">
</div>

    <div class="app-content">
        <div class="container-fluid">

           
        </div>
    </div>

</main>
@endsection
