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
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Attendance Report</h4>
                            <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-info"></i>
                    <h6 class="mb-0 fw-semibold">Filter Attendance</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="{{ url('teacher/attendance/attendance_report') }}">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">Class</label>
                                <select name="class_id" class="form-select">
                                    <option value="">All Classes</option>
                                    @foreach($getClass as $class)
                                        <option value="{{ $class->class_id }}"
                                                {{ Request::get('class_id') == $class->class_id ? 'selected' : '' }}>
                                            {{ $class->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">Student Name</label>
                                <input type="text" class="form-control" name="student_name"
                                       placeholder="Search student…" value="{{ Request::get('student_name') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="attendance_date"
                                           id="attendance_date" value="{{ Request::get('attendance_date') }}">
                                    <span class="input-group-text" onclick="document.getElementById('attendance_date').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">Type</label>
                                <select name="attendance_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="1" {{ Request::get('attendance_type') == 1 ? 'selected' : '' }}>Present</option>
                                    <option value="2" {{ Request::get('attendance_type') == 2 ? 'selected' : '' }}>Absent</option>
                                    <option value="3" {{ Request::get('attendance_type') == 3 ? 'selected' : '' }}>Late</option>
                                    <option value="4" {{ Request::get('attendance_type') == 4 ? 'selected' : '' }}>Half Day</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-info text-white flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('teacher/attendance/attendance_report') }}"
                                   class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-list-check text-info"></i>
                    <h6 class="mb-0 fw-semibold">Attendance Records</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Student</th>
                                    <th>Marked By</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                    <th>Attendance Date</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold small text-dark">
                                            {{ $value->student_name }} {{ $value->student_last_name }}
                                        </td>
                                        <td class="small text-muted">
                                            {{ $value->created_name }} {{ $value->created_last_name }}
                                        </td>
                                        <td class="small">{{ $value->class_name }}</td>
                                        <td>
                                            @if($value->attendance_type == 1)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>Present
                                                </span>
                                            @elseif($value->attendance_type == 2)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>Absent
                                                </span>
                                            @elseif($value->attendance_type == 3)
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>Late
                                                </span>
                                            @elseif($value->attendance_type == 4)
                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>Half Day
                                                </span>
                                            @endif
                                        </td>
                                        <td class="small">{{ date('d M Y', strtotime($value->attendance_date)) }}</td>
                                        <td class="small text-muted">{{ date('d-m-Y H:i', strtotime($value->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-bar-chart d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="text-muted small">No attendance records found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
                    <span class="text-muted small">
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }}
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</main>
@endsection