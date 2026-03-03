<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassModel;
use App\Models\StudentAttendanceModel;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function studentAttendance(Request $request){
        $data['getClass'] = ClassModel::getClass();
        if(!empty($request->get('class_id')) && !empty($request->get('attendance_date')))
        {
            $data['getStudent'] = User::getStudentClass($request->get('class_id'));
        }


        $data['header_title']= 'Student  Attendance';
        return view('admin.attendance.student_attendance',$data);
    }
      public function studentAttendanceSave(Request $request)
        {
            // dd($request->all()); // LOG instead of dd

            $request->validate([
                'student_id' => 'required',
                'class_id' => 'required',
                'attendance_date' => 'required|date',
                'attendance_type' => 'required'
            ]);

            StudentAttendanceModel::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'class_id' => $request->class_id,
                    'attendance_type' => $request->attendance_type,
                    'created_by' => Auth::user()->id,
                ]
            );

            $student = User::find($request->student_id);

            return response()->json([
                'message' => $student->name . ' ' . $student->last_name . ': attendance saved successfully'
            ]);
        }

        // attendanceReport
        public function attendanceReport(Request $request)
        {
            $data['getClass'] = ClassModel::getClass();
            $data['getRecord'] = StudentAttendanceModel::getRecord();
            $data['header_title']= 'Student  Attendance Report';
        return view('admin.attendance.attendance_report',$data);
        }

// For teacher  
        public function studentAttendanceTeacher(Request $request){

        $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
        // dd($data['getClass']);
        if(!empty($request->get('class_id')) && !empty($request->get('attendance_date')))
        {
            $data['getStudent'] = User::getStudentClass($request->get('class_id'));
        }


        $data['header_title']= 'Student  Attendance';
        return view('teacher.attendance.student_attendance',$data);
    }
      public function studentAttendanceSaveTeacher(Request $request)
        {
            // dd($request->all()); // LOG instead of dd

            $request->validate([
                'student_id' => 'required',
                'class_id' => 'required',
                'attendance_date' => 'required|date',
                'attendance_type' => 'required'
            ]);

            StudentAttendanceModel::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'class_id' => $request->class_id,
                    'attendance_type' => $request->attendance_type,
                    'created_by' => Auth::user()->id,
                ]
            );

            $student = User::find($request->student_id);

            return response()->json([
                'message' => $student->name . ' ' . $student->last_name . ': attendance saved successfully'
            ]);
        }

        // attendanceReport
    public function attendanceReportTeacher(Request $request)
        {
            $teacher_id = Auth::user()->id;
            $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);
            $data['getRecord'] = StudentAttendanceModel::getRecordTeacher($teacher_id);
            $data['header_title']= 'Student  Attendance Report';
        return view('teacher.attendance.attendance_report',$data);
        }

// Student sid eto show attendance
    public function studentMyAttendance(Request $request)
    {
        $student_id = Auth::user()->id;
        $data['getClass'] = StudentAttendanceModel::getMyClassStudent($student_id);
        $data['header_title']= 'My  Attendance';
        $data['getRecord'] = StudentAttendanceModel::getRecordStudent($student_id);
        return view('student.my_attendance',$data);
    }
// parent side to show attendance 

    public function parentMyAttendance($student_id)
    {
        $data['getStudent'] = User::getSingle($student_id);
        $data['getClass'] = StudentAttendanceModel::getMyClassStudent($student_id);
        $data['header_title']= 'Student  Attendance';
        $data['getRecord'] = StudentAttendanceModel::getRecordStudent($student_id);
        return view('parent.my_attendance',$data);
    }
}
