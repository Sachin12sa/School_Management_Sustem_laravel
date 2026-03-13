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
                            <h4 class="mb-0 fw-semibold text-dark">Teacher Attendance Report</h4>
                            <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/attendance/teacher_attendance') }}" class="btn btn-primary btn-sm fw-semibold">
                        <i class="bi bi-person-check-fill me-1"></i>Take Attendance
                    </a>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-info"></i>
                    <h6 class="mb-0 fw-semibold">Filter Records</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="{{ url('admin/attendance/teacher_attendance_report') }}">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Teacher Name</label>
                                <input type="text" class="form-control" name="teacher_name"
                                       value="{{ Request::get('teacher_name') }}"
                                       placeholder="Search teacher…">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Date</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="attendance_date"
                                           id="attendance_date"
                                           value="{{ Request::get('attendance_date') }}">
                                    <span class="input-group-text"
                                          onclick="document.getElementById('attendance_date').showPicker()">
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
                                <a href="{{ url('admin/attendance/teacher_attendance_report') }}"
                                   class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Stats (visible only when a date is selected) --}}
            @if(Request::get('attendance_date') && $getRecord->total())
                @php
                    $all      = $getRecord->getCollection();
                    $present  = $all->where('attendance_type', 1)->count();
                    $absent   = $all->where('attendance_type', 2)->count();
                    $late     = $all->where('attendance_type', 3)->count();
                    $halfday  = $all->where('attendance_type', 4)->count();
                    $total    = $all->count();
                @endphp
                <div class="row g-3 mb-4">
                    @foreach([
                        ['Present',  $present,  'success', 'bi-check-circle-fill'],
                        ['Absent',   $absent,   'danger',  'bi-x-circle-fill'],
                        ['Late',     $late,     'warning', 'bi-clock-fill'],
                        ['Half Day', $halfday,  'info',    'bi-circle-half'],
                    ] as [$lbl, $cnt, $col, $ico])
                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                <div class="rounded-3 bg-{{ $col }} bg-opacity-10 text-{{ $col }} d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:44px;height:44px;font-size:1.2rem;">
                                    <i class="bi {{ $ico }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-4 text-dark lh-1">{{ $cnt }}</div>
                                    <div class="text-muted small mt-1">{{ $lbl }}</div>
                                </div>
                            </div>
                            @if($total > 0)
                                <div class="card-footer bg-transparent border-top py-1 px-3">
                                    <div class="progress" style="height:3px;">
                                        <div class="progress-bar bg-{{ $col }}"
                                             style="width:{{ round(($cnt/$total)*100) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-table text-info"></i>
                    <h6 class="mb-0 fw-semibold">Attendance Records</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase"
                                   style="font-size:.72rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th style="min-width:220px;">Teacher</th>
                                    <th>Status</th>
                                    <th>Attendance Date</th>
                                    <th>Marked By</th>
                                    <th>Recorded On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                     style="width:36px;height:36px;font-size:.76rem;">
                                                    {{ strtoupper(substr($value->teacher_name,0,1)) }}{{ strtoupper(substr($value->teacher_last_name??'',0,1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small text-dark">
                                                        {{ $value->teacher_name }} {{ $value->teacher_last_name }}
                                                    </div>
                                                    <div class="text-muted" style="font-size:.72rem;">{{ $value->teacher_email }}</div>
                                                </div>
                                            </div>
                                        </td>
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
                                        <td class="small">
                                            {{ \Carbon\Carbon::parse($value->attendance_date)->format('d M Y') }}
                                            <div class="text-muted" style="font-size:.7rem;">
                                                {{ \Carbon\Carbon::parse($value->attendance_date)->format('l') }}
                                            </div>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $value->created_name }} {{ $value->created_last_name }}
                                        </td>
                                        <td class="small text-muted">
                                            {{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            {{-- Quick-edit: re-open take-attendance for that date --}}
                                            <a href="{{ url('admin/attendance/teacher_attendance?attendance_date='.$value->attendance_date->format('Y-m-d')) }}"
                                               class="btn btn-sm btn-outline-primary fw-semibold"
                                               title="Edit attendance for this date">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-bar-chart d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No attendance records found</div>
                                            <div class="text-muted" style="font-size:.78rem;">Try adjusting your search filters</div>
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