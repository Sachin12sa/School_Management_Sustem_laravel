<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\WeekModel;
use App\Models\ClassSubjectTimetableModel;
use Illuminate\Http\Request;

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



}
