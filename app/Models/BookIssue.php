<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookIssue extends Model
{
    protected $table = 'book_issues';

    protected $fillable = [
        'book_id', 'member_id', 'issue_date', 'due_date', 'return_date',
        'fine_per_day', 'fine_amount', 'fine_status',
        'fine_payment_method', 'fine_paid_at', 'fine_collected_by', 'fine_note',
        'status', 'note', 'created_by', 'returned_by',
    ];

    // !! DO NOT cast date columns as 'date' !!
    // Casting as 'date' returns Carbon objects which cause double-parse bugs
    // in queries and in blade {{ $record->due_date }} output ("2026-03-11 00:00:00").
    // Instead we use explicit format() calls in the controller and blade.
    protected $casts = [];

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

    // ── Safe date helper (use everywhere instead of Carbon::parse) ─
    // Returns a clean YYYY-MM-DD string regardless of what's stored.
    public static function safeDate($date): string
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d');
        }
        // Strip any time portion stored as string e.g. "2026-03-11 00:00:00"
        return substr((string) $date, 0, 10);
    }

    // ── Query methods ──────────────────────────────────────────────

    public static function getRecord($filters = [])
    {
        $query = self::join('books as b', 'b.id', '=', 'book_issues.book_id')
            ->join('users as m', 'm.id', '=', 'book_issues.member_id')
            ->select(
                'book_issues.*',
                'b.title as book_title', 'b.author as book_author',
                'm.name as member_name', 'm.last_name as member_last_name',
                'm.user_type as member_type', 'm.admission_number'
            )
            ->where('book_issues.is_delete', 0);

        if (!empty($filters['member_name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('m.name', 'like', '%' . $filters['member_name'] . '%')
                  ->orWhere('m.last_name', 'like', '%' . $filters['member_name'] . '%');
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

    // Tab-aware fine query — used by fineList()
    public static function getFinesByTab(string $tab, array $filters = [])
    {
        $query = self::join('books as b', 'b.id', '=', 'book_issues.book_id')
            ->join('users as m', 'm.id', '=', 'book_issues.member_id')
            ->select(
                'book_issues.*',
                'b.title as book_title',
                'm.name as member_name', 'm.last_name as member_last_name',
                'm.user_type as member_type', 'm.admission_number'
            )
            ->where('book_issues.is_delete', 0);

        if ($tab === 'unpaid') {
            // Returned books with a fine not yet paid.
            // fine_status = 'unpaid' OR fine_status IS NULL (pre-migration rows)
            $query->where('book_issues.status', 'returned')
                  ->where('book_issues.fine_amount', '>', 0)
                  ->where(function ($q) {
                      $q->where('book_issues.fine_status', 'unpaid')
                        ->orWhereNull('book_issues.fine_status');
                  });

        } elseif ($tab === 'accruing') {
            // Books still out AND past due AND fine not yet settled
            $query->where('book_issues.status', 'overdue')
                  ->where('book_issues.fine_per_day', '>', 0)
                  ->where(function ($q) {
                      // NULL means pre-migration — treat as unsettled
                      $q->whereNull('book_issues.fine_status')
                        ->orWhereNotIn('book_issues.fine_status', ['paid', 'waived']);
                  });

        } elseif ($tab === 'paid') {
            $query->where('book_issues.fine_status', 'paid');

        } elseif ($tab === 'waived') {
            $query->where('book_issues.fine_status', 'waived');
        }

        if (!empty($filters['member_name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('m.name',       'like', '%' . $filters['member_name'] . '%')
                  ->orWhere('m.last_name', 'like', '%' . $filters['member_name'] . '%');
            });
        }
        if (!empty($filters['member_type'])) {
            $query->where('m.user_type', $filters['member_type']);
        }

        return $query->with('fineCollector')
                     ->orderByDesc('book_issues.due_date')
                     ->paginate(15);
    }

    // Fines for a specific member (student/teacher portal)
    public static function getMemberFines($member_id)
    {
        return self::with('book')
            ->where('member_id', $member_id)
            ->where('is_delete', 0)
            ->where(function ($q) {
                // Has a fine OR is overdue with fine_per_day set
                $q->where('fine_amount', '>', 0)
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'overdue')
                         ->where('fine_per_day', '>', 0);
                  });
            })
            ->orderByDesc('due_date')
            ->get();
    }

    public static function getMemberBooks($member_id)
    {
        return self::with('book')
            ->where('member_id', $member_id)
            ->where('is_delete', 0)
            ->orderByDesc('issue_date')
            ->get();
    }

    // Summary counts for the 4 cards on the fine list page
    public static function getFineSummary(): array
    {
        // Unpaid: returned books with fine_amount > 0 and fine not yet paid/waived
        $unpaidQ = self::where('status', 'returned')
                       ->where('fine_amount', '>', 0)
                       ->where('is_delete', 0)
                       ->where(function ($q) {
                           $q->where('fine_status', 'unpaid')
                             ->orWhereNull('fine_status');
                       });

        // Accruing: still-out overdue books with fine_per_day, not yet settled
        $accruingQ = self::where('status', 'overdue')
                         ->where('fine_per_day', '>', 0)
                         ->where('is_delete', 0)
                         ->where(function ($q) {
                             $q->whereNull('fine_status')
                               ->orWhereNotIn('fine_status', ['paid', 'waived']);
                         });

        // Calculate total accruing fine (days overdue × fine_per_day for each)
        $accruingTotal = 0;
        foreach ($accruingQ->get(['due_date', 'fine_per_day']) as $issue) {
            $dueStr = self::safeDate($issue->due_date);
            $days   = Carbon::createFromFormat('Y-m-d', $dueStr)
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay());
            $accruingTotal += $days * $issue->fine_per_day;
        }

        return [
            'unpaid_count'   => $unpaidQ->count(),
            'unpaid_total'   => $unpaidQ->sum('fine_amount'),
            'accruing_count' => $accruingQ->count(),
            'accruing_total' => $accruingTotal,
            'paid_total'     => self::where('fine_status', 'paid')->where('is_delete', 0)->sum('fine_amount'),
            'waived_total'   => self::where('fine_status', 'waived')->where('is_delete', 0)->sum('fine_amount'),
        ];
    }

    // ── Accessors ──────────────────────────────────────────────────

    // Live fine: for overdue = days × rate; for returned = stored amount
    public function getLiveFineAttribute(): float
    {
        if ($this->status === 'overdue' && $this->fine_per_day > 0) {
            $dueStr = self::safeDate($this->due_date);
            $days   = Carbon::createFromFormat('Y-m-d', $dueStr)
                            ->startOfDay()
                            ->diffInDays(now()->startOfDay());
            return (float) ($days * $this->fine_per_day);
        }
        return (float) ($this->fine_amount ?? 0);
    }

    // Formatted due date string — always YYYY-MM-DD, no time
    public function getDueDateStrAttribute(): string
    {
        return self::safeDate($this->due_date);
    }

    public function getIssueDateStrAttribute(): string
    {
        return self::safeDate($this->issue_date);
    }

    public function getReturnDateStrAttribute(): ?string
    {
        return $this->return_date ? self::safeDate($this->return_date) : null;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'returned' => '<span class="badge bg-success">Returned</span>',
            'overdue'  => '<span class="badge bg-danger">Overdue</span>',
            default    => '<span class="badge bg-primary">Issued</span>',
        };
    }

    public function getFineStatusBadgeAttribute(): string
    {
        return match($this->fine_status ?? 'none') {
            'unpaid' => '<span class="badge bg-danger">Unpaid</span>',
            'paid'   => '<span class="badge bg-success">Paid</span>',
            'waived' => '<span class="badge bg-secondary">Waived</span>',
            default  => '<span class="badge bg-light text-dark border">No Fine</span>',
        };
    }
}