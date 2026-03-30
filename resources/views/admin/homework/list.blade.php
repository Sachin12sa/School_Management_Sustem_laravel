@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">

                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Homework</h4>
                                <span class="text-muted small">All assigned homework tasks</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/homework/homework/add') }}"
                            class="btn btn-secondary px-4 shadow-sm fw-semibold">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add New Homework
                        </a>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-secondary"></i>
                        <h6 class="mb-0 fw-semibold">Filter Homework</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="GET" action="">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-building me-1"></i>Class
                                    </label>
                                    <input type="text" name="class_name" value="{{ Request::get('class_name') }}"
                                        class="form-control" placeholder="Search class…">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-journal-bookmark me-1"></i>Subject
                                    </label>
                                    <input type="text" name="subject_name" value="{{ Request::get('subject_name') }}"
                                        class="form-control" placeholder="Search subject…">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>Homework Date
                                    </label>
                                    <input type="date" name="homework_date" value="{{ Request::get('homework_date') }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3 me-1"></i>Submission Date
                                    </label>
                                    <input type="date" name="submission_date"
                                        value="{{ Request::get('submission_date') }}" class="form-control">
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-secondary flex-fill fw-semibold">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url('admin/homework/homework') }}"
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
                            <i class="bi bi-table me-2 text-secondary"></i>Homework List
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
                                        <th>Homework Date</th>
                                        <th>Submission Date</th>
                                        <th>Status</th>
                                        <th>Document</th>
                                        <th>Description</th>
                                        <th>Created By</th>
                                        <th class="text-center pe-4" width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Fixed: had <thead> nested inside <tbody> --}}
                                    @forelse($getRecord as $value)
                                        @php
                                            $deadline = \Carbon\Carbon::parse($value->submission_date)->endOfDay();
                                            $now = \Carbon\Carbon::now();
                                            $daysLeft = $now->diffInDays($deadline, false);
                                            $isOverdue = $now->gt($deadline);
                                            $isDueSoon = !$isOverdue && $daysLeft <= 3;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                    {{ $value->class_name }}
                                                </span><br>
                                                @if (!empty($value->class_section_name))
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2">
                                                        Section:{{ $value->class_section_name }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="fw-semibold small text-dark">{{ $value->subject_name }}</div>
                                            </td>

                                            <td>
                                                <div class="small text-dark">
                                                    {{ date('d M Y', strtotime($value->homework_date)) }}</div>
                                            </td>

                                            <td>
                                                <div class="small text-dark">
                                                    {{ date('d M Y', strtotime($value->submission_date)) }}</div>
                                            </td>

                                            <td>
                                                @if ($isOverdue)
                                                    <span
                                                        class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-2">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.4rem;vertical-align:middle;"></i>Overdue
                                                    </span>
                                                @elseif($isDueSoon)
                                                    <span
                                                        class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-2">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.4rem;vertical-align:middle;"></i>Due Soon
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success px-2">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.4rem;vertical-align:middle;"></i>Active
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if (!empty($value->document_file))
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
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">No
                                                        File</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="small text-muted"
                                                    style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {!! strip_tags($value->description) !!}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-person-circle text-muted"></i>
                                                    <span class="small">{{ $value->created_by_name }}</span>
                                                </div>
                                            </td>

                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <a href="{{ url('admin/homework/homework/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-2">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    <a href="{{ url('admin/homework/homework/submitted/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-success px-2">
                                                        <i class="bi bi-check2-circle me-1"></i>Submitted
                                                    </a>
                                                    <a href="{{ url('admin/homework/homework/delete/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-danger px-2"
                                                        onclick="return confirm('Delete this homework?')">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="bi bi-journal d-block mb-2 text-muted"
                                                    style="font-size:2.5rem;opacity:.3;"></i>
                                                <div class="fw-semibold small text-muted">No homework found</div>
                                                <div class="text-muted" style="font-size:.78rem;">Add new homework to get
                                                    started.</div>
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
                            {{ $getRecord->total() }} entries
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
