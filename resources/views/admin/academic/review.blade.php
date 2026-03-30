@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#d1fae5;color:#059669;">
                                <i class="bi bi-clipboard2-check-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Academic Upgrade — Review Results</h4>
                                <span class="text-muted small">
                                    Step 3 of 3 —
                                    <strong>{{ $fromSession->name }}</strong>
                                    <i class="bi bi-arrow-right mx-1"></i>
                                    <strong>{{ $toSession->name }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end d-flex gap-2 justify-content-end flex-wrap">
                        {{-- Rollback (only if not yet confirmed) --}}
                        @if (!($summary['promoted'] ?? 0) || true)
                            <form method="POST" action="{{ url('admin/academic/rollback') }}"
                                onsubmit="return confirm('Rollback this promotion batch? All new student records will be removed.')">
                                @csrf
                                <input type="hidden" name="from_session_id" value="{{ $fromSession->id }}">
                                <input type="hidden" name="to_session_id" value="{{ $toSession->id }}">
                                <button type="submit" class="btn btn-outline-danger px-4">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Rollback
                                </button>
                            </form>
                        @endif

                        {{-- Confirm & Activate --}}
                        <form method="POST" action="{{ url('admin/academic/confirm') }}"
                            onsubmit="return confirm('Confirm and activate session {{ $toSession->name }}?\n\nThis will set it as the current working session. This action cannot be undone.')">
                            @csrf
                            <input type="hidden" name="from_session_id" value="{{ $fromSession->id }}">
                            <input type="hidden" name="to_session_id" value="{{ $toSession->id }}">
                            <button type="submit" class="btn px-5 fw-semibold text-white" style="background:#059669;">
                                <i class="bi bi-check-circle-fill me-2"></i>Confirm &amp; Activate {{ $toSession->name }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="d-flex gap-2 mb-4">
                    @foreach (['Setup Rules', 'Preview Students', 'Review & Confirm'] as $i => $step)
                        <div class="flex-fill text-center py-2 rounded-3 fw-semibold small
                        {{ $i === 2 ? 'text-white' : 'bg-success bg-opacity-10 text-success' }}"
                            style="{{ $i === 2 ? 'background:#059669;' : '' }}">
                            <span class="badge me-1 bg-success">
                                @if ($i < 2)
                                    <i class="bi bi-check-lg"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </span>
                            {{ $step }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                {{-- Summary Cards --}}
                <div class="row g-3 mb-4">
                    @php
                        $total = $summary->sum();
                        $promoted = $summary['promoted'] ?? 0;
                        $failed = $summary['failed'] ?? 0;
                        $graduated = $summary['graduated'] ?? 0;
                    @endphp

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-primary">
                            <div class="fw-bold fs-3 text-primary">{{ $total }}</div>
                            <div class="text-muted small">Total Processed</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-success">
                            <div class="fw-bold fs-3 text-success">{{ $promoted }}</div>
                            <div class="text-muted small">Promoted</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-danger">
                            <div class="fw-bold fs-3 text-danger">{{ $failed }}</div>
                            <div class="text-muted small">Kept Back (Failed)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3 border-start border-4 border-warning">
                            <div class="fw-bold fs-3 text-warning">{{ $graduated }}</div>
                            <div class="text-muted small">Graduated</div>
                        </div>
                    </div>
                </div>

                {{-- Filter bar --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body py-3">
                        <form method="get" action="" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">Student Name</label>
                                <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                    placeholder="Search by name…">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">From Class</label>
                                <select name="from_class_id" class="form-select">
                                    <option value="">— All Classes —</option>
                                    @foreach ($getClass as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('from_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold small text-secondary">Result</label>
                                <select name="result" class="form-select">
                                    <option value="">— All —</option>
                                    <option value="promoted" {{ request('result') === 'promoted' ? 'selected' : '' }}>
                                        Promoted</option>
                                    <option value="failed" {{ request('result') === 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                    <option value="graduated" {{ request('result') === 'graduated' ? 'selected' : '' }}>
                                        Graduated</option>
                                </select>
                            </div>
                            {{-- Pass session params through filter --}}
                            <input type="hidden" name="from" value="{{ $fromSession->id }}">
                            <input type="hidden" name="to" value="{{ $toSession->id }}">
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.academic.review', ['from' => $fromSession->id, 'to' => $toSession->id]) }}"
                                    class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Results Table --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-table me-2 text-success"></i>Promotion Results
                        </h6>
                        <span class="badge bg-success bg-opacity-10 text-success small">
                            Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary"
                                        style="font-size:.7rem;letter-spacing:.04em;">
                                        <th class="ps-4" width="50">#</th>
                                        <th style="min-width:200px;">Student</th>
                                        <th>Admission No.</th>
                                        <th>From Class</th>
                                        <th>To Class</th>
                                        <th>Section Change</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $key => $value)
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                            <td>
                                                <div class="fw-semibold small text-dark">
                                                    {{ $value->student_name }} {{ $value->student_last_name }}
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge bg-dark bg-opacity-10 text-dark fw-semibold px-2"
                                                    style="font-family:monospace;">
                                                    {{ $value->admission_number }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2">
                                                    {{ $value->from_class_name }}
                                                </span>
                                            </td>

                                            <td>
                                                @if ($value->to_class_name)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2">
                                                        <i class="bi bi-arrow-right me-1"></i>{{ $value->to_class_name }}
                                                    </span>
                                                @elseif($value->result === 'graduated')
                                                    <span class="badge bg-warning text-dark px-2">
                                                        <i class="bi bi-mortarboard-fill me-1"></i>Graduated
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Same class</span>
                                                @endif
                                            </td>

                                            <td class="small">
                                                @if ($value->from_section_name || $value->to_section_name)
                                                    <span class="text-muted">{{ $value->from_section_name ?? '—' }}</span>
                                                    <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                    <span
                                                        style="color:#7c3aed;">{{ $value->to_section_name ?? '—' }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($value->result === 'promoted')
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                        <i class="bi bi-arrow-up me-1"></i>Promoted
                                                    </span>
                                                @elseif($value->result === 'failed')
                                                    <span
                                                        class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                        <i class="bi bi-arrow-down me-1"></i>Failed
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-1">
                                                        <i class="bi bi-mortarboard-fill me-1"></i>Graduated
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($value->is_confirmed)
                                                    <span class="badge bg-success px-2 py-1">
                                                        <i class="bi bi-check-lg me-1"></i>Confirmed
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark px-2 py-1">
                                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                No promotion records found.
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
                            {{ $getRecord->total() }} records
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
