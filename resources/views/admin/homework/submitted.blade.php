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
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Submitted Homework</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/homework/homework') }}" class="text-muted text-decoration-none">Back to Homework List</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-success"></i>
                    <h6 class="mb-0 fw-semibold">Filter Submissions</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-person me-1"></i>Student Name
                                </label>
                                <input type="text" name="student_name"
                                       value="{{ Request::get('student_name') }}"
                                       class="form-control" placeholder="First or last name…">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Submitted Date
                                </label>
                                <input type="date" name="created_at"
                                       value="{{ Request::get('created_at') }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ Request::url() }}" class="btn btn-outline-secondary flex-fill">
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
                        <i class="bi bi-check2-circle me-2 text-success"></i>Student Submissions
                    </h6>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        {{ $getRecord->total() }} {{ Str::plural('submission', $getRecord->total()) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Student</th>
                                    <th>Document</th>
                                    <th>Description</th>
                                    <th>Submitted Date</th>
                                    <th>Deadline</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $deadline    = \Carbon\Carbon::parse($value->getHomework->submission_date)->endOfDay();
                                        $submittedAt = \Carbon\Carbon::parse($value->created_at);
                                        $isLate      = $submittedAt->gt($deadline);
                                        $daysLate    = $isLate ? $deadline->diffInDays($submittedAt) : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                     style="width:32px;height:32px;font-size:.72rem;">
                                                    {{ strtoupper(substr($value->student_name ?? 'S', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small text-dark">
                                                        {{ $value->student_name }} {{ $value->student_last_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            @if(!empty($value->document_file))
                                                <div class="d-flex gap-1">
                                                    <a href="{{ asset('upload/homework/' . $value->document_file) }}"
                                                       class="btn btn-xs btn-outline-primary px-2" target="_blank"
                                                       style="font-size:.72rem;padding:2px 8px;">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </a>
                                                    <a href="{{ asset('upload/homework/' . $value->document_file) }}"
                                                       class="btn btn-xs btn-outline-secondary px-2" download
                                                       style="font-size:.72rem;padding:2px 8px;">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">No File</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="small text-muted" style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {!! strip_tags($value->description) !!}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="small text-dark fw-semibold">{{ date('d M Y', strtotime($value->created_at)) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ date('h:i A', strtotime($value->created_at)) }}</div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ date('d M Y', strtotime($value->getHomework->submission_date)) }}</div>
                                        </td>

                                        <td class="pe-4">
                                            @if($isLate)
                                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                    <i class="bi bi-clock me-1"></i>Late
                                                    @if($daysLate > 0)
                                                        · {{ $daysLate }} {{ $daysLate == 1 ? 'day' : 'days' }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                    <i class="bi bi-check-circle me-1"></i>On Time
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bi bi-inbox d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No submissions yet</div>
                                            <div class="text-muted" style="font-size:.78rem;">No student has submitted this homework yet.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="text-muted small">
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }} submissions
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

</main>
@endsection