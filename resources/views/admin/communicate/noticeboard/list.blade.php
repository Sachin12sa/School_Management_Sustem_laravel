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
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Notice Board</h4>
                            <span class="text-muted small">
                                <i class="bi bi-collection me-1"></i>{{ $getRecord->total() }} {{ Str::plural('notice', $getRecord->total()) }} total
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/communicate/notice_board/add') }}"
                       class="btn btn-warning text-dark px-4 shadow-sm fw-semibold">
                        <i class="bi bi-plus-circle-fill me-2"></i>Add New Notice
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Filter Notices</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="get" action="">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-megaphone me-1"></i>Title
                                </label>
                                <input type="text" name="title" value="{{ request('title') }}"
                                       class="form-control" placeholder="Search by title…">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Notice Date
                                </label>
                                <input type="date" name="notice_date" id="notice_date"
                                       value="{{ request('notice_date') ? date('Y-m-d', strtotime(request('notice_date'))) : '' }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3-event me-1"></i>Publish Date
                                </label>
                                <input type="date" name="publish_date" id="publish_date"
                                       value="{{ request('publish_date') ? date('Y-m-d', strtotime(request('publish_date'))) : '' }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-chat-left-text me-1"></i>Message
                                </label>
                                <input type="text" name="message" value="{{ request('message') }}"
                                       class="form-control" placeholder="Search in message…">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-warning text-dark flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('admin/communicate/notice_board') }}"
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
                    {{-- Fixed: card title said "Admin List" --}}
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-megaphone-fill me-2 text-warning"></i>All Notices
                    </h6>
                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary"
                                    style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Title</th>
                                    <th>Notice Date</th>
                                    <th>Publish Date</th>
                                    <th>Recipients</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th class="text-center pe-4" width="160">Actions</th>
                                </tr>
                            </thead>
                            {{-- Fixed: had a <thead> nested inside <tbody>, and used @foreach without @forelse/empty state --}}
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        {{-- Fixed: was using $value->id (DB id) as S.N --}}
                                        <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                        <td>
                                            <div class="fw-semibold small text-dark">{{ $value->title }}</div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ date('d M Y', strtotime($value->notice_date)) }}</div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ date('d M Y', strtotime($value->publish_date)) }}</div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($value->getMessage as $message)
                                                    @php
                                                        $labels = [2 => 'Teacher', 3 => 'Student', 4 => 'Parent'];
                                                        $colors = [2 => 'success', 3 => 'warning',  4 => 'danger'];
                                                        $lbl   = $labels[$message->message_to] ?? null;
                                                        $color = $colors[$message->message_to]  ?? 'secondary';
                                                    @endphp
                                                    @if($lbl)
                                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-2"
                                                              style="font-size:.7rem;">{{ $lbl }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-circle text-muted"></i>
                                                <span class="small">{{ $value->created_by_name }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ \Carbon\Carbon::parse($value->created_at)->format('d M Y') }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}</div>
                                        </td>

                                        <td class="text-center pe-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <a href="{{ url('admin/communicate/notice_board/edit/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-primary px-3">
                                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                                </a>
                                                <a href="{{ url('admin/communicate/notice_board/delete/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-danger px-2"
                                                   onclick="return confirm('Delete this notice?')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="bi bi-megaphone d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No notices found</div>
                                            <div class="text-muted" style="font-size:.78rem;">Add a new notice to get started.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                    <span class="text-muted small">
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }} notices
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

</main>
@endsection