@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-calendar3-week-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Exam Timetable</h4>
                            <span class="text-muted small">Scheduled examination dates and times</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            @forelse($getRecord as $value)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-3">
                        <div class="rounded-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:36px;height:36px;font-size:1rem;">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-dark">{{ $value['name'] }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4">Subject</th>
                                        <th>Day</th>
                                        <th>Exam Date</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                        <th>Full Mark</th>
                                        <th class="pe-4">Pass Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($value['exam'] as $valueS)
                                        @php
                                            $examDate  = \Carbon\Carbon::parse($valueS['exam_date']);
                                            $isPast    = $examDate->isPast();
                                            $isToday   = $examDate->isToday();
                                        @endphp
                                        <tr class="{{ $isToday ? 'table-warning' : '' }}">
                                            <td class="ps-4">
                                                <div class="fw-semibold small text-dark">{{ $valueS['subject_name'] }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    {{ date('l', strtotime($valueS['exam_date'])) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small text-dark fw-semibold">{{ date('d M Y', strtotime($valueS['exam_date'])) }}</div>
                                                @if($isToday)
                                                    <span class="badge bg-warning text-dark" style="font-size:.6rem;">Today!</span>
                                                @elseif($isPast)
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:.6rem;">Completed</span>
                                                @endif
                                            </td>
                                            <td class="small text-muted">
                                                {{ date('h:i A', strtotime($valueS['start_time'])) }} – {{ date('h:i A', strtotime($valueS['end_time'])) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info px-2">{{ $valueS['room_number'] }}</span>
                                            </td>
                                            <td class="fw-semibold small text-dark">{{ $valueS['full_mark'] }}</td>
                                            <td class="pe-4 text-muted small">{{ $valueS['passing_mark'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted small">No subjects scheduled for this exam</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-calendar3-week d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No exam timetable available yet</div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</main>
@endsection