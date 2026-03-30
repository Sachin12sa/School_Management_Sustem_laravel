<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\ClassSubjectModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /*──────────────────────────────────────────────────────────────────────────
     | LIST
     ──────────────────────────────────────────────────────────────────────────*/
    public function list()
    {
        $data['getRecord']    = ClassModel::getRecord();
        $data['header_title'] = 'Class List';
        return view('admin.class.list', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | ADD FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function add()
    {
        $data['header_title'] = 'Add New Class';
        return view('admin.class.add', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | INSERT
     ──────────────────────────────────────────────────────────────────────────*/
    public function insert(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100|unique:classes,name',
            'status' => 'required|in:0,1',
        ]);

        $class             = new ClassModel;
        $class->name       = trim($request->name);
        $class->status     = $request->status;
        $class->created_by = Auth::id();
        $class->save();

        return redirect('admin/class/list')->with('success', 'Class successfully created.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | EDIT FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function edit($id)
    {
        $getRecord = ClassModel::getSingle($id);

        if (empty($getRecord)) {
            abort(404);
        }

        $data['getRecord']    = $getRecord;
        $data['header_title'] = 'Edit Class';
        return view('admin.class.edit', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | UPDATE
     ──────────────────────────────────────────────────────────────────────────*/
    public function update($id, Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100|unique:classes,name,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $class         = ClassModel::getSingle($id);
        $class->name   = trim($request->name);
        $class->status = $request->status;
        $class->save();

        return redirect('admin/class/list')->with('success', 'Class successfully updated.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | DELETE (SOFT)
     ──────────────────────────────────────────────────────────────────────────*/
    public function delete($id)
    {
        $class            = ClassModel::getSingle($id);
        $class->is_delete = 1;
        $class->save();

        return redirect('admin/class/list')->with('success', 'Class successfully deleted.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | VIEW STUDENTS PER CLASS
     ──────────────────────────────────────────────────────────────────────────*/
    public function viewStudents($class_id)
    {
        $getClass = ClassModel::getSingle($class_id);

        if (empty($getClass)) {
            abort(404);
        }

        $data['getClass']     = $getClass;
        $data['getSections']  = ClassSectionModel::getSectionsByClass($class_id);
        $data['getRecord']    = User::getStudentPerClass($class_id);
        $data['header_title'] = 'Students of ' . $getClass->name;

        return view('admin.class.students', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | VIEW SUBJECTS PER CLASS
     ──────────────────────────────────────────────────────────────────────────*/
    public function viewSubjects($class_id)
    {
        $getClass = ClassModel::getSingle($class_id);

        if (empty($getClass)) {
            abort(404);
        }

        $data['getClass']     = $getClass;
        $data['getRecord']    = ClassSubjectModel::getSubjectPerClass($class_id);
        $data['header_title'] = 'Subjects for ' . $getClass->name;

        return view('admin.class.subjects', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | VIEW SECTIONS PER CLASS
     ──────────────────────────────────────────────────────────────────────────*/
    public function viewSections($class_id)
    {
        $getClass = ClassModel::getSingle($class_id);

        if (empty($getClass)) {
            abort(404);
        }

        $data['getClass']     = $getClass;
        $data['getRecord']    = ClassSectionModel::getRecord(); // filtered by class via request if needed
        // Override with class-specific sections (all statuses, paginated)
        $data['getRecord']    = ClassSectionModel::select(
                'class_sections.*',
                'users.name as created_by_name'
            )
            ->join('users', 'users.id', '=', 'class_sections.created_by')
            ->where('class_sections.class_id', $class_id)
            ->where('class_sections.is_delete', 0)
            ->orderBy('class_sections.name', 'asc')
            ->paginate(20);

        $data['header_title'] = 'Sections of ' . $getClass->name;

        return view('admin.class.sections', $data);
    }
}