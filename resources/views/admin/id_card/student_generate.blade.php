@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-people-fill me-2 text-success"></i>Generate Student ID Cards
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/id_card/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                {{-- Filter --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-transparent border-bottom py-2">
                        <span class="fw-semibold small text-muted text-uppercase" style="letter-spacing:.06em;">
                            <i class="bi bi-funnel me-1"></i>Select Ground
                        </span>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Class <span class="text-danger">*</span></label>
                                <select name="class_id" class="form-select" id="sel-class" required>
                                    <option value="">— Select Class —</option>
                                    @foreach ($getClass as $c)
                                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Section</label>
                                <select name="section_id" class="form-select" id="sel-section">
                                    <option value="all">All Sections</option>
                                    @foreach ($getSection as $sec)
                                        <option value="{{ $sec->id }}"
                                            {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                            Section {{ $sec->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Template <span
                                        class="text-danger">*</span></label>
                                <select name="template_id" class="form-select" required id="sel-template">
                                    <option value="">— Select Template —</option>
                                    @foreach ($templates as $tpl)
                                        <option value="{{ $tpl->id }}"
                                            {{ request('template_id') == $tpl->id ? 'selected' : '' }}>
                                            {{ $tpl->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($getStudents->isNotEmpty())
                    {{-- Student list --}}
                    <form action="{{ url('admin/id_card/print') }}" method="POST" target="_blank" id="generate-form">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ request('template_id') }}">

                        <div class="card border-0 shadow-sm rounded-3">
                            <div
                                class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-2">
                                <span class="fw-semibold small text-muted text-uppercase" style="letter-spacing:.06em;">
                                    <i class="bi bi-people me-1"></i>Student List
                                    <span class="badge bg-primary ms-1">{{ $getStudents->count() }}</span>
                                </span>
                                <button type="submit" class="btn btn-success btn-sm fw-semibold" id="gen-btn" disabled>
                                    <i class="bi bi-printer me-1"></i>Generate Selected
                                </button>
                            </div>
                            <div class="card-body p-3">
                                {{-- Dates --}}
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small">Print Date</label>
                                        <input type="date" name="print_date" class="form-control"
                                            value="{{ now()->toDateString() }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small">Expiry Date</label>
                                        <input type="date" name="expiry_date" class="form-control"
                                            value="{{ now()->addYear()->toDateString() }}">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:40px;">
                                                    <input type="checkbox" class="form-check-input" id="chk-all"
                                                        onchange="toggleAll(this.checked)">
                                                </th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">#</th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">
                                                    Student Name</th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">Adm.
                                                    No.</th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">Roll
                                                </th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">Class
                                                </th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">
                                                    Section</th>
                                                <th class="small text-muted text-uppercase" style="font-size:.68rem;">
                                                    Mobile</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getStudents as $i => $s)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="user_ids[]"
                                                            value="{{ $s->id }}"
                                                            class="form-check-input student-chk" onchange="updateBtn()">
                                                    </td>
                                                    <td class="small text-muted">{{ $i + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $s->getProfile() }}"
                                                                class="rounded-circle border"
                                                                style="width:32px;height:32px;object-fit:cover;">
                                                            <div>
                                                                <div class="fw-semibold small">{{ $s->name }}
                                                                    {{ $s->last_name }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="small">{{ $s->admission_number ?? '—' }}</td>
                                                    <td class="small">{{ $s->roll_number ?? '—' }}</td>
                                                    <td class="small">{{ $s->class_name }}</td>
                                                    <td class="small">
                                                        {{ $s->section_name ? 'Sec. ' . $s->section_name : '—' }}</td>
                                                    <td class="small">{{ $s->mobile_number ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                @elseif(request('class_id'))
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                            No students found for the selected filters.
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="bi bi-filter d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                            Select a class and template above, then click Filter.
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        function toggleAll(checked) {
            document.querySelectorAll('.student-chk').forEach(function(cb) {
                cb.checked = checked;
            });
            updateBtn();
        }

        function updateBtn() {
            var n = document.querySelectorAll('.student-chk:checked').length;
            var btn = document.getElementById('gen-btn');
            btn.disabled = (n === 0);
            btn.innerHTML = n > 0 ?
                '<i class="bi bi-printer me-1"></i>Generate ' + n + ' ID Card' + (n > 1 ? 's' : '') :
                '<i class="bi bi-printer me-1"></i>Generate Selected';
        }

        // Class change → reload sections
        document.getElementById('sel-class').addEventListener('change', function() {
            var url = new URL(window.location.href);
            url.searchParams.set('class_id', this.value);
            url.searchParams.delete('section_id');
            window.location.href = url.toString();
        });
    </script>
@endsection
