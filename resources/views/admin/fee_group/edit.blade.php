@extends('layouts.app')

@section('style')
    <style>
        .fee-row {
            transition: background .15s;
        }

        .fee-row.checked-row {
            background: #f0f6ff;
        }

        .fee-row td {
            vertical-align: middle;
        }

        .fee-row.disabled-row input[type=date],
        .fee-row.disabled-row input[type=number] {
            background: #f4f4f4;
            color: #aaa;
            pointer-events: none;
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
                            <i class="bi bi-collection me-2 text-primary"></i>Edit Fee Group
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/fee_group/list') }}" class="btn btn-outline-secondary btn-sm">
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

                {{-- Build a lookup of existing items keyed by fee_type_id --}}
                @php
                    $existingItems = $getRecord->itemsWithType->keyBy('fee_type_id');
                @endphp

                <form action="{{ url('admin/fee_group/update/' . $getRecord->id) }}" method="POST" id="fgForm">
                    @csrf

                    {{-- ── Group Info ───────────────────────────────────────── --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-transparent border-bottom small fw-semibold text-muted text-uppercase">
                            <i class="bi bi-info-circle me-1"></i> Group Info
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Group Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $getRecord->name) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ $getRecord->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $getRecord->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description', $getRecord->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Fee Types Table ──────────────────────────────────── --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-transparent border-bottom d-flex align-items-center gap-2">
                            <span class="small fw-semibold text-muted text-uppercase">
                                <i class="bi bi-list-check me-1"></i> Fee Types &amp; Details
                            </span>
                            <div class="ms-auto d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2"
                                    onclick="selectAll(true)" style="font-size:.75rem;">
                                    <i class="bi bi-check2-all me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2"
                                    onclick="selectAll(false)" style="font-size:.75rem;">
                                    <i class="bi bi-x-lg me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50" class="text-center">
                                                <input type="checkbox" id="checkAll" onchange="selectAll(this.checked)"
                                                    class="form-check-input">
                                            </th>
                                            <th>Fee Type</th>
                                            <th width="190">Due Date <span class="text-danger">*</span></th>
                                            <th width="190">Amount (Rs.) <span class="text-danger">*</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($getFeeTypes as $ft)
                                            @php
                                                $item = $existingItems->get($ft->id);
                                                $isChecked = $item !== null;
                                                $dueDate = old('due_dates.' . $ft->id, $item->due_date ?? '');
                                                $amount = old('amounts.' . $ft->id, $item->amount ?? $ft->amount);
                                            @endphp
                                            <tr class="fee-row {{ $isChecked ? 'checked-row' : 'disabled-row' }}"
                                                id="row-{{ $ft->id }}">
                                                <td class="text-center">
                                                    <input type="checkbox" name="fee_type_ids[]" value="{{ $ft->id }}"
                                                        id="cb-{{ $ft->id }}" class="form-check-input fee-cb"
                                                        {{ $isChecked ? 'checked' : '' }}
                                                        onchange="toggleRow({{ $ft->id }}, this.checked)">
                                                </td>
                                                <td>
                                                    <label for="cb-{{ $ft->id }}" class="mb-0 fw-semibold"
                                                        style="cursor:pointer;">
                                                        {{ $ft->name }}
                                                    </label>
                                                    <div class="text-muted small">{{ $ft->frequencyLabel }} — Default:
                                                        Rs.{{ number_format($ft->amount, 2) }}</div>
                                                </td>
                                                <td>
                                                    <input type="date" name="due_dates[{{ $ft->id }}]"
                                                        id="dd-{{ $ft->id }}" class="form-control form-control-sm"
                                                        value="{{ $dueDate }}" {{ $isChecked ? '' : 'disabled' }}>
                                                </td>
                                                <td>
                                                    <input type="number" name="amounts[{{ $ft->id }}]"
                                                        id="amt-{{ $ft->id }}" class="form-control form-control-sm"
                                                        step="0.01" min="0" value="{{ $amount }}"
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">No active fee types
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ── Summary + Save ──────────────────────────────────── --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body py-2 px-3 d-flex align-items-center gap-3 flex-wrap">
                            <span class="text-muted small">
                                <i class="bi bi-check2-square me-1 text-primary"></i>
                                <strong id="selCount">0</strong> fee type(s) selected
                            </span>
                            <span class="text-muted small">
                                Total: <strong class="text-success" id="totalAmt">Rs.0.00</strong>
                            </span>
                            <div class="ms-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Fee Group
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        function toggleRow(id, checked) {
            const row = document.getElementById('row-' + id);
            const dd = document.getElementById('dd-' + id);
            const amt = document.getElementById('amt-' + id);
            if (checked) {
                row.classList.add('checked-row');
                row.classList.remove('disabled-row');
                dd.disabled = false;
                amt.disabled = false;
            } else {
                row.classList.remove('checked-row');
                row.classList.add('disabled-row');
                dd.disabled = true;
                amt.disabled = true;
            }
            updateSummary();
        }

        function selectAll(checked) {
            document.querySelectorAll('.fee-cb').forEach(cb => {
                cb.checked = checked;
                toggleRow(cb.value, checked);
            });
            document.getElementById('checkAll').checked = checked;
        }

        function updateSummary() {
            let count = 0,
                total = 0;
            document.querySelectorAll('.fee-cb:checked').forEach(cb => {
                count++;
                total += parseFloat(document.getElementById('amt-' + cb.value).value) || 0;
            });
            document.getElementById('selCount').textContent = count;
            document.getElementById('totalAmt').textContent = 'Rs.' + total.toLocaleString('en-IN', {
                minimumFractionDigits: 2
            });
        }

        document.querySelectorAll('[id^="amt-"]').forEach(i => i.addEventListener('input', updateSummary));

        document.getElementById('fgForm').addEventListener('submit', function(e) {
            if (!document.querySelectorAll('.fee-cb:checked').length) {
                e.preventDefault();
                alert('Please select at least one Fee Type.');
                return;
            }
            // Re-enable disabled inputs so they are submitted
            document.querySelectorAll('.fee-row.checked-row input').forEach(i => i.disabled = false);
        });

        // Init summary on load
        updateSummary();
    </script>
@endsection
