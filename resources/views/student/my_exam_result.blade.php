@extends('layouts.app')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <h3 class="mb-0">My Exam Results</h3>
                </div>
            </div>

            @forelse ($getRecord as $value)
            <div class="card mb-5 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">{{ $value['exam_name'] }}</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th class="text-center">Class Work</th>
                                <th class="text-center">Home Work</th>
                                <th class="text-center">Test Work</th>
                                <th class="text-center">Exam</th>
                                <th class="text-center">Total Score</th>
                                <th class="text-center">Full Mark</th>
                                <th class="text-center">Pass Mark</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($value['subject'] as $exam)
                                <tr>
                                    <td class="fw-bold">{{ $exam['subject_name'] }}</td>
                                    <td class="text-center">{{ $exam['class_work'] }}</td>
                                    <td class="text-center">{{ $exam['home_work'] }}</td>
                                    <td class="text-center">{{ $exam['test_work'] }}</td>
                                    <td class="text-center">{{ $exam['exam'] }}</td>
                                    <td class="text-center fw-bold">{{ $exam['total_score'] }}</td>
                                    <td class="text-center">{{ $exam['full_mark'] }}</td>
                                    <td class="text-center text-muted">{{ $exam['passing_mark'] }}</td>
                                    <td class="text-center">
                                        @if($exam['total_score'] >= $exam['passing_mark'])
                                            <span class="badge bg-success">Pass</span>
                                        @else
                                            <span class="badge bg-danger">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Grand Total</small>
                            <span class="h5">{{ $value['total_obtained_marks'] }} / {{ $value['total_full_marks'] }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Percentage</small>
                            <span class="h5">{{ number_format($value['percentage'], 2) }}%</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Final Status</small>
                            @if($value['final_result'] == 'Pass')
                                <span class="badge bg-success fs-6">OVERALL PASS</span>
                            @else
                                <span class="badge bg-danger fs-6">OVERALL FAIL</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="alert alert-info text-center">
                    No Exam Results found for your account.
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection