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
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Students of {{ $getClass->name }}</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('student', $getRecord->total()) }} found
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/class/list') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i>Back to Classes
                        </a>
                    </div>
                </div>

                {{-- Sections quick-links --}}
                @if ($getSections->count())
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ url()->current() }}"
                            class="btn btn-sm {{ !request('section_id') ? 'btn-warning text-dark' : 'btn-outline-secondary' }} fw-semibold">
                            <i class="bi bi-people me-1"></i>All Sections
                        </a>
                        @foreach ($getSections as $sec)
                            <a href="{{ url()->current() . '?section_id=' . $sec->id }}"
                                class="btn btn-sm fw-semibold {{ request('section_id') == $sec->id ? 'text-white' : '' }}"
                                style="{{ request('section_id') == $sec->id
                                    ? 'background:#7c3aed;border-color:#7c3aed;color:#fff;'
                                    : 'border-color:#7c3aed;color:#7c3aed;' }}">
                                <i class="bi bi-diagram-3 me-1"></i>Section {{ $sec->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Filter Card --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-warning"></i>
                        <h6 class="mb-0 fw-semibold">Filter Students</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="get" action="">
                            {{-- Preserve section_id when searching --}}
                            @if (request('section_id'))
                                <input type="hidden" name="section_id" value="{{ request('section_id') }}">
                            @endif
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">Name</label>
                                    <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                        placeholder="Search by name…">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">Email</label>
                                    <input type="text" name="email" value="{{ request('email') }}"
                                        class="form-control" placeholder="Search by email…">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small text-secondary">Admission Number</label>
                                    <input type="text" name="admission_number" value="{{ request('admission_number') }}"
                                        class="form-control" placeholder="Search by admission no…">
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-warning text-dark flex-fill">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
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
                            <i class="bi bi-table me-2 text-warning"></i>
                            All Students
                            @if (request('section_id') && $getSections->count())
                                @php $activeSection = $getSections->firstWhere('id', request('section_id')); @endphp
                                @if ($activeSection)
                                    — <span style="color:#7c3aed;">Section {{ $activeSection->name }}</span>
                                @endif
                            @endif
                        </h6>
                        <span class="badge bg-warning bg-opacity-10 text-warning small">
                            Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary"
                                        style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4" width="50">#</th>
                                        <th style="min-width:230px;">Student</th>
                                        <th>Section</th>
                                        <th>Parent</th>
                                        <th>Admission No.</th>
                                        <th>Admission Date</th>
                                        <th>Gender</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $key => $value)
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if (!empty($value->profile_pic))
                                                        <img src="{{ asset('storage/' . $value->profile_pic) }}"
                                                            alt="{{ $value->name }}" class="rounded-circle shadow-sm"
                                                            style="width:42px;height:42px;object-fit:cover;">
                                                    @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark fw-bold shadow-sm"
                                                            style="width:42px;height:42px;font-size:.9rem;">
                                                            {{ strtoupper(substr($value->name, 0, 1)) }}{{ strtoupper(substr($value->last_name ?? '', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold text-dark small">{{ $value->name }}
                                                            {{ $value->last_name ?? '' }}</div>
                                                        <div class="text-muted" style="font-size:.75rem;">
                                                            {{ $value->email }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Section --}}
                                            <td>
                                                @if (!empty($value->section_name))
                                                    <span class="badge fw-semibold px-2 py-1"
                                                        style="background:#ede9fe;color:#7c3aed;font-size:.75rem;">
                                                        <i class="bi bi-diagram-3 me-1"></i>{{ $value->section_name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>

                                            <td class="small">
                                                @if (!empty($value->parent_name))
                                                    <div class="d-flex align-items-center gap-1">
                                                        <i class="bi bi-house-heart text-muted"></i>
                                                        <span>{{ $value->parent_name }}
                                                            {{ $value->parent_last_name ?? '' }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-dark bg-opacity-10 text-dark fw-semibold px-2 py-1"
                                                    style="font-family:monospace;">
                                                    {{ $value->admission_number ?? '—' }}
                                                </span>
                                            </td>

                                            <td class="small">
                                                {{ !empty($value->admission_date) ? date('d M Y', strtotime($value->admission_date)) : '—' }}
                                            </td>

                                            <td class="small">{{ $value->gender ?? '—' }}</td>

                                            <td class="small">
                                                @if (!empty($value->mobile_number))
                                                    <a href="tel:{{ $value->mobile_number }}"
                                                        class="text-decoration-none text-dark">
                                                        <i
                                                            class="bi bi-telephone me-1 text-muted"></i>{{ $value->mobile_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if (($value->status ?? 0) == 0)
                                                    <span
                                                        class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.45rem;vertical-align:middle;"></i>Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="font-size:.45rem;vertical-align:middle;"></i>Inactive
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="bi bi-people d-block mb-2"
                                                        style="font-size:2.5rem;opacity:.3;"></i>
                                                    <div class="fw-semibold small">No students found</div>
                                                    <div style="font-size:.78rem;">Try adjusting your search filters.</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                        <span class="text-muted small">
                            Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }}
                            of {{ $getRecord->total() }} students
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection
