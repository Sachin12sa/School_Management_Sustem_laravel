@extends('layouts.app')

@section('style')
    <style>
        .mode-btn {
            cursor: pointer;
            border: 2px solid #dee2e6;
            border-radius: .5rem;
            padding: .6rem 1.2rem;
            background: #f8f9fa;
            transition: all .15s;
            user-select: none;
            font-size: .9rem;
        }

        .mode-btn.active {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
            font-weight: 600;
        }

        .mode-btn:hover:not(.active) {
            background: #e9ecef;
        }

        .student-card {
            border: 1.5px solid #dee2e6;
            border-radius: .4rem;
            padding: .5rem .8rem;
            cursor: pointer;
            transition: all .12s;
            background: #fff;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 6px;
        }

        .student-card:hover {
            border-color: #0d6efd;
            background: #f0f6ff;
        }

        .student-card.picked {
            border-color: #0d6efd;
            background: #e8f0fe;
        }

        .student-card input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: #0d6efd;
            flex-shrink: 0;
            pointer-events: none;
        }

        .summary-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            padding: .85rem 1rem;
        }

        .disc-badge {
            font-size: .72rem;
            padding: .2em .6em;
            border-radius: .3rem;
            background: #fff3cd;
            color: #856404;
            font-weight: 600;
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
                            <i class="bi bi-cash-coin me-2 text-primary"></i>Assign Fee
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/fee/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ url('admin/fee/add') }}" method="POST" id="feeForm">
                    @csrf
                    <input type="hidden" name="assign_mode" id="assignMode" value="single">

                    <div class="row g-3">

                        {{-- ════ LEFT ════════════════════════════════════════════════ --}}
                        <div class="col-lg-7">

                            {{-- STEP 1: Mode --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-toggles me-1"></i> Step 1 — Who to assign to?
                                </div>
                                <div class="card-body">
                                    <div class="d-flex gap-2 flex-wrap">
                                        <div class="mode-btn active" id="btn-single" onclick="setMode('single')">
                                            <i class="bi bi-person me-1"></i> Single Student
                                        </div>
                                        <div class="mode-btn" id="btn-class" onclick="setMode('class')">
                                            <i class="bi bi-people me-1"></i> Whole Class
                                        </div>
                                        {{-- <div class="mode-btn" id="btn-all" onclick="setMode('all')">
                                        <i class="bi bi-globe me-1"></i> All Students
                                    </div> --}}
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 2: Class picker (hidden in 'all' mode) --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3" id="sec-class">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-building me-1"></i> Step 2 — Filter by Class
                                </div>
                                <div class="card-body">
                                    <select id="classSelect" name="class_id" class="form-select">
                                        <option value="">-- All classes (no filter) --</option>
                                        @foreach ($getClasses as $cl)
                                            <option value="{{ $cl->id }}">{{ $cl->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Pick a class to filter the student list below.</div>
                                </div>
                            </div>

                            {{-- STEP 3: Student selection --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-people-fill me-1"></i> Step 3 — Select Student(s)</span>
                                    <span class="badge bg-primary" id="selBadge">0 selected</span>
                                </div>
                                <div class="card-body">

                                    {{-- SINGLE mode --}}
                                    <div id="sec-single">
                                        <select name="student_ids[]" id="singlePick" class="form-select">
                                            <option value="">-- Select a student --</option>
                                            @foreach ($getStudents as $s)
                                                <option value="{{ $s->id }}" data-cls="{{ $s->class_id }}">
                                                    {{ $s->name }} {{ $s->last_name }}
                                                    @if ($s->admission_number)
                                                        ({{ $s->admission_number }})
                                                    @endif
                                                    ({{ $s->class_name }})

                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- CLASS mode --}}
                                    <div id="sec-multi" style="display:none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small text-muted" id="multiLabel">Select students</span>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="checkAll()">All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="uncheckAll()">None</button>
                                            </div>
                                        </div>
                                        <div id="checkList" style="max-height:300px;overflow-y:auto;">
                                            <p class="text-muted small text-center py-3">
                                                <i class="bi bi-arrow-up me-1"></i>Select a class above first
                                            </p>
                                        </div>
                                    </div>

                                    {{-- ALL mode --}}
                                    <div id="sec-all" style="display:none;">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Fee will be assigned to <strong>all {{ count($getStudents) }} active
                                                students</strong>.
                                            Great for new academic year bulk assignments.
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- ════ RIGHT ═══════════════════════════════════════════════ --}}
                        <div class="col-lg-5">

                            {{-- Fee Type & Amount --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-tags-fill me-1"></i> Fee Details
                                </div>
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Fee Type <span
                                                class="text-danger">*</span></label>
                                        <select name="fee_type_id" id="feeTypePick" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($getFeeTypes as $ft)
                                                <option value="{{ $ft->id }}" data-max="{{ $ft->amount }}"
                                                    {{ old('fee_type_id') == $ft->id ? 'selected' : '' }}>
                                                    {{ $ft->name }} — Rs.{{ number_format($ft->amount, 2) }} /
                                                    {{ $ft->frequency_label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Max info box --}}
                                    <div id="maxBox" style="display:none;" class="summary-box mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="font-size:.78rem;color:#6c757d;">Standard / max amount</span>
                                            <span style="font-size:1.3rem;font-weight:700;color:#0d6efd;"
                                                id="maxDisplay">—</span>
                                        </div>
                                        <div class="form-text">You can assign less for lower grades. Cannot exceed this.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Amount (Rs.) <span class="text-danger">*</span>
                                            <span class="badge bg-secondary ms-1" style="font-size:.63rem;">
                                                max: <span id="maxLabel">—</span>
                                            </span>
                                        </label>
                                        <input type="number" name="amount" id="amountIn" class="form-control"
                                            step="0.01" min="0" value="{{ old('amount') }}" required
                                            placeholder="0.00">
                                        <div id="amountErr" class="text-danger small" style="display:none;">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Cannot exceed Rs.<span id="amountErrVal"></span>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-semibold">Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="due_date" class="form-control"
                                            value="{{ old('due_date') }}" required>
                                    </div>

                                </div>
                            </div>

                            {{-- Discount --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-percent me-1"></i> Discount (Optional)
                                </div>
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Discount Type</label>
                                        <select name="discount_type" id="discType" class="form-select">
                                            <option value="none">No Discount</option>
                                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>
                                                Percentage (%)</option>
                                            <option value="flat" {{ old('discount_type') == 'flat' ? 'selected' : '' }}>
                                                Flat Amount (Rs.)</option>
                                        </select>
                                    </div>

                                    <div id="discValWrap" style="display:none;" class="mb-3">
                                        <label class="form-label fw-semibold" id="discValLabel">Discount Value</label>
                                        <input type="number" name="discount_value" id="discVal" class="form-control"
                                            step="0.01" min="0" value="{{ old('discount_value', 0) }}"
                                            placeholder="0">
                                    </div>

                                    <div id="discReasonWrap" style="display:none;" class="mb-3">
                                        <label class="form-label fw-semibold">Reason</label>
                                        <input type="text" name="discount_reason" class="form-control"
                                            value="{{ old('discount_reason') }}"
                                            placeholder="e.g. Scholarship, Merit, Sibling">
                                    </div>

                                    {{-- Live preview --}}
                                    <div id="discPreview" style="display:none;" class="summary-box">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span style="font-size:.78rem;color:#6c757d;">Original</span>
                                            <span style="text-decoration:line-through;color:#adb5bd;font-size:.85rem;"
                                                id="prevOrig">—</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span style="font-size:.78rem;color:#6c757d;">Discount</span>
                                            <span class="disc-badge" id="prevDisc">—</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span style="font-size:.78rem;font-weight:600;color:#212529;">Final
                                                Amount</span>
                                            <span style="font-size:1.05rem;font-weight:700;color:#198754;"
                                                id="prevFinal">—</span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="final_amount" id="finalAmt">

                                </div>
                            </div>

                            {{-- Remarks --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div
                                    class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                                    <i class="bi bi-chat-left-text me-1"></i> Remarks
                                </div>
                                <div class="card-body">
                                    <textarea name="remarks" class="form-control" rows="2" placeholder="Optional notes">{{ old('remarks') }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2" id="submitBtn">
                                <i class="bi bi-check-circle me-1"></i>
                                <span id="submitTxt">Assign Fee</span>
                            </button>

                        </div>
                    </div>{{-- /row --}}
                </form>

            </div>
        </div>
    </main>

    <script>
        // 1. Load data safely
        const STUDENTS = @json($studentsJson);
        console.log("Students Loaded:", STUDENTS); // DEBUG: Check your console (F12) to see if this prints

        // 2. Global Variables
        let currentMode = 'single';
        let maxAmount = 0;

        // 3. Helper to get elements
        const el = (id) => document.getElementById(id);

        // ── MODE SWITCHING ────────────────────────────────────────────
        window.setMode = function(m) {
            console.log("Switching to mode:", m);
            currentMode = m;
            el('assignMode').value = m;

            // update button styles
            ['single', 'class', 'all'].forEach(k => {
                const btn = el('btn-' + k);
                if (btn) btn.classList.toggle('active', k === m);
            });

            // show/hide sections
            el('sec-single').style.display = (m === 'single') ? '' : 'none';
            el('sec-multi').style.display = (m === 'class') ? '' : 'none';
            el('sec-all').style.display = (m === 'all') ? '' : 'none';
            el('sec-class').style.display = (m === 'all') ? 'none' : '';

            refreshCount();
        };

        // ── CLASS FILTER & LIST BUILDING ──────────────────────────────
        el('classSelect').addEventListener('change', function() {
            const clsId = this.value; // Get value from dropdown
            console.log("Class Selected ID:", clsId);

            // Filter single-student dropdown
            const opts = el('singlePick').options;
            for (let i = 0; i < opts.length; i++) {
                if (!opts[i].value) continue;
                // Use == for flexible comparison (string vs int)
                opts[i].hidden = clsId ? (opts[i].dataset.cls != clsId) : false;
            }
            el('singlePick').value = '';

            buildCheckList(clsId);
            refreshCount();
        });

        function buildCheckList(clsId) {
            const list = el('checkList');

            // Filter logic: compare as strings to be safe
            const filtered = clsId ?
                STUDENTS.filter(s => String(s.cls) === String(clsId)) :
                STUDENTS;

            console.log("Filtered Students Count:", filtered.length);

            if (filtered.length === 0) {
                list.innerHTML = '<p class="text-muted small text-center py-3">No students found in this class.</p>';
                el('multiLabel').textContent = '0 students';
                return;
            }

            el('multiLabel').textContent = filtered.length + (filtered.length > 1 ? ' students' : ' student') + ' in class';

            // Build the HTML
            let html = '';
            filtered.forEach(s => {
                html += `
            <label class="student-card w-100" id="sc-${s.id}" for="cb-${s.id}">
                <input type="checkbox" name="student_ids[]" value="${s.id}" id="cb-${s.id}"
                       onchange="el('sc-${s.id}').classList.toggle('picked', this.checked); window.refreshCount();">
                <div>
                    <div class="small fw-semibold">${s.name}</div>
                    <div class="text-muted" style="font-size:.7rem;">${s.num || 'No Admission #'}</div>
                </div>
            </label>`;
            });
            list.innerHTML = html;
        }

        // ── SELECTION LOGIC ───────────────────────────────────────────
        window.toggleCard = function(id) {
            const cb = el('cb-' + id);
            const card = el('sc-' + id);
            if (cb && card) {
                cb.checked = !cb.checked;
                card.classList.toggle('picked', cb.checked);
                refreshCount();
            }
        };

        window.checkAll = function() {
            el('checkList').querySelectorAll('input[type=checkbox]').forEach(cb => {
                cb.checked = true;
                el('sc-' + cb.value).classList.add('picked');
            });
            refreshCount();
        };

        window.uncheckAll = function() {
            el('checkList').querySelectorAll('input[type=checkbox]').forEach(cb => {
                cb.checked = false;
                el('sc-' + cb.value).classList.remove('picked');
            });
            refreshCount();
        };

        // ── COUNTING & CALCULATIONS ───────────────────────────────────
        window.refreshCount = function() {
            let n = 0;
            if (currentMode === 'single') {
                n = el('singlePick').value ? 1 : 0;
            } else if (currentMode === 'class') {
                n = el('checkList').querySelectorAll('input:checked').length;
            } else {
                n = STUDENTS.length;
            }
            el('selBadge').textContent = n + ' selected';
            el('submitTxt').textContent = n > 1 ? `Assign Fee to ${n} Students` : 'Assign Fee';
        };

        // ── FEE TYPE CHANGE ───────────────────────────────────────────
        el('feeTypePick').addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            maxAmount = parseFloat(opt.dataset.max) || 0;

            if (maxAmount > 0) {
                const fmt = 'Rs.' + maxAmount.toLocaleString('en-IN', {
                    minimumFractionDigits: 2
                });
                el('maxDisplay').textContent = fmt;
                el('maxLabel').textContent = fmt;
                el('amountIn').value = maxAmount;
                el('amountIn').max = maxAmount;
                el('maxBox').style.display = '';
            } else {
                el('maxBox').style.display = 'none';
            }
        });

        // ── AMOUNT VALIDATION ─────────────────────────────────────────
        el('amountIn').addEventListener('input', function() {
            const val = parseFloat(this.value) || 0;
            const bad = maxAmount > 0 && val > maxAmount;
            this.classList.toggle('is-invalid', bad);
            el('amountErr').style.display = bad ? '' : 'none';
            if (bad) el('amountErrVal').textContent = maxAmount.toFixed(2);
            el('submitBtn').disabled = bad;
            calcDiscount();
        });

        // ── DISCOUNT ──────────────────────────────────────────────────
        el('discType').addEventListener('change', function() {
            const has = this.value !== 'none';
            el('discValWrap').style.display = has ? '' : 'none';
            el('discReasonWrap').style.display = has ? '' : 'none';
            el('discValLabel').textContent = this.value === 'percent' ? 'Discount (%)' : 'Discount Amount (Rs.)';
            calcDiscount();
        });

        el('discVal').addEventListener('input', calcDiscount);

        function calcDiscount() {
            const base = parseFloat(el('amountIn').value) || 0;
            const dtype = el('discType').value;
            const dval = parseFloat(el('discVal').value) || 0;
            if (dtype === 'none' || dval <= 0 || base <= 0) {
                el('discPreview').style.display = 'none';
                el('finalAmt').value = base;
                return;
            }
            const disc = dtype === 'percent' ? Math.min((dval / 100) * base, base) : Math.min(dval, base);
            const final = base - disc;
            el('prevOrig').textContent = 'Rs.' + base.toFixed(2);
            el('prevDisc').textContent = dtype === 'percent' ? '-' + dval + '% (Rs.' + disc.toFixed(2) + ')' : '-Rs.' + disc
                .toFixed(2);
            el('prevFinal').textContent = 'Rs.' + final.toFixed(2);
            el('finalAmt').value = final.toFixed(2);
            el('discPreview').style.display = '';
        }

        // ── FORM SUBMIT GUARD ─────────────────────────────────────────
        el('feeForm').addEventListener('submit', function(e) {
            if (!el('finalAmt').value) el('finalAmt').value = parseFloat(el('amountIn').value) || 0;
            if (currentMode === 'single' && !el('singlePick').value) {
                e.preventDefault();
                alert('Please select a student.');
                return;
            }
            if (currentMode === 'class' && el('checkList').querySelectorAll('input:checked').length === 0) {
                e.preventDefault();
                alert('Please select at least one student.');
            }
        });

        el('singlePick').addEventListener('change', window.refreshCount);

        // ── INIT — runs immediately, no DOMContentLoaded needed ───────
        window.setMode('single');
    </script>
@endsection
