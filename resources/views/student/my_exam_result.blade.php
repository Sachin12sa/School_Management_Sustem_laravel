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
                            <h4 class="mb-0 fw-semibold text-dark">My Exam Results</h4>
                            <span class="text-muted small">Your academic performance by exam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @forelse($getRecord as $value)
                @php
                    $pct          = number_format($value['percentage'], 1);
                    $overallGrade = App\Models\MarksGradeModel::getGrade($pct);
                    $isPass       = $value['final_result'] === 'Pass';
                @endphp

                <div class="card border-0 shadow-sm mb-5">

                    {{-- Exam header --}}
                    <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap gap-2"
                         style="background:linear-gradient(135deg,#1a73e8 0%,#0d47a1 100%);border-radius:.75rem .75rem 0 0;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-mortarboard-fill text-white" style="font-size:1.4rem;opacity:.8;"></i>
                            <h6 class="mb-0 fw-bold text-white">{{ $value['exam_name'] }}</h6>
                        </div>
                        @if($isPass)
                            <span class="badge bg-success fs-6 px-3 py-2 fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i>OVERALL PASS
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">
                                <i class="bi bi-x-circle-fill me-1"></i>OVERALL FAIL
                            </span>
                        @endif
                    </div>

                    {{-- Subject breakdown --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary" style="font-size:.7rem;letter-spacing:.05em;">
                                        <th class="ps-4">Subject</th>
                                        <th class="text-center">Class Work</th>
                                        <th class="text-center">Home Work</th>
                                        <th class="text-center">Test</th>
                                        <th class="text-center">Exam</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Full</th>
                                        <th class="text-center">Pass</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center pe-4">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value['subject'] as $exam)
                                        @php
                                            $passed     = $exam['total_score'] >= $exam['passing_mark'];
                                            $subGrade   = App\Models\MarksGradeModel::getGrade($exam['total_score']);
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold small text-dark">{{ $exam['subject_name'] }}</div>
                                            </td>
                                            <td class="text-center small">{{ $exam['class_work'] }}</td>
                                            <td class="text-center small">{{ $exam['home_work'] }}</td>
                                            <td class="text-center small">{{ $exam['test_work'] }}</td>
                                            <td class="text-center small">{{ $exam['exam'] }}</td>
                                            <td class="text-center">
                                                <span class="fw-bold text-dark">{{ $exam['total_score'] }}</span>
                                            </td>
                                            <td class="text-center text-muted small">{{ $exam['full_mark'] }}</td>
                                            <td class="text-center text-muted small">{{ $exam['passing_mark'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $subGrade }}</span>
                                            </td>
                                            <td class="text-center pe-4">
                                                @if($passed)
                                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-2">Pass</span>
                                                @else
                                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-2">Fail</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Summary footer --}}
                    <div class="card-footer bg-light border-top py-3">
                        <div class="row g-3 text-center">

                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-white rounded-2 shadow-sm">
                                    <div class="text-muted small mb-1">Grand Total</div>
                                    <div class="fw-bold fs-5 text-dark">
                                        {{ $value['total_obtained_marks'] }}
                                        <span class="text-muted fw-normal" style="font-size:.85rem;">/ {{ $value['total_full_marks'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-white rounded-2 shadow-sm">
                                    <div class="text-muted small mb-1">Percentage</div>
                                    @php
                                        $pctNum = (float)$pct;
                                        $pctColor = $pctNum >= 80 ? 'success' : ($pctNum >= 60 ? 'primary' : ($pctNum >= 40 ? 'warning' : 'danger'));
                                    @endphp
                                    <div class="fw-bold fs-5 text-{{ $pctColor }}">{{ $pct }}%</div>
                                    <div class="progress mt-1" style="height:4px;border-radius:2px;">
                                        <div class="progress-bar bg-{{ $pctColor }}" style="width:{{ $pctNum }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-white rounded-2 shadow-sm">
                                    <div class="text-muted small mb-1">Grade</div>
                                    <div class="fw-bold fs-4 text-primary">{{ $overallGrade }}</div>
                                </div>
                            </div>

                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-white rounded-2 shadow-sm">
                                    <div class="text-muted small mb-1">Final Status</div>
                                    @if($isPass)
                                        <span class="badge bg-success fs-6 px-3 fw-bold">PASS</span>
                                    @else
                                        <span class="badge bg-danger fs-6 px-3 fw-bold">FAIL</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-award d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No exam results found</div>
                        <div class="text-muted" style="font-size:.78rem;">Your results will appear here once exams are marked.</div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</main>
@endsection