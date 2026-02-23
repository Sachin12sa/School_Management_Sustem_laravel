@extends('layouts.app')    
@section('content')
<main class="app-main">
@section('style')
<style>
.fc-v-event .fc-event-main-frame {
    white-space: normal !important;
}

.fc-direction-ltr .fc-daygrid-event.fc-event-start,
.fc-direction-rtl .fc-daygrid-event.fc-event-end {
    background-color: rgb(140, 190, 210);
    white-space: normal !important;
}
.fc .fc-daygrid-event-harness-abs {
  
    position: relative;
}
</style>
@endsection
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    {{-- <h3 class="mb-0">My Calender <span style="color: blue;">{{$getStudent->name}} {{$getStudent->last_name}}</span></h3>
                    <h4 class="mb-0"><p>Class: {{ $student_class_name }}</p></h4> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-12">

                    <div id="calendar"></div>
              
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')

<script src="{{ asset('dist/fullcalender/index.global.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    var events = [];

    @if(!empty($getClassTimetable))
        @foreach ($getClassTimetable as $value)
                    events.push({
                        title: "Class : {{ $value->class_name }} {{ $value->subject_name }} ({{date('h:i A',strtotime($value->start_time))}}-{{date('h:i A',strtotime($value->end_time))}}) ",
                        daysOfWeek: [{{ (int)$value->fullcalender_day }}],
                        startTime: "{{ $value ->start_time}}",
                        endTime: "{{ $value->end_time}}"
                    });
                @endforeach
           
    @endif

      @if(!empty($getExamTimetableTeacher))
                @foreach ($getExamTimetableTeacher as $exam)

                    events.push({
                        title: "Exam Name:{{ $exam->exam_name }} SubjectName: {{$exam->subject_name}} ({{date('h:i A',strtotime($exam->start_time))}}-{{date('h:i A',strtotime($exam->end_time))}})",
                        start: "{{ $exam->exam_date }}T{{ $exam->start_time}}",
                        end: "{{ $exam->exam_date}}T{{ $exam->end_time}}",
                        color:'red',
                        url:'{{url('student/my_exam_timetable')}}'
                    });

                @endforeach
        @endif

        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            allDaySlot: false,
            slotMinTime: "06:00:00",
            slotMaxTime: "20:00:00",
            events: events
        });

    calendar.render();
});
</script>

@endsection
