<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\ClassSubjectTimetableModel;
use App\Models\Subject;
use App\Models\User;
use App\Models\WeekModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassTimeTableController extends Controller
{
    public function list(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getSubject'] = [];

        if (!empty($request->class_id)) {
            $data['getSubject'] = ClassSubjectModel::mySubject($request->class_id);
        }

        $getWeek = WeekModel::getRecord();
        $week = [];

        foreach ($getWeek as $value) {

            $row = [];  // ✅ use different variable
            $row['id'] = $value->id;
            $row['week_name'] = $value->name;

            if (!empty($request->class_id) && !empty($request->subject_id)) {

                $ClassSubject = ClassSubjectTimetableModel::getRecordClassSubject(
                    $request->class_id,
                    $request->subject_id,
                    $value->id
                );

                if (!empty($ClassSubject)) {
                    $row['start_time'] = $ClassSubject->start_time;
                    $row['end_time'] = $ClassSubject->end_time;
                    $row['room_number'] = $ClassSubject->room_number;
                } else {
                    $row['start_time'] = '';
                    $row['end_time'] = '';
                    $row['room_number'] = '';
                }
            } else {
                $row['start_time'] = '';
                $row['end_time'] = '';
                $row['room_number'] = '';
            }

            $week[] = $row;
        }

        $data['week'] = $week;
        $data['getRecord'] = ClassSubjectModel::getRecord();
        $data['header_title'] = 'Class TimeTable';

        return view('admin.class_timetable.list', $data);
    }

    public function get_subject(Request $request)
    {
        // Temporary debug: This will show up in your Browser's Network Tab
        // dd($request->all()); 

        $subjects = ClassSubjectModel::mySubject($request->class_id);

        $html = '<option value="">Select</option>';
        foreach ($subjects as $subject) {
            $html .= '<option value="'.$subject->subject_id.'">'.$subject->subject_name.'</option>';
        }

        return response()->json(['html' => $html]);
    }


public function insert_update(Request $request)
{
    if (!empty($request->timetable)) {

        foreach ($request->timetable as $timetable) {

            if (
                !empty($timetable['week_id']) &&
                !empty($timetable['start_time']) &&
                !empty($timetable['end_time']) &&
                !empty($timetable['room_number'])
            ) {

                ClassSubjectTimetableModel::updateOrCreate(
                    [
                        'class_id'   => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'week_id'    => $timetable['week_id'],
                    ],
                    [
                        'start_time'  => $timetable['start_time'],
                        'end_time'    => $timetable['end_time'],
                        'room_number' => $timetable['room_number'],
                    ]
                );
            }
        }
    }

    return redirect()->back()->with('success', 'Class TimeTable Successfully Saved');
}

// Student side myTimetable
   public function myTimetable()
        {
            $result = [];

            $getRecord = ClassSubjectModel::mySubject(Auth::user()->class_id);

            foreach ($getRecord as $subject) {

                $data = []; // reset for each subject
                $data['subject_name'] = $subject->subject_name;

                $weeks = [];
                $getWeek = WeekModel::getRecord();

                foreach ($getWeek as $week) {

                    $dataW = [];
                    $dataW['week_name'] = $week->name;

                    $classSubject = ClassSubjectTimetableModel::getRecordClassSubject(
                        $subject->class_id,
                        $subject->subject_id,
                        $week->id
                    );

                    if (!empty($classSubject)) {
                        $dataW['start_time']  = $classSubject->start_time;
                        $dataW['end_time']    = $classSubject->end_time;
                        $dataW['room_number'] = $classSubject->room_number;
                    } else {
                        $dataW['start_time']  = '';
                        $dataW['end_time']    = '';
                        $dataW['room_number'] = '';
                    }

                    $weeks[] = $dataW;
                }

                $data['week'] = $weeks; // attach weeks to subject
                $result[] = $data;
            }
            
            $data['getRecord'] = $result;
            $data['header_title'] = 'My TimeTable';

            return view('student.my_timetable', $data);
        }

// teacher side mytimetable myTimetableTeacher
    public function myTimetableTeacher($class_id, $subject_id)
        {
            $data['getClass'] = ClassModel::getSingle($class_id);
            $data['getSubject'] = Subject::getSingle($subject_id);

            $weeks = WeekModel::getRecord();

            // Get all timetable records for this class & subject
            $timetable = ClassSubjectTimetableModel::getAllRecordClassSubject($class_id, $subject_id);

            $result = [];

            foreach ($weeks as $week) {

                $dataW = [];
                $dataW['week_name'] = $week->name;

                // Find matching timetable record for this week
                $match = $timetable->where('week_id', $week->id)->first();

                if ($match) {
                    $dataW['start_time']  = $match->start_time;
                    $dataW['end_time']    = $match->end_time;
                    $dataW['room_number'] = $match->room_number;
                } else {
                    $dataW['start_time']  = '';
                    $dataW['end_time']    = '';
                    $dataW['room_number'] = '';
                }

                $result[] = $dataW;
            }

            $data['weeks'] = $result;
            $data['header_title'] = 'My TimeTable';

            return view('teacher.my_timetable', $data);
        }

        // parent side to see child timetable 
        public function myTimetableParent($class_id, $subject_id, $student_id)
        {
            $user = User::getSingle($student_id);
            $data['getUser']=$user;
            $data['getClass'] = ClassModel::getSingle($class_id);
            $data['getSubject'] = Subject::getSingle($subject_id);

            $weeks = WeekModel::getRecord();

            // Get all timetable records for this class & subject
            $timetable = ClassSubjectTimetableModel::getAllRecordClassSubject($class_id, $subject_id);

            $result = [];

            foreach ($weeks as $week) {

                $dataW = [];
                $dataW['week_name'] = $week->name;

                // Find matching timetable record for this week
                $match = $timetable->where('week_id', $week->id)->first();

                if ($match) {
                    $dataW['start_time']  = $match->start_time;
                    $dataW['end_time']    = $match->end_time;
                    $dataW['room_number'] = $match->room_number;
                } else {
                    $dataW['start_time']  = '';
                    $dataW['end_time']    = '';
                    $dataW['room_number'] = '';
                }

                $result[] = $dataW;
            }

            $data['weeks'] = $result;
            $data['header_title'] = 'My TimeTable';

            return view('parent.my_timetable', $data);
        }

}