<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
class StudentAttendanceModel extends Model
{
    protected $table = 'student_attendances'; 
    protected $fillable = [
        'student_id',
        'class_id',
        'attendance_date',
        'attendance_type',
        'created_by'
    ];

    static public function CheckAlreadyAttendance($student_id,$class_id,$attendance_date)
    {
        return self::where('student_id','=',$student_id)
                    ->where('class_id','=',$class_id)
                    ->where('attendance_date','=',$attendance_date)
                    ->first();
    }

    static public function getRecord()
    {
        $return = self::select('student_attendances.*','classes.name as class_name','student.name as student_name','student.last_name as student_last_name','created_by.name as created_name','created_by.last_name as created_last_name')
                    ->join('classes','classes.id','=','student_attendances.class_id')
                    ->join('users as student','student.id','=','student_attendances.student_id')
                    ->join('users as created_by','created_by.id','=','student_attendances.created_by');
                    if(!empty(Request::get('class_id')))
                        {
                            $return = $return->where('student_attendances.class_id','=', Request::get('class_id'));
                        }
                    if (!empty(Request::get('student_name'))) {
                            $search = Request::get('student_name');

                            $return = $return->where(function ($query) use ($search) {
                                $query->where('student.name', 'like', '%' . $search . '%')
                                    ->orWhere('student.last_name', 'like', '%' . $search . '%');
                            });
                        }
                    if(!empty(Request::get('start_attendance_date')))
                        {
                            $return = $return->where('student_attendances.attendance_date','>=', Request::get('start_attendance_date'));
                        }
                    if(!empty(Request::get('end_attendance_date')))
                        {
                            $return = $return->where('student_attendances.attendance_date','<=', Request::get('end_attendance_date'));
                        }    
                    if(!empty(Request::get('attendance_type')))
                        {
                            $return = $return->where('student_attendances.attendance_type','=', Request::get('attendance_type'));
                        }
        $return = $return->orderBy('student_attendances.id','desc')
                    ->paginate(10);

              return $return;      
    }

    // for teacher 

     static public function getRecordTeacher($teacher_id)
        {
            $return = self::select('student_attendances.*','classes.name as class_name','student.name as student_name','student.last_name as student_last_name','created_by.name as created_name','created_by.last_name as created_last_name')
                    ->join('classes','classes.id','=','student_attendances.class_id')
                    ->join('assign_class_teachers', 'assign_class_teachers.class_id', '=', 'student_attendances.class_id')
                    ->join('users as student','student.id','=','student_attendances.student_id')
                    ->join('users as created_by','created_by.id','=','student_attendances.created_by')
                    ->where('assign_class_teachers.teacher_id', '=', $teacher_id)
                    ->where('assign_class_teachers.is_delete', '=', 0)
                    ->where('assign_class_teachers.status', '=', 0);
                    if(!empty(Request::get('class_id')))
                        {
                            $return = $return->where('student_attendances.class_id','=', Request::get('class_id'));
                        }
                    if (!empty(Request::get('student_name'))) {
                            $search = Request::get('student_name');

                            $return = $return->where(function ($query) use ($search) {
                                $query->where('student.name', 'like', '%' . $search . '%')
                                    ->orWhere('student.last_name', 'like', '%' . $search . '%');
                            });
                        }
                    if(!empty(Request::get('attendance_date')))
                        {
                            $return = $return->where('student_attendances.attendance_date','=', Request::get('attendance_date'));
                        }
                    if(!empty(Request::get('attendance_type')))
                        {
                            $return = $return->where('student_attendances.attendance_type','=', Request::get('attendance_type'));
                        }
        $return = $return->orderBy('student_attendances.id','desc')
                    ->paginate(15);

             return $return; 
                
              
                
        }
    // student side
    static public function getRecordStudent($student_id)
        {
            $return = self::select('student_attendances.*','classes.name as class_name','student.name as student_name','student.last_name as student_last_name')
                    ->join('classes','classes.id','=','student_attendances.class_id')
                    ->join('users as student','student.id','=','student_attendances.student_id')
                    ->where('student_attendances.student_id','=',$student_id);
                    if(!empty(Request::get('attendance_date')))
                        {
                            $return = $return->where('student_attendances.attendance_date','=', Request::get('attendance_date'));
                        }
                    if(!empty(Request::get('attendance_type')))
                        {
                            $return = $return->where('student_attendances.attendance_type','=', Request::get('attendance_type'));
                        }
                   $return = $return ->orderBy('student_attendances.attendance_date','desc')
                    ->paginate(15);

             return $return;     
        }
    static public function getMyClassStudent($student_id)
    {
        return self::select('student_attendances.*','classes.name as class_name')
        ->join('classes','class_id','=','student_attendances.class_id')
        ->where('student_attendances.student_id','=',$student_id)
        ->groupBy('student_attendances.class_id')
        ->get();
    }    
    
}
