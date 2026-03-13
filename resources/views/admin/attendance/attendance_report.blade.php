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
                            <span class="text-muted small">
                                <i class="bi bi-collection me-1"></i>{{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found
                            </span>
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
                    <form method="GET" action="{{ url('admin/attendance/attendance_report') }}">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-building me-1"></i>Class
                                </label>
                                {{-- Fixed: was id="getClass" — same id as attendance_type select below (duplicate ids) --}}
                                <select name="class_id" id="filterClass" class="form-select">
                                    <option value="">— All Classes —</option>
                                    @foreach($getClass as $class)
                                        <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-person me-1"></i>Student Name
                                </label>
                                {{-- Fixed: had a stray </select> closing tag after the text input --}}
                                <input type="text" name="student_name"
                                       value="{{ Request::get('student_name') }}"
                                       class="form-control" placeholder="Search student…">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Start Date
                                </label>
                                {{-- Fixed: value was bound to Request::get('attendance_date') instead of 'start_attendance_date' --}}
                                <input type="date" name="start_attendance_date"
                                       value="{{ Request::get('start_attendance_date') }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>End Date
                                </label>
                                {{-- Fixed: same bug — was bound to 'attendance_date' instead of 'end_attendance_date' --}}
                                <input type="date" name="end_attendance_date"
                                       value="{{ Request::get('end_attendance_date') }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-check2-circle me-1"></i>Type
                                </label>
                                {{-- Fixed: duplicate id="getClass" — renamed --}}
                                <select name="attendance_type" id="filterType" class="form-select">
                                    <option value="">— All Types —</option>
                                    <option {{ Request::get('attendance_type') == 1 ? 'selected' : '' }} value="1">Present</option>
                                    <option {{ Request::get('attendance_type') == 2 ? 'selected' : '' }} value="2">Absent</option>
                                    <option {{ Request::get('attendance_type') == 3 ? 'selected' : '' }} value="3">Late</option>
                                    <option {{ Request::get('attendance_type') == 4 ? 'selected' : '' }} value="4">Half Day</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-info text-white flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('admin/attendance/attendance_report') }}" class="btn btn-outline-secondary flex-fill">
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
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2 text-info"></i>Attendance Records
                    </h6>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary"
                                    style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Attendance</th>
                                    <th>Attendance Date</th>
                                    <th>Recorded By</th>
                                    <th class="pe-4">Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    <tr>
                                        {{-- Fixed: was displaying $value->id (the DB row id) as the serial number --}}
                                        <td class="ps-4 text-muted small">
                                            {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold small text-dark">
                                                {{ $value->student_name }} {{ $value->student_last_name }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                {{ $value->class_name }}
                                            </span>
                                        </td>

                                        <td>
                                            @php
                                                $attMap = [
                                                    1 => ['label' => 'Present',  'color' => 'success'],
                                                    2 => ['label' => 'Absent',   'color' => 'danger'],
                                                    3 => ['label' => 'Late',     'color' => 'warning'],
                                                    4 => ['label' => 'Half Day', 'color' => 'info'],
                                                ];
                                                $att = $attMap[$value->attendance_type] ?? null;
                                            @endphp
                                            @if($att)
                                                <span class="badge rounded-pill bg-{{ $att['color'] }} bg-opacity-10 text-dark px-3 py-1"
                                                      style="font-size:.75rem;">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;color:var(--bs-{{ $att['color'] }});"></i>
                                                    {{ $att['label'] }}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="small fw-semibold text-dark">
                                                {{ date('d M Y', strtotime($value->attendance_date)) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-circle text-muted"></i>
                                                <span class="small">{{ $value->created_name }} {{ $value->created_last_name }}</span>
                                            </div>
                                        </td>

                                        <td class="pe-4">
                                            <div class="small text-dark">{{ date('d M Y', strtotime($value->created_at)) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ date('h:i A', strtotime($value->created_at)) }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-bar-chart d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No attendance records found</div>
                                            <div class="text-muted" style="font-size:.78rem;">Try adjusting your filters.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="text-muted small">
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }} records
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

</main>
@endsection