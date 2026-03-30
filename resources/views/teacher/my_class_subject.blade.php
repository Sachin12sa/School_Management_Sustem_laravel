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
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">My Class &amp; Subjects</h4>
                                <span class="text-muted small">Your assigned classes and subjects</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-list-ul text-info"></i>
                        <h6 class="mb-0 fw-semibold">Assigned Classes</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary text-uppercase"
                                    style="font-size:.72rem;letter-spacing:.04em;">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Type</th>
                                        <th>Today's Schedule</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($getRecord && $getRecord->count())
                                        @foreach ($getRecord as $key => $value)
                                            @php
                                                $ClassSubject = $value->getMyTimeTable(
                                                    $value->class_id,
                                                    $value->subject_id,
                                                    $value->section_id,
                                                );
                                            @endphp
                                            <tr>
                                                <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                                        {{ $value->class_name }}
                                                    </span>
                                                    @if (!empty($value->section_name))
                                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                                            Section: {{ $value->section_name }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold small text-dark">{{ $value->subject_name }}</td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2"
                                                        style="font-size:.7rem;">
                                                        {{ $value->subject_type == 0 ? 'Theory' : 'Practical' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if (!empty($value->today_slot?->start_time))
                                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                                            <span
                                                                class="badge bg-success bg-opacity-10 text-success px-2 py-1"
                                                                style="font-size:.7rem;">
                                                                <i class="bi bi-clock me-1"></i>
                                                                {{ date('h:i A', strtotime($value->today_slot->start_time)) }}
                                                                –
                                                                {{ date('h:i A', strtotime($value->today_slot->end_time)) }}
                                                            </span>
                                                            @if ($value->today_slot->room_number)
                                                                <span class="text-muted small">Room
                                                                    {{ $value->today_slot->room_number }}</span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1"
                                                            style="font-size:.7rem;">
                                                            <i class="bi bi-x-circle me-1"></i>No Class Today
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="small text-muted">
                                                    {{ optional($value->created_at)->format('d-m-Y h:i A') }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('teacher/my_class_subject/class_timetable/' . $value->class_id . '/' . $value->section_id . '/' . $value->subject_id) }}"
                                                        class="btn btn-info btn-sm text-white">
                                                        <i class="bi bi-calendar3-week me-1"></i>Timetable
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="bi bi-journal d-block mb-2 text-muted"
                                                    style="font-size:2rem;opacity:.3;"></i>
                                                <div class="text-muted small">No assignments found</div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top d-flex justify-content-end py-3">
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
