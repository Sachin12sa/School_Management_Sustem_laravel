<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPromotionModel extends Model
{
    protected $table = 'student_promotions';

    protected $fillable = [
        'from_session_id',
        'to_session_id',
        'student_id',
        'new_student_id',
        'from_class_id',
        'to_class_id',
        'from_section_id',
        'to_section_id',
        'result',
        'remarks',
        'is_confirmed',
        'promoted_by',
    ];

    /*──────────────────────────────────────────────────────────────────────────
     | RELATIONSHIPS
     ──────────────────────────────────────────────────────────────────────────*/

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function newStudent()
    {
        return $this->belongsTo(User::class, 'new_student_id');
    }

    public function fromClass()
    {
        return $this->belongsTo(ClassModel::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(ClassModel::class, 'to_class_id');
    }

    public function fromSession()
    {
        return $this->belongsTo(AcademicSessionModel::class, 'from_session_id');
    }

    public function toSession()
    {
        return $this->belongsTo(AcademicSessionModel::class, 'to_session_id');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC QUERY METHODS
     ──────────────────────────────────────────────────────────────────────────*/

    /** Paginated promotion log for review page. */
    static public function getRecord($from_session_id, $to_session_id, $classId = null)
    {
        $return = self::select(
                'student_promotions.*',
                'u.name as student_name',
                'u.last_name as student_last_name',
                'u.admission_number',
                'fc.name as from_class_name',
                'tc.name as to_class_name',
                'fs.name as from_section_name',
                'ts.name as to_section_name'
            )
            ->join('users as u',                'u.id',   '=', 'student_promotions.student_id')
            ->join('classes as fc',             'fc.id',  '=', 'student_promotions.from_class_id')
            ->leftJoin('classes as tc',         'tc.id',  '=', 'student_promotions.to_class_id')
            ->leftJoin('class_sections as fs',  'fs.id',  '=', 'student_promotions.from_section_id')
            ->leftJoin('class_sections as ts',  'ts.id',  '=', 'student_promotions.to_section_id')
            ->where('student_promotions.from_session_id', $from_session_id)
            ->where('student_promotions.to_session_id',   $to_session_id)
            ->when($classId, fn($q) => $q->where('from_class_id', $classId));

        if (request('result')) {
            $return->where('student_promotions.result', request('result'));
        }
        if (request('from_class_id')) {
            $return->where('student_promotions.from_class_id', request('from_class_id'));
        }
        if (request('name')) {
            $return->where('u.name', 'like', '%' . request('name') . '%');
        }

        return $return->orderBy('student_promotions.id', 'asc')->paginate(20);
    }

    /** Check if a promotion batch has already run. */
    static public function batchExists($from_session_id, $to_session_id)
    {
        return self::where('from_session_id', $from_session_id)
            ->where('to_session_id',   $to_session_id)
            ->exists();
    }

    /** Summary counts — promoted / failed / graduated for a batch. */
    static public function getSummary($from_session_id, $to_session_id)
    {
        return self::selectRaw('result, COUNT(*) as total')
            ->where('from_session_id', $from_session_id)
            ->where('to_session_id',   $to_session_id)
            ->groupBy('result')
            ->pluck('total', 'result');
    }
}