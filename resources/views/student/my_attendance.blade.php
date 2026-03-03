@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        My Attendance Report
                        <small style="background-color: skyblue;" class="text-muted">Total : {{ $getRecord->total() }}</small>
                    </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                       
                        <div class="card-header">
                            <h3 class="card-title">Search My Attendance Report</h3>
                        </div>

                        <form method="GET" action="">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <!-- Class -->
                                    <div class="form-group col-md-2" style="margin-bottom: 18px;">
                                        <label>Class</label>
                                        <select name="class_id" class="form-control">
                                            <option value="">Select Class</option>
                                            @foreach ($getClass as $value)
                                                <option 
                                                    value="{{ $value->class_id }}"
                                                    {{ Request::get('class_id') == $value->class_id ? 'selected' : '' }}>
                                                    {{ $value->class_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date -->
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Choose Attendance Date</label>
                                        <input 
                                            type="date" 
                                            class="form-control" 
                                            name="attendance_date" 
                                            value="{{ Request::get('attendance_date') }}">
                                    </div>

                                    <!-- Attendance Type -->
                                    <div class="mb-3 col-md-2">
                                        <label class="form-label">Choose Attendance Type</label>
                                        <select name="attendance_type" class="form-control">
                                            <option value="">Select Attendance Type</option>
                                            <option value="1" {{ Request::get('attendance_type') == 1 ? 'selected' : '' }}>Present</option>
                                            <option value="2" {{ Request::get('attendance_type') == 2 ? 'selected' : '' }}>Absent</option>
                                            <option value="3" {{ Request::get('attendance_type') == 3 ? 'selected' : '' }}>Late</option>
                                            <option value="4" {{ Request::get('attendance_type') == 4 ? 'selected' : '' }}>Half Day</option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-md-3 mb-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('student/my_attendance') }}" class="btn btn-success ms-1">Reset</a>
                                    </div>

                                </div>
                            </div>
                        </form>
                         @include('message')

                    </div>
                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Attendance List</h3>
                    </div>


                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>

                                            <th>Class Name</th>
                                            <th>Attendance</th>
                                            <th>Attendance Date</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                        <tr>
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
                    </div>
            
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
