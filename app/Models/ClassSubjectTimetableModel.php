<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubjectTimetableModel extends Model
{
    protected $table = 'class_subject_timetables';
    protected $fillable = [
        'class_id',
        'subject_id',
        'week_id',
        'start_time',
        'end_time',
        'room_number'
    ];
    static public function getRecordClassSubject($class_id,$subject_id,$week_id)
    {
        return self::where('class_id', '=', $class_id)
                    ->where('subject_id','=', $subject_id)
                    ->where('week_id', '=', $week_id)
                    ->first();
    }
    public static function getAllRecordClassSubject($class_id, $subject_id)
    {      
        return self::where('class_id', $class_id)
                    ->where('subject_id', $subject_id)
                    ->get();
    }

}
