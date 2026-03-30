<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\FeeGroup;
use App\Models\FeeGroupItem;
use App\Models\FeeType;
use App\Models\StudentFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeeGroupController extends Controller
{
    // ── LIST ──────────────────────────────────────────────────────────────

    public function list()
    {
        $data['getRecord']    = FeeGroup::getRecord();
        $data['header_title'] = 'Fee Groups';
        return view('admin.fee_group.list', $data);
    }

    // ── ADD FORM ──────────────────────────────────────────────────────────

    public function add()
    {
        $data['getFeeTypes']  = FeeType::getActive();
        $data['header_title'] = 'Add Fee Group';
        return view('admin.fee_group.add', $data);
    }

    // ── INSERT ────────────────────────────────────────────────────────────

    public function insert(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:150',
            'fee_type_ids'      => 'required|array|min:1',
            'fee_type_ids.*'    => 'exists:fee_types,id',
            'due_dates.*'       => 'required|date',
            'amounts.*'         => 'required|numeric|min:0',
        ]);

        // At least one fee type must be checked
        $checkedIds = $request->input('fee_type_ids', []);
        if (empty($checkedIds)) {
            return back()->withInput()->withErrors(['fee_type_ids' => 'Please select at least one Fee Type.']);
        }

        DB::transaction(function () use ($request, $checkedIds) {
            $group = FeeGroup::create([
                'name'        => trim($request->name),
                'description' => $request->description,
                'status'      => $request->input('status', 1),
                'created_by'  => Auth::id(),
            ]);

            $dueDates = $request->input('due_dates', []);
            $amounts  = $request->input('amounts', []);

            foreach ($checkedIds as $feeTypeId) {
                FeeGroupItem::create([
                    'fee_group_id' => $group->id,
                    'fee_type_id'  => $feeTypeId,
                    'due_date'     => $dueDates[$feeTypeId] ?? now()->toDateString(),
                    'amount'       => $amounts[$feeTypeId]  ?? 0,
                ]);
            }
        });

        return redirect('admin/fee_group/list')->with('success', 'Fee Group created successfully.');
    }

    // ── EDIT FORM ─────────────────────────────────────────────────────────

    public function edit($id)
    {
        $data['getRecord']    = FeeGroup::getSingle($id);
        if (empty($data['getRecord'])) abort(404);
        $data['getFeeTypes']  = FeeType::getActive();
        $data['header_title'] = 'Edit Fee Group';
        return view('admin.fee_group.edit', $data);
    }

    // ── UPDATE ────────────────────────────────────────────────────────────

    public function update($id, Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:150',
            'fee_type_ids'   => 'required|array|min:1',
            'fee_type_ids.*' => 'exists:fee_types,id',
        ]);

        $checkedIds = $request->input('fee_type_ids', []);

        DB::transaction(function () use ($id, $request, $checkedIds) {
            $group = FeeGroup::findOrFail($id);
            $group->update([
                'name'        => trim($request->name),
                'description' => $request->description,
                'status'      => $request->input('status', 1),
            ]);

            // Remove old items and re-insert fresh
            FeeGroupItem::where('fee_group_id', $id)->delete();

            $dueDates = $request->input('due_dates', []);
            $amounts  = $request->input('amounts', []);

            foreach ($checkedIds as $feeTypeId) {
                FeeGroupItem::create([
                    'fee_group_id' => $id,
                    'fee_type_id'  => $feeTypeId,
                    'due_date'     => $dueDates[$feeTypeId] ?? now()->toDateString(),
                    'amount'       => $amounts[$feeTypeId]  ?? 0,
                ]);
            }
        });

        return redirect('admin/fee_group/list')->with('success', 'Fee Group updated successfully.');
    }

    // ── DELETE (soft) ─────────────────────────────────────────────────────

    public function delete($id)
    {
        $group = FeeGroup::findOrFail($id);
        $group->is_delete = 1;
        $group->save();
        return redirect('admin/fee_group/list')->with('success', 'Fee Group deleted.');
    }

    // ── API: return items for a group (used by Fees Allocation AJAX) ──────

    public function getItems($id)
    {
        $items = FeeGroupItem::with('feeType')
            ->where('fee_group_id', $id)
            ->get()
            ->map(fn($i) => [
                'fee_type_id'   => $i->fee_type_id,
                'fee_type_name' => $i->feeType->name ?? '',
                'due_date'      => $i->due_date,
                'amount'        => $i->amount,
            ]);

        return response()->json($items);
    }
    public function allocate()
{
    $data['getFeeGroups'] = FeeGroup::getActive();
    $data['getClasses']   = \App\Models\ClassModel::getClass();
    $data['getSections']  = \App\Models\ClassSectionModel::getRecord();
 
    // All students JSON (id, name, class, section, roll, gender, mobile, guardian)
    $students = \App\Models\User::select(
            'users.id', 'users.name', 'users.last_name',
            'users.class_id', 'users.section_id',
            'users.admission_number', 'users.roll_number',
            'users.gender', 'users.mobile_number',
            'parents.name as guardian_name'
        )
        ->leftJoin('users as parents', 'parents.id', '=', 'users.parent_id')
        ->where('users.user_type', 3)
        ->where('users.is_delete', 0)
        ->where('users.status', 0)
        ->orderBy('users.class_id')
        ->orderBy('users.name')
        ->get();
 
    $data['studentsJson'] = $students->map(fn($s) => [
        'id'       => $s->id,
        'name'     => $s->name . ' ' . $s->last_name,
        'num'      => $s->admission_number ?? '',
        'roll'     => $s->roll_number ?? '',
        'cls'      => $s->class_id,
        'sec'      => $s->section_id,
        'gender'   => ucfirst($s->gender ?? ''),
        'mobile'   => $s->mobile_number ?? '',
        'guardian' => $s->guardian_name ?? '',
    ])->values();
 
    $data['header_title'] = 'Fees Allocation';
    return view('admin.fee_group.allocate', $data);
}
 
