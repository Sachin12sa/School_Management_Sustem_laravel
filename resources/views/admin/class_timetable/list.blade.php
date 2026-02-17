@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Class TimeTable                       
                </div>
             
              </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Class Timetable </h3>
                        </div>

                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <div class="col-md-3">
                                        <label class="form-label"> Class Name</label>
                                        <select  name="class_id" class="form-control getClass" id="">
                                            <option value="">Select</option>
                                            @foreach ($getClass as $class)
                                            <option {{ (Request::get('class_id') == $class->id) ? 'selected' : '' }} value="{{$class->id}}">{{$class->name}}</option>
                                                
                                            @endforeach
                                        </select>
                                    
                                    </div>
                                <div class="col-md-3">
                                    <label class="form-label">Subject Name</label>
                                    <select  name="subject_id" class="form-control getSubject">
                                        @if(!empty($getSubject) && $getSubject->count() > 0)
                                            <option value="">Select Subject</option>
                                            @foreach ($getSubject as $subject)
                                                <option {{ (Request::get('subject_id') == $subject->subject_id) ? 'selected' : '' }} value="{{ $subject->subject_id }}">
                                                    {{ $subject->subject_name }}
                                                </option>
                                            @endforeach
                                        @elseif(!empty(Request::get('class_id')))
                                            <option value="">No Subject Assigned</option>
                                        @else
                                            <option value="">Select Class First</option>
                                        @endif
                                    </select>
                                </div>
                
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('admin/class_timetable/list') }}" class="btn btn-success ms-1">
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @include('message')
        @if (!empty(Request::get('class_id')) && !empty(Request::get('subject_id')))
                <form action="{{url('admin/class_timetable/add')}}" method="post">
                    @csrf
                    <input type="hidden" name="class_id" value="{{Request::get('class_id')}}">
                    <input type="hidden" name="subject_id" value="{{Request::get('subject_id')}}">
                    
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Class TimeTable</h3>
                </div>


                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Week</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Room Number</th>
                            </tr>
                        </thead>
             
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                       @foreach($week as $value)
                       <tr>
                        <th>
                        <input type="hidden" name="timetable[{{ $i }}][week_id]" value="{{ $value['id'] }}">
                            {{$value['week_name']}}</th>
                        <td><input type="time" name="timetable[{{ $i }}][start_time]" value="{{$value['start_time']}}" class="form-control"></td>
                        <td><input type="time" name="timetable[{{ $i }}][end_time]" value="{{$value['end_time']}}" class="form-control"></td>
                        <td><input type="text" style="width:200px " name="timetable[{{ $i }}][room_number]" value=" {{$value['room_number']}}" class="form-control"></td>
                       </tr>
                       @php
                               $i++;
                            @endphp
                        </tbody>
                        @endforeach
                    </table>
                    <div style="text-align:center; padding:14px; ">
                    <button class="btn btn-primary "> submit</button>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>
        </form>
            @endif
        </div>
    </div>

    <!-- Content -->

</main>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
        $('.getClass').change(function(){
            var class_id = $(this).val();

            if(class_id != "") {
                $.ajax({
                    url: "{{ url('admin/class_timetable/get_subject') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        class_id: class_id,
                    },
                    dataType: "json",
                    success: function(response){
                        $('.getSubject').html(response.html);
                    }
                });
            } else {
                $('.getSubject').html('<option value="">Select</option>');
            }
        });
});
</script>
@endsection

