@extends('layouts.app')

@section('style')
<style>
.period-btn {
    border: 1.5px solid #dee2e6; border-radius: .4rem; padding: .35rem .9rem;
    background: #f8f9fa; font-size: .82rem; cursor: pointer; transition: all .15s;
    text-decoration: none; color: #495057; display: inline-block;
}
.period-btn:hover  { background: #e9ecef; color: #212529; }
.period-btn.active { background: #0d6efd; border-color: #0d6efd; color: #fff; font-weight: 600; }
.method-icon {
    width: 38px; height: 38px; border-radius: .4rem;
    display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
}
.stat-card { border: none; border-radius: .75rem; transition: transform .15s; }
.stat-card:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold">
                        <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Payment Report
                    </h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url($prefix.'/fee/list') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- FILTER BAR --}}
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body py-2 px-3">
                    <form method="GET" id="filterForm">
                        <div class="d-flex flex-wrap gap-2 align-items-center">

                            <span class="small text-muted fw-semibold">Period:</span>

                            @foreach(['daily'=>'Today','weekly'=>'This Week','monthly'=>'This Month','quarterly'=>'This Quarter','yearly'=>'This Year','custom'=>'Custom'] as $val => $label)
                                <a href="javascript:void(0)"
                                   onclick="setPeriod('{{ $val }}')"
                                   class="period-btn {{ $period === $val ? 'active' : '' }}">
                                    {{ $label }}
                                </a>
                            @endforeach

                            <input type="hidden" name="period" id="periodInput" value="{{ $period }}">

                            {{-- Custom range --}}
                            <span id="customRange" class="d-flex gap-2 align-items-center"
                                  style="{{ $period === 'custom' ? '' : 'display:none!important;' }}">
                                <input type="date" name="date_from" class="form-control form-control-sm"
                                       value="{{ $dateFrom }}" style="width:140px;">
                                <span class="text-muted small">to</span>
                                <input type="date" name="date_to" class="form-control form-control-sm"
                                       value="{{ $dateTo }}" style="width:140px;">
                                <button type="submit" class="btn btn-primary btn-sm px-3">Apply</button>
                            </span>

                            <div class="ms-auto d-flex gap-2">
                                <select name="payment_method" class="form-select form-select-sm" style="width:150px;" onchange="this.form.submit()">
                                    <option value=""       {{ $method===''       ? 'selected':'' }}>All Methods</option>
                                    <option value="cash"   {{ $method==='cash'   ? 'selected':'' }}>Cash</option>
                                    <option value="bank"   {{ $method==='bank'   ? 'selected':'' }}>Bank Transfer</option>
                                    <option value="online" {{ $method==='online' ? 'selected':'' }}>Online</option>
                                </select>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- Date range label --}}
            @if($dateFrom && $dateTo)
            <p class="text-muted small mb-3">
                <i class="bi bi-calendar3 me-1"></i>
                Showing: <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong>
                — <strong>{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong>
            </p>
            @endif

            {{-- SUMMARY CARDS --}}
            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="method-icon bg-primary bg-opacity-15 text-primary"><i class="bi bi-currency-rupee"></i></div>
                            <div>
                                <div class="text-muted small">Total Collected</div>
                                <div class="fw-bold fs-5 text-primary">Rs. {{ number_format($summary['total'], 2) }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $summary['count'] }} payments</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="method-icon bg-success bg-opacity-15 text-success"><i class="bi bi-cash-stack"></i></div>
                            <div>
                                <div class="text-muted small">Cash</div>
                                <div class="fw-bold fs-5 text-success">Rs. {{ number_format($summary['cash'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="method-icon bg-info bg-opacity-15 text-info"><i class="bi bi-bank2"></i></div>
                            <div>
                                <div class="text-muted small">Bank Transfer</div>
                                <div class="fw-bold fs-5 text-info">Rs. {{ number_format($summary['bank'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3 p-3">
                            <div class="method-icon bg-warning bg-opacity-15 text-warning"><i class="bi bi-phone-fill"></i></div>
                            <div>
                                <div class="text-muted small">Online / eSewa / Khalti</div>
                                <div class="fw-bold fs-5 text-warning">Rs. {{ number_format($summary['online'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CHARTS ROW --}}
            <div class="row g-3 mb-3">

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-transparent border-bottom">
                            <span class="fw-semibold small"><i class="bi bi-graph-up me-1 text-primary"></i>Daily Collection Trend</span>
                        </div>
                        <div class="card-body">
                            <div style="position:relative;height:220px;">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-transparent border-bottom">
                            <span class="fw-semibold small"><i class="bi bi-pie-chart-fill me-1 text-primary"></i>By Payment Method</span>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center">
                            @if($summary['total'] > 0)
                                <div style="position:relative;height:160px;">
                                    <canvas id="methodChart"></canvas>
                                </div>
                                <div class="mt-2">
                                    @foreach($byMethod as $m)
                                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                        <span class="small">
                                            @if($m->payment_method === 'cash')
                                                <span class="badge bg-success me-1">Cash</span>
                                            @elseif($m->payment_method === 'bank')
                                                <span class="badge bg-info text-dark me-1">Bank</span>
                                            @else
                                                <span class="badge bg-warning text-dark me-1">Online</span>
                                            @endif
                                            {{ $m->count }} payments
                                        </span>
                                        <span class="fw-semibold small">Rs. {{ number_format($m->total, 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small text-center mb-0">No payments in this period.</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- FEE TYPE + TRANSACTIONS --}}
            <div class="row g-3 mb-3">

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-transparent border-bottom">
                            <span class="fw-semibold small"><i class="bi bi-tags-fill me-1 text-primary"></i>By Fee Type</span>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr><th>Fee Type</th><th class="text-end">Count</th><th class="text-end">Amount</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($byFeeType as $ft)
                                    <tr>
                                        <td class="small">{{ $ft->fee_type_name }}</td>
                                        <td class="small text-end">{{ $ft->count }}</td>
                                        <td class="small text-end fw-semibold">Rs. {{ number_format($ft->total, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3 small">No data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                            <span class="fw-semibold small"><i class="bi bi-list-ul me-1 text-primary"></i>Transactions</span>
                            <span class="badge bg-secondary">{{ $transactions->total() }} records</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th><th>Fee Type</th><th>Method</th>
                                            <th class="text-end">Paid</th><th>Date</th><th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $row)
                                        <tr>
                                            <td>
                                                <div class="small fw-semibold">{{ $row->student_name }} {{ $row->student_last_name }}</div>
                                                <div class="text-muted" style="font-size:.68rem;">{{ $row->admission_number }}</div>
                                            </td>
                                            <td class="small">{{ $row->fee_type_name }}</td>
                                            <td>
                                                @if($row->payment_method === 'cash')
                                                    <span class="badge bg-success">Cash</span>
                                                @elseif($row->payment_method === 'bank')
                                                    <span class="badge bg-info text-dark">Bank</span>
                                                @elseif($row->payment_method === 'online')
                                                    <span class="badge bg-warning text-dark">Online</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="small fw-semibold text-success text-end">Rs. {{ number_format($row->paid_amount, 2) }}</td>
                                            <td class="small">{{ $row->payment_date ?? '—' }}</td>
                                            <td>{!! $row->status_badge !!}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6" class="text-center text-muted py-4">No transactions found for this period.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($transactions->hasPages())
                                <div class="px-3 py-2">{{ $transactions->withQueryString()->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

{{--
    Scripts are placed INLINE inside @section('content') — NOT in @section('scripts')
    This guarantees the canvas elements exist in the DOM before Chart.js runs.
--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>

// ── Period filter ─────────────────────────────────────────────
function setPeriod(val) {
    document.getElementById('periodInput').value = val;
    document.getElementById('customRange').style.display = (val === 'custom') ? '' : 'none';
    if (val !== 'custom') {
        document.getElementById('filterForm').submit();
    }
}

// ── Build trend chart labels + values from PHP data ───────────
// Use YYYY-MM-DD strings directly — no Date() timezone issues
var trendRaw  = @json($dailyTrend->toArray());
var trendFrom = '{{ $trendFrom }}';
var trendTo   = '{{ $trendTo }}';

function nextDate(str) {
    var parts = str.split('-');
    var d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]) + 1);
    return d.getFullYear() + '-'
        + String(d.getMonth() + 1).padStart(2, '0') + '-'
        + String(d.getDate()).padStart(2, '0');
}

var chartLabels = [];
var chartValues = [];
var cur = trendFrom;
while (cur <= trendTo) {
    chartLabels.push(cur.slice(5)); // MM-DD
    chartValues.push(trendRaw[cur] ? parseFloat(trendRaw[cur].total) : 0);
    cur = nextDate(cur);
}

// ── Trend bar chart ───────────────────────────────────────────
new Chart(document.getElementById('trendChart'), {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Collected (Rs.)',
            data: chartValues,
            backgroundColor: 'rgba(13,110,253,0.75)',
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return 'Rs. ' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(v) {
                        return v >= 1000 ? 'Rs.' + (v / 1000).toFixed(0) + 'k' : 'Rs.' + v;
                    }
                }
            },
            x: { ticks: { maxRotation: 45, autoSkip: true, maxTicksLimit: 20 } }
        }
    }
});

// ── Method donut chart ────────────────────────────────────────
@if($summary['total'] > 0)
new Chart(document.getElementById('methodChart'), {
    type: 'doughnut',
    data: {
        labels: ['Cash', 'Bank', 'Online'],
        datasets: [{
            data: [{{ $summary['cash'] }}, {{ $summary['bank'] }}, {{ $summary['online'] }}],
            backgroundColor: ['#198754', '#0dcaf0', '#ffc107'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return ctx.label + ': Rs. ' + ctx.parsed.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                    }
                }
            }
        }
    }
});
@endif

</script>
@endsection