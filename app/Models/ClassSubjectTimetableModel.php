<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubjectTimetableModel extends Model
{
    protected $table = 'class_subject_timetables';
    protected $fillable = [
        'class_id',
        'subject_id',
        'section_id',
        'week_id',
        'start_time',
        'end_time',
        'room_number'
    ];
    static public function getRecordClassSubject($class_id, $section_id, $subject_id, $week_id)
{
    $query = self::where('class_id', $class_id)
        ->where('subject_id', $subject_id)
        ->where('week_id', $week_id);

    if (!empty($section_id)) {
        $query->where('section_id', $section_id);
    } else {
        $query->whereNull('section_id'); // ✅ IMPORTANT
    }

    return $query->first();
}
    public static function getAllRecordClassSubject($class_id, $subject_id)
    {      
        return self::where('class_id', $class_id)
                    ->where('subject_id', $subject_id)
                    ->get();
    }

}