// ── ALLOCATE SAVE ──────────────────────────────────────────────────────────────
 
public function allocateSave(Request $request)
{
    $request->validate([
        'class_id'      => 'required|exists:classes,id',
        'fee_group_id'  => 'required|exists:fee_groups,id',
        'student_ids'   => 'required|array|min:1',
        'student_ids.*' => 'exists:users,id',
    ]);
 
    $group = FeeGroup::with('items')->findOrFail($request->fee_group_id);
 
    if ($group->items->isEmpty()) {
        return back()->with('error', 'This fee group has no fee types. Please add fee types to the group first.');
    }
 
    $studentIds = $request->input('student_ids', []);
    $students   = \App\Models\User::whereIn('id', $studentIds)->get()->keyBy('id');
 
    $created = 0;
    $skipped = 0;
 
    DB::transaction(function () use ($group, $studentIds, $students, &$created, &$skipped) {
        foreach ($studentIds as $studentId) {
            $student = $students[$studentId] ?? null;
            if (!$student) continue;
 
            foreach ($group->items as $item) {
                // Skip if already assigned (same student + fee_type + due_date)
                $exists = \App\Models\StudentFee::where('student_id',  $studentId)
                    ->where('fee_type_id', $item->fee_type_id)
                    ->where('due_date',    $item->due_date)
                    ->where('is_delete',   0)
                    ->exists();
 
                if ($exists) { $skipped++; continue; }
 
                \App\Models\StudentFee::create([
                    'student_id'  => $studentId,
                    'class_id'    => $student->class_id,
                    'fee_type_id' => $item->fee_type_id,
                    'amount'      => $item->amount,
                    'due_date'    => $item->due_date,
                    'status'      => 'pending',
                    'paid_amount' => 0,
                    'remarks'     => 'Allocated via group: ' . $group->name,
                    'created_by'  => Auth::id(),
                ]);
                $created++;
            }
        }
    });
 
    $msg = "Fee group '{$group->name}' allocated — {$created} fee record(s) created.";
    if ($skipped > 0) {
        $msg .= " ({$skipped} skipped — already assigned.)";
    }
 
    return redirect('admin/fee/list')->with('success', $msg);
}

}