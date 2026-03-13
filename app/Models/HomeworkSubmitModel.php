<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Auth;
class HomeworkSubmitModel extends Model
{
    protected $table = 'homework_submits';

    static public function getRecord($homework_id)
    {
        $return = self::select('homework_submits.*','users.name as student_name','users.last_name as student_last_name')
        ->join('users', 'users.id', '=', 'homework_submits.student_id')
        ->where('homework_submits.homework_id', '=', $homework_id);
        if (!empty(Request::get('student_name'))) {
        $return = $return->where('users.name', 'like', '%' . Request::get('student_name') . '%');
        }
       
        // Search by Submission Date
        if (!empty(Request::get('submission_date'))) {
        $return = $return->whereDate('homeworks.submission_date', '=', Request::get('submission_date'));
    }
         $return = $return->orderBy('homework_submits.id', 'desc')
                     ->paginate(10);
                     return $return; 
    }
    // to get submitted homework for teacher
   static public function getRecordTeacher($class_ids)
        {
            $teacher_id = Auth::id();

            $return = self::select(
                    'homework_submits.*',
                    'users.name as student_name',
                    'users.last_name as student_last_name'
                )
                ->join('users', 'users.id', '=', 'homework_submits.student_id')
                ->join('homeworks', 'homeworks.id', '=', 'homework_submits.homework_id')
                ->join('assign_class_teachers', 'assign_class_teachers.class_id', '=', 'homeworks.class_id')

                ->where('assign_class_teachers.teacher_id', $teacher_id)
                ->whereIn('homeworks.class_id', $class_ids);

            // Search student
            if (!empty(Request::get('student_name'))) {
                $return = $return->where('users.name', 'like', '%' . Request::get('student_name') . '%');
            }

            // Search by submission date
            if (!empty(Request::get('submission_date'))) {
                $return = $return->whereDate('homework_submits.created_at', Request::get('submission_date'));
            }

            $return = $return->orderBy('homework_submits.id', 'desc')
                            ->paginate(10);

            return $return;
        }
    static public function getRecordStudent($student_id)
        {
            $return = self::select('homework_submits.*', 'classes.name as class_name', 'subjects.name as subject_name', 'users.name as created_by_name', 'users.last_name as created_by_last_name')
                ->join('homeworks', 'homeworks.id', '=', 'homework_submits.homework_id')
                ->join('users', 'users.id', '=', 'homeworks.created_by')
                ->join('classes', 'classes.id', '=', 'homeworks.class_id')
                ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id')
                ->where('homework_submits.student_id', '=', $student_id) // Filter by the specific student
                ->where('homeworks.is_delete', '=', 0); // Ensure assignment isn't deleted

            // Search by Subject Name
            if (!empty(Request::get('subject_name'))) {
                $return = $return->where('subjects.name', 'like', '%' . Request::get('subject_name') . '%');
            }

            // Search by Homework Date
            if (!empty(Request::get('from_homework_date'))) {
                $return = $return->where('homeworks.homework_date', '>=', Request::get('from_homework_date'));
            }

            // Search by Submission Date
            if (!empty(Request::get('from_submission_date'))) {
                $return = $return->where('homeworks.submission_date', '>=', Request::get('from_submission_date'));
            }

            // Finalize the query
            $return = $return->orderBy('homework_submits.id', 'desc')
                            ->paginate(10);

            return $return;
        }
  static public function getHomeworkReport()
        {
            $return = self::select(
                    'homework_submits.*',
                    'classes.name as class_name',
                    'subjects.name as subject_name',
                    'users.name as first_name',
                    'users.last_name as last_name'
                )
                ->join('homeworks', 'homeworks.id', '=', 'homework_submits.homework_id')
                ->join('users', 'users.id', '=', 'homework_submits.student_id')
                ->join('classes', 'classes.id', '=', 'homeworks.class_id')
                ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id');

            // Search by Student Name
            if (!empty(Request::get('student_name'))) {
                $search = Request::get('student_name');

                $return = $return->where(function ($query) use ($search) {
                    $query->where('users.name', 'like', '%' . $search . '%')
                        ->orWhere('users.last_name', 'like', '%' . $search . '%');
                });
            }
            if (!empty(Request::get('class_id'))) {
                    $return = $return->where('homeworks.class_id', Request::get('class_id'));
                }

            // Search by Subject Name
            if (!empty(Request::get('subject_name'))) {
                $return = $return->where('subjects.name', 'like', '%' . Request::get('subject_name') . '%');
            }

            // Search by Homework Date
            if (!empty(Request::get('from_homework_date'))) {
                $return = $return->where('homeworks.homework_date', '>=', Request::get('from_homework_date'));
            }

            // Search by Submission Date
            if (!empty(Request::get('from_submission_date'))) {
                $return = $return->where('homeworks.submission_date', '>=', Request::get('from_submission_date'));
            }

            $return = $return->orderBy('homework_submits.id', 'desc')
                            ->paginate(10);

            return $return;
        }

     static public function getHomeworkReportTeacher($class_id)
    {
        $return = self::select('homework_submits.*', 'classes.name as class_name', 'subjects.name as subject_name', 'users.name as created_by_name','users.last_name as created_by_last_name')
           ->join('homeworks', 'homeworks.id', '=', 'homework_submits.homework_id')
            ->join('users', 'users.id', '=', 'homework_submits.student_id')
            ->join('classes', 'classes.id', '=', 'homeworks.class_id')
            ->join('subjects', 'subjects.id', '=', 'homeworks.subject_id')
            ->whereIn('homeworks.class_id', $class_id) // Filter by Teacher's classes
            ->where('homeworks.is_delete', '=', 0);

        if (!empty(Request::get('created_by_name'))) {
            $return = $return->where('users.name', 'like', '%' . Request::get('created_by_name') . '%');
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
        return $this->belongsTo(HomeworkModel::class,'homework_id');
    }
}
