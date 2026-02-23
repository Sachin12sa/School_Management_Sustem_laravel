@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">My Exam TimeTable</h3>
                </div>
            </div>

            @include('message')
            @forelse ($getRecord as $class)

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Class: {{ $class['class_name'] }}</h3>
                    </div>

                    <div class="card-body p-0">

                        @foreach($class['exams'] as $exam)

                            <div class="p-3">
                                <h5 style="color: blue;" class="mb-3">Exam: {{ $exam['exam_name'] }}</h5>

                                <table class="table table-striped mb-4">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Exam Date</th>
                                            <th>Exam Day</th>
                                            <th>Start Time</th>

                                            <th>End Time</th>
                                            <th>Room Number</th>
                                            <th>Full Mark</th>
                                            <th>Passing Mark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($exam['subject'] as $subject)
                                            <tr>
                                                <td>{{ $subject['subject_name'] }}</td>
                                                <td>{{ $subject['exam_date'] }}</td>
                                                <td>{{date('l', strtotime($subject['exam_date'])) }}</td>
                                                <td>{{ date('h:i A', strtotime($subject['start_time'])) }}</td>
                                                <td>{{ date('h:i A', strtotime($subject['end_time'])) }}</td>
                                                <td>{{ $subject['room_number'] }}</td>
                                                <td>{{ $subject['full_mark'] }}</td>
                                                <td>{{ $subject['passing_mark'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        @endforeach

                    </div>
                </div>

            @empty
                <div class="alert alert-warning text-center">
                    No Exam Timetable Available
                </div>
@endforelse

        </div>
    </div>

</main>
@endsection
