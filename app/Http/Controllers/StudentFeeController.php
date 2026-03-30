<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassSection;
use App\Models\ClassSectionModel;
use App\Models\FeeType;
use App\Models\StudentFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentFeeController extends Controller
{
    // ─── FEE LIST (Admin) ─────────────────────────────────────────────────

    public function list(Request $request)
{
    $filters             = $request->only(['student_id', 'class_id', 'section_id', 'fee_type_id', 'status']);
    $data['getRecord']   = StudentFee::getRecord($filters);
    $data['getFeeTypes'] = FeeType::getActive();
    $data['getClasses']  = ClassModel::getClass();        // already eager-loads activeSections
    $data['getSections'] = ClassSectionModel::getRecord();
    $data['getStudents'] = User::getStudents();
    $data['header_title'] = 'Student Fees';
    return view('admin.fee.list', $data);
}

    // ─── STUDENT LEDGER ───────────────────────────────────────────────────
    // Shows all fees for ONE student — the main "how much does this student owe?" view

    public function studentLedger($student_id)
    {
        $student = User::findOrFail($student_id);

        $fees = StudentFee::with('feeType')
            ->where('student_id', $student_id)
            ->where('is_delete', 0)
            ->orderBy('due_date')
            ->get();

        // Group by status for summary
        $totalAmount  = $fees->sum('amount');
        $totalPaid    = $fees->sum('paid_amount');
        $totalBalance = $totalAmount - $totalPaid;

        $pending  = $fees->where('status', 'pending');
        $partial  = $fees->where('status', 'partial');
        $paid     = $fees->where('status', 'paid');
        $overdue  = $fees->where('status', '!=', 'paid')->where('due_date', '<', now()->toDateString());

        $data = compact('student', 'fees', 'totalAmount', 'totalPaid', 'totalBalance',
                        'pending', 'partial', 'paid', 'overdue');
        $data['header_title'] = 'Fee Ledger — ' . $student->name . ' ' . $student->last_name;
        $prefix        = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
        $view          = $prefix . '.fee.student_ledger';

        return view($view, $data);
    }

    // ─── ASSIGN FEE ───────────────────────────────────────────────────────

    public function add()
    {
        $data['getFeeTypes'] = FeeType::getActive();
        $data['getClasses']  = ClassModel::getClass();

        $students = User::select(
                'users.id', 'users.name', 'users.last_name',
                'users.class_id', 'users.admission_number',
                'classes.name as class_name'
            )
            ->join('classes', 'classes.id', '=', 'users.class_id')
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0)
            ->where('users.status', 0)
            ->orderBy('users.class_id')
            ->orderBy('users.name')
            ->get();

        $data['getStudents']  = $students;
        $data['studentsJson'] = $students->map(fn($s) => [
            'id'   => $s->id,
            'name' => $s->name . ' ' . $s->last_name,
            'num'  => $s->admission_number ?? '',
            'cls'  => $s->class_id,
        ])->values();

        $data['header_title'] = 'Assign Fee';
        return view('admin.fee.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'assign_mode'    => 'required|in:single,class,all',
            'fee_type_id'    => 'required|exists:fee_types,id',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'discount_type'  => 'nullable|in:none,percent,flat',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        $mode = $request->assign_mode;

        // Resolve student IDs
        if ($mode === 'all') {
            $studentIds = User::where('user_type', 3)->where('is_delete', 0)
                ->where('status', 0)->pluck('id')->toArray();
        } else {
            $studentIds = array_filter($request->input('student_ids', []));
            if (empty($studentIds)) {
                return back()->withInput()->withErrors(['student_ids' => 'Please select at least one student.']);
            }
        }

        $feeType    = FeeType::findOrFail($request->fee_type_id);
        $baseAmount = (float) $request->amount;

        if ($baseAmount > $feeType->amount) {
            return back()->withInput()->withErrors(['amount' => 'Amount cannot exceed Rs. ' . number_format($feeType->amount, 2)]);
        }

        // Compute discount
        $discountType  = $request->discount_type ?? 'none';
        $discountValue = (float) ($request->discount_value ?? 0);
        $discountAmt   = 0;

        if ($discountType === 'percent' && $discountValue > 0) {
            $discountAmt = min(($discountValue / 100) * $baseAmount, $baseAmount);
        } elseif ($discountType === 'flat' && $discountValue > 0) {
            $discountAmt = min($discountValue, $baseAmount);
        }

        $finalAmount = round($baseAmount - $discountAmt, 2);

        $remarks = $request->remarks ?? '';
        if ($discountAmt > 0) {
            $note = $discountType === 'percent'
                ? "Discount: {$discountValue}% (Rs. " . number_format($discountAmt, 2) . ")"
                : "Discount: Rs. " . number_format($discountAmt, 2);
            if ($request->discount_reason) {
                $note .= ' — ' . $request->discount_reason;
            }
            $remarks = $note . ($remarks ? ' | ' . $remarks : '');
        }

        $created = 0;
        $skipped = 0;
        $students = User::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            $exists = StudentFee::where('student_id', $student->id)
                ->where('fee_type_id', $request->fee_type_id)
                ->where('due_date', $request->due_date)
                ->where('is_delete', 0)
                ->exists();

            if ($exists) { $skipped++; continue; }

            StudentFee::create([
                'student_id'  => $student->id,
                'class_id'    => $student->class_id,
                'fee_type_id' => $request->fee_type_id,
                'amount'      => $finalAmount,
                'due_date'    => $request->due_date,
                'remarks'     => $remarks,
                'status'      => 'pending',
                'paid_amount' => 0,
                'created_by'  => Auth::id(),
            ]);
            $created++;
        }

        $msg = "Fee assigned to {$created} student" . ($created !== 1 ? 's' : '') . '.';
        if ($skipped > 0) $msg .= " ({$skipped} already assigned — skipped.)";

        return redirect('admin/fee/list')->with('success', $msg);
    }

    // ─── EDIT FEE ─────────────────────────────────────────────────────────

    public function edit($id)
    {
        $data['getRecord']   = StudentFee::findOrFail($id);
        $data['getFeeTypes'] = FeeType::getActive();
        $data['getClasses']  = ClassModel::getClass();
        $data['getStudents'] = User::select('users.*', 'classes.name as class_name')
            ->join('classes', 'classes.id', '=', 'users.class_id')
            ->where('users.user_type', 3)->where('users.is_delete', 0)->get();
        $data['header_title'] = 'Edit Fee Record';
        return view('admin.fee.edit', $data);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:users,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'amount'      => 'required|numeric|min:0',
            'due_date'    => 'required|date',
        ]);

        $record = StudentFee::findOrFail($id);
        $record->update([
            'student_id'  => $request->student_id,
            'fee_type_id' => $request->fee_type_id,
            'amount'      => $request->amount,
            'due_date'    => $request->due_date,
            'remarks'     => $request->remarks,
        ]);

        return redirect('admin/fee/list')->with('success', 'Fee record updated.');
    }

    // ─── COLLECT PAYMENT (single fee) ────────────────────────────────────

    public function collect($id)
    {
        $prefix              = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
        $data['getRecord']   = StudentFee::with(['student', 'feeType'])->findOrFail($id);
        $data['prefix']      = $prefix;
        $data['header_title'] = 'Collect Payment';
        return view('admin.fee.collect', $data);
    }

    public function collectStore($id, Request $request)
    {
        $record = StudentFee::findOrFail($id);

        $request->validate([
            'paid_amount'    => 'required|numeric|min:0.01|max:' . ($record->amount - $record->paid_amount),
            'payment_method' => 'required|in:cash,bank,online',
            'payment_date'   => 'required|date',
        ]);

        $newPaid = $record->paid_amount + $request->paid_amount;
        $status  = $newPaid >= $record->amount ? 'paid' : ($newPaid > 0 ? 'partial' : 'pending');
        if ($newPaid >= $record->amount) $newPaid = $record->amount;

        $record->update([
            'paid_amount'    => $newPaid,
            'status'         => $status,
            'payment_date'   => $request->payment_date,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'remarks'        => $request->remarks,
            'collected_by'   => Auth::id(),
        ]);

        $prefix = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
        return redirect(url($prefix . '/fee/receipt/' . $record->student_id))
            ->with('paid_fee_ids',    [$record->id])
            ->with('amount_paid_now', (float) $request->paid_amount);
    }

  
