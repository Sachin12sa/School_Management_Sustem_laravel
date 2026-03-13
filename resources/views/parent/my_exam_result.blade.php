@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Exam Results</h4>
                            <span class="text-muted small">
                                <i class="bi bi-person me-1"></i>
                                <strong>{{ $getStudent->name }} {{ $getStudent->last_name }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('parent/my_student') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @forelse($getRecord as $value)
                @php
                    $pct = number_format($value['percentage'], 2);
                    $overallGrade = App\Models\MarksGradeModel::getGrade($pct);
                    $isPass = $value['final_result'] == 'Pass';
                @endphp
                <div class="card border-0 shadow-sm mb-4">

                    {{-- Exam Header --}}
                    <div class="card-header d-flex align-items-center justify-content-between py-3"
                         style="background: linear-gradient(135deg, rgba(220,53,69,.08), rgba(220,53,69,.03));">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-journal-text text-danger"></i>
                            <h6 class="mb-0 fw-semibold text-dark">{{ $value['exam_name'] }}</h6>
                        </div>
                        <span class="badge px-3 py-2 fw-semibold {{ $isPass ? 'bg-success' : 'bg-danger' }}" style="font-size:.72rem;">
                            {{ $isPass ? 'PASS' : 'FAIL' }}
                        </span>
                    </div>

                    {{-- Marks Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary text-uppercase" style="font-size:.68rem;letter-spacing:.04em;">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th class="text-center">Class Work</th>
                                        <th class="text-center">Home Work</th>
                                        <th class="text-center">Test Work</th>
                                        <th class="text-center">Exam</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Full Mark</th>
                                        <th class="text-center">Pass Mark</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value['subject'] as $exam)
                                        @php $grade = App\Models\MarksGradeModel::getGrade($exam['total_score']); @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold small text-dark">{{ $exam['subject_name'] }}</td>
                                            <td class="text-center small">{{ $exam['class_work'] }}</td>
                                            <td class="text-center small">{{ $exam['home_work'] }}</td>
                                            <td class="text-center small">{{ $exam['test_work'] }}</td>
                                            <td class="text-center small">{{ $exam['exam'] }}</td>
                                            <td class="text-center fw-bold text-dark">{{ $exam['total_score'] }}</td>
                                            <td class="text-center small text-muted">{{ $exam['full_mark'] }}</td>
                                            <td class="text-center small text-muted">{{ $exam['passing_mark'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info bg-opacity-10 text-info px-2">{{ $grade }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($exam['total_score'] >= $exam['passing_mark'])
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Pass</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">Fail</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Summary Footer --}}
                    <div class="card-footer bg-white border-top">
                        <div class="row g-3 text-center">
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Grand Total</div>
                                <div class="fw-bold text-dark fs-5">
                                    {{ $value['total_obtained_marks'] }} / {{ $value['total_full_marks'] }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Percentage</div>
                                <div class="fw-bold text-dark fs-5">{{ $pct }}%</div>
                                <div class="progress mt-1" style="height:4px;">
                                    <div class="progress-bar {{ $pct >= 75 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                         style="width:{{ min($pct,100) }}%"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Overall Grade</div>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 fs-6">{{ $overallGrade }}</span>
                            </div>
                            <div class="col-md-3">
                                <div class="text-muted small mb-1">Final Result</div>
                                <span class="badge px-3 py-2 fs-6 {{ $isPass ? 'bg-success' : 'bg-danger' }}">
                                    {{ $isPass ? 'OVERALL PASS' : 'OVERALL FAIL' }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-award d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No exam results found</div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</main>
@endsection