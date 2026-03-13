@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-clipboard2-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Homework</h4>
                            <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('assignment', $getRecord->total()) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('parent/my_student') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>

            {{-- Search --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Search Homework</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Subject</label>
                                <input type="text" class="form-control" name="subject_name"
                                       value="{{ Request::get('subject_name') }}" placeholder="Subject name…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Homework Date</label>
                                <input type="date" class="form-control" name="homework_date"
                                       value="{{ Request::get('homework_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Submission Date</label>
                                <input type="date" class="form-control" name="submission_date"
                                       value="{{ Request::get('submission_date') }}">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-warning flex-fill fw-semibold">
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
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-list-task text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Homework List</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>HW Date</th>
                                    <th>Due Date</th>
                                    <th>Document</th>
                                    <th style="max-width:180px;">Description</th>
                                    <th>Created By</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $value)
                                    @php
                                        $due      = \Carbon\Carbon::parse($value->submission_date)->endOfDay();
                                        $daysLeft = now()->diffInDays($due, false);
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-2">
                                                {{ $value->class_name }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold small text-dark">{{ $value->subject_name }}</td>
                                        <td class="small text-muted">{{ date('d M Y', strtotime($value->homework_date)) }}</td>
                                        <td>
                                            <div class="small text-muted">{{ date('d M Y', strtotime($value->submission_date)) }}</div>
                                            @if($daysLeft < 0)
                                                <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:.62rem;">Overdue</span>
                                            @elseif($daysLeft <= 2)
                                                <span class="badge bg-warning bg-opacity-15 text-warning" style="font-size:.62rem;">Due Soon</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.62rem;">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($value->document_file))
                                                <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                   class="btn btn-sm btn-outline-secondary" target="_blank">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </a>
                                                <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                   class="btn btn-sm btn-secondary ms-1" download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">No File</span>
                                            @endif
                                        </td>
                                        <td style="max-width:180px;">
                                            <div class="text-muted small text-truncate">{!! strip_tags($value->description) !!}</div>
                                        </td>
                                        <td class="small text-muted">{{ $value->created_by_name }} {{ $value->created_by_last_name }}</td>
                                        <td class="small text-muted">{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="bi bi-clipboard2 d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="text-muted small">No homework found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
                    <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }}</span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</main>
@endsection