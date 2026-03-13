@extends('layouts.app')
@section('content')
<main class="app-main">

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-house-heart-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Parent's Students</h4>
                            <span class="text-muted small">
                                <i class="bi bi-person me-1"></i>
                                {{ $getParent->name }} {{ $getParent->last_name }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/parent/list') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>Back to Parents
                    </a>
                </div>
            </div>

            {{-- ── Search Card ──────────────────────────────────────── --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-search text-danger"></i>
                    <h6 class="mb-0 fw-semibold">Search &amp; Assign Student</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">Student ID</label>
                                <input type="text" name="student_id"
                                       value="{{ request('student_id') }}"
                                       class="form-control" placeholder="e.g. 42">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">First Name</label>
                                <input type="text" name="name"
                                       value="{{ request('name') }}"
                                       class="form-control" placeholder="First name">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Last Name</label>
                                <input type="text" name="last_name"
                                       value="{{ request('last_name') }}"
                                       class="form-control" placeholder="Last name">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">Email</label>
                                <input type="text" name="email"
                                       value="{{ request('email') }}"
                                       class="form-control" placeholder="Email">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-danger flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('admin/parent/my-student/' . $parent_id) }}"
                                   class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Content ──────────────────────────────────────────────── --}}
    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            {{-- ── Search Results ──────────────────────────────────── --}}
            @if(!empty($getSearchStudent) && $getSearchStudent->count())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-search me-2 text-info"></i>Search Results
                        </h6>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            {{ $getSearchStudent->count() }} found
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="50">#</th>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Email</th>
                                        <th>Current Parent</th>
                                        <th>Enrolled</th>
                                        <th class="text-center pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getSearchStudent as $student)
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($student->profile_pic)
                                                        <img src="{{ asset('storage/' . $student->profile_pic) }}"
                                                             class="rounded-circle flex-shrink-0"
                                                             style="width:34px;height:34px;object-fit:cover;">
                                                    @else
                                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                             style="width:34px;height:34px;font-size:.8rem;">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold small text-dark">{{ $student->name }} {{ $student->last_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $student->class_name ?? 'N/A' }}</span>
                                            </td>
                                            <td><a href="mailto:{{ $student->email }}" class="text-muted small text-decoration-none">{{ $student->email ?? 'N/A' }}</a></td>
                                            <td class="small text-muted">{{ $student->parent_name ?? '—' }}</td>
                                            <td>
                                                <div class="small text-dark">{{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}</div>
                                                <div class="text-muted" style="font-size:.72rem;">{{ \Carbon\Carbon::parse($student->created_at)->format('h:i A') }}</div>
                                            </td>
                                            <td class="text-center pe-4">
                                                <a href="{{ url('admin/parent/assign_student_parent/' . $parent_id . '/' . $student->id) }}"
                                                   class="btn btn-sm btn-success px-3">
                                                    <i class="bi bi-person-plus-fill me-1"></i>Assign
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Assigned Students ───────────────────────────────── --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-people-fill me-2 text-danger"></i>Assigned Students
                    </h6>
                    <span class="badge bg-danger bg-opacity-10 text-danger">
                        {{ $getRecord->count() }} {{ Str::plural('student', $getRecord->count()) }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="50">#</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Email</th>
                                    <th>Enrolled</th>
                                    <th class="text-center pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $student)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($student->profile_pic)
                                                    <img src="{{ asset('storage/' . $student->profile_pic) }}"
                                                         class="rounded-circle flex-shrink-0"
                                                         style="width:34px;height:34px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                         style="width:34px;height:34px;font-size:.8rem;">
                                                        {{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold small text-dark">{{ $student->name }} {{ $student->last_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $student->class_name ?? 'N/A' }}</span>
                                        </td>
                                        <td><a href="mailto:{{ $student->email }}" class="text-muted small text-decoration-none">{{ $student->email ?? 'N/A' }}</a></td>
                                        <td>
                                            <div class="small text-dark">{{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ \Carbon\Carbon::parse($student->created_at)->format('h:i A') }}</div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="{{ url('admin/parent/assign_student_parent_delete/' . $student->id) }}"
                                               class="btn btn-sm btn-outline-danger px-3"
                                               onclick="return confirm('Remove this student from {{ $getParent->name }}?')">
                                                <i class="bi bi-person-dash-fill me-1"></i>Remove
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            <div class="fw-semibold small text-muted">No students assigned yet</div>
                                            <div class="text-muted" style="font-size:.78rem;">Use the search above to find and assign students.</div>
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