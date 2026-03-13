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
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Timetable</h4>
                            <span class="text-muted small">Weekly class schedule</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            {{-- Fixed: had a stray </form> closing tag and an empty broken search card --}}
            @forelse($getRecord as $value)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-3">
                        <div class="rounded-2 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:36px;height:36px;font-size:1rem;">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold text-dark">{{ $value['subject_name'] }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="160">Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th class="pe-4">Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value['week'] as $valueW)
                                        <tr>
                                            <td class="ps-4">
                                                @php
                                                    $dayColors = [
                                                        'Sunday'=>'danger','Monday'=>'primary','Tuesday'=>'success',
                                                        'Wednesday'=>'warning','Thursday'=>'info','Friday'=>'secondary','Saturday'=>'dark'
                                                    ];
                                                    $dc = $dayColors[$valueW['week_name']] ?? 'secondary';
                                                @endphp
                                                <span class="badge rounded-pill bg-{{ $dc }} bg-opacity-10 text-{{ $dc }} px-3">
                                                    {{ $valueW['week_name'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold small text-dark">
                                                    {{ !empty($valueW['start_time']) ? date('h:i A', strtotime($valueW['start_time'])) : '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold small text-dark">
                                                    {{ !empty($valueW['end_time']) ? date('h:i A', strtotime($valueW['end_time'])) : '—' }}
                                                </span>
                                            </td>
                                            <td class="pe-4">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2">
                                                    {{ $valueW['room_number'] ?: '—' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clock d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No timetable found for your class</div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</main>
@endsection