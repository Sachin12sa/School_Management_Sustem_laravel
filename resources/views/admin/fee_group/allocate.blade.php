@extends('layouts.app')

@section('style')
    <style>
        .student-row {
            transition: background .12s;
            cursor: pointer;
        }

        .student-row.picked {
            background: #e8f0fe;
        }

        .student-row td {
            vertical-align: middle;
        }

        #previewBox {
            display: none;
        }

        .fee-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #f0f6ff;
            border: 1px solid #c7d9f8;
            border-radius: .4rem;
            padding: .3rem .7rem;
            font-size: .8rem;
            margin: 2px;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-people-fill me-2 text-primary"></i>Fees Allocation
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/fee/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Fee List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ url('admin/fee_group/allocate') }}" method="POST" id="allocForm">
                    @csrf

                    {{-- ── SELECT GROUND ─────────────────────────────────────── --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-transparent border-bottom fw-semibold">
                            <i class="bi bi-funnel me-1 text-primary"></i> Select Ground
                        </div>
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        Class <span class="text-danger">*</span>
                                    </label>
                                    <select name="class_id" id="classSelect" class="form-select" required>
                                        <option value="">-- Select Class --</option>
                                        @foreach ($getClasses as $cl)
                                            <option value="{{ $cl->id }}"
                                                {{ old('class_id') == $cl->id ? 'selected' : '' }}>
                                                {{ $cl->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        Section <span class="text-danger">*</span>
                                    </label>
                                    <select name="section_id" id="sectionSelect" class="form-select">
                                        <option value="">All Sections</option>
                                        @foreach ($getSections as $sec)
                                            <option value="{{ $sec->id }}" data-class="{{ $sec->class_id }}"
                                                {{ old('section_id') == $sec->id ? 'selected' : '' }}>
                                                {{ $sec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Fee Group <span class="text-danger">*</span>
                                    </label>
                                    <select name="fee_group_id" id="feeGroupSelect" class="form-select" required>
                                        <option value="">-- Select Fee Group --</option>
                                        @foreach ($getFeeGroups as $fg)
                                            <option value="{{ $fg->id }}"
                                                {{ old('fee_group_id') == $fg->id ? 'selected' : '' }}>
                                                {{ $fg->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary w-100" id="filterBtn">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── FEE GROUP PREVIEW ────────────────────────────────── --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3" id="previewBox">
                        <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                            <i class="bi bi-eye me-1"></i> Fee Group Contents
                        </div>
                        <div class="card-body" id="previewContent">
                            <p class="text-muted small mb-0">Loading…</p>
                        </div>
                    </div>

                    {{-- ── STUDENT LIST ──────────────────────────────────────── --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3" id="studentBox" style="display:none;">
                        <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill text-primary"></i>
                            <span class="fw-semibold">Student List</span>
                            <span class="badge bg-primary ms-1" id="studentCount">0</span>
                            <div class="ms-auto d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="selectAllStudents(true)">
                                    <i class="bi bi-check2-all me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="selectAllStudents(false)">
                                    <i class="bi bi-x-lg me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50" class="text-center">
                                                <input type="checkbox" class="form-check-input" id="checkAllStudents"
                                                    onchange="selectAllStudents(this.checked)">
                                            </th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Reg. No</th>
                                            <th>Roll</th>
                                            <th>Gender</th>
                                            <th>Mobile</th>
                                            <th>Guardian</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentTableBody">
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                Filter by class to load students.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ── SAVE BAR ──────────────────────────────────────────── --}}
                    <div class="d-flex justify-content-end" id="saveBar" style="display:none!important;">
                        <button type="submit" class="btn btn-success px-4" id="saveBtn">
                            <i class="bi bi-save me-1"></i>
                            <span id="saveTxt">Allocate Fees</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Students JSON from server (all students with class/section info)
        const ALL_STUDENTS = @json($studentsJson);

        const el = id => document.getElementById(id);

        // ── SECTION FILTER (show only sections for selected class) ────────────
        el('classSelect').addEventListener('change', function() {
            const clsId = this.value;
            const sec = el('sectionSelect');
            [...sec.options].forEach(opt => {
                if (!opt.value) return;
                opt.hidden = clsId ? (opt.dataset.class !== clsId) : false;
            });
            sec.value = '';
        });

        // ── FEE GROUP PREVIEW ─────────────────────────────────────────────────
        el('feeGroupSelect').addEventListener('change', function() {
            const fgId = this.value;
            if (!fgId) {
                el('previewBox').style.display = 'none';
                return;
            }

            fetch(`/admin/fee_group/items/${fgId}`)
                .then(r => r.json())
                .then(items => {
                    if (!items.length) {
                        el('previewContent').innerHTML =
                            '<p class="text-muted small mb-0">This group has no fee types.</p>';
                    } else {
                        let html = '';
                        items.forEach(item => {
                            html += `<span class="fee-pill">
                            <i class="bi bi-tag-fill text-primary"></i>
                            <strong>${item.fee_type_name}</strong>
                            <span class="text-success fw-semibold">Rs.${parseFloat(item.amount).toFixed(2)}</span>
                            <span class="text-muted">Due: ${item.due_date}</span>
                        </span>`;
                        });
                        el('previewContent').innerHTML = html;
                    }
                    el('previewBox').style.display = '';
                })
                .catch(() => {
                    el('previewContent').innerHTML =
                        '<p class="text-danger small">Failed to load group items.</p>';
                    el('previewBox').style.display = '';
                });
        });

        // ── FILTER BUTTON ─────────────────────────────────────────────────────
        el('filterBtn').addEventListener('click', function() {
            const clsId = el('classSelect').value;
            const secId = el('sectionSelect').value;

            if (!clsId) {
                alert('Please select a class.');
                return;
            }
            if (!el('feeGroupSelect').value) {
                alert('Please select a fee group.');
                return;
            }

            const filtered = ALL_STUDENTS.filter(s => {
                const clsMatch = s.cls == clsId;
                const secMatch = !secId || s.sec == secId;
                return clsMatch && secMatch;
            });

            buildStudentTable(filtered);
            el('studentBox').style.display = '';
            el('saveBar').style.display = '';
        });

        function buildStudentTable(students) {
            el('studentCount').textContent = students.length;
            if (!students.length) {
                el('studentTableBody').innerHTML =
                    '<tr><td colspan="8" class="text-center text-muted py-4">No students found for this filter.</td></tr>';
                return;
            }

            let html = '';
            students.forEach((s, i) => {
                html += `<tr class="student-row" id="srow-${s.id}" onclick="toggleStudent(${s.id})">
                <td class="text-center">
                    <input type="checkbox" name="student_ids[]" value="${s.id}"
                           id="scb-${s.id}" class="form-check-input student-cb"
                           checked onclick="event.stopPropagation(); toggleStudentUI(${s.id}, this.checked)">
                </td>
                <td class="text-muted small">${i + 1}</td>
                <td><span class="fw-semibold">${s.name}</span></td>
                <td class="small">${s.num || '—'}</td>
                <td class="small">${s.roll || '—'}</td>
                <td class="small">${s.gender || '—'}</td>
                <td class="small">${s.mobile || '—'}</td>
                <td class="small">${s.guardian || '—'}</td>
            </tr>`;
            });
            el('studentTableBody').innerHTML = html;

            // All checked by default → highlight
            students.forEach(s => document.getElementById('srow-' + s.id).classList.add('picked'));
            updateSaveTxt();
        }

        window.toggleStudent = function(id) {
            const cb = el('scb-' + id);
            cb.checked = !cb.checked;
            toggleStudentUI(id, cb.checked);
        };

        window.toggleStudentUI = function(id, checked) {
            const row = el('srow-' + id);
            row.classList.toggle('picked', checked);
            updateSaveTxt();
        };

        window.selectAllStudents = function(checked) {
            document.querySelectorAll('.student-cb').forEach(cb => {
                cb.checked = checked;
                el('srow-' + cb.value).classList.toggle('picked', checked);
            });
            el('checkAllStudents').checked = checked;
            updateSaveTxt();
        };

        function updateSaveTxt() {
            const n = document.querySelectorAll('.student-cb:checked').length;
            el('saveTxt').textContent = n > 1 ?
                `Allocate Fees to ${n} Students` :
                (n === 1 ? 'Allocate Fee to 1 Student' : 'Allocate Fees');
        }

        // ── FORM GUARD ────────────────────────────────────────────────────────
        el('allocForm').addEventListener('submit', function(e) {
            if (!el('feeGroupSelect').value) {
                e.preventDefault();
                alert('Please select a Fee Group.');
                return;
            }
            const checked = document.querySelectorAll('.student-cb:checked').length;
            if (!checked) {
                e.preventDefault();
                alert('Please select at least one student.');
                return;
            }
        });
    </script>
@endsection
