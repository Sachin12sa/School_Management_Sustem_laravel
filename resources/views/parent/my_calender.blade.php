@extends('layouts.app')

@section('style')
<style>
.fc-v-event .fc-event-main-frame { white-space: normal !important; }
.fc-direction-ltr .fc-daygrid-event.fc-event-start,
.fc-direction-rtl .fc-daygrid-event.fc-event-end { white-space: normal !important; }
.fc .fc-daygrid-event-harness-abs { position: relative; }
.calendar-legend span { display:inline-block;width:14px;height:14px;border-radius:3px;vertical-align:middle; }
</style>
@endsection

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Calendar</h4>
                            <span class="text-muted small">
                                <i class="bi bi-person me-1"></i>
                                <strong>{{ $getStudent->name }} {{ $getStudent->last_name }}</strong>
                                @isset($student_class_name)
                                    &nbsp;&middot;&nbsp; Class: {{ $student_class_name }}
                                @endisset
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <div class="calendar-legend d-inline-flex align-items-center gap-3 small text-muted me-3">
                        <span><span style="background:#4e89d4;"></span>&nbsp;Class</span>
                        <span><span style="background:#e74c3c;"></span>&nbsp;Exam</span>
                    </div>
                    <a href="{{ url('parent/my_student') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
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
document.addEventListener('DOMContentLoaded', function () {
    var events = [];

    @if(!empty($getTimetable))
        @foreach($getTimetable as $value)
            @if(!empty($value['week']))
                @foreach($value['week'] as $week)
                    events.push({
                        title: '{{ $value['subject_name'] }} ({{ date("h:i A", strtotime($week['start_time'])) }}–{{ date("h:i A", strtotime($week['end_time'])) }})',
                        daysOfWeek: [{{ (int)$week['fullcalender_day'] }}],
                        startTime: '{{ $week['start_time'] }}',
                        endTime:   '{{ $week['end_time'] }}',
                        color: '#4e89d4'
                    });
                @endforeach
            @endif
        @endforeach
    @endif

    @if(!empty($getExamTimetable))
        @foreach($getExamTimetable as $valueE)
            @if(!empty($valueE['exam']))
                @foreach($valueE['exam'] as $exam)
                    events.push({
                        title: 'Exam: {{ $valueE['name'] }} · {{ $exam['subject_name'] }} ({{ date("h:i A", strtotime($exam['start_time'])) }}–{{ date("h:i A", strtotime($exam['end_time'])) }})',
                        start: '{{ $exam['exam_date'] }}T{{ $exam['start_time'] }}',
                        end:   '{{ $exam['exam_date'] }}T{{ $exam['end_time'] }}',
                        color: '#e74c3c',
                        url:   '{{ url("parent/my_student/exam_timetable/".$getStudent->id) }}'
                    });
                @endforeach
            @endif
        @endforeach
    @endif

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        allDaySlot: false,
        slotMinTime: '06:00:00',
        slotMaxTime: '20:00:00',
        events: events,
        height: 'auto'
    });

    calendar.render();
});
</script>
@endsection