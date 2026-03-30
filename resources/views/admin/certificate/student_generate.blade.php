@extends('layouts.app')

@section('style')
    <style>
        .ground-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .ground-card .gc-header {
            background: #f8faff;
            border-bottom: 2px solid #e74c3c;
            padding: 12px 18px;
            font-weight: 600;
            font-size: .9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ground-card .gc-body {
            padding: 20px;
        }

        .student-table thead th {
            background: #f8faff;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 14px;
            white-space: nowrap;
        }

        .student-table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: .85rem;
        }

        .student-table tbody tr:last-child td {
            border-bottom: none;
        }

        .student-table tbody tr:hover {
            background: #fafbff;
            cursor: pointer;
        }

        .student-table tbody tr.row-selected {
            background: #eff6ff;
        }

        .generate-bar {
            position: sticky;
            bottom: 0;
            background: #fff;
            border-top: 1px solid #e5e7eb;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            z-index: 10;
            border-radius: 0 0 12px 12px;
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
                            <i class="bi bi-mortarboard me-2 text-primary"></i>Student Certificate Generate
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/certificate/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ── SELECT GROUND ──────────────────────────────────────── --}}
                <div class="ground-card">
                    <div class="gc-header">
                        <i class="bi bi-funnel text-danger"></i> Select Ground
                    </div>
                    <div class="gc-body">
                        <form method="POST" action="{{ url('admin/certificate/student-generate') }}" id="filterForm">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Class <span
                                            class="text-danger">*</span></label>
                                    <select name="class_id" id="classSelect" class="form-select" required>
                                        <option value="">-- Select Class --</option>
                                        @foreach ($getClasses as $cl)
                                            <option value="{{ $cl->id }}"
                                                {{ isset($selectedClass) && $selectedClass == $cl->id ? 'selected' : '' }}>
                                                {{ $cl->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Section <span
                                            class="text-danger">*</span></label>
                                    <select name="section_id" id="sectionSelect" class="form-select">
                                        <option value="">All Sections</option>
                                        @foreach ($getSections as $sec)
                                            <option value="{{ $sec->id }}" data-class="{{ $sec->class_id }}"
                                                {{ isset($selectedSection) && $selectedSection == $sec->id ? 'selected' : '' }}>
                                                {{ $sec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Template <span
                                            class="text-danger">*</span></label>
                                    <select name="template_id" class="form-select" required>
                                        <option value="">-- Select Template --</option>
                                        @foreach ($getTemplates as $tmpl)
                                            <option value="{{ $tmpl->id }}"
                                                {{ isset($selectedTemplate) && $selectedTemplate == $tmpl->id ? 'selected' : '' }}>
                                                {{ $tmpl->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── STUDENT LIST ───────────────────────────────────────── --}}
                @if (isset($getStudents) && $getStudents->count() > 0)
                    <form method="POST" action="{{ url('admin/certificate/student-print') }}" target="_blank"
                        id="printForm">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $selectedTemplate }}">
                        <input type="hidden" name="print_date" value="{{ $printDate ?? now()->toDateString() }}">

                        <div class="ground-card">
                            <div class="gc-header d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-people-fill text-danger me-1"></i> Student List
                                    <span class="badge bg-primary ms-2">{{ $getStudents->count() }}</span>
                                </span>
                                <button type="submit" class="btn btn-sm btn-success" id="generateBtn">
                                    <i class="bi bi-printer me-1"></i> Generate
                                </button>
                            </div>
                            <div class="gc-body p-0">

                                {{-- Print Date --}}
                                <div class="px-4 pt-3 pb-2">
                                    <label class="form-label fw-semibold small">Print Date</label>
                                    <input type="date" name="print_date" class="form-control" style="max-width:220px;"
                                        value="{{ $printDate ?? now()->toDateString() }}">
                                </div>

                                <div class="table-responsive">
                                    <table class="table student-table mb-0">
                                        <thead>
                                            <tr>
                                                <th width="50" class="text-center">
                                                    <input type="checkbox" class="form-check-input" id="checkAll"
                                                        onchange="selectAll(this.checked)">
                                                </th>
                                                <th width="50">#</th>
                                                <th>Student Name</th>
                                                <th>Category</th>
                                                <th>Register No</th>
                                                <th>Roll</th>
                                                <th>Mobile No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getStudents as $i => $s)
                                                <tr id="srow-{{ $s->id }}"
                                                    onclick="toggleRow({{ $s->id }})">
                                                    <td class="text-center" onclick="event.stopPropagation()">
                                                        <input type="checkbox" name="student_ids[]"
                                                            value="{{ $s->id }}" id="scb-{{ $s->id }}"
                                                            class="form-check-input student-cb"
                                                            onclick="event.stopPropagation(); toggleUI({{ $s->id }}, this.checked)"
                                                            checked>
                                                    </td>
                                                    <td class="text-muted">{{ $i + 1 }}</td>
                                                    <td class="fw-semibold">{{ $s->name }} {{ $s->last_name }}</td>
                                                    <td class="text-muted">{{ $s->religion ?? 'General' }}</td>
                                                    <td class="text-muted">{{ $s->admission_number ?? '—' }}</td>
                                                    <td class="text-muted">{{ $s->roll_number ?? '—' }}</td>
                                                    <td class="text-muted">{{ $s->mobile_number ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="generate-bar">
                                    <span class="text-muted small">
                                        <strong id="selCount">{{ $getStudents->count() }}</strong> student(s) selected
                                    </span>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="bi bi-printer me-1"></i>
                                        <span id="genTxt">Generate Certificates</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @elseif(isset($getStudents) && $getStudents->count() === 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>No students found for the selected filters.
                    </div>
                @endif

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Section filter by class
        const classSelect = document.getElementById('classSelect');
        const sectionSelect = document.getElementById('sectionSelect');
        const allSections = [...sectionSelect.options].map(o => ({
            value: o.value,
            text: o.text,
            cls: o.dataset.class ?? ''
        }));

        classSelect.addEventListener('change', function() {
            const clsId = this.value;
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            allSections.forEach(s => {
                if (!s.value) return;
                if (clsId && s.cls !== clsId) return;
                const opt = document.createElement('option');
                opt.value = s.value;
                opt.text = s.text;
                opt.dataset.class = s.cls;
                sectionSelect.appendChild(opt);
            });
            sectionSelect.value = '';
        });

        // Init on load
        (function() {
            if (classSelect.value) classSelect.dispatchEvent(new Event('change'));
        })();

        // Student row toggle
        window.toggleRow = function(id) {
            const cb = document.getElementById('scb-' + id);
            cb.checked = !cb.checked;
            toggleUI(id, cb.checked);
        };
        window.toggleUI = function(id, checked) {
            document.getElementById('srow-' + id).classList.toggle('row-selected', checked);
            updateCount();
        };
        window.selectAll = function(checked) {
            document.querySelectorAll('.student-cb').forEach(cb => {
                cb.checked = checked;
                document.getElementById('srow-' + cb.value).classList.toggle('row-selected', checked);
            });
            updateCount();
        };

        function updateCount() {
            const n = document.querySelectorAll('.student-cb:checked').length;
            document.getElementById('selCount').textContent = n;
            const gt = document.getElementById('genTxt');
            if (gt) gt.textContent = `Generate ${n} Certificate${n !== 1 ? 's' : ''}`;
        }

        // Mark all selected rows on load
        document.querySelectorAll('.student-cb:checked').forEach(cb => {
            document.getElementById('srow-' + cb.value).classList.add('row-selected');
        });

        // Guard: don't submit with zero selected
        document.getElementById('printForm')?.addEventListener('submit', function(e) {
            if (!document.querySelectorAll('.student-cb:checked').length) {
                e.preventDefault();
                alert('Please select at least one student.');
            }
        });
    </script>
@endsection
