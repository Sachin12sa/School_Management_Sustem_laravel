<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title', 'author', 'isbn', 'publisher', 'edition', 'publish_year',
        'category', 'rack_number', 'quantity', 'available',
        'description', 'cover_image', 'status', 'created_by',
    ];

    // ── Relationships ─────────────────────────────────────────────
    public function issues()
    {
        return $this->hasMany(BookIssue::class, 'book_id');
    }

    public function activeIssues()
    {
        return $this->hasMany(BookIssue::class, 'book_id')->where('status', 'issued');
    }

    // ── Query methods ─────────────────────────────────────────────
    public static function getRecord($filters = [])
    {
        $query = self::where('is_delete', 0);

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }
        if (!empty($filters['author'])) {
            $query->where('author', 'like', '%' . $filters['author'] . '%');
        }
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('title')->paginate(15);
    }

    public static function getAvailable()
    {
        return self::where('is_delete', 0)
                   ->where('status', 1)
                   ->where('available', '>', 0)
                   ->orderBy('title')
                   ->get();
    }

    public static function getSingle($id)
    {
        return self::find($id);
    }

    public static function getCategories()
    {
        return self::where('is_delete', 0)
                   ->whereNotNull('category')
                   ->distinct()
                   ->pluck('category')
                   ->sort()
                   ->values();
    }

    // ── Dashboard summary ─────────────────────────────────────────
    public static function getSummary()
    {
        
        return [
            'total_books'     => self::where('is_delete', 0)->count(),
            'available_books' => self::where('is_delete', 0)->where('available', '>', 0)->count(),
            'total_issued'    => BookIssue::where('status', 'issued')->where('is_delete', 0)->count(),
            'overdue'         => BookIssue::where('status', 'issued')
                                          ->where('due_date', '<', now()->toDateString())
                                          ->where('is_delete', 0)->count(),
        ];
    }

    // ── Accessors ─────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status == 0) {
            return '<span class="badge bg-secondary">Inactive</span>';
        }
        if ($this->available == 0) {
            return '<span class="badge bg-danger">All Issued</span>';
        }
        return '<span class="badge bg-success">' . $this->available . ' Available</span>';
    }
}