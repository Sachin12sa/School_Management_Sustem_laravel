@extends('layouts.app')

@section('style')
<style>
/* ── FullCalendar overrides ─────────────────────────────────────── */
#calendar { border-radius:.75rem; overflow:hidden; }

.fc .fc-toolbar-title { font-size:1.1rem; font-weight:700; color:#1a1a2e; }

.fc .fc-button-primary {
    background:#1a73e8; border-color:#1a73e8; font-size:.78rem;
    padding:.3rem .7rem; border-radius:.4rem;
}
.fc .fc-button-primary:hover { background:#1558b0; border-color:#1558b0; }
.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active { background:#0d47a1; border-color:#0d47a1; }

/* Class event = info blue */
.fc-daygrid-event.class-event,
.fc-timegrid-event.class-event {
    background: rgba(13,110,253,.75) !important;
    border-color: rgba(13,110,253,.9) !important;
    border-radius: .3rem;
}
/* Exam event = danger red */
.fc-daygrid-event.exam-event,
.fc-timegrid-event.exam-event {
    background: rgba(220,53,69,.8) !important;
    border-color: rgba(220,53,69,1) !important;
    border-radius: .3rem;
}

.fc-v-event .fc-event-main-frame { white-space:normal!important; }
.fc-direction-ltr .fc-daygrid-event.fc-event-start { white-space:normal!important; }
.fc .fc-daygrid-event-harness-abs { position:relative; }
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
                            <h4 class="mb-0 fw-semibold text-dark">My Calendar</h4>
                            <span class="text-muted small">Classes &amp; exam schedule in one view</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- Legend --}}
            <div class="d-flex align-items-center gap-4 mb-3 ps-1 flex-wrap">
                <div class="d-flex align-items-center gap-2 small">
                    <span class="rounded-1 d-inline-block" style="width:14px;height:14px;background:rgba(13,110,253,.75);"></span>
                    <span class="text-muted fw-semibold">Regular Class</span>
                </div>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="rounded-1 d-inline-block" style="width:14px;height:14px;background:rgba(220,53,69,.8);"></span>
                    <span class="text-muted fw-semibold">Exam</span>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-3">
                <div id="calendar"></div>
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

    {{-- Regular timetable events --}}
    @if(!empty($getTimetable))
        @foreach($getTimetable as $value)
            @if(!empty($value['week']))
                @foreach($value['week'] as $week)
                    events.push({
                        title: '{{ addslashes($value['subject_name']) }} · {{ date("h:i", strtotime($week['start_time'])) }}–{{ date("h:i A", strtotime($week['end_time'])) }}',
                        daysOfWeek: [{{ $week['fullcalender_day'] }}],
                        startTime: '{{ $week['start_time'] }}',
                        endTime:   '{{ $week['end_time'] }}',
                        classNames: ['class-event'],
                    });
                @endforeach
            @endif
        @endforeach
    @endif

    {{-- Exam events --}}
    @if(!empty($getExamTimetable))
        @foreach($getExamTimetable as $valueE)
            @if(!empty($valueE['exam']))
                @foreach($valueE['exam'] as $exam)
                    events.push({
                        title: '📝 Exam: {{ addslashes($valueE['name']) }} · {{ addslashes($exam['subject_name']) }}',
                        start: '{{ $exam['exam_date'] }}T{{ $exam['start_time'] }}',
                        end:   '{{ $exam['exam_date'] }}T{{ $exam['end_time'] }}',
                        classNames: ['exam-event'],
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
        allDaySlot:  false,
        slotMinTime: '06:00:00',
        slotMaxTime: '20:00:00',
        height:      'auto',
        events:      events,
        eventDidMount: function (info) {
            // Bootstrap tooltip on hover
            info.el.setAttribute('title', info.event.title);
        }
    });

    calendar.render();
});
</script>
@endsection