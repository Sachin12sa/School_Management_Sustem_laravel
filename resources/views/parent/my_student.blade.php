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
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Children</h4>
                            <span class="text-muted small">{{ $getRecord->count() }} {{ Str::plural('student', $getRecord->count()) }} assigned</span>
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
                    <i class="bi bi-table text-danger"></i>
                    <h6 class="mb-0 fw-semibold">Assigned Students</h6>
                </div>
                <div class="card-body p-0">
                    @if($getRecord->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size:.7rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th style="min-width:220px;">Student</th>
                                    <th>Admission No</th>
                                    <th>Admission Date</th>
                                    <th>Roll No</th>
                                    <th>Class</th>
                                    <th>Gender</th>
                                    <th>DOB</th>
                                    <th>Height</th>
                                    <th>Weight</th>
                                    <th>Created</th>
                                    <th style="min-width:310px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if(!empty($value->profile_pic))
                                                    <img src="{{ asset('storage/'.$value->profile_pic) }}"
                                                         class="rounded-circle flex-shrink-0"
                                                         style="width:38px;height:38px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                         style="width:38px;height:38px;font-size:.78rem;">
                                                        {{ strtoupper(substr($value->name,0,1)) }}{{ strtoupper(substr($value->last_name??'',0,1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold small text-dark">{{ $value->name }} {{ $value->last_name }}</div>
                                                    <div class="text-muted" style="font-size:.72rem;">{{ $value->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small fw-semibold font-monospace text-dark">{{ $value->admission_number }}</td>
                                        <td class="small text-muted">{{ $value->admission_date ? date('d M Y', strtotime($value->admission_date)) : '—' }}</td>
                                        <td class="small text-muted">{{ $value->roll_number ?? '—' }}</td>
                                        <td><span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $value->class_name }}</span></td>
                                        <td class="small">{{ $value->gender }}</td>
                                        <td class="small text-muted">{{ $value->date_of_birth ? date('d-m-Y', strtotime($value->date_of_birth)) : '—' }}</td>
                                        <td class="small text-muted">{{ $value->height ?? '—' }}</td>
                                        <td class="small text-muted">{{ $value->weight ?? '—' }}</td>
                                        <td>
                                            <div class="small text-dark">{{ date('d-m-Y', strtotime($value->created_at)) }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">{{ date('h:i A', strtotime($value->created_at)) }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ url('parent/my_student/subject/'.$value->id) }}"
                                                   class="btn btn-sm btn-success fw-semibold" title="Subjects">
                                                    <i class="bi bi-journal-bookmark me-1"></i>Subjects
                                                </a>
                                                <a href="{{ url('parent/my_student/exam_timetable/'.$value->id) }}"
                                                   class="btn btn-sm btn-primary fw-semibold" title="Exam Timetable">
                                                    <i class="bi bi-calendar3-week me-1"></i>Timetable
                                                </a>
                                                <a href="{{ url('parent/my_student/my_exam_result/'.$value->id) }}"
                                                   class="btn btn-sm btn-info text-white fw-semibold" title="Results">
                                                    <i class="bi bi-award me-1"></i>Result
                                                </a>
                                                <a href="{{ url('parent/my_student/calendar/'.$value->id) }}"
                                                   class="btn btn-sm btn-secondary fw-semibold" title="Calendar">
                                                    <i class="bi bi-calendar-event me-1"></i>Calendar
                                                </a>
                                                <a href="{{ url('parent/my_student/my_attendance/'.$value->id) }}"
                                                   class="btn btn-sm btn-warning fw-semibold" title="Attendance">
                                                    <i class="bi bi-person-check me-1"></i>Attendance
                                                </a>
                                                <a href="{{ url('parent/my_student/homework/'.$value->id) }}"
                                                   class="btn btn-sm btn-outline-warning fw-semibold" title="Homework">
                                                    <i class="bi bi-clipboard2 me-1"></i>Homework
                                                </a>
                                                <a href="{{ url('parent/my_student/submitted_homework/'.$value->id) }}"
                                                   class="btn btn-sm btn-outline-secondary fw-semibold" title="Submitted">
                                                    <i class="bi bi-box-arrow-in-down me-1"></i>Submitted
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-5">
                                            <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="text-muted small">No students assigned yet</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                            <div class="fw-semibold small text-muted">No students assigned</div>
                            <div class="text-muted" style="font-size:.78rem;">Contact the admin to link your children.</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</main>
@endsection