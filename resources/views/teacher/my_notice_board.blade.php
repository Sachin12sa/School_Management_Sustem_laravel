@extends('layouts.app')

@section('content')
<main class="app-main bg-light pb-5">

    <div class="container mt-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold text-dark">
                <i class="bi bi-megaphone me-2"></i>My Notice Board
            </h2>
            <span class="badge bg-primary rounded-pill">{{ $getRecord->total() }} Notices</span>
        </div>
         <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Search Notice Board</h3>
                        </div>
                         <form method="get" action="">
                            <div class="card-body">
                                <div class="row align-items-end">

                                    <div class="col-md-3">
                                        <label class="form-label">Title</label>
                                        <input
                                            type="text"
                                            name="title"
                                            value="{{ request('title') }}"
                                            class="form-control"
                                            placeholder="Enter Title"
                                        />
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Publish Date</label>
                                        <div class="input-group">
                                        <input type="date" name="publish_date" id="publish_date" class="form-control" 
                                                value="{{ request('publish_date') ? date('Y-m-d', strtotime(request('publish_date'))) : '' }}">
                                        <span class="input-group-text" onclick="document.getElementById('publish_date').showPicker()">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Message</label>
                                        <input
                                            type="text"
                                            name="message"
                                            value="{{ request('message') }}"
                                            class="form-control"
                                            placeholder="Enter Message"
                                        />
                                    </div>
                                    
                                    

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">
                                            Search
                                        </button>
                                        <a href="{{ url('student/my_notice_board') }}" class="btn btn-success ms-1">
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </form>

                       

                    </div>
                </div>
            </div>

        <div class="row">
            @forelse ($getRecord as $value)
            <div class="col-12 mb-4">
                <div class="card notice-card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="notice-title mb-1 text-primary">
                                    {{ $value->title }}
                                </h4>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ date('d M Y', strtotime($value->publish_date)) }}
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ date('h:i A', strtotime($value->publish_date)) }}
                                </div>
                            </div>
                        </div>

                        <hr class="opacity-10">

                        <div class="notice-message text-secondary">
                            {!! $value->message !!}
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card card-body text-center py-5 shadow-sm">
                    <p class="text-muted mb-0">No notices found at this time.</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $getRecord->appends(request()->query())->links() }}
        </div>

    </div>

</main>

<style>
    .notice-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-left: 5px solid #0d6efd !important; /* Visual accent */
    }

    .notice-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .notice-title {
        letter-spacing: -0.02em;
        font-weight: 700;
    }

    .notice-message {
        line-height: 1.6;
        font-size: 1.05rem;
    }

    /* Styling for the pagination if using Bootstrap */
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endsection