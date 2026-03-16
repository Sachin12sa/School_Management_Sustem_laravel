@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-cash-coin me-2 text-danger"></i>Library Fines
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button
                            type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif

                {{-- Summary Cards --}}
                <div class="row g-3 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(220,53,69,.12);color:#dc3545;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Unpaid Fines</div>
                                    <div class="fw-bold fs-5 text-danger">Rs.
                                        {{ number_format($fineSummary['unpaid_total'], 2) }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $fineSummary['unpaid_count'] }}
                                        records</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(255,193,7,.12);color:#ffc107;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-arrow-up-circle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Accruing (Overdue)</div>
                                    <div class="fw-bold fs-5 text-warning">Rs.
                                        {{ number_format($fineSummary['accruing_total'], 2) }}</div>
                                    <div class="text-muted" style="font-size:.7rem;">{{ $fineSummary['accruing_count'] }}
                                        overdue books</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(25,135,84,.12);color:#198754;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Total Collected</div>
                                    <div class="fw-bold fs-5 text-success">Rs.
                                        {{ number_format($fineSummary['paid_total'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body d-flex align-items-center gap-3 p-3">
                                <div
                                    style="width:44px;height:44px;border-radius:.5rem;background:rgba(108,117,125,.12);color:#6c757d;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">
                                    <i class="bi bi-slash-circle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Total Waived</div>
                                    <div class="fw-bold fs-5 text-secondary">Rs.
                                        {{ number_format($fineSummary['waived_total'], 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body py-2 px-3">
                        <form method="GET" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <input type="text" name="member_name" class="form-control form-control-sm"
                                    placeholder="Search member..." value="{{ request('member_name') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="fine_status" class="form-select form-select-sm">
                                    <option value="">All Fine Status</option>
                                    <option value="unpaid" {{ request('fine_status') === 'unpaid' ? 'selected' : '' }}>
                                        Unpaid
                                    </option>
                                    <option value="paid" {{ request('fine_status') === 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="waived" {{ request('fine_status') === 'waived' ? 'selected' : '' }}>
                                        Waived
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="member_type" class="form-select form-select-sm">
                                    <option value="">All Members</option>
                                    <option value="2" {{ request('member_type') === '2' ? 'selected' : '' }}>Teachers
                                    </option>
                                    <option value="3" {{ request('member_type') === '3' ? 'selected' : '' }}>Students
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary"><i
                                        class="bi bi-funnel me-1"></i>Filter</button>
                                <a href="{{ url('librarian/library/fine/list') }}"
                                    class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Member</th>
                                        <th>Book</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Book Status</th>
                                        <th>Fine Amount</th>
                                        <th>Fine Status</th>
                                        <th>Paid On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $i => $row)
                                        @php
                                            // For overdue books, show live accruing fine
                                            $liveFine =
                                                $row->status === 'overdue' && $row->fine_per_day > 0
                                                    ? \Carbon\Carbon::parse($row->due_date)->diffInDays(now()) *
                                                        $row->fine_per_day
                                                    : $row->fine_amount;
                                        @endphp
                                        <tr class="{{ $row->fine_status === 'unpaid' ? 'table-warning' : '' }}">
                                            <td class="small text-muted">{{ $getRecord->firstItem() + $i }}</td>
                                            <td>
                                                <div class="fw-semibold small">{{ $row->member_name }}
                                                    {{ $row->member_last_name }}</div>
                                                <span class="badge bg-primary bg-opacity-15" style="font-size:.65rem;">
                                                    {{ $row->member_type == 2 ? 'Teacher' : 'Student' }}
                                                </span>
                                            </td>
                                            <td class="small fw-semibold">{{ $row->book_title }}</td>
                                            <td class="small text-danger fw-semibold">
                                                {{ \Carbon\Carbon::parse($row->due_date)->format('d M Y') }}
                                            </td>
                                            <td class="small">
                                                {{ $row->return_date ? \Carbon\Carbon::parse($row->return_date)->format('d M Y') : '—' }}
                                            </td>
                                            <td>{!! $row->status_badge !!}</td>
                                            <td>
                                                <span
                                                    class="fw-bold {{ $row->fine_status === 'unpaid' ? 'text-danger' : 'text-muted' }}">
                                                    Rs. {{ number_format($liveFine, 2) }}
                                                </span>
                                                @if ($row->status === 'overdue')
                                                    <div class="text-muted" style="font-size:.65rem;">
                                                        Rs.{{ $row->fine_per_day }}/day — accruing
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{!! $row->fine_status_badge !!}</td>
                                            <td class="small">
                                                {{ $row->fine_paid_at ? \Carbon\Carbon::parse($row->fine_paid_at)->format('d M Y') : '—' }}
                                            </td>
                                            <td>
                                                @if (in_array($row->fine_status ?? 'none', ['unpaid', 'none']) && $liveFine > 0)
                                                    <a href="{{ url('librarian/library/fine/collect/' . $row->id) }}"
                                                        class="btn btn-sm btn-success mb-1" title="Collect Payment">
                                                        <i class="bi bi-cash me-1"></i>Collect
                                                    </a>
                                                    <form action="{{ url('librarian/library/fine/waive/' . $row->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Waive this fine?')">
                                                        @csrf
                                                        <input type="hidden" name="reason" value="Waived by librarian">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                            title="Waive Fine">
                                                            <i class="bi bi-slash-circle me-1"></i>Waive
                                                        </button>
                                                    </form>
                                                @elseif($row->fine_status === 'paid')
                                                    <span class="text-success small">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Via {{ ucfirst($row->fine_payment_method ?? '') }}
                                                    </span>
                                                @elseif($row->fine_status === 'waived')
                                                    <span class="text-secondary small">
                                                        <i class="bi bi-slash-circle me-1"></i>Waived
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-5">
                                                <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                                                No outstanding fines.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($getRecord->hasPages())
                            <div class="px-3 py-2">{{ $getRecord->withQueryString()->links() }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
