<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AcademicSessionModel;

class AcademicSessionController extends Controller
{
    /*──────────────────────────────────────────────────────────────────────────
     | LIST
     ──────────────────────────────────────────────────────────────────────────*/
    public function list()
    {
        $data['getRecord']    = AcademicSessionModel::getRecord();
        $data['getCurrent']   = AcademicSessionModel::getCurrent();
        $data['header_title'] = 'Academic Sessions';
        return view('admin.academic.session.list', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | ADD FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function add()
    {
        $data['header_title'] = 'Add New Academic Session';
        return view('admin.academic.session.add', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | INSERT
     ──────────────────────────────────────────────────────────────────────────*/
    public function insert(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:20|unique:academic_sessions,name',
            'label'      => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        AcademicSessionModel::create([
            'name'       => trim($request->name),
            'label'      => trim($request->label),
            'start_date' => $request->start_date ?: null,
            'end_date'   => $request->end_date   ?: null,
            'is_current' => 0,
            'status'     => 0,
            'created_by' => Auth::id(),
        ]);

        return redirect('admin/academic_session/list')
            ->with('success', 'Academic session created successfully.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | EDIT FORM
     ──────────────────────────────────────────────────────────────────────────*/
    public function edit($id)
    {
        $getRecord = AcademicSessionModel::findOrFail($id);

        $data['getRecord']    = $getRecord;
        $data['header_title'] = 'Edit Academic Session';
        return view('admin.academic.session.edit', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | UPDATE
     ──────────────────────────────────────────────────────────────────────────*/
    public function update($id, Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:20|unique:academic_sessions,name,' . $id,
            'label'      => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $session             = AcademicSessionModel::findOrFail($id);
        $session->name       = trim($request->name);
        $session->label      = trim($request->label);
        $session->start_date = $request->start_date ?: null;
        $session->end_date   = $request->end_date   ?: null;
        $session->save();

        return redirect('admin/academic_session/list')
            ->with('success', 'Academic session updated successfully.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | DELETE (SOFT)
     ──────────────────────────────────────────────────────────────────────────*/
    public function delete($id)
    {
        $session = AcademicSessionModel::findOrFail($id);

        if ($session->is_current) {
            return redirect('admin/academic_session/list')
                ->with('error', 'Cannot delete the currently active session.');
        }

        $session->is_delete = 1;
        $session->save();

        return redirect('admin/academic_session/list')
            ->with('success', 'Academic session deleted successfully.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | SET AS CURRENT (activate new academic year)
     ──────────────────────────────────────────────────────────────────────────*/
    public function setAsCurrent($id)
    {
        $session = AcademicSessionModel::findOrFail($id);
        AcademicSessionModel::setAsCurrent($id);

        return redirect('admin/academic_session/list')
            ->with('success', '"' . $session->name . '" is now the active academic session.');
    }
}