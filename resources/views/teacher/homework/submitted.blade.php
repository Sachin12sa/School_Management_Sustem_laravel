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
                            <i class="bi bi-box-arrow-in-down"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Submitted Homework</h4>
                            <span class="text-muted small">Student submissions for this assignment</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('teacher/homework/homework') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Homework
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            {{-- Search --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-success"></i>
                    <h6 class="mb-0 fw-semibold">Search Submissions</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Student Name</label>
                                <input type="text" class="form-control" name="created_by_name"
                                       value="{{ Request::get('created_by_name') }}" placeholder="First or last name…">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Submitted Date</label>
                                <input type="date" class="form-control" name="submission_date"
                                       value="{{ Request::get('submission_date') }}">
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url(Request::url()) }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-table text-success"></i>
                    <h6 class="mb-0 fw-semibold">Student Submissions</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Student</th>
                                    <th>Class Name</th>
                                    <th>Student Document</th>
                                    <th>Description</th>
                                    <th>Submitted</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $deadline    = \Carbon\Carbon::parse($value->getHomework->submission_date)->endOfDay();
                                        $submittedAt = \Carbon\Carbon::parse($value->created_at);
                                        $isLate      = $submittedAt->gt($deadline);
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                     style="width:34px;height:34px;font-size:.75rem;">
                                                    {{ strtoupper(substr($value->created_by_name,0,1)) }}{{ strtoupper(substr($value->created_by_name??'',0,1)) }}
                                                </div>
                                                <span class="fw-semibold small text-dark">
                                                    {{ $value->created_by_name }} {{ $value->created_by_last_name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td style="max-width:200px;">
                                            <div class="text-muted small text-truncate">{!! strip_tags($value->class_name) !!}</div>
                                        </td>
                                        <td>
                                            @if(!empty($value->document_file))
                                                <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                                <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                   class="btn btn-sm btn-primary ms-1" download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">No File</span>
                                            @endif
                                        </td>
                                        <td style="max-width:200px;">
                                            <div class="text-muted small text-truncate">{!! strip_tags($value->description) !!}</div>
                                        </td>
                                        <td class="small text-muted">{{ date('d M Y', strtotime($value->created_at)) }}</td>
                                        <td class="small text-muted">{{ date('d M Y', strtotime($value->getHomework->submission_date)) }}</td>
                                        <td>
                                            @if($isLate)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">
                                                    <i class="bi bi-clock me-1"></i>Late
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">
                                                    <i class="bi bi-check-circle me-1"></i>On Time
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-inbox d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="text-muted small">No submissions yet</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
                    <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('submission', $getRecord->total()) }}</span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</main>
@endsection