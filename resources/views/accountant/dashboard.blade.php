@extends('layouts.app')

@section('style')
<style>
.stat-card { border: none; border-radius: .75rem; overflow: hidden; transition: transform .2s; }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important; }
.stat-icon { width: 54px; height: 54px; font-size: 1.4rem; border-radius: .6rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
</style>
@endsection

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-calculator-fill me-2 text-primary"></i>Accountant Dashboard</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- Summary Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="stat-icon bg-success bg-opacity-15 text-success"><i class="bi bi-cash-coin"></i></div>
                            <div>
                                <div class="text-muted small">Total Collected</div>
                                <div class="fw-bold fs-5 text-success">Rs. {{ number_format($summary['total_collected'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="stat-icon bg-danger bg-opacity-15 text-danger"><i class="bi bi-hourglass-split"></i></div>
                            <div>
                                <div class="text-muted small">Total Pending</div>
                                <div class="fw-bold fs-5 text-danger">Rs. {{ number_format($summary['total_pending'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="stat-icon bg-warning bg-opacity-15 text-warning"><i class="bi bi-pie-chart-fill"></i></div>
                            <div>
                                <div class="text-muted small">Partial Payments</div>
                                <div class="fw-bold fs-5">{{ $summary['total_partial'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="stat-icon bg-danger bg-opacity-15 text-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            <div>
                                <div class="text-muted small">Overdue Fees</div>
                                <div class="fw-bold fs-5 text-danger">{{ $summary['overdue'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">

                {{-- Recent Fees --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <span class="fw-semibold small"><i class="bi bi-clock-history me-1 text-primary"></i>Recent Fee Records</span>
                            <a href="{{ url('accountant/fee/list') }}" class="btn btn-outline-primary btn-sm">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Fee</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentFees as $fee)
                                    <tr>
                                        <td class="small">{{ $fee->student->name ?? '-' }} {{ $fee->student->last_name ?? '' }}</td>
                                        <td class="small">{{ $fee->feeType->name ?? '-' }}</td>
                                        <td class="small">Rs. {{ number_format($fee->amount, 2) }}</td>
                                        <td>{!! $fee->status_badge !!}</td>
                                        <td>
                                            @if($fee->status != 'paid')
                                                <a href="{{ url('accountant/fee/collect/'.$fee->id) }}" class="btn btn-sm btn-success py-0 px-2" style="font-size:.72rem;"><i class="bi bi-cash"></i> Collect</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-3">No recent records.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Overdue Fees --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-transparent fw-semibold small">
                            <i class="bi bi-exclamation-circle-fill me-1 text-danger"></i>Overdue Fees
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr><th>Student</th><th>Due Date</th><th>Balance</th><th></th></tr>
                                </thead>
                                <tbody>
                                    @forelse($overdueFees as $fee)
                                    <tr>
                                        <td class="small">{{ $fee->student->name ?? '-' }} {{ $fee->student->last_name ?? '' }}</td>
                                        <td class="small text-danger fw-semibold">{{ $fee->due_date }}</td>
                                        <td class="small">Rs. {{ number_format($fee->amount - $fee->paid_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ url('accountant/fee/collect/'.$fee->id) }}" class="btn btn-sm btn-danger py-0 px-2" style="font-size:.72rem;"><i class="bi bi-cash"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">No overdue fees.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection