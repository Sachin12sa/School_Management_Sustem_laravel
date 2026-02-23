<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\ClassSubjectTimetableModel;
use App\Models\ExamScheduleModel;
use App\Models\User;
use App\Models\WeekModel;
use Auth;
use Illuminate\Http\Request;

class CalenderController extends Controller
{
    // for student timetable
    public function MyCalender()
    {
        $data['getTimetable'] = $this ->getTimetable(Auth::user()->class_id);
        $data['getExamTimetable'] = $this ->getExamTimetable(Auth::user()->class_id);
        // dd($data['getExamTimetable']);
        $data['header_title']= 'My Calender';
        return view('student.my_calender',$data);
    }
    
    public function getExamTimetable($class_id)
    {
        $getExam = ExamScheduleModel::getExam($class_id);
        $result = array();
        foreach($getExam as $value)
            {
                $dataE = array();
                $resultS = array();
                
                $dataE['name'] = $value->exam_name;
                $getExamTimetable = ExamScheduleModel::getExamTimetable($value->exam_id,$class_id);
                foreach($getExamTimetable as $value)
                    {
                        $dataS = array();
                        $dataS['subject_name'] = $value->subject_name;
                        $dataS['exam_date'] = $value->exam_date;
                        $dataS['start_time'] = $value->start_time;
                        $dataS['end_time'] = $value->end_time;
                        $dataS['room_number'] = $value->room_number;
                        $dataS['full_mark'] = $value->full_mark;
                        $dataS['passing_mark'] = $value->passing_mark;
                        $resultS [ ]= $dataS;

                    }
                    $dataE['exam'] = $resultS;
                    $result[]=$dataE;

            }
            return $result;

    }
    public function getTimetable($class_id)
    {
        $result = [];

        $getRecord = ClassSubjectModel::mySubject($class_id);

        foreach ($getRecord as $subject) {

            $data = [];
            $data['subject_name'] = $subject->subject_name;

            $weeks = [];
            $getWeek = WeekModel::getRecord();

            foreach ($getWeek as $week) {

                $dataW = [];
                $dataW['week_id'] = $week->id;
                $dataW['week_name'] = $week->name;
                $dataW['fullcalender_day'] = $week->fullcalender_day;

                $classSubject = ClassSubjectTimetableModel::getRecordClassSubject(
                    $subject->class_id,
                    $subject->subject_id,
                    $week->id
                );

                if (!empty($classSubject)) {
                    $dataW['start_time']  = $classSubject->start_time;
                    $dataW['end_time']    = $classSubject->end_time;
                    $dataW['room_number'] = $classSubject->room_number;
                    $weeks[] = $dataW;
                } 
            }

            $data['week'] = $weeks;
            $result[] = $data;
        }

        return $result;
    }
    // parent calendar 
    public function MyParentCalendar($student_id)
    {
        $getStudent = User::getSingle($student_id);
        $studentClass = ClassModel::find($getStudent->class_id);
        $data['student_class_name'] = $studentClass ? $studentClass->name : 'N/A';
        $data['getTimetable'] = $this ->getTimetable($getStudent->class_id);
        // dd($data['getTimetable']);
        $data['getExamTimetable'] = $this ->getExamTimetable($getStudent->class_id);
        $data['getStudent'] = $getStudent;
        $data['header_title']= 'Student Calender';
        return view('parent.my_calender',$data);
    }
    // teacher side 
    public function MyTeacherCalendar()
    {
        
        $teacher_id = Auth::user()->id;
        $data['getClassTimetable'] = AssignClassTeacherModel::getCalendarTeacher($teacher_id);
        $data['getExamTimetableTeacher'] = ExamScheduleModel::getExamTimetableTeacher($teacher_id);
        // dd($data['getExamTimetableTeacher']);
        $data['header_title']= 'My Calender';
        return view('teacher.my_calender',$data);
    }
}
