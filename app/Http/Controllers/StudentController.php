<?php

namespace App\Http\Controllers;

use App\Helpers\NepaliCalendar;
use App\Http\Controllers\Controller;
use App\Models\AcademicSessionModel;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /*──────────────────────────────────────────────────────────────────────────
     | LIST
     ──────────────────────────────────────────────────────────────────────────*/
    public function list()
    {
        $data['getRecord']    = User::getStudent();
        $data['getClass']     = ClassModel::getClass();   // for class/section filter dropdowns
        $data['getCurrentSession'] = AcademicSessionModel::getCurrent();
        $data['getSessions']       = AcademicSessionModel::getAll();
        $data['header_title'] = 'Student List';
        return view('admin.student.list', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | ADD FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function add()
    {
        $currentYear = NepaliCalendar::currentYear();

        $lastStudent = User::where('user_type', 3)
            ->where('admission_number', 'like', "ADM-$currentYear-%")
            ->orderBy('id', 'desc')
            ->first();

        $nextId = 1;
        if ($lastStudent) {
            $parts  = explode('-', $lastStudent->admission_number);
            $nextId = (int) end($parts) + 1;
        }

        $data['suggestedId']  = 'ADM-' . $currentYear . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $data['getClass']     = ClassModel::getClass();
        $data['getParent']    = User::getParentListToStudent();
        $data['header_title'] = 'Add New Student';

        return view('admin.student.add', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | GET ROLL NUMBER (AJAX)
     ──────────────────────────────────────────────────────────────────────────*/
    public function getRollNumber(Request $request)
    {
        $class_id = $request->class_id;

        $lastRoll = User::where('class_id', $class_id)
            ->where('user_type', 3)
            ->whereNotNull('roll_number')
            ->orderBy('id', 'desc')
            ->first();

        $nextRollId = 1;
        if ($lastRoll && is_numeric($lastRoll->roll_number)) {
            $nextRollId = (int) $lastRoll->roll_number + 1;
        }

        return response()->json([
            'roll_number' => str_pad($nextRollId, 2, '0', STR_PAD_LEFT),
        ]);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | INSERT (STORE)
     ──────────────────────────────────────────────────────────────────────────*/
    public function insert(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'last_name'        => 'required|string|max:100',
            'admission_number' => 'required|string|max:50|unique:users,admission_number',
            'roll_number'      => 'nullable|string|max:50',
            'class_id'         => 'required|integer|exists:classes,id',
            'section_id'       => 'nullable|integer|exists:class_sections,id',
            'gender'           => 'required|in:Male,Female,Other',
            'date_of_birth'    => 'required|date',
            'admission_date'   => 'required|date',
            // Email unique per session — same student can have same email in different years
            'email'            => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')
                    ->where('session_id', \App\Models\AcademicSessionModel::getCurrent()?->id),
            ],
            'password'         => 'required|min:5',
            'status'           => 'required|in:0,1',
            'profile_pic'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mobile_number'    => 'nullable|string|min:8|max:15',
            'blood_group'      => 'nullable|string|max:10',
            'religion'         => 'nullable|string|max:50',
            'height'           => 'nullable|string|max:10',
            'weight'           => 'nullable|string|max:10',
        ]);

        $student                   = new User;
        $student->name             = trim($request->name);
        $student->middle_name      = trim($request->middle_name);
        $student->last_name        = trim($request->last_name);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number      = trim($request->roll_number);
        $student->class_id         = $request->class_id;
        $student->section_id       = $request->section_id ?: null;
        $student->gender           = $request->gender;
        $student->date_of_birth    = $request->date_of_birth;
        $student->admission_date   = $request->admission_date;
        $student->blood_group      = trim($request->blood_group);
        $student->mobile_number    = trim($request->mobile_number);
        $student->religion         = trim($request->religion);
        $student->height           = trim($request->height);
        $student->weight           = trim($request->weight);
        $student->status           = $request->status;
        $student->email            = trim($request->email);
        $student->password         = Hash::make($request->password);
        $student->user_type        = 3;

        if ($request->hasFile('profile_pic')) {
            $file                 = $request->file('profile_pic');
            $extension            = $file->getClientOriginalExtension();
            $slugName             = Str::slug($request->name . ' ' . $request->last_name);
            $fileName             = $slugName . '-' . time() . '.' . $extension;
            $student->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }

        $student->save();

        if (!empty($request->user_id)) {
            $student->parents()->sync($request->user_id);
        }

        return redirect('admin/student/list')->with('success', 'Student successfully added.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | EDIT FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function edit($id)
    {
        $getRecord = User::where('id', $id)->where('user_type', 3)->firstOrFail();
        $getClass  = ClassModel::getClass();
        $getParent = User::getParentListToStudent();

        $getStudentParent = $getRecord->parents;
        $selectedParents  = $getStudentParent->pluck('id')->toArray();

        // Pre-load sections for the student's current class
        $getSections = $getRecord->class_id
            ? ClassSectionModel::getSectionsByClass($getRecord->class_id)
            : collect();

        return view('admin.student.edit', compact(
            'getRecord',
            'getClass',
            'getParent',
            'getStudentParent',
            'selectedParents',
            'getSections'
        ));
    }

    /*──────────────────────────────────────────────────────────────────────────
     | UPDATE
     ──────────────────────────────────────────────────────────────────────────*/
    public function update($id, Request $request)
    {
        $student = User::where('id', $id)->where('user_type', 3)->firstOrFail();

        $request->validate([
            'name'             => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'last_name'        => 'required|string|max:100',
            'admission_number' => 'required|string|max:50|unique:users,admission_number,' . $id,
            'roll_number'      => 'nullable|string|max:50',
            'class_id'         => 'required|integer|exists:classes,id',
            'section_id'       => 'nullable|integer|exists:class_sections,id',
            'gender'           => 'required|in:Male,Female,Other',
            'date_of_birth'    => 'required|date',
            'admission_date'   => 'required|date',
            // Email unique per session — ignore this student's own record
            'email'            => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')
                    ->where('session_id', $student->session_id)
                    ->ignore($id),
            ],
            'password'         => 'nullable|min:5',
            'status'           => 'required|in:0,1',
            'profile_pic'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mobile_number'    => 'nullable|string|min:8|max:15',
            'blood_group'      => 'nullable|string|max:10',
            'religion'         => 'nullable|string|max:50',
            'height'           => 'nullable|string|max:10',
            'weight'           => 'nullable|string|max:10',
        ]);

        $student->name             = trim($request->name);
        $student->middle_name      = trim($request->middle_name);
        $student->last_name        = trim($request->last_name);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number      = trim($request->roll_number);
        $student->class_id         = $request->class_id;
        $student->section_id       = $request->section_id ?: null;
        $student->gender           = $request->gender;
        $student->date_of_birth    = $request->date_of_birth;
        $student->admission_date   = $request->admission_date;
        $student->blood_group      = trim($request->blood_group);
        $student->mobile_number    = trim($request->mobile_number);
        $student->religion         = trim($request->religion);
        $student->height           = trim($request->height);
        $student->weight           = trim($request->weight);
        $student->status           = $request->status;
        $student->email            = trim($request->email);

        if (!empty($request->password)) {
            $student->password = Hash::make($request->password);
        }

        $student->user_type = 3;

        if ($request->hasFile('profile_pic')) {
            if (!empty($student->profile_pic) && file_exists(storage_path('app/public/' . $student->profile_pic))) {
                unlink(storage_path('app/public/' . $student->profile_pic));
            }
            $file                 = $request->file('profile_pic');
            $extension            = $file->getClientOriginalExtension();
            $slugName             = Str::slug($request->name . ' ' . $request->last_name);
            $fileName             = $slugName . '-' . time() . '.' . $extension;
            $student->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }

        $student->save();

        $student->parents()->sync($request->user_id ?? []);

        return redirect('admin/student/list')->with('success', 'Student successfully updated.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | DELETE (SOFT)
     ──────────────────────────────────────────────────────────────────────────*/
    public function delete($id)
    {
        $user            = User::where('id', $id)->where('user_type', 3)->firstOrFail();
        $user->is_delete = 1;
        $user->save();

        return redirect('admin/student/list')->with('success', 'Student successfully deleted.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | TEACHER SIDE — MY STUDENTS
     ──────────────────────────────────────────────────────────────────────────*/
    public function myStudent()
    {
        $data['getRecord']    = User::getTeacherStudent(Auth::user()->id);
        $data['header_title'] = 'Student List';
        return view('teacher.my_student', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | SEARCH PARENT (AJAX)
     ──────────────────────────────────────────────────────────────────────────*/
    public function searchParent(Request $request)
    {
        $json = [];

        if (!empty($request->search)) {
            $getUser = User::where('user_type', 4)
                ->where('is_delete', 0)
                ->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('last_name', 'like', '%' . $request->search . '%')
                          ->orWhere('mobile_number', 'like', '%' . $request->search . '%');
                })
                ->limit(20)
                ->get();

            foreach ($getUser as $value) {
                $json[] = [
                    'id'    => $value->id,
                    'text'  => $value->name . ' ' . $value->last_name,
                    'email' => $value->email,
                    'phone' => $value->mobile_number,
                ];
            }
        }

        return response()->json(['results' => $json]);
    }
}