<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClassTeacherModel extends Model
{
    use HasFactory;

      protected  $table = 'assign_class_teacher';
       protected $fillable = [
        'class_id',
        'teacher_id',
        'status',
        'created_by',
    ];
    public static function getRecord()
        {
            $query = self::select(
                    'assign_class_teacher.*',
                    'teacher.name as teacher_name',
                    'teacher.last_name as teacher_last_name',
                    'classes.name as class_name',
                    'users.name as created_by_name'
                )
                ->join('users as teacher', 'teacher.id', '=', 'assign_class_teacher.teacher_id')
                ->join('classes', 'classes.id', '=', 'assign_class_teacher.class_id')
                ->join('users', 'users.id', '=', 'assign_class_teacher.created_by')
                ->where('assign_class_teacher.is_delete', 0);

            if (request()->filled('class_name')) {
                $query->where('classes.name', 'like', '%' . request('class_name') . '%');
            }

            if (request()->filled('teacher_name')) {
                $query->where('teacher.name', 'like', '%' . request('teacher_name') . '%');
            }

            if (request()->filled('created_by_name')) {
                $query->where('users.name', 'like', '%' . request('created_by_name') . '%');
            }

            if (request()->filled('date')) {
                $query->whereDate('assign_class_teacher.created_at', request('date'));
            }

            return $query->orderByDesc('assign_class_teacher.id')
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
}
