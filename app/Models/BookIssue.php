<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookIssue extends Model
{
    protected $table    = 'book_issues';
    protected $casts    = []; // NO casts — all dates handled as raw strings

    protected $fillable = [
        'book_id', 'member_id', 'issue_date', 'due_date', 'return_date',
        'fine_per_day', 'fine_amount', 'fine_status',
        'fine_payment_method', 'fine_paid_at', 'fine_collected_by', 'fine_note',
        'book_condition', 'damage_charge', 'damage_note',
        'status', 'note', 'created_by', 'returned_by', 'is_delete',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function fineCollector()
    {
        return $this->belongsTo(User::class, 'fine_collected_by');
    }

    // ── Date helper ────────────────────────────────────────────────
    // Always returns YYYY-MM-DD regardless of Carbon / datetime string / date string

    public static function dateStr($date): string
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }
        return substr((string) $date, 0, 10);
    }

    // ── Issue list ─────────────────────────────────────────────────

    public static function getRecord(array $filters = [])
    {
        $query = self::join('books as b', 'b.id', '=', 'book_issues.book_id')
            ->join('users as m', 'm.id', '=', 'book_issues.member_id')
            ->select(
                'book_issues.*',
                'b.title  as book_title',
                'b.author as book_author',
                'm.name      as member_name',
                'm.last_name as member_last_name',
                'm.user_type as member_type',
                'm.admission_number'
            )
            ->where('book_issues.is_delete', 0);

        if (!empty($filters['member_name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('m.name', 'like', '%'.$filters['member_name'].'%')
                  ->orWhere('m.last_name', 'like', '%'.$filters['member_name'].'%');
            });
        }
        if (!empty($filters['book_id'])) {
            $query->where('book_issues.book_id', $filters['book_id']);
        }
        if (!empty($filters['status'])) {
            $query->where('book_issues.status', $filters['status']);
        }

        return $query->orderByDesc('book_issues.issue_date')->paginate(15);
    }

    // ── Member books ───────────────────────────────────────────────

    public static function getMemberBooks($member_id)
    {
        return self::with('book')
            ->where('member_id', $member_id)
            ->where('is_delete', 0)
            ->orderByDesc('issue_date')
            ->get();
    }

    // ── Member fines ───────────────────────────────────────────────

    public static function getMemberFines($member_id)
    {
        return self::with('book')
            ->where('member_id', $member_id)
            ->where('is_delete', 0)
            ->where(function ($q) {
                // Show: returned with any unpaid fine (including 0-amount late returns)
                // OR: still-out overdue books with a fine rate
                $q->where('fine_status', 'unpaid')
                  ->orWhere('fine_status', 'waived')   // show history
                  ->orWhere('fine_status', 'paid')      // show history
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'overdue')->where('fine_per_day', '>', 0);
                  });
            })
            ->orderByDesc('due_date')
            ->get();
    }

    // ── Fine summary cards ─────────────────────────────────────────

    public static function getFineSummary(): array
    {
        // Unpaid = RETURNED books marked unpaid (late OR damaged).
        // Deliberately NO fine_amount > 0 filter — late returns with fine_per_day = 0
        // are still marked unpaid so admin can see and act on them.
        $unpaidQ = DB::table('book_issues')
            ->where('status',      'returned')
            ->where('fine_status', 'unpaid')
            ->where('is_delete',   0);

        // Accruing = still-out overdue books (not yet waived/paid)
        $overdueRows = DB::table('book_issues')
            ->where('status',      'overdue')
            ->where('fine_per_day', '>', 0)
            ->whereNotIn('fine_status', ['waived', 'paid'])
            ->where('is_delete', 0)
            ->select('due_date', 'fine_per_day')
            ->get();

        $accruingTotal = 0;
        foreach ($overdueRows as $row) {
            $days           = (int) Carbon::parse(substr((string) $row->due_date, 0, 10))
                                          ->startOfDay()
                                          ->diffInDays(now()->startOfDay());
            $accruingTotal += $days * (int) $row->fine_per_day;
        }

        return [
            'unpaid_count'   => (int)   $unpaidQ->count(),
            'unpaid_total'   => (float) $unpaidQ->sum('fine_amount'),
            'accruing_count' => (int)   $overdueRows->count(),
            'accruing_total' => (float) $accruingTotal,
            'paid_total'     => (float) DB::table('book_issues')->where('fine_status','paid')->where('is_delete',0)->sum('fine_amount'),
            'waived_total'   => (float) DB::table('book_issues')->where('fine_status','waived')->where('is_delete',0)->sum('fine_amount'),
        ];
    }
    public static function getSummary()
{
    // 1. Sync overdue status first
    DB::table('book_issues')
        ->where('status', 'issued')
        ->where('due_date', '<', now()->toDateString())
        ->where('is_delete', 0)
        ->update(['status' => 'overdue', 'updated_at' => now()]);

    // 2. Fetch raw counts
    $totalCopies = (int) Book::where('is_delete', 0)->sum('quantity');

    // Count of books currently NOT in the library (Issued + Overdue)
    $booksOut = (int) BookIssue::whereIn('status', ['issued', 'overdue'])
                                ->where('is_delete', 0)
                                ->count();

    // Count of specifically Overdue books
    $overdueCount = (int) BookIssue::where('status', 'overdue')
                                    ->where('is_delete', 0)
                                    ->count();

   
    return [
        'total_books'      => Book::where('is_delete', 0)->count(),
        'total_copies'     => $totalCopies,
        'total_issued'     => BookIssue::where('status', 'issued')->where('is_delete', 0)->count(),
        'overdue'          => $overdueCount,
        'total_out'        => $booksOut, // Both issued and overdue
        
        // Math: Total Stock - (Issued + Overdue)
        'available_copies' => $totalCopies - $booksOut,
    ];
}
}