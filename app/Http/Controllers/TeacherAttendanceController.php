<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeacherAttendanceModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $getTeacher = null;

        if ($request->filled('attendance_date')) {
            // All active teachers (user_type = 2)
            $getTeacher = User::where('user_type', 2)
                              ->where('is_delete', 0)
                              ->get();
        }

        return view('admin.attendance.teacher_attendance', compact('getTeacher'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'teacher_id'      => 'required|exists:users,id',
            'attendance_date'  => 'required|date',
            'attendance_type'  => 'required|in:1,2,3,4',
        ]);

        TeacherAttendanceModel::updateOrCreate(
            [
                'teacher_id'     => $request->teacher_id,
                'attendance_date' => $request->attendance_date,
            ],
            [
                'attendance_type' => $request->attendance_type,
                'created_by'      => Auth::id(),
            ]
        );

        return response()->json(['message' => 'Attendance saved successfully.']);
    }

public function report(Request $request)
    {
        $query = TeacherAttendanceModel::query()
            ->join('users as t', 't.id', '=', 'teacher_attendances.teacher_id')
            ->join('users as c', 'c.id', '=', 'teacher_attendances.created_by')
            ->select(
                'teacher_attendances.*',
                't.name        as teacher_name',
                't.last_name   as teacher_last_name',
                't.email       as teacher_email',
                'c.name        as created_name',
                'c.last_name   as created_last_name'
            );

        // Filters
        if ($request->filled('teacher_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('t.name', 'like', '%' . $request->teacher_name . '%')
                  ->orWhere('t.last_name', 'like', '%' . $request->teacher_name . '%');
            });
        }

        if ($request->filled('attendance_date')) {
            $query->where('teacher_attendances.attendance_date', $request->attendance_date);
        }

        if ($request->filled('attendance_type')) {
            $query->where('teacher_attendances.attendance_type', $request->attendance_type);
        }

        $getRecord = $query->orderByDesc('teacher_attendances.attendance_date')
                           ->paginate(15);

        return view('admin.attendance.teacher_attendance_report', compact('getRecord'));
    }
}