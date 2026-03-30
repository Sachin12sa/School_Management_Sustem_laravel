<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\User;

class ClassSectionController extends Controller
{
    /*──────────────────────────────────────────────────────────────────────────
     | LIST
     ──────────────────────────────────────────────────────────────────────────*/
    public function list()
    {
        $data['getRecord']    = ClassSectionModel::getRecord();
        
        $data['header_title'] = 'Class Section List';
        return view('admin.class_section.list', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | ADD FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function add()
    {
        $data['getClass']     = ClassModel::getClass();
        $data['header_title'] = 'Add New Section';
        return view('admin.class_section.add', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | INSERT
     ──────────────────────────────────────────────────────────────────────────*/
    public function insert(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'name'     => [
                'required',
                'string',
                'max:50',
                // Section name must be unique within the same class
                \Illuminate\Validation\Rule::unique('class_sections')->where(function ($query) use ($request) {
                    return $query->where('class_id', $request->class_id)->where('is_delete', 0);
                }),
            ],
            'status'   => 'required|in:0,1',
        ], [
            'name.unique' => 'This section name already exists for the selected class.',
        ]);

        ClassSectionModel::create([
            'class_id'   => $request->class_id,
            'name'       => trim($request->name),
            'status'     => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect('admin/section/list')->with('success', 'Section successfully created.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | EDIT FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function edit($id)
    {
        $getRecord = ClassSectionModel::getSingle($id);

        if (empty($getRecord) || $getRecord->is_delete) {
            abort(404);
        }

        $data['getRecord']    = $getRecord;
        $data['getClass']     = ClassModel::getClass();
        $data['header_title'] = 'Edit Section';
        return view('admin.class_section.edit', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | UPDATE
     ──────────────────────────────────────────────────────────────────────────*/
    public function update($id, Request $request)
    {
        $section = ClassSectionModel::findOrFail($id);

        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'name'     => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('class_sections', 'name')
                    ->where(fn($q) => $q->where('class_id', $request->class_id)->where('is_delete', 0))
                    ->ignore($id),
            ],
            'status'   => 'required|in:0,1',
        ], [
            'name.unique' => 'This section name already exists for the selected class.',
        ]);

        $section->update([
            'class_id' => $request->class_id,
            'name'     => trim($request->name),
            'status'   => $request->status,
        ]);

        return redirect('admin/section/list')->with('success', 'Section successfully updated.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | DELETE (SOFT)
     ──────────────────────────────────────────────────────────────────────────*/
    public function delete($id)
    {
        $section            = ClassSectionModel::findOrFail($id);
        $section->is_delete = 1;
        $section->save();

        return redirect('admin/section/list')->with('success', 'Section successfully deleted.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | VIEW STUDENTS IN SECTION
     ──────────────────────────────────────────────────────────────────────────*/
    public function viewStudents($section_id)
    {
        $section = ClassSectionModel::with('classModel')->findOrFail($section_id);

        $data['getSection']   = $section;
        $data['getClass']     = $section->classModel;
        $data['getRecord']    = User::getStudentBySection($section_id);
        // dd($data['getRecord']);
        $data['header_title'] = 'Students in ' . $section->classModel->name . ' — Section ' . $section->name;

        return view('admin.class_section.students', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | AJAX — get sections for a class (used in student add/edit forms)
     ──────────────────────────────────────────────────────────────────────────*/
    public function getSections(Request $request)
    {
        $sections = ClassSectionModel::getSectionsForAjax($request->class_id);
        return response()->json($sections);
    }
}