@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-clipboard2-check-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Submitted Homework</h4>
                            <span class="text-muted small">
                                {{ $getRecord->total() }} {{ Str::plural('submission', $getRecord->total()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Filter Submissions</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Subject</label>
                                <input type="text" name="subject_name"
                                       value="{{ Request::get('subject_name') }}"
                                       class="form-control" placeholder="Subject name…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Homework Date</label>
                                <input type="date" name="homework_date" class="form-control"
                                       value="{{ Request::get('homework_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Submission Date</label>
                                <input type="date" name="submission_date" class="form-control"
                                       value="{{ Request::get('submission_date') }}">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Go
                                </button>
                                <a href="{{ url('student/my_submitted_homework') }}"
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
                        <i class="bi bi-clipboard2-check-fill me-2 text-primary"></i>Submission History
                    </h6>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width:1100px;">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary"
                                    style="font-size:.7rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="50">#</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>HW Date</th>
                                    <th>Deadline</th>
                                    <th>Teacher's Instructions</th>
                                    <th>Teacher Doc</th>
                                    <th>Your Doc</th>
                                    <th>Your Notes</th>
                                    <th>Submitted On</th>
                                    <th class="pe-4" width="180">Status / Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $deadline      = \Carbon\Carbon::parse($value->getHomework->submission_date)->endOfDay();
                                        $submittedAt   = \Carbon\Carbon::parse($value->created_at);
                                        $now           = \Carbon\Carbon::now();
                                        $isPastDeadline = $now->gt($deadline);
                                        $wasLate        = $submittedAt->gt($deadline);
                                        $daysLate       = $wasLate ? (int)$deadline->diffInDays($submittedAt) : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                {{ $value->class_name }}
                                            </span>
                                        </td>

                                        <td class="fw-semibold small text-dark">{{ $value->subject_name }}</td>

                                        <td class="small text-muted">
                                            {{ date('d M Y', strtotime($value->getHomework->homework_date)) }}
                                        </td>

                                        <td>
                                            <div class="small text-dark">
                                                {{ date('d M Y', strtotime($value->getHomework->submission_date)) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div class="small text-muted"
                                                 style="max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {!! strip_tags($value->getHomework->description) !!}
                                            </div>
                                        </td>

                                        {{-- Teacher doc --}}
                                        <td>
                                            @if(!empty($value->getHomework->document_file))
                                                <div class="d-flex gap-1">
                                                    <a href="{{ asset('upload/homework/'.$value->getHomework->document_file) }}"
                                                       target="_blank"
                                                       class="btn btn-xs btn-outline-primary px-2"
                                                       style="font-size:.68rem;padding:2px 7px;">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ asset('upload/homework/'.$value->getHomework->document_file) }}"
                                                       download
                                                       class="btn btn-xs btn-outline-secondary px-2"
                                                       style="font-size:.68rem;padding:2px 7px;">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:.65rem;">None</span>
                                            @endif
                                        </td>

                                        {{-- Your doc --}}
                                        <td>
                                            @if(!empty($value->document_file))
                                                <div class="d-flex gap-1">
                                                    <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                       target="_blank"
                                                       class="btn btn-xs btn-outline-info px-2"
                                                       style="font-size:.68rem;padding:2px 7px;">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                       download
                                                       class="btn btn-xs btn-outline-secondary px-2"
                                                       style="font-size:.68rem;padding:2px 7px;">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:.65rem;">None</span>
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
                                                {{ date('d M Y', strtotime($value->created_at)) }}
                                            </div>
                                            <div class="text-muted" style="font-size:.7rem;">
                                                {{ date('h:i A', strtotime($value->created_at)) }}
                                            </div>
                                        </td>

                                        <td class="pe-4">
                                            {{-- Submission status badge --}}
                                            <div class="mb-2">
                                                @if($wasLate)
                                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-2">
                                                        <i class="bi bi-clock me-1"></i>Late
                                                        @if($daysLate > 0)· {{ $daysLate }}d @endif
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-2">
                                                        <i class="bi bi-check-circle me-1"></i>On Time
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Resubmit / Locked --}}
                                            @if(!$isPastDeadline)
                                                <a href="{{ url('student/homework/edit_submit/'.$value->homework_id) }}"
                                                   class="btn btn-sm btn-warning text-dark fw-semibold px-2"
                                                   style="font-size:.72rem;">
                                                    <i class="bi bi-arrow-repeat me-1"></i>Resubmit
                                                </a>
                                                <div class="text-muted mt-1" style="font-size:.67rem;">
                                                    Due: {{ $deadline->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="bi bi-lock-fill me-1"></i>Locked
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                           <i class="bi bi-clipboard2-check d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                         <div class="fw-semibold small text-muted">No submissions found</div>
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