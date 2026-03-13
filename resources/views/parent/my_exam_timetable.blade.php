@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-calendar3-week-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Exam Timetable</h4>
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
            @include('message')

            @forelse($getRecord as $value)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <span class="badge bg-warning bg-opacity-15 text-primary px-3 py-2 fw-semibold">
                            <i class="bi bi-award me-1"></i>{{ $value['name'] }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary text-uppercase" style="font-size:.7rem;letter-spacing:.04em;">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Day</th>
                                        <th>Date</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Room</th>
                                        <th>Full Mark</th>
                                        <th>Pass Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($value['exam'] as $valueS)
                                        @php
                                            $examDate = \Carbon\Carbon::parse($valueS['exam_date']);
                                            $isToday  = $examDate->isToday();
                                            $isPast   = $examDate->isPast() && !$isToday;
                                        @endphp
                                        <tr class="{{ $isToday ? 'table-warning' : '' }}">
                                            <td class="ps-4 fw-semibold small text-dark">
                                                {{ $valueS['subject_name'] }}
                                                @if($isToday)
                                                    <span class="badge bg-warning text-dark ms-1" style="font-size:.6rem;">Today</span>
                                                @elseif($isPast)
                                                    <span class="badge bg-secondary ms-1" style="font-size:.6rem;">Done</span>
                                                @endif
                                            </td>
                                            <td class="small text-muted">{{ date('l', strtotime($valueS['exam_date'])) }}</td>
                                            <td class="small">{{ date('d M Y', strtotime($valueS['exam_date'])) }}</td>
                                            <td class="small">{{ date('h:i A', strtotime($valueS['start_time'])) }}</td>
                                            <td class="small">{{ date('h:i A', strtotime($valueS['end_time'])) }}</td>
                                            <td class="small text-muted"><i class="bi bi-door-open me-1"></i>{{ $valueS['room_number'] }}</td>
                                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $valueS['full_mark'] }}</span></td>
                                            <td><span class="badge bg-success bg-opacity-10 text-success">{{ $valueS['passing_mark'] }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-3 text-muted small">No exam schedule found</td>
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
                        <i class="bi bi-calendar3 d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No exam timetable available</div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</main>
@endsection