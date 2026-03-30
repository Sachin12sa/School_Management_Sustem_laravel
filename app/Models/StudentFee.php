<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $table = 'student_fees';

    protected $fillable = [
        'student_id','class_id', 'fee_type_id', 'amount', 'due_date',
        'paid_amount', 'status', 'payment_date', 'payment_method',
        'transaction_id', 'remarks', 'created_by', 'collected_by',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
//     public function getPaidAmount() {
//     return $this->hasMany(FeePayment::class, 'student_fee_id')->sum('paid_amount');
// }

public function getRemainingAmount() {
    return $this->final_amount - $this->getPaidAmount();
}

    // All fees with student + fee type info (admin/accountant list)
   // StudentFee.php — replace the getRecord() method

public static function getRecord($filters = [])
{
    $query = self::with(['student', 'feeType'])
        ->where('student_fees.is_delete', 0)
        ->join('users as s',          's.id',  '=', 'student_fees.student_id')
        ->leftJoin('class_sections as cs', 'cs.id', '=', 's.section_id')
        ->join('classes as c',        'c.id',  '=', 'student_fees.class_id')
        ->join('fee_types as ft',     'ft.id', '=', 'student_fees.fee_type_id')
        ->select(
            'student_fees.*',
            's.name as student_name', 's.last_name as student_last_name',
            's.admission_number',
            'ft.name as fee_type_name',
            'c.name as class_name',
            'cs.name as section_name'
        );

    if (!empty($filters['student_id'])) {
        $query->where('student_fees.student_id', $filters['student_id']);
    }
    if (!empty($filters['class_id'])) {
        $query->where('student_fees.class_id', $filters['class_id']);   // ← was missing
    }
    if (!empty($filters['section_id'])) {
        $query->where('s.section_id', $filters['section_id']);          // ← new
    }
    if (!empty($filters['fee_type_id'])) {
        $query->where('student_fees.fee_type_id', $filters['fee_type_id']);
    }
    if (!empty($filters['due_date'])) {
        $query->where('student_fees.due_date', $filters['due_date']);
    }
    // status — handle 'overdue' as a virtual status
    if (!empty($filters['status'])) {
        if ($filters['status'] === 'overdue') {
            $query->whereIn('student_fees.status', ['pending', 'partial'])
                  ->where('student_fees.due_date', '<', now()->toDateString());
        } else {
            $query->where('student_fees.status', $filters['status']);
        }
    }

    return $query->orderByDesc('student_fees.created_at')->paginate(20);
}

    // Fees for a single student (student portal view)
    public static function getStudentFees($student_id)
    {
        return self::with('feeType')
            ->where('student_id', $student_id)
            ->where('is_delete', 0)
            ->orderByDesc('due_date')
            ->get();
    }

    // Summary counts for dashboard
    public static function getSummary()
    {
        return [
            'total_collected' => self::where('status', 'paid')->sum('paid_amount'),
            'total_pending'   => self::where('status', 'pending')->sum('amount'),
            'total_partial'   => self::where('status', 'partial')->count(),
            'overdue'         => self::where('status', '!=', 'paid')
                                     ->where('due_date', '<', now()->toDateString())
                                     ->count(),
        ];
    }

    public function getBalanceAttribute(): float
    {
        return $this->amount - $this->paid_amount;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid'    => '<span class="badge bg-success">Paid</span>',
            'partial' => '<span class="badge bg-warning text-dark">Partial</span>',
            default   => '<span class="badge bg-danger">Pending</span>',
        };
    }
}