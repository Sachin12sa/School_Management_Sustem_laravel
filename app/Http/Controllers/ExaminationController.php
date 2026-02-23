<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacherModel;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\ExamModel;
use App\Models\ExamScheduleModel;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
     public function exam_list(){
        $data['getRecord'] = ExamModel::getRecord();
         $data['totalExam'] = ExamModel::count();
        $data['header_title']= 'Exam List';
        return view('admin.examination.exam.list',$data);
    }
     public function exam_add(){
        $data['header_title']= 'Add New Exam';
        return view('admin.examination.exam.add',$data);
    }
     public function exam_insert(Request $request){
        $exam = new ExamModel;
        $exam->name = trim($request -> name);
        $exam->note = trim($request -> note);
        $exam->created_by = Auth::user()->id;
        $exam->save();
            return redirect('admin/examination/exam/list')->with('success','Exam Successfully Created');
        }

    public function exam_edit($id){
        $data['getRecord'] = ExamModel::getSingle($id);
        if(!empty($data['getRecord']))
            {
                $data['header_title']= 'Edit  Exam';
        return view('admin.examination.exam.edit',$data);
            }else{
                abort(404);
            }


    }    
    public function exam_update($id, Request $request){
       
      $user = ExamModel::getSingle($id);
        $user->name = trim($request -> name);
        $user->note = trim($request -> note);
        
        $user->save();
            return redirect('admin/examination/exam/list')->with('success','Exam Successfully updated');
        
    }
    public function exam_delete($id){
        $user = ExamModel::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/examination/exam/list')->with('success','Exam Successfully deleted');
    }

    // examination schedule 
    public function exam_schedule(Request $request)
    {
    $data['getClass'] = ClassModel::getClass();
    $data['getExam'] = ExamModel::getExam();

    $result = [];

    if (!empty($request->get('exam_id')) && !empty($request->get('class_id'))) {
        $getSubject = ClassSubjectModel::mySubject($request->get('class_id'));

        foreach ($getSubject as $value) {
            $dataS = [];
            $dataS['subject_id'] = $value->subject_id;
            $dataS['class_id'] = $value->class_id;
            $dataS['subject_name'] = $value->subject_name;
            $dataS['subject_type'] = $value->subject_type;
            $ExamSchedule = ExamScheduleModel::getRecordingSingle($request->get('exam_id'),$request->get('class_id'),$value->subject_id);
            if(!empty($ExamSchedule))
                {
                    $dataS['exam_date'] = $ExamSchedule->exam_date;
                    $dataS['start_time'] = $ExamSchedule->start_time;
                    $dataS['end_time'] = $ExamSchedule->end_time;
                    $dataS['room_number'] = $ExamSchedule->room_number;
                    $dataS['full_mark'] = $ExamSchedule->full_mark;
                    $dataS['passing_mark'] = $ExamSchedule->passing_mark;
                }
                else{
                    $dataS['exam_date'] = '';
                    $dataS['start_time'] = '';
                    $dataS['end_time'] = '';
                    $dataS['room_number'] = '';
                    $dataS['full_mark'] = '';
                    $dataS['passing_mark'] = '';
                }
            $result[] = $dataS;
        }
    }

    $data['getRecord'] = $result;

        $data['header_title']= 'Exam Schedule List';
        return view('admin.examination.exam_schedule',$data);
    }
    
    public function exam_schedule_insert(Request $request)
    {
        $exam_id = $request->exam_id;
        $class_id = $request->class_id;

        if (!empty($request->schedule)) {
            $submittedSubjects = []; 
 
            foreach ($request->schedule as $schedule) {
                if (!empty($schedule['subject_id']) &&
                    !empty($schedule['exam_date']) &&
                    !empty($schedule['start_time']) &&
                    !empty($schedule['end_time']) &&
                    !empty($schedule['room_number']) &&
                    !empty($schedule['full_mark']) &&
                    !empty($schedule['passing_mark'])) {

                    $submittedSubjects[] = $schedule['subject_id'];

                    // Update existing or create new
                    ExamScheduleModel::updateOrCreate(
                        [
                            'exam_id' => $exam_id,
                            'class_id' => $class_id,
                            'subject_id' => $schedule['subject_id'],
                        ],
                        [
                            'exam_date' => $schedule['exam_date'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                            'room_number' => $schedule['room_number'],
                            'full_mark' => $schedule['full_mark'],
                            'passing_mark' => $schedule['passing_mark'],
                            'created_by' => Auth::user()->id,
                        ]
                    );
                }
            }

          
        }

        return redirect()->back()->with('success', 'Exam Schedule Successfully Saved');
    }
// sutuent side to show the time table 

    public function myExamTimetable()
    {
        $class_id = Auth::user()->class_id;
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
            $data['getRecord'] = $result;
        $data['header_title']= 'My Exam Timetable';
        return view('student.my_exam_timetable',$data);
    }

    // teacher side work 
    public function myExamTimetableTeacher()
{ 
    $getClass = AssignClassTeacherModel::getMyClassSubjectGroup(Auth::user()->id);

    $result = [];

    foreach($getClass as $value)
    {
        $dataC = [];
        $dataC['class_name'] = $value->class_name;

        $getExam = ExamScheduleModel::getExam($value->class_id);

        $examArray = [];

        foreach($getExam as $exam)
        {
            $dataE = [];
            $dataE['exam_name'] = $exam->exam_name;

            $getExamTimetable = ExamScheduleModel::getExamTimetable(
                $exam->exam_id,
                $value->class_id
            );

            $subjectArray = [];

            foreach($getExamTimetable as $valueS)
            {
                $subjectArray[] = [
                    'subject_name' => $valueS->subject_name,
                    'exam_date' => $valueS->exam_date,
                    'start_time' => $valueS->start_time,
                    'end_time' => $valueS->end_time,
                    'room_number' => $valueS->room_number,
                    'full_mark' => $valueS->full_mark,
                    'passing_mark' => $valueS->passing_mark,
                ];
            }

            $dataE['subject'] = $subjectArray;
            $examArray[] = $dataE;   // ✅ push exam properly
        }

        $dataC['exams'] = $examArray;  // ✅ attach exams to class
        $result[] = $dataC;            // ✅ push class
    }

    $data['getRecord'] = $result;
    $data['header_title'] = 'My Exam Timetable';

    return view('teacher.my_exam_timetable', $data);
}
// parent side 

    public function ParentMyExamTimetable($student_id)
    {
        $getStudent = User::getSingle($student_id);
        $class_id = $getStudent->class_id;
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
        
        $data['getRecord'] = $result;
        $data['getStudent']= $getStudent;
        $data['header_title']= 'My Student Exam Timetable';
        return view('parent.my_exam_timetable',$data);
    }
}



