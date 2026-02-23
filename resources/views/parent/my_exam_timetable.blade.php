@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">Exam TimeTable <span style="color: blue;"> ({{$getStudent->name}} {{$getStudent->last_name}})</span></h3>
                </div>
            </div>

            @include('message')

            @forelse ($getRecord as $value)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $value['name'] }}</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Day</th>
                                <th>Exam Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Room number</th>
                                <th>Full mark</th>
                                <th>Passing Mark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($value['exam'] as $valueS)
                                <tr>
                                    <td>{{ $valueS['subject_name'] }}</td>
                                    <td>{{ date('l', strtotime($valueS['exam_date'])) }}</td>
                                    <td>{{ date('d M Y', strtotime($valueS['exam_date'])) }}</td>
                                    <td>{{ date('h:i A', strtotime($valueS['start_time'])) }}</td>
                                    <td>{{ date('h:i A', strtotime($valueS['end_time'])) }}</td>
                                    <td>{{ $valueS['room_number'] }}</td>
                                    <td>{{ $valueS['full_mark'] }}</td>
                                    <td>{{ $valueS['passing_mark'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Exam Schedule Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
