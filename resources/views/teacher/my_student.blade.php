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
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">My Students</h4>
                            <span class="text-muted small">{{ $getRecord->total() }} {{ Str::plural('student', $getRecord->total()) }} total</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-success"></i>
                    <h6 class="mb-0 fw-semibold">Search Students</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Name</label>
                                <input type="text" name="name" value="{{ request('name') }}"
                                       class="form-control" placeholder="Student name…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Email</label>
                                <input type="text" name="email" value="{{ request('email') }}"
                                       class="form-control" placeholder="Email address…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Class</label>
                                <input type="text" name="class_name" value="{{ request('class_name') }}"
                                       class="form-control" placeholder="Class name…">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('teacher/my_student') }}" class="btn btn-outline-secondary flex-fill">
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
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.04em;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th style="min-width:220px;">Student</th>
                                    <th>Admission No</th>
                                    <th>Admission Date</th>
                                    <th>Class</th>
                                    <th>Gender</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if(!empty($value->profile_pic))
                                                    <img src="{{ asset('storage/'.$value->profile_pic) }}"
                                                         class="rounded-circle flex-shrink-0"
                                                         style="width:38px;height:38px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                                         style="width:38px;height:38px;font-size:.78rem;">
                                                        {{ strtoupper(substr($value->name,0,1)) }}{{ strtoupper(substr($value->last_name??'',0,1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold text-dark small">{{ $value->name }} {{ $value->last_name }}</div>
                                                    <div class="text-muted" style="font-size:.72rem;">{{ $value->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold font-monospace small text-dark">{{ $value->admission_number }}</td>
                                        <td class="small text-muted">{{ $value->admission_date ? date('d M Y', strtotime($value->admission_date)) : '—' }}</td>
                                        <td class="small">{{ $value->class_name }}</td>
                                        <td class="small">{{ $value->gender }}</td>
                                        <td class="small">{{ $value->mobile_number ?? '—' }}</td>
                                        <td>
                                            @if($value->status == 0)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small text-dark">{{ date('d-m-Y', strtotime($value->created_at)) }}</div>
                                            <div class="text-muted" style="font-size:.7rem;">{{ date('h:i A', strtotime($value->created_at)) }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2rem;opacity:.3;"></i>
                                            <div class="text-muted small">No students found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
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