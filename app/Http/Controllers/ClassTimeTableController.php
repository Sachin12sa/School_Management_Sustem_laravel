<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\ClassSubjectModel;
use App\Models\ClassSubjectTimetableModel;
use App\Models\Subject;
use App\Models\User;
use App\Models\WeekModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassTimeTableController extends Controller
{
    // ================= ADMIN LIST / EDIT =================
    // Flow: Class → Section → Week Day → all subjects for that slot

   public function list(Request $request)
{
    $data['getClass']   = ClassModel::getClass();
    $data['getWeeks']   = WeekModel::getRecord();
    $data['getSection'] = [];
    $data['slotData']   = [];

    if (!empty($request->class_id)) {
        $data['getSection'] = ClassSectionModel::getSectionsByClass($request->class_id);
    }

    // Handle section_id = '0' (no section class) or real section
    $sectionId = (!empty($request->section_id) && $request->section_id != '0')
        ? $request->section_id
        : null;

    if (!empty($request->class_id) && !empty($request->week_id)) {
        $subjects = ClassSubjectModel::mySubject($request->class_id);

        foreach ($subjects as $subject) {
            // Build query conditionally
            $query = [
                'class_id'   => $request->class_id,
                'subject_id' => $subject->subject_id,
                'week_id'    => $request->week_id,
            ];

            if ($sectionId) {
                $query['section_id'] = $sectionId;
            }

            $record = ClassSubjectTimetableModel::where($query)->first();

            $data['slotData'][] = [
                'subject_id'   => $subject->subject_id,
                'subject_name' => $subject->subject_name,
                'start_time'   => $record->start_time  ?? '',
                'end_time'     => $record->end_time    ?? '',
                'room_number'  => $record->room_number ?? '',
            ];
        }
    }

    $data['header_title'] = 'Class Timetable';
    return view('admin.class_timetable.list', $data);
}

    // ================= AJAX: Get sections for a class =================

    public function getSections(Request $request)
{
    $sections = ClassSectionModel::getSectionsByClass($request->class_id);
    $html     = '<option value="">— Select Section —</option>';
    foreach ($sections as $sec) {
        $html .= '<option value="' . $sec->id . '">Section ' . $sec->name . '</option>';
    }
    return response()->json([
        'section_html' => $html,
        'sections'     => $sections,   // ← add this so JS knows if empty
    ]);
}

    // ================= AJAX: Get slot data (class + section + week) =================
    // Used when week day is selected — returns all subjects with existing times

    public function getSlot(Request $request)
{
     $subjects = ClassSubjectModel::mySubject($request->class_id);
    
    // TEMP DEBUG — remove after fixing
    if ($subjects->isEmpty()) {
        return response()->json([
            'success' => false, 
            'debug'   => 'No subjects found for class_id: ' . $request->class_id,
            'rows'    => []
        ]);
    }
    $rows     = [];

    foreach ($subjects as $subject) {
        // Build query — section_id 0 means no section
        $query = [
            'class_id'   => $request->class_id,
            'subject_id' => $subject->subject_id,
            'week_id'    => $request->week_id,
        ];

        // Only filter by section if a real section was chosen
        if (!empty($request->section_id) && $request->section_id != '0') {
            $query['section_id'] = $request->section_id;
        }

        $record = ClassSubjectTimetableModel::where($query)->first();

        $rows[] = [
            'subject_id'   => $subject->subject_id,
            'subject_name' => $subject->subject_name,
            'start_time'   => $record->start_time  ?? '',
            'end_time'     => $record->end_time    ?? '',
            'room_number'  => $record->room_number ?? '',
        ];
    }

    return response()->json(['success' => true, 'rows' => $rows]);
}

    // ================= AJAX: Legacy get_subject (kept for other pages) =================

    public function get_subject(Request $request)
    {
        $subjects = ClassSubjectModel::mySubject($request->class_id);
        $html     = '<option value="">— Select Subject —</option>';
        foreach ($subjects as $s) {
            $html .= '<option value="' . $s->subject_id . '">' . $s->subject_name . '</option>';
        }
        return response()->json(['subject_html' => $html]);
    }

    // ================= INSERT / UPDATE =================
    // Saves all subjects for one class + section + week day in one go

public function insert_update(Request $request)
{
    $request->validate([
        'class_id'  => 'required|integer',
        'week_id'   => 'required|integer',
        'timetable' => 'required|array',
    ]);

    // Treat '0' or empty as null for section
    $sectionId = (!empty($request->section_id) && $request->section_id != '0')
        ? $request->section_id
        : null;

    $saved = 0;
    foreach ($request->timetable as $row) {
        if (empty($row['subject_id'])) continue;


        $hasData = !empty($row['start_time'])
                || !empty($row['end_time'])
                || !empty(trim($row['room_number'] ?? ''));

        $match = [
            'class_id'   => $request->class_id,
            'section_id' => $sectionId,
            'subject_id' => $row['subject_id'],
            'week_id'    => $request->week_id,
        ];

        if ($hasData) {
            ClassSubjectTimetableModel::updateOrCreate($match, [
                'start_time'  => $row['start_time']  ?? null,
                'end_time'    => $row['end_time']    ?? null,
                'room_number' => trim($row['room_number'] ?? ''),
            ]);
            $saved++;
        } else {
            ClassSubjectTimetableModel::where($match)->delete();
        }
    }

    return redirect(url('admin/class_timetable/list') . '?' . http_build_query([
        'class_id'   => $request->class_id,
        'section_id' => $request->section_id,
        'week_id'    => $request->week_id,
    ]))->with('success', "Timetable saved — {$saved} subject(s) scheduled.");
}

    // ================= STUDENT TIMETABLE =================

    public function myTimetable()
{
    $weeks    = WeekModel::getRecord();
    $subjects = ClassSubjectModel::mySubject(Auth::user()->class_id);
    $today    = strtolower(date('l'));

    $result = [];
    foreach ($weeks as $week) {
        $slots = [];
        foreach ($subjects as $subject) {
            $record = ClassSubjectTimetableModel::where([
                'class_id'   => $subject->class_id,
                'section_id' => Auth::user()->section_id,
                'subject_id' => $subject->subject_id,
                'week_id'    => $week->id,
            ])->first();

            if ($record && $record->start_time) {
                $slots[] = [
                    'subject_name' => $subject->subject_name,
                    'start_time'   => $record->start_time,
                    'end_time'     => $record->end_time,
                    'room_number'  => $record->room_number,
                ];
            }
        }

        // Sort slots by start_time
        usort($slots, fn($a, $b) => $a['start_time'] <=> $b['start_time']);

        $result[] = [
            'week_name' => $week->name,
            'is_today'  => strtolower($week->name) === $today,
            'slots'     => $slots,
        ];
    }

    return view('student.my_timetable', [
        'getRecord'    => $result,
        'header_title' => 'My Timetable',
    ]);
}

    // ================= TEACHER TIMETABLE =================

    public function myTimetableTeacher($class_id, $section_id, $subject_id)
    {
       
        $data['getClass']   = ClassModel::getSingle($class_id);
        $data['getSection'] = ClassSectionModel::getSingle($section_id);
        $data['getSubject'] = Subject::getSingle($subject_id);

        $weeks     = WeekModel::getRecord();
        $timetable = ClassSubjectTimetableModel::where([
            'class_id'   => $class_id,
            'section_id' => $section_id,
            'subject_id' => $subject_id,
        ])->get();

        $result = [];
        foreach ($weeks as $week) {
            $row   = ['week_name' => $week->name, 'start_time' => '', 'end_time' => '', 'room_number' => ''];
            $match = $timetable->where('week_id', $week->id)->first();
            if ($match) {
                $row['start_time']  = $match->start_time;
                $row['end_time']    = $match->end_time;
                $row['room_number'] = $match->room_number;
            }
            $result[] = $row;
        }

        $data['weeks']        = $result;
        $data['header_title'] = 'My TimeTable';
        return view('teacher.my_timetable', $data);
    }

    // ================= PARENT TIMETABLE =================

    public function myTimetableParent($class_id, $subject_id, $student_id, $section_id)
    {
        $data['getUser']    = User::getSingle($student_id);
        $data['getClass']   = ClassModel::getSingle($class_id);
        $data['getSection'] = ClassSectionModel::getSingle($section_id);
        $data['getSubject'] = Subject::getSingle($subject_id);

        $weeks     = WeekModel::getRecord();
        $timetable = ClassSubjectTimetableModel::where([
            'class_id'   => $class_id,
            'section_id' => $section_id,
            'subject_id' => $subject_id,
        ])->get();

        $result = [];
        foreach ($weeks as $week) {
            $row   = ['week_name' => $week->name, 'start_time' => '', 'end_time' => '', 'room_number' => ''];
            $match = $timetable->where('week_id', $week->id)->first();
            if ($match) {
                $row['start_time']  = $match->start_time;
                $row['end_time']    = $match->end_time;
                $row['room_number'] = $match->room_number;
            }
            $result[] = $row;
        }

        $data['weeks']        = $result;
        $data['header_title'] = 'My TimeTable';
        return view('parent.my_timetable', $data);
    }
}