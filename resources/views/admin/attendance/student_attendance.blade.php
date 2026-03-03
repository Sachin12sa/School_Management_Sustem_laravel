@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Student Attendance
                        {{-- <small class="text-muted">Total Exams: {{ $totalExam }}</small> --}}
                    </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search class and date</h3>
                        </div>

                        <form method="GET" action="{{ url('admin/attendance/student_attendance') }}">
                            <div class="card-body form-control">
                                <div class="row align-items-end">


                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Choose Class</label>
                                        <select name="class_id" id="getClass" required class="form-control">
                                            <option value="">Select Class</option>
                                            @foreach($getClass as $class)
                                                <option {{ (Request::get('class_id') == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                   <div class="mb-3 col-md-4 position-relative">
                                        <label for="attendance_date" class="form-label">Choose Attendance Date</label>
                                        <div class="input-group">
                                            <input 
                                                type="date" 
                                                class="form-control" 
                                                name="attendance_date" 
                                                id="attendance_date" 
                                                placeholder="Select date"
                                                value="{{ Request::get('attendance_date') }}" 
                                            >
                                            <span class="input-group-text date-icon" onclick="document.getElementById('attendance_date').showPicker()">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>



                                    <div class="col-md-4" style="margin-bottom: 15px;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('admin/attendance/student_attendance') }}" class="btn btn-success ms-1">Reset</a>
                                    </div>

                                </div>
                            </div>
                        </form>
                         @include('message')

                    </div>
                   @if(!empty(Request::get('class_id')) && !empty(Request::get('attendance_date')))
                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Student List</h3>
                    </div>

                    <form action="{{ url('admin/examination/submit_marks_register') }}" method="post" id="SubmitMarksForm">
                        @csrf
                        

                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th> Student Id </th>
                                        <th>Student Name</th>
                                        <th>Attendance</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($getStudent) && $getStudent->count() > 0)
                                    @foreach ( $getStudent as $value)
                                    @php
                                        $attendance_type = '';
                                        $getAttendance = $value->getAttendance($value->id,Request::get('class_id'),Request::get('attendance_date'));
                                        if(!empty($getAttendance->attendance_type))
                                        {
                                            $attendance_type = $getAttendance->attendance_type;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$value->id}}</td>
                                        <td>{{$value->name}} {{$value->last_name}}</td>
                                        <td>
                                            <label  for=""> <input type="radio"{{($attendance_type == '1') ? 'checked' : ''}} name="attendance{{$value->id}}" value="1" class="SaveAttendance" data-student-id="{{ $value->id }}">Present</label>
                                            <label  for=""> <input type="radio" {{($attendance_type == '2') ? 'checked' : ''}}  name="attendance{{$value->id}}" value="2" class="SaveAttendance"  data-student-id="{{ $value->id }}">Absent</label>
                                            <label  for=""> <input type="radio" {{($attendance_type == '3') ? 'checked' : ''}} name="attendance{{$value->id}}" value="3" class="SaveAttendance"  data-student-id="{{ $value->id }}">Late</label>
                                            <label  for=""> <input type="radio" {{($attendance_type == '4') ? 'checked' : ''}} name="attendance{{$value->id}}" value="4" class="SaveAttendance"  data-student-id="{{ $value->id }}">Half Day</label>
                                        </td>
                                    </tr>
                                        
                                    @endforeach
                                    <tr>

                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                    </div>
                @endif
            
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

@section('script')
<script type="text/javascript">

function showAjaxMessage(message, type) {
    const messageDiv = $('#ajax-response-message');

    messageDiv.removeClass('alert-success alert-danger alert-warning alert-info');

    if (type === 'success') {
        messageDiv.addClass('alert alert-success');
    } else {
        messageDiv.addClass('alert alert-danger');
    }

    messageDiv.stop(true, true).hide().html(message).fadeIn();

    setTimeout(function () {
        messageDiv.fadeOut();
    }, 4000);
}

$('.SaveAttendance').change(function () {

    var student_id = $(this).data('student-id');
    var attendance_type = $(this).val();
    var class_id = $('#getClass').val();
    var attendance_date = $('#attendance_date').val();

    if (!class_id || !attendance_date) {
        showAjaxMessage('Please select class and date first.', 'danger');
        return;
    }

    $.ajax({
        type: "POST",
        url: "{{ url('admin/attendance/student_attendance_save') }}",
        data: {
            _token: "{{ csrf_token() }}",
            student_id: student_id,
            attendance_type: attendance_type,
            class_id: class_id,
            attendance_date: attendance_date
        },
        dataType: "json",

        success: function (data) {
            showAjaxMessage(data.message, 'success');
        },

        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON) {
                showAjaxMessage(xhr.responseJSON.message, 'danger');
            } else {
                showAjaxMessage('A server error occurred. Please try again.', 'danger');
            }
        }
    });
});
</script>
@endsection