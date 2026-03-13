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
                                <i class="bi bi-collection me-1"></i>
                                {{ $getRecord->total() }} {{ Str::plural('notice', $getRecord->total()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Search Notices</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Title</label>
                                <input type="text" name="title" value="{{ request('title') }}"
                                       class="form-control" placeholder="Search by title…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i>Publish Date
                                </label>
                                <input type="date" name="publish_date" class="form-control"
                                       value="{{ request('publish_date') ? date('Y-m-d', strtotime(request('publish_date'))) : '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Message Keywords</label>
                                <input type="text" name="message" value="{{ request('message') }}"
                                       class="form-control" placeholder="Search in message…">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-warning text-dark flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('student/my_notice_board') }}"
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

            @forelse($getRecord as $value)
                <div class="card border-0 shadow-sm mb-3"
                     style="border-left:4px solid rgba(255,193,7,.6)!important;border-radius:.75rem;">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3 flex-wrap">
                            <div>
                                <h5 class="fw-bold text-dark mb-1" style="letter-spacing:-.02em;">
                                    {{ $value->title }}
                                </h5>
                                <div class="d-flex align-items-center gap-3 text-muted small flex-wrap">
                                    <span>
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ date('d M Y', strtotime($value->publish_date)) }}
                                    </span>
                                    <span>
                                        <i class="bi bi-clock me-1"></i>
                                        {{ date('h:i A', strtotime($value->publish_date)) }}
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-warning bg-opacity-15 text-primary border px-3 py-2"
                                  style="border-color:rgba(12, 12, 1, 0.3)!important;font-size:.72rem;">
                                <i class="bi bi-megaphone me-1"></i>Notice
                            </span>
                        </div>

                        <hr class="my-3 opacity-10">

                        <div class="text-secondary" style="line-height:1.7;font-size:1rem;">
                            {!! $value->message !!}
                        </div>

                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-megaphone d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                        <div class="fw-semibold small text-muted">No notices found</div>
                        <div class="text-muted" style="font-size:.78rem;">Check back later for announcements.</div>
                    </div>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $getRecord->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</main>
@endsection