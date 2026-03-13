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
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Attendance</h4>
                            <span class="text-muted small">
                                <i class="bi bi-collection me-1"></i>
                                {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }}
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
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-building me-1"></i>Class
                                </label>
                                <select name="class_id" class="form-select">
                                    <option value="">— All Classes —</option>
                                    @foreach($getClass as $value)
                                        <option value="{{ $value->class_id }}"
                                                {{ Request::get('class_id') == $value->class_id ? 'selected' : '' }}>
                                            {{ $value->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Attendance Date
                                </label>
                                <input type="date" name="attendance_date" class="form-control"
                                       value="{{ Request::get('attendance_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-filter me-1"></i>Attendance Type
                                </label>
                                <select name="attendance_type" class="form-select">
                                    <option value="">— All Types —</option>
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
                                <a href="{{ url('student/my_attendance') }}"
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
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2 text-info"></i>Attendance Records
                    </h6>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        {{ $getRecord->total() }} {{ Str::plural('entry', $getRecord->total()) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary"
                                    style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                    <th>Attendance Date</th>
                                    <th class="pe-4">Recorded On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $attMap = [
                                            1 => ['label'=>'Present',  'color'=>'success', 'icon'=>'bi-check-circle-fill'],
                                            2 => ['label'=>'Absent',   'color'=>'danger',  'icon'=>'bi-x-circle-fill'],
                                            3 => ['label'=>'Late',     'color'=>'warning', 'icon'=>'bi-clock-fill'],
                                            4 => ['label'=>'Half Day', 'color'=>'info',    'icon'=>'bi-circle-half'],
                                        ];
                                        $att = $attMap[$value->attendance_type] ?? ['label'=>'Unknown','color'=>'secondary','icon'=>'bi-question-circle'];
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                {{ $value->class_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $att['color'] }} bg-opacity-10 text-{{ $att['color'] }} px-3 py-1">
                                                <i class="bi {{ $att['icon'] }} me-1" style="font-size:.65rem;"></i>{{ $att['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold text-dark">
                                                {{ date('d M Y', strtotime($value->attendance_date)) }}
                                            </div>
                                            <div class="text-muted" style="font-size:.72rem;">
                                                {{ date('l', strtotime($value->attendance_date)) }}
                                            </div>
                                        </td>
                                        <td class="pe-4 small text-muted">
                                            {{ date('d M Y, h:i A', strtotime($value->created_at)) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-person-check d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
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
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }}
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</main>
@endsection