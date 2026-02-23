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
                    <h3 class="mb-0">My Calender</h3>
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

    @if(!empty($getTimetable))
        @foreach ($getTimetable as $value)
            @if(!empty($value['week']))
                @foreach ($value['week'] as $week)

                    events.push({
                        title: "{{ $value['subject_name'] }} ({{date('h:i A',strtotime($week['start_time']))}}-{{date('h:i A',strtotime($week['end_time']))}}) ",
                        daysOfWeek: [{{ $week['fullcalender_day'] }}],
                        startTime: "{{ $week['start_time'] }}",
                        endTime: "{{ $week['end_time'] }}"
                    });

                @endforeach
            @endif
        @endforeach
    @endif


    @if(!empty($getExamTimetable))
        @foreach ($getExamTimetable as $valueE)
            @if(!empty($valueE['exam']))
                @foreach ($valueE['exam'] as $exam)

                    events.push({
                        title: "Exam Name:{{ $valueE['name'] }} SubjectName: {{$exam['subject_name']}} ({{date('h:i A',strtotime($exam['start_time']))}}-{{date('h:i A',strtotime($exam['end_time']))}})",
                        start: "{{ $exam['exam_date'] }}T{{ $exam['start_time'] }}",
                        end: "{{ $exam['exam_date'] }}T{{ $exam['end_time'] }}",
                        color:'red',
                       
                    });

                @endforeach
            @endif
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