public function bulkCollect($student_id, Request $request)
{
    $student = User::select('users.*', 'classes.name as class_name')
        ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
        ->where('users.id', $student_id)
        ->firstOrFail();
 
    // Load ALL non-deleted fees for this student (paid ones shown greyed out)
    $fees = StudentFee::with('feeType')
        ->where('student_id', $student_id)
        ->where('is_delete', 0)
        ->orderByRaw("FIELD(status, 'pending', 'partial', 'paid')")
        ->orderBy('due_date')
        ->get();
 
    $prefix       = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
    $studentClass = $student->class_name ?? null;
 
    return view('admin.fee.collect', compact(
        'student', 'fees', 'prefix', 'studentClass'
    ));
}

 
// ─── Single fee quick-collect redirect to same unified page ───────────────
// Clicking "💰" on fee list still goes to the same collect page
// but the URL carries ?focus={fee_id} so JS can pre-select only that fee
 
public function collectPayment($id)
{
    $fee        = StudentFee::with(['student', 'feeType'])->findOrFail($id);
    $student_id = $fee->student_id;
    $prefix     = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
 
    // Redirect to the unified collect page with a focus hint
    return redirect(url($prefix . '/fee/bulk-collect/' . $student_id . '?focus=' . $id));
}
 
// ─── Process payment (single & bulk) ─────────────────────────────────────
 
