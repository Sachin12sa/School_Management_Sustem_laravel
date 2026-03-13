@extends('layouts.app')

@section('content')
<main class="app-main">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
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
                            <h4 class="mb-0 fw-semibold text-dark">Parent List</h4>
                            <span class="text-muted small">
                                <i class="bi bi-people me-1"></i>
                                {{ $getRecord->total() }} {{ Str::plural('record', $getRecord->total()) }} found
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/parent/add') }}" class="btn btn-danger px-4 shadow-sm">
                        <i class="bi bi-plus-circle-fill me-2"></i>Add New Parent
                    </a>
                </div>
            </div>

            {{-- ── Search Card ──────────────────────────────────────────────── --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-danger"></i>
                    <h6 class="mb-0 fw-semibold">Filter Parents</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="get" action="">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-person me-1"></i>Name
                                </label>
                                <input type="text" name="name"
                                       value="{{ request('name') }}"
                                       class="form-control"
                                       placeholder="Search by name…">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-envelope me-1"></i>Email
                                </label>
                                <input type="text" name="email"
                                       value="{{ request('email') }}"
                                       class="form-control"
                                       placeholder="Search by email…">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-phone me-1"></i>Phone Number
                                </label>
                                <input type="text" name="mobile_number"
                                       value="{{ request('mobile_number') }}"
                                       class="form-control"
                                       placeholder="Search by phone…">
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-danger flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('admin/parent/list') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Content ──────────────────────────────────────────────────────── --}}
    <div class="app-content">
        <div class="container-fluid">

            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2 text-danger"></i>All Parents
                    </h6>
                    <span class="badge bg-danger bg-opacity-10 text-danger small">
                        Page {{ $getRecord->currentPage() }} of {{ $getRecord->lastPage() }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="50">#</th>
                                    <th style="min-width:230px;">Parent</th>
                                    <th>Mobile</th>
                                    <th>Gender</th>
                                    <th>Occupation</th>
                                    <th style="min-width:160px;">Address</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th class="text-center pe-4" width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $getRecord->firstItem() + $key }}</td>

                                        {{-- Avatar + Name + Email --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="flex-shrink-0">
                                                    @if(!empty($value->profile_pic))
                                                        <img src="{{ asset('storage/' . $value->profile_pic) }}"
                                                             alt="{{ $value->name }}"
                                                             class="rounded-circle shadow-sm"
                                                             style="width:42px;height:42px;object-fit:cover;">
                                                    @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger text-white fw-bold shadow-sm"
                                                             style="width:42px;height:42px;font-size:.9rem;">
                                                            {{ strtoupper(substr($value->name, 0, 1)) }}{{ strtoupper(substr($value->last_name ?? '', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark small">{{ $value->name }} {{ $value->last_name ?? '' }}</div>
                                                    <div class="text-muted" style="font-size:.75rem;">{{ $value->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="small">
                                            @if(!empty($value->mobile_number))
                                                <a href="tel:{{ $value->mobile_number }}" class="text-decoration-none text-dark">
                                                    <i class="bi bi-telephone me-1 text-muted"></i>{{ $value->mobile_number }}
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td class="small">{{ $value->gender ?? '—' }}</td>

                                        <td class="small">
                                            @if(!empty($value->occupation))
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                                    {{ $value->occupation }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td class="small text-muted" style="max-width:160px;">
                                            <span class="d-block text-truncate" title="{{ $value->address ?? '' }}">
                                                {{ $value->address ?? '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            @if(($value->status ?? 0) == 0)
                                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-1">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Active
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-1">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Inactive
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ date('d M Y', strtotime($value->created_at)) }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ date('h:i A', strtotime($value->created_at)) }}</div>
                                        </td>

                                        <td class="text-center pe-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <a href="{{ url('admin/parent/edit/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-primary px-3"
                                                   title="Edit Parent">
                                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                                </a>
                                                <a href="{{ url('admin/parent/my-student/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-info px-3"
                                                   title="View Children">
                                                    <i class="bi bi-people-fill me-1"></i>Students
                                                </a>
                                                <a href="{{ url('admin/parent/delete/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-danger px-2"
                                                   title="Delete Parent"
                                                   onclick="return confirm('Are you sure you want to delete this parent?')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-house-heart d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
                                                <div class="fw-semibold small">No parents found</div>
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
                        Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of {{ $getRecord->total() }} parents
                    </span>
                    {{ $getRecord->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

</main>
@endsection