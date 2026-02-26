@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        Marks Register
                        {{-- <small class="text-muted">Total Exams: {{ $totalExam }}</small> --}}
                    </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Marks Register</h3>
                        </div>

                        <form method="GET" action="{{ url('admin/examination/marks_register') }}">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Exam Name</label>
                                        <select name="exam_id" required class="form-control">
                                            <option value="">Select Exam</option>
                                            @foreach($getExam as $exam)
                                                <option {{ (Request::get('exam_id') == $exam->id) ? 'selected' : '' }} value="{{ $exam->id }}">
                                                    {{ $exam->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Choose Class</label>
                                        <select name="class_id" required class="form-control">
                                            <option value="">Select Class</option>
                                            @foreach($getClass as $class)
                                                <option {{ (Request::get('class_id') == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ url('admin/examination/marks_register') }}" class="btn btn-success ms-1">Reset</a>
                                    </div>

                                </div>
                            </div>
                        </form>

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
            @include('message')

            @if(!empty($getSubject) && $getSubject->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Marks Register</h3>
                    </div>

                    <form action="{{ url('admin/examination/submit_marks_register') }}" method="post" id="SubmitMarksForm">
                        @csrf
                        

                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>STUDENT NAME</th>
                                        @foreach ($getSubject as $subject)
                                            <th>
                                                {{ $subject->subject_name }} <br>
                                                ({{ $subject->subject_type == 0 ? 'Theory' : 'Practical' }} 
                                                {{$subject->full_mark}} / {{$subject->passing_mark}})
                                            </th>
                                            
                                        @endforeach
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($getStudent) && $getStudent->count() > 0)
                                        @foreach ($getStudent as $student)
                                            <tr>
                                                <td>
                                                    {{$student->name}} {{$student->last_name}}
                                                </td>
                                                @php
                                                $totalStudentMark = 0;
                                                $totalFullMark = 0;
                                                $totalPassingMark = 0;
                                            @endphp
                                                @foreach ($getSubject as $subject)
                                                   @php
                                                    $totalMark = 0;
                                                    $totalFullMark += $subject->full_mark;
                                                    $totalPassingMark += $subject->passing_mark;

                                                    $idKey = $student->id . '_' . $subject->id;
                                                    $existing = $getMarks[$idKey] ?? null;

                                                    if (!empty($existing)) {
                                                        $totalMark =
                                                            ($existing->class_work ?? 0) +
                                                            ($existing->home_work ?? 0) +
                                                            ($existing->test_work ?? 0) +
                                                            ($existing->exam ?? 0);
                                                    }

                                                    $totalStudentMark += $totalMark;
                                                    $getGrade = App\Models\MarksGradeModel::getGrade($totalMark);
                                                @endphp

                                                    <td>
                                                     <input type="hidden" name="mark[{{ $idKey }}][student_id]" value="{{ $student->id }}">
        
                                                    <input type="hidden" name="mark[{{ $idKey }}][exam_schedule_id]" value="{{ $subject->id }}">        
                                                    <input type="hidden" name="mark[{{ $idKey }}][exam_id]" value="{{ Request::get('exam_id') }}">
                                                    
                                                    <input type="hidden" name="mark[{{ $idKey }}][class_id]" value="{{ Request::get('class_id') }}">

                                                   
                                                    <input type="hidden" name="mark[{{ $idKey }}][full_mark]" value="{{ $subject->full_mark }}">
                                                    <input type="hidden" name="mark[{{ $idKey }}][passing_mark]" value="{{ $subject->passing_mark }}">

                                                        @php
                                                                $existing = $getMarks[$student->id . '_' . $subject->id] ?? null;
                                                            @endphp
                                                       <div class="mb-2">
                                                     
                                                                <small>Class Work</small>
                                                                <input type="text" class="form-control" 
                                                                    name="mark[{{ $idKey }}][class_work]" 
                                                                    value="{{ old("mark.$idKey.class_work", $existing->class_work ?? 0) }}" 
                                                                    placeholder="0">
                                                            </div>

                                                            <div class="mb-2">
                                                                <small>Home Work</small>
                                                                <input type="text" class="form-control" 
                                                                    name="mark[{{ $idKey }}][home_work]" 
                                                                    value="{{ old("mark.$idKey.home_work", $existing->home_work ?? 0) }}" 
                                                                    placeholder="0">
                                                            </div>

                                                            <div class="mb-2">
                                                                <small>Test Work</small>
                                                                <input type="text" class="form-control" 
                                                                    name="mark[{{ $idKey }}][test_work]" 
                                                                    value="{{ old("mark.$idKey.test_work", $existing->test_work ?? 0) }}" 
                                                                    placeholder="0">
                                                            </div>

                                                            <div class="mb-2">
                                                                <small>Exam</small>
                                                                <input type="text" class="form-control" 
                                                                    name="mark[{{ $idKey }}][exam]" 
                                                                    value="{{ old("mark.$idKey.exam", $existing->exam ?? 0) }}" 
                                                                    placeholder="0">
                                                            </div>
                                                            <div class="">
                                                                <div class="mb-2">
                                                               <button type="button" 
                                                                class="btn btn-sm btn-primary save-single-column-btn" 
                                                                data-schedule-id="{{ $subject->id }}">
                                                            Save Column
                                                        </button>
                                                            </div>
                                                            @if(!empty($totalMark))
                                                            <div class="mb-2">
                                                            <b>Total Mark = </b> {{$totalMark}} <br>
                                                            <b>Passing Mark = </b> {{$subject->passing_mark}} <br>
                                                             @if(!empty($getGrade))
                                                            <strong>Grade: {{ $getGrade }}</strong><br>
                                                        @endif
                                                            @if($totalMark >=  $subject->passing_mark )

                                                            <b>Result: </b>
                                                                <span style="color:green; font-weight:bold;"> Pass</span>
                                                            @else
                                                            <b>Result: </b>
                                                                 <span style="color:red; font-weight:bold;"> Fail</span>
                                                            @endif
                                                            
                                                            </div>
                                                            @endif
                                                            </div>
                                                    </td>
                                                @endforeach
                                                <td>
                                                    <button style="margin-top: 90px;" type="button" class="btn btn-success single-save-row-btn">Save Row</button>
                                                    <div>
                                                        <strong>Total Student Marks: {{ $totalStudentMark }}</strong><br>
                                                        <strong>Total Full Marks: {{ $totalFullMark }}</strong><br>
                                                        <strong>Total Passing Marks: {{ $totalPassingMark }}</strong><br>
                                                        @php
                                                           $percentage = $totalStudentMark * 100 /  $totalFullMark ;
                                                           $getGrade = App\Models\MarksGradeModel::getGrade($percentage);
                                                       
                                                    
                                                        @endphp
                                                        <strong>Percentage: {{round($percentage) }} %</strong><br>
                                                        @if(!empty($getGrade))
                                                            <strong>Grade: {{ $getGrade }}</strong><br>
                                                        @endif
                                                        @if($totalStudentMark >= $totalFullMark * 0.4)
                                                            <b>Result: </b>
                                                            <span class="text-success">Pass</span>
                                                        @else
                                                        <b>Result: </b>
                                                            <span class="text-danger">Fail</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                            
                                        @endforeach
                                        
                                    @endif
                                </tbody>
                            </table>

                            <div style="text-align: center; padding:20px">
                                <button type="submit" class="btn btn-primary">Submit All Marks</button>
                            </div>

                        </div>
                    </form>
                    </div>
            @endif
        </div>
    </div>

</main>
@endsection

@section('script')
<script type="text/javascript">
function showAjaxMessage(message, type) {
    const messageDiv = $('#ajax-response-message');

    // Remove all alert classes first
    messageDiv.removeClass('alert-success alert-danger alert-warning alert-info');

    // Add correct type class
    if (type === 'success') {
        messageDiv.addClass('alert alert-success');
    } else if (type === 'danger') {
        messageDiv.addClass('alert alert-danger');
    }

    messageDiv
        .stop(true, true)
        .hide()
        .html(message)
        .fadeIn();

    // Auto hide
    setTimeout(function () {
        messageDiv.fadeOut();
    }, 4000);
}
    $(document).ready(function() {
        // --- 1. SUBMIT ALL MARKS ---
        $('#SubmitMarksForm').on('submit', function(e) {
            e.preventDefault();
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Saving All...');

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(data) {
                    submitBtn.prop('disabled', false).text('Submit All Marks');
                    showAjaxMessage(data.message, 'success');
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text('Submit All Marks');
                    // Look at the console to see the real PHP error
                    if (xhr.status === 422) {
                            const response = JSON.parse(xhr.responseText);
                            showAjaxMessage(response.message, 'danger');
                        } else {
                            showAjaxMessage("A server error occurred. Please try again.", 'danger');
                        }
                }
            });
        });

        // --- 2. SAVE SINGLE ROW ---
        $('.single-save-row-btn').on('click', function() {
            const btn = $(this);
            // Find all inputs in the same row as this button
            const row = btn.closest('tr');
            const rowData = row.find('input').serialize();
            const _token = "{{ csrf_token() }}"; // Ensure token is included

            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                type: "POST",
                url: "{{ url('admin/examination/submit_marks_register') }}",
                data: rowData + "&_token=" + _token,
                dataType: "json",
                success: function(data) {
                    btn.prop('disabled', false).text('Save Row');
                    showAjaxMessage(data.message, 'success');
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Save Row');
                    if (xhr.status === 422) {
                            const response = JSON.parse(xhr.responseText);
                            showAjaxMessage(response.message, 'danger');
                        } else {
                            showAjaxMessage("A server error occurred. Please try again.", 'danger');
                        }
                }
            });
        });
        // save Single COlumn
        $('.save-single-column-btn').on('click', function() {
                const btn = $(this);
                const scheduleId = btn.data('schedule-id'); // e.g., 8 or 9
                const _token = "{{ csrf_token() }}";

                // Select only inputs that belong to this specific column (Schedule ID)
                // We look for inputs where the name contains "_ID]"
                const columnInputs = $('input[name^="mark["][name*="_' + scheduleId + ']["]');
                const columnData = columnInputs.serialize();

                if (columnData === "") {
                    alert("No data found for this column.");
                    return;
                }

                btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    type: "POST",
                    url: "{{ url('admin/examination/submit_marks_register') }}",
                    data: columnData + "&_token=" + _token,
                    dataType: "json",
                    success: function(data) {
                        btn.prop('disabled', false).text('Save Column');
                        showAjaxMessage(data.message, 'success');
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).text('Save Column');
                        if (xhr.status === 422) {
                            const response = JSON.parse(xhr.responseText);
                            showAjaxMessage(response.message, 'danger');
                        } else {
                            showAjaxMessage("A server error occurred. Please try again.", 'danger');
                        }
                    }
                });
            });

    });
</script>
@endsection