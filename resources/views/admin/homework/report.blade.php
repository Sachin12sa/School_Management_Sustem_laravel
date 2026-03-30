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
                                <i class="bi bi-clipboard2-data-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Homework Report</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-collection me-1"></i>{{ $getRecord->total() }}
                                    {{ Str::plural('record', $getRecord->total()) }} found
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-info"></i>
                        <h6 class="mb-0 fw-semibold">Filter Homework Report</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="GET" action="">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-person me-1"></i>Student Name
                                    </label>
                                    <input type="text" name="student_name" value="{{ Request::get('student_name') }}"
                                        class="form-control" placeholder="Enter student name…">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-building me-1"></i>Class
                                    </label>
                                    <select name="class_id" class="form-select">
                                        <option value="">— All Classes —</option>
                                        @foreach ($getClass as $class)
                                            <option value="{{ $class->id }}"
                                                {{ Request::get('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-journal-bookmark me-1"></i>Subject
                                    </label>
                                    <input type="text" name="subject_name" value="{{ Request::get('subject_name') }}"
                                        class="form-control" placeholder="Subject name…">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>Homework Date
                                    </label>
                                    <x-bs-date-input name="from_homework_date" id="from_homework_date"
                                        value="{{ Request::get('from_homework_date') }}" class="nepali-date form-control" />
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>Submission Date
                                    </label>
                                    <x-bs-date-input name="from_submission_date" id="from_submission_date"
                                        value="{{ Request::get('from_submission_date') }}"
                                        class="nepali-date form-control" />
                                </div>

                                <div class="col-md-1 d-flex gap-2">
                                    <button type="submit" class="btn btn-info text-white flex-fill fw-semibold">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <a href="{{ url('admin/homework/homework_report') }}"
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
                            <i class="bi bi-clipboard2-data-fill me-2 text-info"></i>Submitted Homework Report
                        </h6>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width:1200px;">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary"
                                        style="font-size:.7rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="50">#</th>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>HW Date</th>
                                        <th>Deadline</th>
                                        <th>Description</th>
                                        <th>Teacher Doc</th>
                                        <th>Student Doc</th>
                                        <th>Submission Note</th>
                                        <th>Submitted On</th>
                                        <th class="pe-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $value)
                                        @php
                                            $deadline = \Carbon\Carbon::parse(
                                                $value->getHomework->submission_date,
                                            )->endOfDay();
                                            $submittedAt = \Carbon\Carbon::parse($value->created_at);
                                            $wasLate = $submittedAt->gt($deadline);
                                            $daysLate = $wasLate
                                                ? (int) ceil($deadline->diffInDays($submittedAt, false))
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                        style="width:30px;height:30px;font-size:.7rem;">
                                                        {{ strtoupper(substr($value->first_name ?? 'S', 0, 1)) }}
                                                    </div>
                                                    <div class="fw-semibold small text-dark">
                                                        {{ $value->first_name }} {{ $value->last_name }}
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                    {{ $value->class_name }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="small fw-semibold text-dark">{{ $value->subject_name }}</div>
                                            </td>

                                            <td>
                                                <div class="small text-dark">
                                                    {{ date('d M Y', strtotime($value->getHomework->homework_date)) }}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="small text-dark">
                                                    {{ date('d M Y', strtotime($value->getHomework->submission_date)) }}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="small text-muted"
                                                    style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {!! strip_tags($value->getHomework->description) !!}
                                                </div>
                                            </td>

                                            {{-- Teacher Document --}}
                                            <td>
                                                @if (!empty($value->getHomework->document_file))
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ asset('upload/homework/' . $value->getHomework->document_file) }}"
                                                            class="btn btn-xs btn-outline-primary px-2" target="_blank"
                                                            style="font-size:.7rem;padding:2px 8px;">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ asset('upload/homework/' . $value->getHomework->document_file) }}"
                                                            class="btn btn-xs btn-outline-secondary px-2" download
                                                            style="font-size:.7rem;padding:2px 8px;">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary"
                                                        style="font-size:.65rem;">None</span>
                                                @endif
                                            </td>

                                            {{-- Student Document --}}
                                            <td>
                                                @if (!empty($value->document_file))
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ asset('upload/homework/' . $value->document_file) }}"
                                                            class="btn btn-xs btn-outline-info px-2" target="_blank"
                                                            style="font-size:.7rem;padding:2px 8px;">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ asset('upload/homework/' . $value->document_file) }}"
                                                            class="btn btn-xs btn-outline-secondary px-2" download
                                                            style="font-size:.7rem;padding:2px 8px;">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary"
                                                        style="font-size:.65rem;">None</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="small text-muted"
                                                    style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {!! strip_tags($value->description) !!}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="small text-dark fw-semibold">
                                                    @php
                                                        // Calculate 20 days before the record was created
                                                        $pastDate = \Carbon\Carbon::parse($value->created_at)->subDays(
                                                            20,
                                                        );
                                                    @endphp
                                                    {{ \App\Helpers\NepaliCalendar::format($pastDate, 'd M Y BS') }}
                                                </div>
                                                <div class="text-muted d-flex align-items-center"
                                                    style="font-size:.7rem; gap: 4px;">
                                                    <i class="bi bi-clock" style="font-size: .65rem;"></i>
                                                    {{ date('h:i A', strtotime($value->created_at)) }}
                                                </div>
                                            </td>

                                            <td class="pe-4">
                                                @if ($wasLate)
                                                    <span
                                                        class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                        <i class="bi bi-clock me-1"></i>Late · {{ $daysLate }}
                                                        {{ $daysLate == 1 ? 'day' : 'days' }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                        <i class="bi bi-check-circle me-1"></i>On Time
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-5">
                                                <i class="bi bi-clipboard2 d-block mb-2 text-muted"
                                                    style="font-size:2.5rem;opacity:.3;"></i>
                                                <div class="fw-semibold small text-muted">No homework reports found</div>
                                                <div class="text-muted" style="font-size:.78rem;">Try adjusting your
                                                    filters.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                        <span class="text-muted small">
                            Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of
                            {{ $getRecord->total() }} records
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
