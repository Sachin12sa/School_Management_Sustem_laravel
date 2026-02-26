<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarksRegisterModel extends Model
{
    protected $table = 'marks_registers'; 
    protected $fillable = [
    'student_id', 'exam_schedule_id', 'exam_id', 'class_id', 
    'class_work', 'home_work', 'test_work', 'exam', 'created_by'
];

// app/Models/MarksRegisterModel.php

    static public function getRegisterMarks($class_id, $exam_id)
        {
            return self::where('class_id', $class_id)
                    ->where('exam_id', $exam_id) // Matches the Exam Category (e.g., 5)
                    ->get()
                    ->keyBy(function($item) {
                        // This MUST match the key we use in the Blade file
                        // In your DB, the schedule ID is now stored in 'subject_id'
                        return $item->student_id . '_' . $item->exam_schedule_id;
                    });
        }
    static public function getExam($student_id)
    {
        return self::select('marks_registers.*','exams.name as exam_name')
        ->join('exams','exams.id','=','marks_registers.exam_id')
        ->where('marks_registers.student_id','=',$student_id)
        ->groupBy('marks_registers.exam_id')
        ->get();

    }
    static public function getExamSubject($exam_id,$student_id)
    {
        return self::select('marks_registers.*','exams.name as exam_name','subjects.name as subject_name')
        ->join('exams','exams.id','=','marks_registers.exam_id')
        ->join('exam_schedules', 'exam_schedules.id', '=', 'marks_registers.exam_schedule_id')
        ->join('subjects', 'subjects.id', '=', 'exam_schedules.subject_id')
        ->where('marks_registers.exam_id','=',$exam_id)
        ->where('marks_registers.student_id','=',$student_id)
        ->get();

    }
}
