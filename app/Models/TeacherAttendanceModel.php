<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendanceModel extends Model
{
    protected $table = 'teacher_attendances';

    protected $fillable = [
        'teacher_id',
        'attendance_date',
        'attendance_type',
        'created_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Return the attendance label for a type integer.
     */
    public static function typeLabel(int $type): string
    {
        return match ($type) {
            1 => 'Present',
            2 => 'Absent',
            3 => 'Late',
            4 => 'Half Day',
            default => 'Unknown',
        };
    }

    /**
     * Fetch a single attendance record for a teacher on a date.
     */
    public static function getAttendance(int $teacherId, string $date): ?self
    {
        return static::where('teacher_id', $teacherId)
                     ->where('attendance_date', $date)
                     ->first();
    }
}