<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\ExamScheduleModel;
use App\Models\HomeworkModel;
use App\Models\NoticeBoardModel;
use App\Models\ParentStudent;
use App\Models\StudentAttendanceModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';
        $user = Auth::user();

        switch ($user->user_type) {

            // ───────────────── ADMIN DASHBOARD ─────────────────
            case 1:

                $data['TotalAdmin']   = User::getTotalUser(1);
                $data['TotalTeacher'] = User::getTotalUser(2);
                $data['TotalStudent'] = User::getTotalUser(3);
                $data['TotalParent']  = User::getTotalUser(4);
                $data['TotalClass']  = ClassModel::getTotalClass();
                $data['TotalHomework']  = HomeworkModel::getTotalHomework();
                $data['getRecentHomework']  = HomeworkModel::getRecordDashboard();
                $data['getRecentNotice']  = NoticeBoardModel::getRecordDashboard();

                

                return view('admin.dashboard', $data);


            // ───────────────── TEACHER DASHBOARD ───────────────
            case 2:
                

                // Classes assigned to teacher
                $students = User::getTeacherStudent($user->id);

                $total = $students->total(); // returns 18
                $data['TotalStudent'] = $students->total();
                

                // Subjects teacher teaches
                $data['TotalSubject'] = AssignClassTeacherModel::getMyClassSubject($user->id)->count();

                // Homework created by teacher
                $data['TotalHomework'] = HomeworkModel::where('created_by', $user->id)->count();

                // Notices for teachers
                $data['TotalNotice'] = NoticeBoardModel::getRecordUser(2)->count();

                return view('teacher.dashboard', $data);


            // ───────────────── STUDENT DASHBOARD ───────────────
            case 3:
                $student_id = Auth::user()->id;

                // Fetch counts for each status
                $data['PresentCount'] = StudentAttendanceModel::where('student_id', $student_id)->where('attendance_type', 1)->count();
                $data['AbsentCount']  = StudentAttendanceModel::where('student_id', $student_id)->where('attendance_type', 2)->count();
                $data['LateCount']    = StudentAttendanceModel::where('student_id', $student_id)->where('attendance_type', 3)->count();
                $data['HalfDayCount'] = StudentAttendanceModel::where('student_id', $student_id)->where('attendance_type', 4)->count();

                // Calculate Total and Percentage
                $totalRecords = $data['PresentCount'] + $data['AbsentCount'] + $data['LateCount'] + $data['HalfDayCount'];
                
                if ($totalRecords > 0) {
                    $presentEquivalent = $data['PresentCount'] + $data['LateCount'] + ($data['HalfDayCount'] * 0.5);
                    $data['AttendancePercent'] = ($presentEquivalent / $totalRecords) * 100;
                } else {
                    $data['AttendancePercent'] = 0;
                }

                // Subjects for student class
                $data['TotalSubject'] = ClassSubjectModel::mySubject($user->class_id)->count();

                // Homework for student class
                $data['TotalHomework'] = HomeworkModel::getRecordStudent($user->class_id, $user->id)->count();
                $data['getRecentHomework'] = HomeworkModel::getRecordStudent($user->class_id, $user->id);

                // Exams for student class
                $data['TotalExam'] = ExamScheduleModel::getExam($user->class_id)->count();
                $data['getUpcomingExams'] = ExamScheduleModel::getExamTimetableDashboard($user->class_id);

                $data['getRecentNotice']  = NoticeBoardModel::getRecordDashboard();
                return view('student.dashboard', $data);


            // ───────────────── PARENT DASHBOARD ────────────────
            case 4:

                $getStudents = User::getMyStudent($user->id);

                $data['getStudents']  = $getStudents;
                $data['TotalStudent'] = $getStudents->count();

                // Notices for parents
                $data['TotalNotice'] = NoticeBoardModel::getRecordUser(4)->count();

                return view('parent.dashboard', $data);


            // ───────────────── ACCOUNTANT DASHBOARD ────────────
            case 5:

                return view('accountant.dashboard', $data);


            default:
                abort(403, 'Unauthorized');
        }
    }
}