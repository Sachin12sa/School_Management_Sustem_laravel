<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClassTeacherModel extends Model
{
    use HasFactory;

      protected  $table = 'assign_class_teachers';
       protected $fillable = [
        'class_id',
        'teacher_id',
        'status',
        'created_by',
    ];
    public static function getRecord()
        {
            $query = self::select(
                    'assign_class_teachers.*',
                    'teacher.name as teacher_name',
                    'teacher.last_name as teacher_last_name',
                    'classes.name as class_name',
                    'users.name as created_by_name'
                )
                ->join('users as teacher', 'teacher.id', '=', 'assign_class_teachers.teacher_id')
                ->join('classes', 'classes.id', '=', 'assign_class_teachers.class_id')
                ->join('users', 'users.id', '=', 'assign_class_teachers.created_by')
                ->where('assign_class_teachers.is_delete', 0);

            if (request()->filled('class_name')) {
                $query->where('classes.name', 'like', '%' . request('class_name') . '%');
            }

            if (request()->filled('teacher_name') || request()->filled('teacher_last_name')) {
                    $query->where(function($q) {
                        if (request()->filled('teacher_name')) {
                            $q->where('teacher.name', 'like', '%' . request('teacher_name') . '%');
                        }
                        if (request()->filled('teacher_last_name')) {
                            $q->where('teacher.last_name', 'like', '%' . request('teacher_last_name') . '%');
                        }
                    });
                }


            if (request()->filled('date')) {
                $query->whereDate('assign_class_teachers.created_at', request('date'));
            }

            return $query->orderByDesc('assign_class_teachers.id')
                        ->paginate(10);
        }

    static public function AssignTeacherID($class_id){
        return self::where('class_id','=',$class_id)->where('is_delete','=',0)->get();
    }
       static public function getSingle($id){
        return self::find($id);
    }
    static public function deleteSubject($class_id){
        return self::where('class_id','=',$class_id)->delete();
    }
    // teacher side to get class and subject
    static public function getMyClassSubject($teacher_id)
    {
            return self::select(
                        'assign_class_teachers.*',
                        'classes.name as class_name',
                        'subjects.name as subject_name',
                        'subjects.type as subject_type'
                    )
                    ->join('classes', 'classes.id', '=', 'assign_class_teachers.class_id')
                    ->join('class_subjects', 'class_subjects.class_id', '=', 'classes.id')
                    ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
                    ->where('assign_class_teachers.is_delete', 0)
                    ->where('assign_class_teachers.status', 0)
                    ->where('subjects.status', 0)
                    ->where('subjects.is_delete', 0)
                     ->where('class_subjects.status', 0)
                    ->where('class_subjects.is_delete', 0)
                    ->where('assign_class_teachers.teacher_id', $teacher_id)
                    ->orderByDesc('assign_class_teachers.id')
                    ->paginate(10);

    }
}
