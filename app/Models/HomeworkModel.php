<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
class HomeworkModel extends Model
{
     protected $table = 'homeworks';

     static public function getSingle($id) {
            return self::find($id);
        }
static public function getRecord()
{
    $return = self::select('homeworks.*', 'classes.name as class_name', 'subjects.name as subject_name', 'users.name as created_by_name','users.last_name as created_by_last_name')
        ->join('users', 'users.id', '=', 'homeworks.created_by')
        ->join('classes', 'classes.id', '=', 'homeworks.class_id')
        ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id')
        ->where('homeworks.is_delete', '=', 0);

    // Search by Class Name
    if (!empty(Request::get('class_name'))) {
        $return = $return->where('classes.name', 'like', '%' . Request::get('class_name') . '%');
    }

    // Search by Subject Name
    if (!empty(Request::get('subject_name'))) {
        $return = $return->where('subjects.name', 'like', '%' . Request::get('subject_name') . '%');
    }

    // Search by Homework Date
    if (!empty(Request::get('homework_date'))) {
        $return = $return->whereDate('homeworks.homework_date', '=', Request::get('homework_date'));
    }

    // Search by Submission Date
    if (!empty(Request::get('submission_date'))) {
        $return = $return->whereDate('homeworks.submission_date', '=', Request::get('submission_date'));
    }

    $return = $return->orderBy('homeworks.id', 'desc')
                     ->paginate(10);

    return $return;
}
static public function getRecordTeacher($class_ids)
    {
        $return = self::select('homeworks.*', 'classes.name as class_name', 'subjects.name as subject_name', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'homeworks.created_by')
            ->join('classes', 'classes.id', '=', 'homeworks.class_id')
            ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id')
            ->whereIn('homeworks.class_id', $class_ids) // Filter by Teacher's classes
            ->where('homeworks.is_delete', '=', 0);

        if (!empty(Request::get('class_name'))) {
            $return = $return->where('classes.name', 'like', '%' . Request::get('class_name') . '%');
        }
        // Search by Subject Name
        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subjects.name', 'like', '%' . Request::get('subject_name') . '%');
        }

        // Search by Homework Date
        if (!empty(Request::get('homework_date'))) {
            $return = $return->whereDate('homeworks.homework_date', '=', Request::get('homework_date'));
        }

        // Search by Submission Date
        if (!empty(Request::get('submission_date'))) {
            $return = $return->whereDate('homeworks.submission_date', '=', Request::get('submission_date'));
    }

        $return = $return->orderBy('homeworks.id', 'desc')->paginate(20);
        return $return;
    }
   static public function getRecordStudent($class_id,$student_id)
{
    $return = self::select('homeworks.*', 'classes.name as class_name', 'subjects.name as subject_name', 'users.name as created_by_name')
        ->join('users', 'users.id', '=', 'homeworks.created_by')
        ->join('classes', 'classes.id', '=', 'homeworks.class_id')
        ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id')
        // Change whereIn to where if $class_id is just one number
        ->where('homeworks.class_id', '=', $class_id) 
         ->whereNotIn('homeworks.id', function ($query) use($student_id) {
        $query->select('homework_submits.homework_id')->from('homework_submits')
            ->where('homework_submits.student_id','=',$student_id);})
        ->where('homeworks.is_delete', '=', 0);

    // Search by Subject Name
    if (!empty(Request::get('subject_name'))) {
        $return = $return->where('subjects.name', 'like', '%' . Request::get('subject_name') . '%');
    }

    // Search by Homework Date
    if (!empty(Request::get('homework_date'))) {
        $return = $return->whereDate('homeworks.homework_date', '=', Request::get('homework_date'));
    }

    // Search by Submission Date
    if (!empty(Request::get('submission_date'))) {
        $return = $return->whereDate('homeworks.submission_date', '=', Request::get('submission_date'));
    }

    $return = $return->orderBy('homeworks.id', 'desc')->paginate(20);
    return $return;
}
public function getHomework()
    {
        return $this->belongsTo(HomeworkSubmitModel::class,'homework_id');
    }

    // get total home work for dashboard
    static public function getTotalHomework()
        {
            return self::select('homeworks.id') 
                ->where('homeworks.is_delete','=',0)
                ->where('homeworks.submission_date', '>=', date('Y-m-d')) 
                ->count();
        }
        static public function getRecordDashboard()
        {
            return self::select('homeworks.*', 'classes.name as class_name', 'subjects.name as subject_name', 'users.name as created_by_name','users.last_name as created_by_last_name')
                ->join('users', 'users.id', '=', 'homeworks.created_by')
                ->join('classes', 'classes.id', '=', 'homeworks.class_id')
                ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id')
                ->where('homeworks.submission_date', '>=', date('Y-m-d'))
                ->where('homeworks.is_delete', '=', 0)
                ->orderBy('homeworks.id', 'desc')
                ->get();
        }
}
