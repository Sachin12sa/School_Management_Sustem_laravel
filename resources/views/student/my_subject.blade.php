@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;background:rgba(255,193,7,.15);color:#d39e00;">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Subjects</h4>
                            <span class="text-muted small">All subjects assigned to your class</span>
                        </div>
                    </div>
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
                        <i class="bi bi-journal-bookmark-fill me-2" style="color:#d39e00;"></i>Subject List
                    </h6>
                    <span class="badge px-3 py-1" style="background:rgba(255,193,7,.15);color:#856404;font-size:.72rem;">
                        {{ count($getRecord) }} {{ Str::plural('subject', count($getRecord)) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="70">#</th>
                                    <th>Subject Name</th>
                                    <th class="pe-4">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:36px;height:36px;font-size:.9rem;background:rgba(255,193,7,.12);color:#d39e00;">
                                                    <i class="bi bi-journal-text"></i>
                                                </div>
                                                <div class="fw-semibold small text-dark">{{ $value->subject_name }}</div>
                                            </div>
                                        </td>
                                        <td class="pe-4">
                                            @if($value->subject_type == 0)
                                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3">
                                                    <i class="bi bi-book me-1"></i>Theory
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3">
                                                    <i class="bi bi-tools me-1"></i>Practical
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <i class="bi bi-journal d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No subjects assigned yet</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection