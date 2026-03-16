<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\FeeType;
use App\Models\StudentFee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    // ─── ADMIN ────────────────────────────────────────────────────────────

    public function list(Request $request)
    {
        $filters = $request->only(['student_id', 'fee_type_id', 'status', 'due_date']);
        $data['getRecord']    = StudentFee::getRecord($filters);
        $data['getFeeTypes']  = FeeType::getActive();
        $data['getClasses']  = \App\Models\ClassModel::getClass();
        $data['getStudents']  = User::where('user_type', 3)->where('is_delete', 0)->get();
        $data['header_title'] = 'Student Fees';
        return view('admin.fee.list', $data);
    }

  public function add()
    {
        $data['getFeeTypes'] = FeeType::getActive();
        $data['getClasses']  = \App\Models\ClassModel::getClass();
 
        $students = User::select(
                    'users.id',
                    'users.name',
                    'users.last_name',
                    'users.class_id',
                    'users.admission_number',
                    'classes.name as class_name'
                )
                ->join('classes','classes.id','=','users.class_id')
                ->where('users.user_type', 3)
                ->where('users.is_delete', 0)
                ->where('users.status', 0)
                ->orderBy('users.class_id')
                ->orderBy('users.name')
                ->get();
 
        $data['getStudents'] = $students;
    // dd($data['getStudents']);
        // Pre-mapped simple array — safe to use with @json() in the blade
        $data['studentsJson'] = $students->map(function ($s) {
            return [
                'id'  => $s->id,
                'name'=> $s->name . ' ' . $s->last_name,
                'num' => $s->admission_number ?? '',
                'cls' => $s->class_id,
            ];
        })->values();
 
        $data['header_title'] = 'Assign Fee';
        return view('admin.fee.add', $data);
    }

    public function insert(Request $request)
    {
        // dd($request->all()); // Remove or comment this out when testing insertion
        $request->validate([
            'assign_mode'    => 'required|in:single,class,all',
            'fee_type_id'    => 'required|exists:fee_types,id',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date',
            'discount_type'  => 'nullable|in:none,percent,flat',
            'discount_value' => 'nullable|numeric|min:0',
        ]);
 
        $mode = $request->assign_mode;
 
        // ── 1. Resolve student IDs based on mode ──────────────────
        if ($mode === 'all') {
            // FIX: Get ALL active students. Do NOT filter by $request->class_id here.
            $studentIds = User::where('user_type', 3)
                ->where('is_delete', 0)
                ->where('status', 1) // Usually 1 is active, adjust if your system uses 0
                ->pluck('id')
                ->toArray();
 
        } elseif ($mode === 'class') {
            // Multiple students from checkbox
            $studentIds = array_filter($request->input('student_ids', []));
            if (empty($studentIds)) {
                return back()->withInput()->withErrors(['student_ids' => 'Please select at least one student.']);
            }
 
        } else {
            // Single student
            $studentIds = array_filter($request->input('student_ids', []));
            if (empty($studentIds)) {
                return back()->withInput()->withErrors(['student_ids' => 'Please select a student.']);
            }
        }
 
        // ── 2. Enforce max amount ─────────────────────────────────
        $feeType    = \App\Models\FeeType::findOrFail($request->fee_type_id);
        $baseAmount = (float) $request->amount;
 
        if ($baseAmount > $feeType->amount) {
            return back()->withInput()->withErrors(['amount' => 'Amount cannot exceed the standard fee of Rs. ' . number_format($feeType->amount, 2)]);
        }
 
        // ── 3. Compute discount ───────────────────────────────────
        $discountType  = $request->discount_type  ?? 'none';
        $discountValue = (float) ($request->discount_value ?? 0);
        $discountAmt   = 0;
 
        if ($discountType === 'percent' && $discountValue > 0) {
            $discountAmt = min(($discountValue / 100) * $baseAmount, $baseAmount);
        } elseif ($discountType === 'flat' && $discountValue > 0) {
            $discountAmt = min($discountValue, $baseAmount);
        }
 
        $finalAmount = round($baseAmount - $discountAmt, 2);
 
        // ── 4. Build remarks with discount info ───────────────────
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
 
        // ── 5. Insert — skip duplicates ───────────────────────────
        $created = 0;
        $skipped = 0;

        // FIX: Fetch the actual student records ONCE, outside the loop
        $students = User::whereIn('id', $studentIds)->get();
 
        // FIX: Loop through the student objects directly. (NO NESTED LOOPS)
        foreach ($students as $student) {
            
            // Check if fee already exists for this exact student
            $exists = \App\Models\StudentFee::where('student_id', $student->id)
                          ->where('fee_type_id', $request->fee_type_id)
                          ->where('due_date', $request->due_date)
                          ->where('is_delete', 0)
                          ->exists();
 
            if ($exists) {
                $skipped++;
                continue; // Move to the next student
            }
 
            // Insert the fee
            \App\Models\StudentFee::create([
                'student_id'  => $student->id,
                'class_id'    => $student->class_id, // Automatically grabs the correct class ID for this student
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
 
        // ── 6. Friendly message ───────────────────────────────────
        $msg = "Fee assigned to {$created} student" . ($created !== 1 ? 's' : '') . ' successfully.';
        if ($skipped > 0) {
            $msg .= " ({$skipped} skipped — already assigned for this period.)";
        }
 
        return redirect('admin/fee/list')->with('success', $msg);
    }

    public function edit($id)
    {
        $data['getRecord']    = StudentFee::findOrFail($id);
        $data['getFeeTypes']  = FeeType::getActive();
        $data['getClasses']  = ClassModel::getClass();
        $data['getStudents'] = User::select('users.*','classes.name as class_name')
                    ->join('classes','classes.id','=','users.class_id')
                    ->where('users.user_type',3)
                    ->where('users.is_delete',0)
                    ->get();
        
        $data['header_title'] = 'Edit Student Fee';
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
            'class_id'     => $request->class_id,
        ]);

        return redirect('admin/fee/list')->with('success', 'Fee record updated.');
    }

    public function delete($id)
    {
        $record = StudentFee::findOrFail($id);
        $record->is_delete = 1;
        $record->save();
        return redirect('admin/fee/list')->with('success', 'Fee record deleted.');
    }

    // ─── COLLECT PAYMENT (Admin & Accountant) ─────────────────────────────

    public function collectPayment($id)
    {
        $data['getRecord']    = StudentFee::with(['student', 'feeType'])->findOrFail($id);
        $data['header_title'] = 'Collect Payment';
        // detect which prefix to use for form action
        $data['prefix'] = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
        
        return view('admin.fee.collect', $data);
    }

    public function submitPayment($id, Request $request)
    {
        $request->validate([
            'paid_amount'     => 'required|numeric|min:0.01',
            'payment_method'  => 'required|in:cash,bank,online',
            'payment_date'    => 'required|date',
        ]);

        $record = StudentFee::findOrFail($id);

        $newPaid = $record->paid_amount + $request->paid_amount;

        if ($newPaid >= $record->amount) {
            $newPaid = $record->amount;
            $status  = 'paid';
        } elseif ($newPaid > 0) {
            $status = 'partial';
        } else {
            $status = 'pending';
        }

        $record->update([
            'paid_amount'      => $newPaid,
            'status'           => $status,
            'payment_date'     => $request->payment_date,
            'payment_method'   => $request->payment_method,
            'transaction_id'   => $request->transaction_id,
            'remarks'          => $request->remarks,
            'collected_by'     => Auth::id(),
        ]);

        $prefix = Auth::user()->user_type == 5 ? 'accountant' : 'admin';
        return redirect($prefix . '/fee/list')->with('success', 'Payment collected successfully.');
    }

    // ─── ACCOUNTANT ROUTES ────────────────────────────────────────────────

    public function accountantList(Request $request)
    {
        $filters = $request->only(['student_id', 'fee_type_id', 'status', 'due_date']);
        $data['getRecord']    = StudentFee::getRecord($filters);
        $data['getFeeTypes']  = FeeType::getActive();
        $data['getStudents']  = User::where('user_type', 3)->where('is_delete', 0)->get();
        $data['header_title'] = 'Fee Collection';
        return view('accountant.fee.list', $data);
    }

    // ─── STUDENT PORTAL ───────────────────────────────────────────────────

    public function myFees()
    {
        $student_id           = Auth::id();
        $data['getRecord']    = StudentFee::getStudentFees($student_id);
        $data['header_title'] = 'My Fees';
        $summary = StudentFee::where('student_id', $student_id);
        $data['total_due']    = (clone $summary)->where('status', '!=', 'paid')->sum('amount');
        $data['total_paid']   = (clone $summary)->sum('paid_amount');
        return view('student.student_my_fees', $data);
    }

    // ─── PARENT PORTAL ────────────────────────────────────────────────────

    public function parentFees($student_id)
    {
        $data['getStudent']   = User::findOrFail($student_id);
        $data['getRecord']    = StudentFee::getStudentFees($student_id);
        $data['header_title'] = 'Student Fees';
        return view('parent.parent_student_fees', $data);
    }

    // ─── ACCOUNTANT DASHBOARD ─────────────────────────────────────────────

    public function accountantDashboard()
    {
        $data['header_title'] = 'Accountant Dashboard';
        $data['summary']      = StudentFee::getSummary();
        $data['recentFees']   = StudentFee::with(['student', 'feeType'])
                                    ->orderByDesc('created_at')
                                    ->limit(8)
                                    ->get();
        $data['overdueFees']  = StudentFee::with(['student', 'feeType'])
                                    ->where('status', '!=', 'paid')
                                    ->where('due_date', '<', now()->toDateString())
                                    ->orderBy('due_date')
                                    ->limit(8)
                                    ->get();
        return view('accountant.dashboard', $data);
    }
    // report for the payment
     public function paymentReport(Request $request)
    {
        // ── Period filter ─────────────────────────────────────────
        $period    = $request->get('period', 'monthly');   // daily|weekly|monthly|quarterly|yearly|custom
        $method    = $request->get('payment_method', '');  // cash|bank|online|''
        $dateFrom  = null;
        $dateTo    = null;
 
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
 
        // ── Base query — only paid/partial records ────────────────
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
 
        // ── Summary cards ─────────────────────────────────────────
        $summary = [
            'total'   => (clone $base)->sum('student_fees.paid_amount'),
            'cash'    => (clone $base)->where('student_fees.payment_method', 'cash')->sum('student_fees.paid_amount'),
            'bank'    => (clone $base)->where('student_fees.payment_method', 'bank')->sum('student_fees.paid_amount'),
            'online'  => (clone $base)->where('student_fees.payment_method', 'online')->sum('student_fees.paid_amount'),
            'count'   => (clone $base)->count(),
        ];
 
        // ── By payment method breakdown ───────────────────────────
        $byMethod = (clone $base)
            ->selectRaw('student_fees.payment_method, SUM(student_fees.paid_amount) as total, COUNT(*) as count')
            ->groupBy('student_fees.payment_method')
            ->get();
 
        // ── By fee type breakdown ─────────────────────────────────
        $byFeeType = (clone $base)
            ->selectRaw('ft.name as fee_type_name, SUM(student_fees.paid_amount) as total, COUNT(*) as count')
            ->groupBy('ft.name')
            ->orderByDesc('total')
            ->get();
 
        // ── Daily trend (for chart — last 30 days or within range) ─
        $trendFrom = $dateFrom ?? now()->subDays(29)->toDateString();
        $trendTo   = $dateTo   ?? now()->toDateString();
 
        $dailyTrend = \App\Models\StudentFee::query()
            ->whereIn('status', ['paid', 'partial'])
            ->where('paid_amount', '>', 0)
            ->where('is_delete', 0)
            ->whereBetween('payment_date', [$trendFrom, $trendTo])
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->selectRaw('DATE(payment_date) as date, SUM(paid_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
 
        // ── Detailed transaction list ─────────────────────────────
        $transactions = (clone $base)
            ->select(
                'student_fees.*',
                's.name as student_name', 's.last_name as student_last_name',
                's.admission_number',
                'ft.name as fee_type_name'
            )
            ->orderByDesc('student_fees.payment_date')
            ->orderByDesc('student_fees.updated_at')
            ->paginate(20);
 
        $prefix = auth()->user()->user_type == 5 ? 'accountant' : 'admin';
 
        $data = compact(
            'summary', 'byMethod', 'byFeeType', 'dailyTrend',
            'transactions', 'period', 'method', 'dateFrom', 'dateTo',
            'trendFrom', 'trendTo', 'prefix'
        );
        $data['header_title'] = 'Payment Report';
 
        return view('admin.fee.fee_payment_report', $data);
    }
}