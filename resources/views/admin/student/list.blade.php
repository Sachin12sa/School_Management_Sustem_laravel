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
                                <h4 class="mb-0 fw-semibold text-dark">Student List</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $getRecord->total() }} {{ Str::plural('student', $getRecord->total()) }}
                                    @if ($getCurrentSession ?? null)
                                        · <span style="color:#d97706;font-weight:600;">
                                            <i class="bi bi-calendar3-range me-1"></i>{{ $getCurrentSession->name }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ url('admin/student/add') }}"
                            class="btn btn-warning text-dark px-4 shadow-sm fw-semibold">
                            <i class="bi bi-plus-circle-fill me-2"></i>Add New Student
                        </a>
                    </div>
                </div>

                {{-- ── Filter Card ──────────────────────────────────────────────── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <i class="bi bi-funnel-fill text-warning"></i>
                        <h6 class="mb-0 fw-semibold">Filter Students</h6>
                    </div>
                    <div class="card-body bg-light bg-opacity-50">
                        <form method="get" action="" id="filter-form">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-person me-1"></i>Name
                                    </label>
                                    <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                        placeholder="Search by name…">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </label>
                                    <input type="text" name="email" value="{{ request('email') }}" class="form-control"
                                        placeholder="Search by email…">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-hash me-1"></i>Admission No.
                                    </label>
                                    <input type="text" name="admission_number" value="{{ request('admission_number') }}"
                                        class="form-control" placeholder="ADM-…">
                                </div>


                                {{-- Class filter --}}
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-building me-1"></i>Class
                                    </label>
                                    <select name="class_id" id="filter_class_id" class="form-select">
                                        <option value="">— All Classes —</option>
                                        @foreach ($getClass as $class)
                                            <option value="{{ $class->id }}"
                                                {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Section filter — loaded via AJAX when class changes --}}
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-diagram-3 me-1" style="color:#7c3aed;"></i>Section
                                    </label>
                                    <select name="section_id" id="filter_section_id" class="form-select"
                                        {{ !request('class_id') ? 'disabled' : '' }}>
                                        <option value="">— All Sections —</option>
                                        {{-- Pre-populate if class was already selected --}}
                                        @if (request('class_id'))
                                            @foreach (\App\Models\ClassSectionModel::getSectionsByClass(request('class_id')) as $sec)
                                                <option value="{{ $sec->id }}"
                                                    {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                                    Section {{ $sec->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold small text-secondary">
                                        <i class="bi bi-calendar3-range me-1" style="color:#d97706;"></i>Session
                                    </label>
                                    <select name="session_id" class="form-select">
                                        @foreach ($getSessions as $session)
                                            <option value="{{ $session->id }}"
                                                {{ request('session_id', $getCurrentSession?->id) == $session->id ? 'selected' : '' }}>
                                                {{ $session->name }}
                                                @if ($session->is_current)
                                                    (Current)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-warning text-dark flex-fill fw-semibold">
                                        <i class="bi bi-search me-1"></i>Search
                                    </button>
                                    <a href="{{ url('admin/student/list') }}" class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Table ────────────────────────────────────────────────────────── --}}
        <div class="app-content">
            <div class="container-fluid">

                @include('message')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-table me-2 text-warning"></i>All Students
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
                                        <th style="min-width:220px;">Student</th>
                                        <th>Class / Section</th>
                                        <th>Parent</th>
                                        <th>Admission No.</th>
                                        <th>Admission Date</th>
                                        <th>Gender</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th class="text-center pe-4" width="100">Actions</th>
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
                                                        @if (!empty($value->profile_pic))
                                                            <img src="{{ asset('storage/' . $value->profile_pic) }}"
                                                                alt="{{ $value->name }}"
                                                                class="rounded-circle shadow-sm"
                                                                style="width:42px;height:42px;object-fit:cover;">
                                                        @else
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark fw-bold shadow-sm"
                                                                style="width:42px;height:42px;font-size:.9rem;">
                                                                {{ strtoupper(substr($value->name, 0, 1)) }}{{ strtoupper(substr($value->last_name ?? '', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark small">{{ $value->name }}
                                                            {{ $value->last_name ?? '' }}</div>
                                                        <div class="text-muted" style="font-size:.75rem;">
                                                            {{ $value->email }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Class + Section combined --}}
                                            <td>
                                                @if (!empty($value->class_name))
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 d-block mb-1"
                                                        style="width:fit-content;">
                                                        <i class="bi bi-building me-1"></i>{{ $value->class_name }}
                                                    </span>
                                                @endif
                                                @if (!empty($value->section_name))
                                                    <span class="badge px-2 py-1 fw-semibold"
                                                        style="background:#ede9fe;color:#7c3aed;font-size:.7rem;width:fit-content;">
                                                        <i class="bi bi-diagram-3 me-1"></i>Section
                                                        {{ $value->section_name }}
                                                    </span>
                                                @elseif(empty($value->class_name))
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>

                                            <td class="small">
                                                @php
                                                    // Get the first 2 parents assigned to this student
                                                    $assignedParents = $value->parents ?? collect(); // make sure it's a Collection
                                                    $firstTwoParents = $assignedParents->take(2);
                                                @endphp

                                                @if ($firstTwoParents->isNotEmpty())
                                                    @foreach ($firstTwoParents as $parent)
                                                        <div class="d-flex align-items-center gap-1">
                                                            <i class="bi bi-house-heart text-muted"></i>
                                                            <span>{{ $parent->name }}
                                                                {{ $parent->last_name ?? '' }}</span>
                                                        </div>
                                                    @endforeach
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

                                            <td class="text-center pe-4">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <a href="{{ url('admin/student/edit/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-primary px-2" title="Edit">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    <a href="{{ url('admin/student/delete/' . $value->id) }}"
                                                        class="btn btn-sm btn-outline-danger px-2" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this student?')">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
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
                            Showing {{ $getRecord->firstItem() }}–{{ $getRecord->lastItem() }} of
                            {{ $getRecord->total() }} students
                        </span>
                        {{ $getRecord->appends(request()->query())->links() }}
                    </div>
                </div>

            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
        function loadSections(class_id, selected_section = null) {
            const $sec = $('#filter_section_id');

            if (!class_id) {
                $sec.html('<option value="">— All Sections —</option>').prop('disabled', true);
                return;
            }

            $sec.html('<option value="">Loading…</option>').prop('disabled', true);

            $.get("{{ url('admin/section/get_sections') }}", {
                class_id
            }, function(sections) {

                let html = '<option value="">— All Sections —</option>';

                sections.forEach(s => {
                    const selected = (selected_section && selected_section == s.id) ? 'selected' : '';
                    html += `<option value="${s.id}" ${selected}>Section ${s.name}</option>`;
                });

                $sec.html(html).prop('disabled', sections.length === 0);

            }).fail(function() {
                $sec.html('<option value="">— Error loading sections —</option>')
                    .prop('disabled', false);
            });
        }

        // On class change
        $.('#filter_class_id').on('change', function() {
            loadSections($(this).val());
        });

        // 🔥 AUTO LOAD ON PAGE LOAD (VERY IMPORTANT)
        $(document).ready(function() {
            const class_id = $('#filter_class_id').val();
            const selected_section = "{{ request('section_id') }}";

            if (class_id) {
                loadSections(class_id, selected_section);
            }
        });
    </script>
@endsection
