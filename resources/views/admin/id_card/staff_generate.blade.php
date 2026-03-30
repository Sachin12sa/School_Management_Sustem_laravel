@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-person-workspace me-2 text-info"></i>Generate Staff ID Cards
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

                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-transparent border-bottom py-2">
                        <span class="fw-semibold small text-muted text-uppercase" style="letter-spacing:.06em;">
                            <i class="bi bi-funnel me-1"></i>Select Ground
                        </span>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="">— Select Role —</option>
                                    @foreach (['teacher' => 'Teacher', 'admin' => 'Admin', 'accountant' => 'Accountant', 'librarian' => 'Librarian'] as $val => $lbl)
                                        <option value="{{ $val }}" {{ request('role') == $val ? 'selected' : '' }}>
                                            {{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Template <span
                                        class="text-danger">*</span></label>
                                <select name="template_id" class="form-select" required>
                                    <option value="">— Select Template —</option>
                                    @foreach ($templates as $tpl)
                                        <option value="{{ $tpl->id }}"
                                            {{ request('template_id') == $tpl->id ? 'selected' : '' }}>
                                            {{ $tpl->name }} ({{ ucfirst($tpl->applicable_user) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($staff->isNotEmpty())
                    <form action="{{ url('admin/id_card/print') }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ request('template_id') }}">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div
                                class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-2">
                                <span class="fw-semibold small text-muted text-uppercase" style="letter-spacing:.06em;">
                                    <i class="bi bi-people me-1"></i>Staff List
                                    <span class="badge bg-info ms-1">{{ $staff->count() }}</span>
                                </span>
                                <button type="submit" class="btn btn-success btn-sm fw-semibold" id="gen-btn" disabled>
                                    <i class="bi bi-printer me-1"></i>Generate Selected
                                </button>
                            </div>
                            <div class="card-body p-3">
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
                                                    <input type="checkbox" class="form-check-input"
                                                        onchange="toggleAll(this.checked)">
                                                </th>
                                                <th class="small text-muted" style="font-size:.68rem;">#</th>
                                                <th class="small text-muted" style="font-size:.68rem;">Name</th>
                                                <th class="small text-muted" style="font-size:.68rem;">Email</th>
                                                <th class="small text-muted" style="font-size:.68rem;">Mobile</th>
                                                <th class="small text-muted" style="font-size:.68rem;">Gender</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($staff as $i => $s)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="user_ids[]"
                                                            value="{{ $s->id }}" class="form-check-input staff-chk"
                                                            onchange="updateBtn()">
                                                    </td>
                                                    <td class="small text-muted">{{ $i + 1 }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $s->getProfile() }}" class="rounded-circle border"
                                                                style="width:32px;height:32px;object-fit:cover;">
                                                            <div class="fw-semibold small">{{ $s->name }}
                                                                {{ $s->last_name }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="small text-muted">{{ $s->email }}</td>
                                                    <td class="small">{{ $s->mobile_number ?? '—' }}</td>
                                                    <td class="small">{{ $s->gender ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                @elseif(request('role'))
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="bi bi-inbox d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                            No staff found for the selected role.
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5 text-muted">
                            <i class="bi bi-filter d-block mb-2" style="font-size:2rem;opacity:.3;"></i>
                            Select a role and template, then click Filter.
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
            document.querySelectorAll('.staff-chk').forEach(function(cb) {
                cb.checked = checked;
            });
            updateBtn();
        }

        function updateBtn() {
            var n = document.querySelectorAll('.staff-chk:checked').length;
            var btn = document.getElementById('gen-btn');
            if (btn) {
                btn.disabled = (n === 0);
                btn.innerHTML = n > 0 ?
                    '<i class="bi bi-printer me-1"></i>Generate ' + n + ' ID Card' + (n > 1 ? 's' : '') :
                    '<i class="bi bi-printer me-1"></i>Generate Selected';
            }
        }
    </script>
@endsection
