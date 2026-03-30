<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    // ── TEMPLATE LIST ──────────────────────────────────────────────────────

    public function list()
    {
        $data['getRecord']    = CertificateTemplate::getRecord();
        $data['header_title'] = 'Certificate Templates';
        return view('admin.certificate.list', $data);
    }

    // ── ADD FORM ───────────────────────────────────────────────────────────

    public function add()
    {
        $data['header_title'] = 'Add Certificate Template';
        return view('admin.certificate.add', $data);
    }

    // ── INSERT ─────────────────────────────────────────────────────────────

    public function insert(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:200',
            'applicable_user' => 'required|in:student,employee',
            'page_layout'     => 'required|string',
            'photo_style'     => 'required|in:square,circle,none',
            'content'         => 'required|string',
        ]);

        $data = $request->only([
            'name', 'applicable_user', 'page_layout', 'photo_style', 'photo_size',
            'top_space', 'bottom_space', 'right_space', 'left_space', 'content',
        ]);
        $data['created_by'] = Auth::id();
        $data['status']     = 1;

        // Handle file uploads
        foreach (['signature_image', 'logo_image', 'background_image'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('certificates', 'public');
            }
        }

        CertificateTemplate::create($data);

        return redirect('admin/certificate/list')->with('success', 'Certificate template created successfully.');
    }

    // ── EDIT FORM ──────────────────────────────────────────────────────────

    public function edit($id)
    {
        $data['getRecord']    = CertificateTemplate::getSingle($id);
        if (empty($data['getRecord'])) abort(404);
        $data['header_title'] = 'Edit Certificate Template';
        return view('admin.certificate.edit', $data);
    }

    // ── UPDATE ─────────────────────────────────────────────────────────────

    public function update($id, Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:200',
            'applicable_user' => 'required|in:student,employee',
            'page_layout'     => 'required|string',
            'photo_style'     => 'required|in:square,circle,none',
            'content'         => 'required|string',
        ]);

        $record = CertificateTemplate::findOrFail($id);

        $data = $request->only([
            'name', 'applicable_user', 'page_layout', 'photo_style', 'photo_size',
            'top_space', 'bottom_space', 'right_space', 'left_space', 'content',
        ]);

        foreach (['signature_image', 'logo_image', 'background_image'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($record->$field) {
                    Storage::disk('public')->delete($record->$field);
                }
                $data[$field] = $request->file($field)->store('certificates', 'public');
            }
        }

        $record->update($data);

        return redirect('admin/certificate/list')->with('success', 'Certificate template updated successfully.');
    }

    // ── DELETE (soft) ──────────────────────────────────────────────────────

    public function delete($id)
    {
        $record = CertificateTemplate::findOrFail($id);
        $record->is_delete = 1;
        $record->save();
        return redirect('admin/certificate/list')->with('success', 'Certificate template deleted.');
    }

    // ══════════════════════════════════════════════════════════════════════
    // STUDENT CERTIFICATE GENERATE
    // ══════════════════════════════════════════════════════════════════════

    public function studentGenerate(Request $request)
    {
        $data['getClasses']   = ClassModel::getClass();
        $data['getSections']  = ClassSectionModel::getRecord();
        $data['getTemplates'] = CertificateTemplate::getActive('student');
        $data['getStudents']  = collect();
        $data['header_title'] = 'Student Certificate Generate';

        if ($request->isMethod('post')) {
            $request->validate([
                'class_id'    => 'required|exists:classes,id',
                'template_id' => 'required|exists:certificate_templates,id',
            ]);

            $query = User::select(
                    'users.id', 'users.name', 'users.last_name', 'users.admission_number',
                    'users.roll_number', 'users.gender', 'users.mobile_number',
                    'users.religion', 'users.section_id',
                    'classes.name as class_name',
                    'class_sections.name as section_name'
                )
                ->join('classes', 'classes.id', '=', 'users.class_id')
                ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
                ->where('users.user_type', 3)
                ->where('users.is_delete', 0)
                ->where('users.status', 0)
                ->where('users.class_id', $request->class_id);

            if ($request->section_id) {
                $query->where('users.section_id', $request->section_id);
            }

            $data['getStudents']  = $query->orderBy('users.roll_number')->get();
            $data['selectedClass']    = $request->class_id;
            $data['selectedSection']  = $request->section_id;
            $data['selectedTemplate'] = $request->template_id;
            $data['printDate']        = $request->print_date ?? now()->toDateString();
        }

        return view('admin.certificate.student_generate', $data);
    }

    // ── Print student certificates ─────────────────────────────────────────

    public function studentPrint(Request $request)
    {
        $request->validate([
            'student_ids'  => 'required|array|min:1',
            'template_id'  => 'required|exists:certificate_templates,id',
            'print_date'   => 'required|date',
        ]);

        $template = CertificateTemplate::findOrFail($request->template_id);
        $students = User::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name',
                'parents.name as father_name',
                'parents.last_name as father_last_name',
            )
            ->join('classes', 'classes.id', '=', 'users.class_id')
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->leftJoin('users as parents', 'parents.id', '=', 'users.parent_id')
            ->whereIn('users.id', $request->student_ids)
            ->get();

        return view('admin.certificate.student_print', compact('template', 'students', 'request'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // EMPLOYEE CERTIFICATE GENERATE
    // ══════════════════════════════════════════════════════════════════════

    public function employeeGenerate(Request $request)
    {
        $data['getTemplates'] = CertificateTemplate::getActive('employee');
        $data['getEmployees'] = collect();
        $data['header_title'] = 'Employee Certificate Generate';

        // Roles available in your system — adjust user_type values as needed
        $data['getRoles'] = [
            2  => 'Teacher',
            5  => 'Accountant',
            6  => 'Librarian',
           
        ];

        if ($request->isMethod('post')) {
            $request->validate([
                'role'        => 'required',
                'template_id' => 'required|exists:certificate_templates,id',
            ]);

            $data['getEmployees'] = User::select(
                    'users.id', 'users.name', 'users.last_name',
                    'users.user_type', 'users.mobile_number', 'users.email'
                   
                )
                ->where('users.user_type', $request->role)
                ->where('users.is_delete', 0)
                ->where('users.status', 0)
                ->orderBy('users.name')
                ->get();

            $data['selectedRole']     = $request->role;
            $data['selectedTemplate'] = $request->template_id;
            $data['printDate']        = $request->print_date ?? now()->toDateString();
        }

        return view('admin.certificate.employee_generate', $data);
    }

    // ── Print employee certificates ────────────────────────────────────────

    public function employeePrint(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array|min:1',
            'template_id'  => 'required|exists:certificate_templates,id',
            'print_date'   => 'required|date',
        ]);

        $template  = CertificateTemplate::findOrFail($request->template_id);
        $employees = User::select('users.*')
            ->whereIn('users.id', $request->employee_ids)
            ->get();

        return view('admin.certificate.employee_print', compact('template', 'employees', 'request'));
    }
}