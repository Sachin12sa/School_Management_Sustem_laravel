@extends('layouts.app')

@section('content')
<main class="app-main">

    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        my TimeTable                       
                </div>
             
              </div>

            <!-- Search Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Class Timetable </h3>
                        </div>

                   

                    </div>
                </div>
            </div>
            @include('message')
            @foreach ($getRecord as $value)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$value['subject_name'] }}</h3>
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
                            @foreach ($value['week'] as $valueW )
                            <tr>
                                <td>{{$valueW['week_name']}}</td>
                                <td>{{!empty($valueW['start_time']) ? date('h:i A',strtotime($valueW['start_time'])) : ''}}</td>
                                <td>{{!empty($valueW['end_time']) ? date('h:i A',strtotime($valueW['end_time'])) : ''}}</td>
                                <td>{{$valueW['room_number']}}</td>

                            </tr>

                            @endforeach
                        </tbody>
             
                    </table>

                </div>

                <!-- Pagination -->
                {{-- <div class="card-footer text-end">
                    {{ $getRecord->appends(request()->query())->links() }}
                </div> --}}
            </div>
            @endforeach
        </form>
        </div>
    </div>

    <!-- Content -->

</main>
@endsection




