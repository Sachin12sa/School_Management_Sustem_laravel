@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Subjects</h4>
                            <span class="text-muted small">
                                <i class="bi bi-person me-1"></i>
                                <strong>{{ $getUser->name }} {{ $getUser->last_name }}</strong>
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

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-list-ul text-success"></i>
                    <h6 class="mb-0 fw-semibold">Subject List</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Today's Schedule</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    @php
                                        $ClassSubject = $value->getMyTimeTable($value->class_id, $value->subject_id);
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                                        <td class="fw-semibold small text-dark">{{ $value->subject_name }}</td>
                                        <td>
                                            @if($value->subject_type == 0)
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">Theory</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success px-2">Practical</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ClassSubject?->start_time)
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1" style="font-size:.7rem;">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ date('h:i A', strtotime($ClassSubject->start_time)) }}
                                                        – {{ date('h:i A', strtotime($ClassSubject->end_time)) }}
                                                    </span>
                                                    <span class="text-muted small">Room {{ $ClassSubject->room_number }}</span>
                                                </div>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2" style="font-size:.7rem;">
                                                    <i class="bi bi-x-circle me-1"></i>No Class Today
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('parent/my_student/subject/class_timetable/'.$value->class_id.'/'.$value->subject_id.'/'.$getUser->id) }}"
                                               class="btn btn-sm btn-primary fw-semibold">
                                                <i class="bi bi-calendar3-week me-1"></i>Timetable
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-journal d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="text-muted small">No subjects found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection