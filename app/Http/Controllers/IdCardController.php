<?php

namespace App\Http\Controllers;

use App\Models\IdCardTemplate;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\User;
use App\Helpers\NepaliCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IdCardController extends Controller
{
    // ─── TEMPLATE LIST ────────────────────────────────────────────

    public function list()
    {
        return view('admin.id_card.list', [
            'getRecord'    => IdCardTemplate::getAll(),
            'header_title' => 'ID Card Templates',
        ]);
    }

    // ─── ADD TEMPLATE ─────────────────────────────────────────────

    public function add()
    {
        return view('admin.id_card.add', [
            'header_title' => 'Add ID Card Template',
        ]);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'applicable_user'  => 'required|in:student,teacher,admin,accountant,librarian',
            'layout_width'     => 'required|numeric|min:10',
            'layout_height'    => 'required|numeric|min:10',
            'photo_style'      => 'required|in:circle,square,rounded',
            'photo_size'       => 'required|integer|min:20|max:300',
            'top_space'        => 'required|integer|min:0',
            'bottom_space'     => 'required|integer|min:0',
            'left_space'       => 'required|integer|min:0',
            'right_space'      => 'required|integer|min:0',
            'accent_color'     => 'nullable|string|max:20',
            'text_color'       => 'nullable|string|max:20',
            'signature_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $tpl = new IdCardTemplate;
        $tpl->name            = trim($request->name);
        $tpl->applicable_user = $request->applicable_user;
        $tpl->layout_width    = $request->layout_width;
        $tpl->layout_height   = $request->layout_height;
        $tpl->photo_style     = $request->photo_style;
        $tpl->photo_size      = $request->photo_size;
        $tpl->top_space       = $request->top_space;
        $tpl->bottom_space    = $request->bottom_space;
        $tpl->left_space      = $request->left_space;
        $tpl->right_space     = $request->right_space;
        $tpl->accent_color    = $request->accent_color  ?? '#1a56a0';
        $tpl->text_color      = $request->text_color    ?? '#ffffff';
        $tpl->extra_content   = $request->extra_content ?? '';

        foreach (['signature_image', 'logo_image', 'background_image'] as $field) {
            if ($request->hasFile($field)) {
                $file     = $request->file($field);
                $fileName = Str::slug($request->name) . '-' . $field . '-' . time() . '.' . $file->getClientOriginalExtension();
                $tpl->$field = $file->storeAs('id_card', $fileName, 'public');
            }
        }

        $tpl->save();
        return redirect('admin/id_card/list')->with('success', 'Template created successfully.');
    }

    // ─── EDIT TEMPLATE ────────────────────────────────────────────

    public function edit($id)
    {
        $tpl = IdCardTemplate::getSingle($id);
        if (!$tpl) abort(404);

        return view('admin.id_card.edit', [
            'getRecord'    => $tpl,
            'header_title' => 'Edit ID Card Template',
        ]);
    }

    public function update($id, Request $request)
    {
        $tpl = IdCardTemplate::getSingle($id);
        if (!$tpl) abort(404);

        $tpl->name            = trim($request->name);
        $tpl->applicable_user = $request->applicable_user;
        $tpl->layout_width    = $request->layout_width;
        $tpl->layout_height   = $request->layout_height;
        $tpl->photo_style     = $request->photo_style;
        $tpl->photo_size      = $request->photo_size;
        $tpl->top_space       = $request->top_space;
        $tpl->bottom_space    = $request->bottom_space;
        $tpl->left_space      = $request->left_space;
        $tpl->right_space     = $request->right_space;
        $tpl->accent_color    = $request->accent_color  ?? $tpl->accent_color;
        $tpl->text_color      = $request->text_color    ?? $tpl->text_color;
        $tpl->extra_content   = $request->extra_content ?? '';

        foreach (['signature_image', 'logo_image', 'background_image'] as $field) {
            if ($request->hasFile($field)) {
                if (!empty($tpl->$field) && file_exists(storage_path('app/public/' . $tpl->$field))) {
                    unlink(storage_path('app/public/' . $tpl->$field));
                }
                $file     = $request->file($field);
                $fileName = Str::slug($request->name) . '-' . $field . '-' . time() . '.' . $file->getClientOriginalExtension();
                $tpl->$field = $file->storeAs('id_card', $fileName, 'public');
            }
        }

        $tpl->save();
        return redirect('admin/id_card/list')->with('success', 'Template updated successfully.');
    }

    public function delete($id)
    {
        $tpl = IdCardTemplate::getSingle($id);
        if ($tpl) { $tpl->is_delete = 1; $tpl->save(); }
        return redirect('admin/id_card/list')->with('success', 'Template deleted.');
    }

    // ─── STUDENT ID CARD GENERATE ─────────────────────────────────

    public function studentGenerate(Request $request)
    {
        $getClass    = ClassModel::getClass();
        $getSection  = [];
        $getStudents = collect();
        $templates   = IdCardTemplate::getForUser('student');

        if ($request->filled('class_id')) {
            $getSection = ClassSectionModel::getSectionsByClass($request->class_id);

            $query = User::select('users.*', 'classes.name as class_name', 'class_sections.name as section_name')
                ->join('classes', 'classes.id', '=', 'users.class_id')
                ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
                ->where('users.user_type', 3)
                ->where('users.is_delete', 0)
                ->where('users.class_id', $request->class_id);

            if ($request->filled('section_id') && $request->section_id !== 'all') {
                $query->where('users.section_id', $request->section_id);
            }

            $getStudents = $query->orderBy('users.roll_number')->get();
        }

        return view('admin.id_card.student_generate', compact(
            'getClass', 'getSection', 'getStudents', 'templates'
        ) + ['header_title' => 'Generate Student ID Cards']);
    }

    // ─── STAFF ID CARD GENERATE ───────────────────────────────────

    public function staffGenerate(Request $request)
    {
        $roleMap = [
            'teacher'    => 2,
            'admin'      => 1,
            'accountant' => 5,
            'librarian'  => 6,
        ];

        $staff     = collect();
        $templates = IdCardTemplate::getAll();

        if ($request->filled('role') && isset($roleMap[$request->role])) {
            $staff = User::select('users.*')
                ->where('user_type', $roleMap[$request->role])
                ->where('is_delete', 0)
                ->orderBy('name')
                ->get();

            // Filter templates to matching role
            $templates = IdCardTemplate::getForUser($request->role);
        }

        return view('admin.id_card.staff_generate', compact('staff', 'templates') + [
            'header_title' => 'Generate Staff ID Cards',
            'roleMap'      => $roleMap,
        ]);
    }

    // ─── PRINT — renders a printable page of ID cards ─────────────

    public function print(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:id_card_templates,id',
            'user_ids'    => 'required|array|min:1',
            'print_date'  => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        $template = IdCardTemplate::findOrFail($request->template_id);

        $users = User::select('users.*', 'classes.name as class_name', 'class_sections.name as section_name')
            ->leftJoin('classes',        'classes.id',        '=', 'users.class_id')
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->whereIn('users.id', $request->user_ids)
            ->get();

        $printDate  = $request->print_date  ?? now()->toDateString();
        $expiryDate = $request->expiry_date ?? now()->addYear()->toDateString();

        // Convert dates to BS
        $printDateBs  = NepaliCalendar::format($printDate,  'd M Y');
        $expiryDateBs = NepaliCalendar::format($expiryDate, 'd M Y');

        return view('admin.id_card.print', compact(
            'template', 'users', 'printDate', 'expiryDate', 'printDateBs', 'expiryDateBs'
        ));
    }
}