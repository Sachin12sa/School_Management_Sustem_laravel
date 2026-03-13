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
                            <i class="bi bi-clipboard2-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Homework</h4>
                            <span class="text-muted small">
                                {{ $getRecord->total() }} {{ Str::plural('assignment', $getRecord->total()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-success"></i>
                    <h6 class="mb-0 fw-semibold">Filter Homework</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Subject</label>
                                <input type="text" name="subject_name"
                                       value="{{ Request::get('subject_name') }}"
                                       class="form-control" placeholder="Search subject…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Homework Date
                                </label>
                                <input type="date" name="homework_date" class="form-control"
                                       value="{{ Request::get('homework_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Submission Date
                                </label>
                                <input type="date" name="submission_date" class="form-control"
                                       value="{{ Request::get('submission_date') }}">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Go
                                </button>
                                <a href="{{ url('student/my_homework') }}"
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
                        <i class="bi bi-clipboard2-fill me-2 text-success"></i>Homework List
                    </h6>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary"
                                    style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="50">#</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>HW Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Document</th>
                                    <th>Instructions</th>
                                    <th>Assigned By</th>
                                    <th class="text-center pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Fixed: <thead> nested inside <tbody> + pagination was commented out --}}
                                @forelse($getRecord as $value)
                                    @php
                                        $dl      = \Carbon\Carbon::parse($value->submission_date)->endOfDay();
                                        $now     = \Carbon\Carbon::now();
                                        $left    = (int)$now->diffInDays($dl, false);
                                        $overdue = $now->gt($dl);
                                        $soon    = !$overdue && $left <= 3;
                                        $bc      = $overdue ? 'danger' : ($soon ? 'warning' : 'success');
                                        $bl      = $overdue ? 'Overdue' : ($soon ? "Due in {$left}d" : 'Active');
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                {{ $value->class_name }}
                                            </span>
                                        </td>

                                        <td class="fw-semibold small text-dark">{{ $value->subject_name }}</td>

                                        <td class="small text-muted">{{ date('d M Y', strtotime($value->homework_date)) }}</td>

                                        <td>
                                            <div class="small text-dark">{{ date('d M Y', strtotime($value->submission_date)) }}</div>
                                        </td>

                                        <td>
                                            <span class="badge rounded-pill bg-{{ $bc }} bg-opacity-10 text-{{ $bc }} px-2">
                                                <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>{{ $bl }}
                                            </span>
                                        </td>

                                        <td>
                                            @if(!empty($value->document_file))
                                                <div class="d-flex gap-1">
                                                    <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                       target="_blank"
                                                       class="btn btn-xs btn-outline-primary px-2"
                                                       style="font-size:.7rem;padding:2px 8px;">
                                                        <i class="bi bi-eye me-1"></i>View
                                                    </a>
                                                    <a href="{{ asset('upload/homework/'.$value->document_file) }}"
                                                       download
                                                       class="btn btn-xs btn-outline-secondary px-2"
                                                       style="font-size:.7rem;padding:2px 8px;">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">None</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="small text-muted"
                                                 style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {!! strip_tags($value->description) !!}
                                            </div>
                                        </td>

                                        <td class="small text-muted">
                                            {{ $value->created_by_name }} {{ $value->created_by_last_name }}
                                        </td>

                                        <td class="text-center pe-4">
                                            @if(!$overdue)
                                                <a href="{{ url('student/my_homework/submit_homework/'.$value->id) }}"
                                                   class="btn btn-sm btn-success px-3 fw-semibold">
                                                    <i class="bi bi-upload me-1"></i>Submit
                                                </a>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2">
                                                    <i class="bi bi-lock me-1"></i>Locked
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="bi bi-clipboard2 d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No homework assigned yet</div>
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
                    {{-- Fixed: pagination was commented out --}}
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</main>
@endsection