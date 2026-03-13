@extends('layouts.app')

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Notice Board</h4>
                            <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('notice', $getRecord->total()) }} found</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-success"></i>
                    <h6 class="mb-0 fw-semibold">Search Notices</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Title</label>
                                <input type="text" name="title" value="{{ request('title') }}"
                                       class="form-control" placeholder="Search title…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Publish Date</label>
                                <div class="input-group">
                                    <input type="date" name="publish_date" id="publish_date" class="form-control"
                                           value="{{ request('publish_date') ? date('Y-m-d', strtotime(request('publish_date'))) : '' }}">
                                    <span class="input-group-text" onclick="document.getElementById('publish_date').showPicker()">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Message</label>
                                <input type="text" name="message" value="{{ request('message') }}"
                                       class="form-control" placeholder="Search in message…">
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('teacher/my_notice_board') }}" class="btn btn-outline-secondary flex-fill">
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

            @forelse($getRecord as $value)
                <div class="card border-0 shadow-sm mb-3 notice-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold text-dark mb-1">{{ $value->title }}</h5>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 flex-shrink-0 ms-3" style="font-size:.72rem;">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ date('d M Y', strtotime($value->publish_date)) }}
                            </span>
                        </div>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-clock me-1"></i>{{ date('h:i A', strtotime($value->publish_date)) }}
                        </div>
                        <hr class="my-2 opacity-10">
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
                        <div class="text-muted" style="font-size:.78rem;">Try adjusting your search filters</div>
                    </div>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $getRecord->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</main>

<style>
.notice-card { border-left: 4px solid rgba(25,135,84,.5) !important; transition: transform .2s ease, box-shadow .2s ease; }
.notice-card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important; }
</style>
@endsection