public function bulkCollectStore($student_id, Request $request)
{
    $request->validate([
        'payment_method'                  => 'required|in:cash,bank,online',
        'payment_date'                    => 'required|date',
        'payments'                        => 'required|array',
        'payments.*.fee_id'               => 'required|exists:student_fees,id',
        'payments.*.paid_amount'          => 'required|numeric|min:0',
        'payments.*.discount_type'        => 'nullable|in:none,percent,flat',
        'payments.*.discount_value'       => 'nullable|numeric|min:0',
    ]);
 
    $collected   = 0;
    $totalNet    = 0;
    $paidFeeIds  = [];  // track every fee_id we actually process

    foreach ($request->payments as $p) {
        $rawAmt = (float) $p['paid_amount'];
        if ($rawAmt <= 0) continue;

        $record = StudentFee::where('id', $p['fee_id'])
            ->where('student_id', $student_id)
            ->where('is_delete', 0)
            ->firstOrFail();

        if ($record->status === 'paid') continue;

        // ── Per-row discount ─────────────────────────────────────
        $discType  = $p['discount_type']  ?? 'none';
        $discValue = (float) ($p['discount_value'] ?? 0);
        $discAmt   = 0;

        if ($discType === 'percent' && $discValue > 0) {
            $discAmt = min(($discValue / 100) * $rawAmt, $rawAmt);
        } elseif ($discType === 'flat' && $discValue > 0) {
            $discAmt = min($discValue, $rawAmt);
        }

        $netAmt     = max(0, round($rawAmt - $discAmt, 2));
        $maxPayable = round($record->amount - $record->paid_amount, 2);
        $netAmt     = min($netAmt, $maxPayable);

        if ($netAmt <= 0) continue;

        $newPaid = round($record->paid_amount + $netAmt, 2);
        $status  = $newPaid >= $record->amount ? 'paid' : 'partial';

        // Build remarks with discount info
        $remarks = $request->remarks ?? '';
        if ($discAmt > 0) {
            $discNote = $discType === 'percent'
                ? "Discount {$discValue}% (Rs. " . number_format($discAmt, 2) . ")"
                : "Discount Rs. " . number_format($discAmt, 2);
            $remarks = $discNote . ($remarks ? ' | ' . $remarks : '');
        }

        $record->update([
            'paid_amount'    => $newPaid,
            'status'         => $status,
            'payment_date'   => $request->payment_date,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'remarks'        => $remarks ?: $record->remarks,
            'collected_by'   => Auth::id(),
        ]);

        $collected++;
        $totalNet   += $netAmt;
        $paidFeeIds[] = $record->id;
    }

    $prefix = Auth::user()->user_type == 5 ? 'accountant' : 'admin';

    if ($collected === 0) {
        return back()->withInput()->withErrors(['payments' => 'No valid payments to process.']);
    }

    // Always go to receipt — pass fee IDs + total via session flash
    // receipt() handles both single and multiple fees
    return redirect(url($prefix . '/fee/receipt/' . $student_id))
        ->with('paid_fee_ids',    $paidFeeIds)
        ->with('amount_paid_now', $totalNet);
}

 

    // ─── INVOICE (printable) ──────────────────────────────────────────────

    public function invoice($student_id)
    {
        $student = User::select('users.*', 'classes.name as class_name')
            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
            ->where('users.id', $student_id)
            ->firstOrFail();

        $fees = StudentFee::with('feeType')
            ->where('student_id', $student_id)
            ->where('is_delete', 0)
            ->orderBy('due_date')
            ->get();

        $totalAmount  = $fees->sum('amount');
        $totalPaid    = $fees->sum('paid_amount');
        $totalBalance = $totalAmount - $totalPaid;

        $data = compact('student', 'fees', 'totalAmount', 'totalPaid', 'totalBalance');
        $data['header_title'] = 'Invoice — ' . $student->name;
        $data['invoiceNo']    = 'INV-' . str_pad($student_id, 5, '0', STR_PAD_LEFT) . '-' . date('Ymd');
        $data['printDate']    = now()->toDateString();
        $prefix        = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
        $view          = $prefix . '.fee.invoice';

        return view($view, $data);
    }

    // ─── DELETE ───────────────────────────────────────────────────────────

    public function delete($id)
    {
        $record = StudentFee::findOrFail($id);
        $record->is_delete = 1;
        $record->save();
        return back()->with('success', 'Fee record deleted.');
    }

    // ─── PAYMENT REPORT ───────────────────────────────────────────────────

    public function paymentReport(Request $request)
    {
        $period   = $request->get('period', 'monthly');
        $method   = $request->get('payment_method', '');
        $dateFrom = null;
        $dateTo   = null;

        switch ($period) {
            case 'daily':
                $dateFrom = now()->toDateString();
                $dateTo   = now()->toDateString();
                break;
            case 'weekly':
                $dateFrom = now()->startOfWeek()->toDateString();
                $dateTo   = now()->endOfWeek()->toDateString();
                break;
            case 'monthly':
                $dateFrom = now()->startOfMonth()->toDateString();
                $dateTo   = now()->endOfMonth()->toDateString();
                break;
            case 'quarterly':
                $dateFrom = now()->firstOfQuarter()->toDateString();
                $dateTo   = now()->lastOfQuarter()->toDateString();
                break;
            case 'yearly':
                $dateFrom = now()->startOfYear()->toDateString();
                $dateTo   = now()->endOfYear()->toDateString();
                break;
            case 'custom':
                $dateFrom = $request->get('date_from');
                $dateTo   = $request->get('date_to');
                break;
        }

        $base = StudentFee::query()
            ->join('users as s',      's.id',  '=', 'student_fees.student_id')
            ->join('fee_types as ft', 'ft.id', '=', 'student_fees.fee_type_id')
            ->whereIn('student_fees.status', ['paid', 'partial'])
            ->where('student_fees.paid_amount', '>', 0)
            ->where('student_fees.is_delete', 0);

        if ($dateFrom && $dateTo) {
            $base->whereBetween('student_fees.payment_date', [$dateFrom, $dateTo]);
        }
        if ($method) {
            $base->where('student_fees.payment_method', $method);
        }

        $summary = [
            'total'  => (clone $base)->sum('student_fees.paid_amount'),
            'cash'   => (clone $base)->where('student_fees.payment_method', 'cash')->sum('student_fees.paid_amount'),
            'bank'   => (clone $base)->where('student_fees.payment_method', 'bank')->sum('student_fees.paid_amount'),
            'online' => (clone $base)->where('student_fees.payment_method', 'online')->sum('student_fees.paid_amount'),
            'count'  => (clone $base)->count(),
        ];

        $byMethod = (clone $base)
            ->selectRaw('student_fees.payment_method, SUM(student_fees.paid_amount) as total, COUNT(*) as count')
            ->groupBy('student_fees.payment_method')->get();

        $byFeeType = (clone $base)
            ->selectRaw('ft.name as fee_type_name, SUM(student_fees.paid_amount) as total, COUNT(*) as count')
            ->groupBy('ft.name')->orderByDesc('total')->get();

        $trendFrom  = $dateFrom ?? now()->subDays(29)->toDateString();
        $trendTo    = $dateTo   ?? now()->toDateString();
        $dailyTrend = StudentFee::query()
            ->whereIn('status', ['paid', 'partial'])
            ->where('paid_amount', '>', 0)->where('is_delete', 0)
            ->whereBetween('payment_date', [$trendFrom, $trendTo])
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->selectRaw('DATE(payment_date) as date, SUM(paid_amount) as total')
            ->groupBy('date')->orderBy('date')->get()->keyBy('date');

        $transactions = (clone $base)
            ->select('student_fees.*', 's.name as student_name', 's.last_name as student_last_name',
                     's.admission_number', 'ft.name as fee_type_name')
            ->orderByDesc('student_fees.payment_date')
            ->orderByDesc('student_fees.updated_at')
            ->paginate(20);

        $prefix = Auth::user()->user_type == 5 ? 'accountant' : 'admin';

        $data = compact('summary', 'byMethod', 'byFeeType', 'dailyTrend',
                        'transactions', 'period', 'method', 'dateFrom', 'dateTo',
                        'trendFrom', 'trendTo', 'prefix');
        $data['header_title'] = 'Payment Report';
        return view('admin.fee.fee_payment_report', $data);
    }


 
