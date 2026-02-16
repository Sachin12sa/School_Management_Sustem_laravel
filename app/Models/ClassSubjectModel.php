<?php

namespace App\Models;

use App\Models\ClassSubjectModel;
use Illuminate\Database\Eloquent\Model;

class ClassSubjectModel extends Model
{
    protected $table = 'class_subjects';

    protected $fillable = [
        'class_id',
        'subject_id',
        'status',
        'created_by',
    ];
    static public function getAssignSubjectID($class_id){
        return self::where('class_id','=',$class_id)->where('is_delete','=',0)->get();
    }

    public static function getRecord()
    {
        $return = self::select(
                'class_subjects.*',
                'classes.name as class_name',
                'subjects.name as subject_name',
                'users.name as created_by_name',
                'subjects.type as subject_type'

            )
            ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
            ->join('classes', 'classes.id', '=', 'class_subjects.class_id')
            ->join('users', 'users.id', '=', 'class_subjects.created_by')
            ->where('class_subjects.is_delete','=',0);
            if (request('class_name')) {
                $return->where('classes.name', 'like', '%' . request('class_name') . '%');
            }
            if (request('Subject_name')) {
                $return->where('subjects.name', 'like', '%' . request('Subject_name') . '%');
            }
             if (request('date')) {
                $return->whereDate('classes.created_at', request('date'));
            }

            $return=$return->orderBy('class_subjects.id', 'desc')
            ->paginate(10);
            return $return;
    }
    // getting subject for the student
    static public function mySubject($class_id)
    {
        return self::select(
                'class_subjects.*',
                'subjects.name as subject_name',
                'subjects.type as subject_type'
            )
            ->join('subjects', 'subjects.id', '=', 'class_subjects.subject_id')
            ->join('classes', 'classes.id', '=', 'class_subjects.class_id')
            ->join('users', 'users.id', '=', 'class_subjects.created_by')
            ->where('class_subjects.class_id','=',$class_id)
            ->where('class_subjects.is_delete','=',0)
            ->where('class_subjects.status','=',0)
            ->orderBy('class_subjects.id', 'desc')
            ->get();
    }
     static public function getSingle($id){
        return ClassSubjectModel::find($id);
    }
    static public function deleteSubject($class_id){
        return self::where('class_id','=',$class_id)->delete();
    }
}
