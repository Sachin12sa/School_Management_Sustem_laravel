@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Exam Schedule List
                        {{-- <small class="text-muted">Total Exams: {{ $totalExam }}</small> --}}
                    </h3>
                </div>
             
            </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Exam Schedule</h3>
                        </div>

                            <form method="GET" action="{{ url('admin/examination/exam_schedule') }}">
                                <div class="card-body">
                                    <div class="row align-items-end">

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Exam Name</label>
                                            <select name="exam_id" required class="form-control">
                                                <option value="">Select Exam</option>
                                                @foreach($getExam as $exam)
                                                    <option {{ (Request::get('exam_id') == $exam->id ) ? 'selected' : '' }} value="{{ $exam->id }}">
                                                        {{ $exam->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Assign Subject Name</label>
                                            <select name="class_id" required class="form-control">
                                                <option value="">Select Class</option>
                                                @foreach($getClass as $class)
                                                    <option {{ (Request::get('class_id') == $class->id ) ? 'selected' : '' }} value="{{ $class->id }}">
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                            <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/examination/exam_schedule') }}" class="btn btn-success ms-1">
                                            Reset
                                        </a>
                                    </div>

                                    </div>
                                </div>
                            </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            @if(!empty($getRecord))
            <form action=" {{url('admin/examination/exam_schedule_insert')}}" method="post">
                @csrf
                <input type="hidden" name="exam_id" value="{{Request::get('exam_id')}}" id="">
                <input type="hidden" name="class_id" value="{{Request::get('class_id')}}" id="">
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Exam Schedule</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                
                                <th>Subject Name</th>
                                <th>Exam Date</th>
                                <th>Start Time</th>
                                <th>End Time </th>
                               <th>Room number</th>
                               <th>Full mark</th>
                               <th>Passing Mark</th>
                               <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                                @forelse($getRecord as $key => $value)
                                <tr>
                                    <td>
                                        {{ $value['subject_name'] }}
                                        <input type="hidden" class="form-control" value="{{ $value['subject_id'] }}" name="schedule[{{$i}}][subject_id]">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" value="{{ $value['exam_date'] }}"  name="schedule[{{$i}}][exam_date]" id="date_{{$i}}">
                                        <span onclick="document.getElementById('date_{{$i}}').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </td>
                                    <td><input type="time" class="form-control" value="{{ $value['start_time'] }}"  name="schedule[{{$i}}][start_time]"></td>
                                    <td><input type="time" class="form-control" value="{{ $value['end_time'] }}"  name="schedule[{{$i}}][end_time]"></td>
                                    <td><input type="text" class="form-control" value="{{ $value['room_number'] }}"  name="schedule[{{$i}}][room_number]"></td>
                                    <td><input type="text" class="form-control" value="{{ $value['full_mark'] }}"  name="schedule[{{$i}}][full_mark]"></td>
                                    <td><input type="text" class="form-control" value="{{ $value['passing_mark'] }}"  name="schedule[{{$i}}][passing_mark]"></td>
                                    <td>
                                        <a href="{{ url('admin/examination/exam_schedule/delete/'. $value['id']) }}"
                                           class="btn btn-icon btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this student?')"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">No records found.</td>
                                </tr>
                                @endforelse

                        </tbody>
                    </table>
                    <div style="text-align: center; padding:20px">
                       <button class="btn btn-primary "> submit</button>
                    </div>
                    
                </div>
                
            </div>
            </form>
            
            @endif
        </div>
    </div>

</main>
@endsection