// ─── SUBMIT PAYMENT — save and redirect to RECEIPT ───────────────────────────
 
public function submitPayment($id, Request $request)
{
    $record = StudentFee::with(['student', 'feeType'])->findOrFail($id);
 
    $maxPayable = $record->amount - $record->paid_amount;
 
    $request->validate([
        'paid_amount'    => 'required|numeric|min:0.01|max:' . $maxPayable,
        'payment_method' => 'required|in:cash,bank,online',
        'payment_date'   => 'required|date',
    ]);
 
    $amountPaidNow  = (float) $request->paid_amount;
    $newPaid        = $record->paid_amount + $amountPaidNow;
 
    if ($newPaid >= $record->amount) {
        $newPaid = $record->amount;
        $status  = 'paid';
    } elseif ($newPaid > 0) {
        $status = 'partial';
    } else {
        $status = 'pending';
    }
 
    $record->update([
        'paid_amount'    => $newPaid,
        'status'         => $status,
        'payment_date'   => $request->payment_date,
        'payment_method' => $request->payment_method,
        'transaction_id' => $request->transaction_id,
        'remarks'        => $request->remarks,
        'collected_by'   => Auth::id(),
    ]);
 
    $prefix = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
 
    // ── Redirect to receipt page ────────────────────────────────────
    return redirect(url($prefix . '/fee/receipt/' . $record->student_id))
        ->with('paid_fee_ids',    [$record->id])
        ->with('amount_paid_now', $amountPaidNow);
}
 
    // ─── RECEIPT — shown after any payment (single or multiple fees) ────────
    // Route: GET {prefix}/fee/receipt/{student_id}
    // Session flash: paid_fee_ids (array), amount_paid_now (float)

    public function receipt($student_id)
    {
        $prefix = Auth::user()->user_type == 5 ? 'accountant' : 'admin';

        // IDs of fees just paid — passed from bulkCollectStore via session flash
        $paidFeeIds    = session('paid_fee_ids', []);
        $amountPaidNow = (float) session('amount_paid_now', 0);

        // Student with class name
        $student = User::select('users.*', 'classes.name as class_name')
            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
            ->where('users.id', $student_id)
            ->firstOrFail();

        $studentClass = $student->class_name;

        // Load the fees that were just paid
        // If session expired (page refresh) fall back to last-updated fees for this student
        if (!empty($paidFeeIds)) {
            $fees = StudentFee::with(['feeType', 'collector'])
                ->whereIn('id', $paidFeeIds)
                ->get();
        } else {
            // Fallback: most recently updated fees for this student
            $fees = StudentFee::with(['feeType', 'collector'])
                ->where('student_id', $student_id)
                ->where('is_delete', 0)
                ->whereIn('status', ['paid', 'partial'])
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();
            $amountPaidNow = $fees->sum('paid_amount');
        }

        if ($fees->isEmpty()) {
            return redirect(url($prefix . '/fee/student/' . $student_id))
                ->with('success', 'Payment recorded successfully.');
        }

        // Collector name (from first fee — all fees in one transaction share the same collector)
        $firstFee      = $fees->first();
        $collectorName = $firstFee->collector
            ? $firstFee->collector->name . ' ' . $firstFee->collector->last_name
            : null;

        // Receipt number: RCP-{student_id}-{date}-{time}
        $receiptNo = 'RCP-' . str_pad($student_id, 4, '0', STR_PAD_LEFT)
                   . '-' . date('Ymd')
                   . '-' . date('His');

        $totalFeeAmount = $fees->sum('amount');
        $totalPaid      = $fees->sum('paid_amount');
        $totalBalance   = $totalFeeAmount - $totalPaid;
        $isSingleFee    = $fees->count() === 1;

        return view('admin.fee.receipt', compact(
            'fees', 'student', 'prefix', 'amountPaidNow',
            'studentClass', 'collectorName', 'receiptNo',
            'totalFeeAmount', 'totalPaid', 'totalBalance', 'isSingleFee'
        ));
    }
    // report invoice
 public function reportInvoice(Request $request)
{
    $period   = $request->get('period', 'monthly');
    $method   = $request->get('payment_method', '');
    $dateFrom = null;
    $dateTo   = null;
 
    switch ($period) {
        case 'daily':
            $dateFrom = now()->toDateString();
            $dateTo   = now()->toDateString();
            break;
        case 'weekly':
            $dateFrom = now()->startOfWeek()->toDateString();
            $dateTo   = now()->endOfWeek()->toDateString();
            break;
        case 'monthly':
            $dateFrom = now()->startOfMonth()->toDateString();
            $dateTo   = now()->endOfMonth()->toDateString();
            break;
        case 'quarterly':
            $dateFrom = now()->firstOfQuarter()->toDateString();
            $dateTo   = now()->lastOfQuarter()->toDateString();
            break;
        case 'yearly':
            $dateFrom = now()->startOfYear()->toDateString();
            $dateTo   = now()->endOfYear()->toDateString();
            break;
        case 'custom':
            $dateFrom = $request->get('date_from');
            $dateTo   = $request->get('date_to');
            break;
    }
 
    // Base query — identical to paymentReport()
    $base = \App\Models\StudentFee::query()
        ->join('users as s',      's.id',  '=', 'student_fees.student_id')
        ->join('fee_types as ft', 'ft.id', '=', 'student_fees.fee_type_id')
        ->whereIn('student_fees.status', ['paid', 'partial'])
        ->where('student_fees.paid_amount', '>', 0)
        ->where('student_fees.is_delete', 0);
 
    if ($dateFrom && $dateTo) {
        $base->whereBetween('student_fees.payment_date', [$dateFrom, $dateTo]);
    }
    if ($method) {
        $base->where('student_fees.payment_method', $method);
    }
 
    $summary = [
        'total'  => (clone $base)->sum('student_fees.paid_amount'),
        'cash'   => (clone $base)->where('student_fees.payment_method', 'cash')->sum('student_fees.paid_amount'),
        'bank'   => (clone $base)->where('student_fees.payment_method', 'bank')->sum('student_fees.paid_amount'),
        'online' => (clone $base)->where('student_fees.payment_method', 'online')->sum('student_fees.paid_amount'),
        'count'  => (clone $base)->count(),
    ];
 
    $byMethod = (clone $base)
        ->selectRaw('student_fees.payment_method, SUM(student_fees.paid_amount) as total, COUNT(*) as count')
        ->groupBy('student_fees.payment_method')
        ->get();
 
    $byFeeType = (clone $base)
        ->selectRaw('ft.name as fee_type_name, SUM(student_fees.paid_amount) as total, COUNT(*) as count')
        ->groupBy('ft.name')
        ->orderByDesc('total')
        ->get();
 
    // ALL transactions for the report (no pagination — this is a printable document)
    $allTransactions = (clone $base)
        ->select(
            'student_fees.*',
            's.name as student_name',
            's.last_name as student_last_name',
            's.admission_number',
            'ft.name as fee_type_name'
        )
        ->orderByDesc('student_fees.payment_date')
        ->orderByDesc('student_fees.updated_at')
        ->get();
 
    $periodLabel = [
        'daily'     => 'Today',
        'weekly'    => 'This Week',
        'monthly'   => 'This Month',
        'quarterly' => 'This Quarter',
        'yearly'    => 'This Year',
        'custom'    => 'Custom Period',
    ][$period] ?? ucfirst($period);
 
    $generatedDate = now()->toDateString();
    $generatedBy   = Auth::user()->name . ' ' . Auth::user()->last_name;
    $reportNo      = 'RPT-' . strtoupper($period) . '-' . date('Ymd') . '-' . date('His');
    $prefix        = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
 
    return view('admin.fee.report_invoice', compact(
        'summary', 'byMethod', 'byFeeType', 'allTransactions',
        'period', 'periodLabel', 'method', 'dateFrom', 'dateTo',
        'reportNo', 'generatedDate', 'generatedBy', 'prefix'
    ));
}
 

    // ─── ACCOUNTANT ROUTES ────────────────────────────────────────────────

    public function accountantList(Request $request)
{
    $filters              = $request->only(['student_id', 'class_id', 'section_id', 'fee_type_id', 'status']);
    $data['getRecord']    = StudentFee::getRecord($filters);
    $data['getFeeTypes']  = FeeType::getActive();
    $data['getClasses']   = ClassModel::getClass();
    $data['getSections']  = ClassSectionModel::getRecord(); // ← add this
    $data['getStudents']  = User::where('user_type', 3)->where('is_delete', 0)->orderBy('name')->get();
    $data['header_title'] = 'Fee Collection';
    return view('accountant.fee.list', $data);
}

    // ─── STUDENT PORTAL ───────────────────────────────────────────────────

    public function myFees()
    {
        $student_id        = Auth::id();
        $data['getRecord'] = StudentFee::getStudentFees($student_id);
        $data['header_title'] = 'My Fees';
        $summary = StudentFee::where('student_id', $student_id);
        $data['total_due']  = (clone $summary)->where('status', '!=', 'paid')->sum('amount');
        $data['total_paid'] = (clone $summary)->sum('paid_amount');
        return view('student.student_my_fees', $data);
    }

    // ─── ACCOUNTANT DASHBOARD ─────────────────────────────────────────────

    public function accountantDashboard()
    {
        $data['header_title'] = 'Accountant Dashboard';
        $data['summary']      = StudentFee::getSummary();
        $data['recentFees']   = StudentFee::with(['student', 'feeType'])
            ->orderByDesc('created_at')->limit(8)->get();
        $data['overdueFees']  = StudentFee::with(['student', 'feeType'])
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now()->toDateString())
            ->orderBy('due_date')->limit(8)->get();
        return view('accountant.dashboard', $data);
    }
}