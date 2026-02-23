<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamScheduleModel extends Model
{
    protected $table = 'exam_schedules';
    protected $fillable = [
        'exam_id',
        'class_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'room_number',
        'full_mark',
        'passing_mark',
        'created_by',
    ];
    
    static public function getRecordingSingle($exam_id,$class_id,$subject_id)

    {
        return self::where('exam_id', $exam_id)
           ->where('class_id', $class_id)
           ->where('subject_id', $subject_id)
           ->first();

    }

    static public function getExam($class_id)

    {
        return self::select('exam_schedules.*','exams.name as exam_name')
        ->join('exams','exams.id','=','exam_schedules.exam_id')
        ->where('exam_schedules.class_id','=',$class_id)
        ->groupBy('exam_schedules.exam_id')
        ->orderBy('exam_schedules.id','desc')
        ->get();
    }
    // student 
    static public function getExamTimetable($exam_id,$class_id)
    {
        return self::select('exam_schedules.*','subjects.name as subject_name','subjects.type as subject_type')
        ->join('subjects','subjects.id','=','exam_schedules.subject_id')
        ->where('exam_schedules.exam_id','=',$exam_id)
        ->where('exam_schedules.class_id','=',$class_id)
        ->get();
    }

    // teacher 
    static public function getExamTimetableTeacher($teacher_id)
    {
        return self::select('exam_schedules.*','classes.name as class_name','subjects.name as subject_name','exams.name as exam_name')
        ->join('assign_class_teachers','assign_class_teachers.class_id','=','exam_schedules.class_id')
        ->join('classes','classes.id','=','exam_schedules.class_id')
        ->join('subjects','subjects.id','=','exam_schedules.subject_id')
        ->join('exams','exams.id','=','exam_schedules.exam_id')
        ->where('assign_class_teachers.teacher_id','=',$teacher_id)

        ->get();
    }
}