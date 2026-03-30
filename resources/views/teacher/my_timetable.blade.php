@extends('layouts.app')

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">My Timetable</h4>
                                <span class="text-muted small">
                                    Class: <strong>{{ $getClass->name }}</strong>
                                    @if (!empty($getSection->name))
                                        &nbsp;&middot;&nbsp;
                                        Section: <strong>{{ $getSection->name }}</strong>
                                    @endif
                                    &nbsp;&middot;&nbsp;
                                    Subject: <strong>{{ $getSubject->name }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('teacher/my_class_subject') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-calendar3-week-fill text-info"></i>
                        <h6 class="mb-0 fw-semibold">Weekly Schedule</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary text-uppercase"
                                    style="font-size:.72rem;letter-spacing:.04em;">
                                    <tr>
                                        <th class="ps-4">Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Room Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $dayColors = [
                                            'Monday' => 'primary',
                                            'Tuesday' => 'info',
                                            'Wednesday' => 'success',
                                            'Thursday' => 'warning',
                                            'Friday' => 'danger',
                                            'Saturday' => 'secondary',
                                            'Sunday' => 'dark',
                                        ];
                                    @endphp
                                    @foreach ($weeks as $week)
                                        @php $color = $dayColors[$week['week_name']] ?? 'secondary'; @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <span
                                                    class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-1 fw-semibold">
                                                    {{ $week['week_name'] }}
                                                </span>
                                            </td>
                                            <td class="small fw-semibold text-dark">
                                                {{ !empty($week['start_time']) ? date('h:i A', strtotime($week['start_time'])) : '—' }}
                                            </td>
                                            <td class="small fw-semibold text-dark">
                                                {{ !empty($week['end_time']) ? date('h:i A', strtotime($week['end_time'])) : '—' }}
                                            </td>
                                            <td class="small text-muted">
                                                @if (!empty($week['room_number']))
                                                    <i class="bi bi-door-open me-1"></i>{{ $week['room_number'] }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
