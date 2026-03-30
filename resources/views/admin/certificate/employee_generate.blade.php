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

        .emp-table thead th {
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

        .emp-table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: .85rem;
        }

        .emp-table tbody tr:last-child td {
            border-bottom: none;
        }

        .emp-table tbody tr:hover {
            background: #fafbff;
            cursor: pointer;
        }

        .emp-table tbody tr.row-selected {
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
                            <i class="bi bi-person-badge me-2 text-primary"></i>Employee Certificate Generate
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

                {{-- ── SELECT GROUND ──────────────────────────────────────── --}}
                <div class="ground-card">
                    <div class="gc-header">
                        <i class="bi bi-funnel text-danger"></i> Select Ground
                    </div>
                    <div class="gc-body">
                        <form method="POST" action="{{ url('admin/certificate/employee-generate') }}" id="filterForm">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Role <span
                                            class="text-danger">*</span></label>
                                    <select name="role" class="form-select" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach ($getRoles as $userType => $label)
                                            <option value="{{ $userType }}"
                                                {{ isset($selectedRole) && $selectedRole == $userType ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
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
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── EMPLOYEE LIST ──────────────────────────────────────── --}}
                @if (isset($getEmployees) && $getEmployees->count() > 0)
                    <form method="POST" action="{{ url('admin/certificate/employee-print') }}" target="_blank"
                        id="printForm">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $selectedTemplate }}">

                        <div class="ground-card">
                            <div class="gc-header d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-people-fill text-danger me-1"></i> Employee List
                                    <span class="badge bg-primary ms-2">{{ $getEmployees->count() }}</span>
                                </span>
                                <button type="submit" class="btn btn-sm btn-success">
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
                                    <table class="table emp-table mb-0">
                                        <thead>
                                            <tr>
                                                <th width="50" class="text-center">
                                                    <input type="checkbox" class="form-check-input" id="checkAll"
                                                        onchange="selectAll(this.checked)">
                                                </th>
                                                <th width="50">#</th>
                                                <th>Name</th>
                                                <th>Staff Id</th>
                                                {{-- <th>Department</th>
                                                <th>Designation</th> --}}
                                                <th>Mobile No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getEmployees as $i => $emp)
                                                <tr id="erow-{{ $emp->id }}"
                                                    onclick="toggleRow({{ $emp->id }})">
                                                    <td class="text-center" onclick="event.stopPropagation()">
                                                        <input type="checkbox" name="employee_ids[]"
                                                            value="{{ $emp->id }}" id="ecb-{{ $emp->id }}"
                                                            class="form-check-input emp-cb"
                                                            onclick="event.stopPropagation(); toggleUI({{ $emp->id }}, this.checked)"
                                                            checked>
                                                    </td>
                                                    <td class="text-muted">{{ $i + 1 }}</td>
                                                    <td class="fw-semibold">{{ $emp->name }} {{ $emp->last_name }}</td>
                                                    <td class="text-muted small">
                                                        {{ $getRoles[$emp->user_type] ?? '—' }}
                                                    </td>
                                                    {{-- <td class="text-muted">{{ $emp->department ?? '—' }}</td>
                                                    <td class="text-muted">{{ $emp->designation ?? '—' }}</td> --}}
                                                    <td class="text-muted">{{ $emp->mobile_number ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="generate-bar">
                                    <span class="text-muted small">
                                        <strong id="selCount">{{ $getEmployees->count() }}</strong> employee(s) selected
                                    </span>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="bi bi-printer me-1"></i>
                                        <span id="genTxt">Generate Certificates</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @elseif(isset($getEmployees) && $getEmployees->count() === 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>No employees found for the selected role.
                    </div>
                @endif

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Toggle row click
        window.toggleRow = function(id) {
            const cb = document.getElementById('ecb-' + id);
            cb.checked = !cb.checked;
            toggleUI(id, cb.checked);
        };

        // Toggle UI (row highlight + count)
        window.toggleUI = function(id, checked) {
            const row = document.getElementById('erow-' + id);
            row.classList.toggle('row-selected', checked);
            updateCount();
        };

        // Select all
        window.selectAll = function(checked) {
            document.querySelectorAll('.emp-cb').forEach(cb => {
                cb.checked = checked;
                document.getElementById('erow-' + cb.value)
                    .classList.toggle('row-selected', checked);
            });
            updateCount();
        };

        // Update selection count + sync "select all"
        function updateCount() {
            const all = document.querySelectorAll('.emp-cb');
            const checked = document.querySelectorAll('.emp-cb:checked');

            const n = checked.length;
            document.getElementById('selCount').textContent = n;

            // 🔥 Sync select-all checkbox
            const checkAll = document.getElementById('checkAll');
            if (checkAll) {
                checkAll.checked = (all.length === checked.length);
            }

            // Update button text
            const gt = document.getElementById('genTxt');
            if (gt) {
                gt.textContent = `Generate ${n} Certificate${n !== 1 ? 's' : ''}`;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {

            // Highlight already checked rows
            document.querySelectorAll('.emp-cb').forEach(cb => {
                toggleUI(cb.value, cb.checked);
            });

            // Prevent submit if none selected
            document.getElementById('printForm')?.addEventListener('submit', function(e) {
                if (!document.querySelectorAll('.emp-cb:checked').length) {
                    e.preventDefault();
                    alert('Please select at least one employee.');
                }
            });

            updateCount();
        });
    </script>
@endsection
