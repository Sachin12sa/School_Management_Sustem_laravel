<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AcademicSessionModel;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\PromotionRuleModel;
use App\Models\StudentPromotionModel;
use App\Models\User;

class StudentPromotionController extends Controller
{
    /*──────────────────────────────────────────────────────────────────────────
     | SETUP — Shows session pickers + class-status dashboard
     | When class_id in query: shows that class's rule form (new or existing)
     ──────────────────────────────────────────────────────────────────────────*/
    public function setup(Request $request)
    {
        $data['getSessions']  = AcademicSessionModel::getAll();
        $data['getClass']     = ClassModel::getClass();
        $data['selectedClass'] = null;
        $data['existingRule']  = null;
        $data['classStatuses'] = collect();

        // If sessions are selected, compute per-class status grid
        if ($request->filled('from_session_id') && $request->filled('to_session_id')) {
            $fromId = $request->from_session_id;
            $toId   = $request->to_session_id;

            $rules    = PromotionRuleModel::getRulesForBatch($fromId, $toId)->keyBy('from_class_id');
            $promoted = StudentPromotionModel::where('from_session_id', $fromId)
                ->where('to_session_id', $toId)
                ->get()
                ->groupBy('from_class_id');

            $data['classStatuses'] = ClassModel::getClass()->map(function ($class) use ($rules, $promoted) {
                $rule   = $rules->get($class->id);
                $promos = $promoted->get($class->id, collect());
                return (object) [
                    'id'            => $class->id,
                    'name'          => $class->name,
                    'has_rule'      => (bool) $rule,
                    'is_final'      => $rule?->is_final_class ?? false,
                    'to_class_name' => $rule?->toClass?->name ?? null,
                    'promoted_count'=> $promos->count(),
                    'is_confirmed'  => $promos->where('is_confirmed', 1)->count() > 0,
                    'is_pending'    => $promos->where('is_confirmed', 0)->count() > 0 && $promos->where('is_confirmed', 1)->count() === 0,
                ];
            });

            // If a specific class is selected → load its rule for the form
            if ($request->filled('class_id')) {
                $data['selectedClass'] = ClassModel::find($request->class_id);
                $data['existingRule']  = PromotionRuleModel::where('from_session_id', $fromId)
                    ->where('to_session_id', $toId)
                    ->where('from_class_id', $request->class_id)
                    ->first();
            }
        }

        $data['header_title'] = 'Academic Upgrade — Setup';
        return view('admin.academic.setup', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | SAVE RULE — Saves/updates the rule for ONE class only
     ──────────────────────────────────────────────────────────────────────────*/
    public function saveRules(Request $request)
    {
        $request->validate([
            'from_session_id' => 'required|integer|exists:academic_sessions,id',
            'to_session_id'   => 'required|integer|exists:academic_sessions,id|different:from_session_id',
            'from_class_id'   => 'required|integer|exists:classes,id',
            'to_class_id'     => 'nullable|integer|exists:classes,id',
            'is_final_class'  => 'nullable|in:0,1',
        ]);

        $isFinal   = $request->boolean('is_final_class');
        $toClassId = $isFinal ? null : $request->to_class_id;

        // Upsert — update existing or create new rule for this specific class
        PromotionRuleModel::updateOrCreate(
            [
                'from_session_id' => $request->from_session_id,
                'to_session_id'   => $request->to_session_id,
                'from_class_id'   => $request->from_class_id,
            ],
            [
                'to_class_id'    => $toClassId,
                'is_final_class' => $isFinal ? 1 : 0,
                'created_by'     => Auth::id(),
            ]
        );

        return redirect(url('admin/academic/setup') . '?' . http_build_query([
            'from_session_id' => $request->from_session_id,
            'to_session_id'   => $request->to_session_id,
        ]))->with('success', 'Rule saved for this class. You can now set rules for other classes or preview students.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | DELETE RULE — Remove promotion rule for one class (allows re-setup)
     ──────────────────────────────────────────────────────────────────────────*/
    public function deleteRule(Request $request)
    {
        $request->validate([
            'from_session_id' => 'required|integer',
            'to_session_id'   => 'required|integer',
            'from_class_id'   => 'required|integer',
        ]);

        // Block if this class already has confirmed promotions
        $hasConfirmed = StudentPromotionModel::where('from_session_id', $request->from_session_id)
            ->where('to_session_id', $request->to_session_id)
            ->where('from_class_id', $request->from_class_id)
            ->where('is_confirmed', 1)
            ->exists();

        if ($hasConfirmed) {
            return redirect()->back()->with('error', 'Cannot remove rule — promotions for this class are already confirmed.');
        }

        PromotionRuleModel::where('from_session_id', $request->from_session_id)
            ->where('to_session_id', $request->to_session_id)
            ->where('from_class_id', $request->from_class_id)
            ->delete();

        return redirect(url('admin/academic/setup') . '?' . http_build_query([
            'from_session_id' => $request->from_session_id,
            'to_session_id'   => $request->to_session_id,
        ]))->with('success', 'Rule removed. You can reconfigure this class.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | AJAX — Get rule data for a class (used by the setup page live form)
     ──────────────────────────────────────────────────────────────────────────*/
    public function getClassRule(Request $request)
    {
        $rule = PromotionRuleModel::where('from_session_id', $request->from_session_id)
            ->where('to_session_id', $request->to_session_id)
            ->where('from_class_id', $request->class_id)
            ->with('toClass')
            ->first();

        // Promotion stats for this class
        $promoStats = StudentPromotionModel::where('from_session_id', $request->from_session_id)
            ->where('to_session_id', $request->to_session_id)
            ->where('from_class_id', $request->class_id)
            ->selectRaw('result, count(*) as total, sum(is_confirmed) as confirmed')
            ->groupBy('result')
            ->get();

        $studentCount = User::where('session_id', $request->from_session_id)
            ->where('class_id', $request->class_id)
            ->where('user_type', 3)
            ->where('is_delete', 0)
            ->count();

        return response()->json([
            'rule'          => $rule,
            'promo_stats'   => $promoStats,
            'student_count' => $studentCount,
        ]);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | PREVIEW — Show students for a specific class (or all if no class filter)
     ──────────────────────────────────────────────────────────────────────────*/
    public function preview(Request $request)
    {
        $fromSessionId = $request->from;
        $toSessionId   = $request->to;
        $filterClassId = $request->class_id; // optional — filter to one class

        $fromSession = AcademicSessionModel::findOrFail($fromSessionId);
        $toSession   = AcademicSessionModel::findOrFail($toSessionId);

        $rules = PromotionRuleModel::getRulesForBatch($fromSessionId, $toSessionId);

        if ($rules->isEmpty()) {
            return redirect(url('admin/academic/setup') . '?' . http_build_query([
                'from_session_id' => $fromSessionId,
                'to_session_id'   => $toSessionId,
            ]))->with('error', 'No promotion rules defined yet. Set up at least one class rule first.');
        }

        // Already fully confirmed? Go to review.
        $totalStudents   = User::where('session_id', $fromSessionId)->where('user_type', 3)->where('is_delete', 0)->count();
        $confirmedCount  = StudentPromotionModel::where('from_session_id', $fromSessionId)
            ->where('to_session_id', $toSessionId)->where('is_confirmed', 1)->count();

        // Load students — filter by class if one is selected
        $query = User::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name'
            )
            ->leftJoin('classes',        'classes.id',        '=', 'users.class_id')
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->where('users.session_id', $fromSessionId)
            ->where('users.user_type',  3)
            ->where('users.is_delete',  0)
            ->orderBy('users.class_id')
            ->orderBy('users.name');

        if ($filterClassId) {
            $query->where('users.class_id', $filterClassId);
        }

        $students = $query->get();

        // Per-class promotion status for the sidebar/tabs
        $classPromotionStatus = StudentPromotionModel::where('from_session_id', $fromSessionId)
            ->where('to_session_id', $toSessionId)
            ->select('from_class_id', DB::raw('count(*) as total'), DB::raw('sum(is_confirmed) as confirmed'))
            ->groupBy('from_class_id')
            ->get()
            ->keyBy('from_class_id');

        $getClass = ClassModel::getClass();

        $data = [
            'fromSession'          => $fromSession,
            'toSession'            => $toSession,
            'students'             => $students,
            'rules'                => $rules,
            'getClass'             => $getClass,
            'filterClassId'        => $filterClassId,
            'classPromotionStatus' => $classPromotionStatus,
            'getSessions'          => AcademicSessionModel::getAll(),
            'header_title'         => 'Academic Upgrade — Preview',
        ];

        return view('admin.academic.preview', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | RUN — Promotes students for a specific class (not wiping the whole session)
     ──────────────────────────────────────────────────────────────────────────*/
    public function run(Request $request)
    {
        $request->validate([
            'from_session_id'   => 'required|integer|exists:academic_sessions,id',
            'to_session_id'     => 'required|integer|exists:academic_sessions,id',
            'from_class_id'     => 'required|integer|exists:classes,id',
            'student_ids'       => 'required|array|min:1',
            'student_ids.*'     => 'integer|exists:users,id',
            'result_override'   => 'nullable|array',
            'result_override.*' => 'nullable|in:promoted,failed,graduated',
            'to_section_id'     => 'nullable|array',
            'to_section_id.*'   => 'nullable|integer|exists:class_sections,id',
        ]);

        $fromSessionId = $request->from_session_id;
        $toSessionId   = $request->to_session_id;
        $fromClassId   = $request->from_class_id;

        // Block if this specific class already has confirmed promotions
        $hasConfirmedRows = StudentPromotionModel::where('from_session_id', $fromSessionId)
            ->where('to_session_id', $toSessionId)
            ->where('from_class_id', $fromClassId)
            ->where('is_confirmed', 1)
            ->exists();

        if ($hasConfirmedRows) {
            return redirect(url('admin/academic/review') . '?' . http_build_query([
                'from'     => $fromSessionId,
                'to'       => $toSessionId,
                'class_id' => $fromClassId,
            ]))->with('error', 'This class has already been confirmed. Cannot re-run.');
        }

        // Clean up any unconfirmed previous run for THIS CLASS ONLY
        $orphanIds = StudentPromotionModel::where('from_session_id', $fromSessionId)
            ->where('to_session_id', $toSessionId)
            ->where('from_class_id', $fromClassId)
            ->where('is_confirmed', 0)
            ->whereNotNull('new_student_id')
            ->pluck('new_student_id');

        if ($orphanIds->isNotEmpty()) {
            DB::table('student_parent')->whereIn('student_id', $orphanIds)->delete();
            User::whereIn('id', $orphanIds)->forceDelete();
            StudentPromotionModel::where('from_session_id', $fromSessionId)
                ->where('to_session_id', $toSessionId)
                ->where('from_class_id', $fromClassId)
                ->where('is_confirmed', 0)
                ->delete();
        }

        $rules    = PromotionRuleModel::getRulesForBatch($fromSessionId, $toSessionId);
        $students = User::whereIn('id', $request->student_ids)
            ->where('user_type', 3)
            ->where('is_delete', 0)
            ->get()
            ->keyBy('id');

        $overrides  = $request->result_override ?? [];
        $toSections = $request->to_section_id   ?? [];
        $promoted   = 0; $failed = 0; $graduated = 0; $skipped = 0;

        DB::transaction(function () use (
            $students, $rules, $fromSessionId, $toSessionId, $fromClassId,
            $overrides, $toSections, &$promoted, &$failed, &$graduated, &$skipped
        ) {
            foreach ($students as $student) {
                $rule = $rules->get($student->class_id);
                if (!$rule) { $skipped++; continue; }

                $result = $overrides[$student->id] ?? null;
                if (!$result) {
                    if ($rule->is_final_class)                      $result = 'graduated';
                    elseif ($student->promotion_status === 'failed') $result = 'failed';
                    else                                             $result = 'promoted';
                }

                $toClassId = match($result) {
                    'graduated' => null,
                    'failed'    => $student->class_id,
                    default     => $rule->to_class_id,
                };

                $toSectionId = $toSections[$student->id] ?? null;
                if ($result === 'failed' && !$toSectionId) {
                    $toSectionId = $student->section_id;
                }

                $newStudent = $this->cloneStudentForNewSession(
                    $student, $toSessionId, $toClassId, $toSectionId, $result
                );

                $parentIds = $student->parents()->pluck('users.id')->toArray();
                if (!empty($parentIds)) $newStudent->parents()->sync($parentIds);

                StudentPromotionModel::create([
                    'from_session_id' => $fromSessionId,
                    'to_session_id'   => $toSessionId,
                    'from_class_id'   => $student->class_id,
                    'student_id'      => $student->id,
                    'new_student_id'  => $newStudent->id,
                    'to_class_id'     => $toClassId,
                    'from_section_id' => $student->section_id,
                    'to_section_id'   => $toSectionId,
                    'result'          => $result,
                    'is_confirmed'    => 0,
                    'promoted_by'     => Auth::id(),
                ]);

                match ($result) {
                    'promoted'  => $promoted++,
                    'failed'    => $failed++,
                    'graduated' => $graduated++,
                };
            }
        });

        $msg = "Class upgrade done — {$promoted} promoted, {$failed} kept back, {$graduated} graduated.";
        if ($skipped) $msg .= " {$skipped} skipped.";

        return redirect(url('admin/academic/review') . '?' . http_build_query([
            'from'     => $fromSessionId,
            'to'       => $toSessionId,
            'class_id' => $fromClassId,
        ]))->with('success', $msg);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | REVIEW — Results filtered by class if requested, or all
     ──────────────────────────────────────────────────────────────────────────*/
    public function review(Request $request)
    {
        $fromSessionId = $request->from;
        $toSessionId   = $request->to;
        $filterClassId = $request->class_id;

        $fromSession = AcademicSessionModel::findOrFail($fromSessionId);
        $toSession   = AcademicSessionModel::findOrFail($toSessionId);

        $summary   = StudentPromotionModel::getSummary($fromSessionId, $toSessionId);
        $getRecord = StudentPromotionModel::getRecord($fromSessionId, $toSessionId, $filterClassId ?? null);
        $getClass  = ClassModel::getClass();

        // Per-class breakdown for the tab bar
        $classSummary = StudentPromotionModel::where('from_session_id', $fromSessionId)
            ->where('to_session_id', $toSessionId)
            ->join('classes', 'classes.id', '=', 'student_promotions.from_class_id')
            ->select(
                'student_promotions.from_class_id',
                'classes.name as class_name',
                DB::raw('count(*) as total'),
                DB::raw('sum(is_confirmed) as confirmed'),
                DB::raw("sum(case when result='promoted' then 1 else 0 end) as promoted"),
                DB::raw("sum(case when result='failed' then 1 else 0 end) as failed"),
                DB::raw("sum(case when result='graduated' then 1 else 0 end) as graduated")
            )
            ->groupBy('student_promotions.from_class_id', 'classes.name')
            ->orderBy('classes.name')
            ->get();

        $data = [
            'fromSession'  => $fromSession,
            'toSession'    => $toSession,
            'summary'      => $summary,
            'getRecord'    => $getRecord,
            'getClass'     => $getClass,
            'filterClassId'=> $filterClassId,
            'classSummary' => $classSummary,
            'header_title' => 'Academic Upgrade — Review Results',
        ];

        return view('admin.academic.review', $data);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | CONFIRM — Can confirm all classes at once, or one class at a time
     ──────────────────────────────────────────────────────────────────────────*/
    public function confirm(Request $request)
    {
        $request->validate([
            'from_session_id' => 'required|integer|exists:academic_sessions,id',
            'to_session_id'   => 'required|integer|exists:academic_sessions,id',
            'from_class_id'   => 'nullable|integer|exists:classes,id',
        ]);

        $toSessionId   = $request->to_session_id;
        $fromSessionId = $request->from_session_id;
        $fromClassId   = $request->from_class_id;

        DB::transaction(function () use ($toSessionId, $fromSessionId, $fromClassId) {
            $query = StudentPromotionModel::where('from_session_id', $fromSessionId)
                ->where('to_session_id', $toSessionId);

            if ($fromClassId) {
                $query->where('from_class_id', $fromClassId);
            }

            $query->update(['is_confirmed' => 1]);

            // Only activate the new session if ALL classes are now confirmed
            if (!$fromClassId) {
                AcademicSessionModel::setAsCurrent($toSessionId);
                AcademicSessionModel::where('id', $fromSessionId)->update(['status' => 1]);
            }
        });

        if ($fromClassId) {
            $className = ClassModel::find($fromClassId)?->name ?? 'Class';
            return redirect(url('admin/academic/review') . '?' . http_build_query([
                'from' => $fromSessionId, 'to' => $toSessionId,
            ]))->with('success', "{$className} promotions confirmed.");
        }

        $session = AcademicSessionModel::findOrFail($toSessionId);
        return redirect('admin/academic_session/list')
            ->with('success', 'Session "' . $session->name . '" is now active. All promotions confirmed.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | ROLLBACK — Can rollback one class at a time or the whole batch
     ──────────────────────────────────────────────────────────────────────────*/
    public function rollback(Request $request)
    {
        $request->validate([
            'from_session_id' => 'required|integer',
            'to_session_id'   => 'required|integer',
            'from_class_id'   => 'nullable|integer',
        ]);

        $fromSessionId = $request->from_session_id;
        $toSessionId   = $request->to_session_id;
        $fromClassId   = $request->from_class_id;

        $hasConfirmed = StudentPromotionModel::where('from_session_id', $fromSessionId)
            ->where('to_session_id', $toSessionId)
            ->when($fromClassId, fn($q) => $q->where('from_class_id', $fromClassId))
            ->where('is_confirmed', 1)
            ->exists();

        if ($hasConfirmed) {
            return redirect()->back()
                ->with('error', 'Cannot rollback — promotions are already confirmed.');
        }

        DB::transaction(function () use ($fromSessionId, $toSessionId, $fromClassId) {
            $query = StudentPromotionModel::where('from_session_id', $fromSessionId)
                ->where('to_session_id', $toSessionId)
                ->when($fromClassId, fn($q) => $q->where('from_class_id', $fromClassId));

            $newStudentIds = (clone $query)->whereNotNull('new_student_id')->pluck('new_student_id');
            DB::table('student_parent')->whereIn('student_id', $newStudentIds)->delete();
            User::whereIn('id', $newStudentIds)->update(['is_delete' => 1]);
            $query->delete();
        });

        return redirect(url('admin/academic/preview') . '?' . http_build_query(array_filter([
            'from'     => $fromSessionId,
            'to'       => $toSessionId,
            'class_id' => $fromClassId,
        ])))->with('success', 'Rolled back successfully. You can re-run the upgrade.');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | PRIVATE — Clone student into new session
     ──────────────────────────────────────────────────────────────────────────*/
    private function cloneStudentForNewSession(
        User $student, int $toSessionId, ?int $toClassId, ?int $toSectionId, string $result
    ): User {
        $new = new User;
        $new->name          = $student->name;
        $new->middle_name   = $student->middle_name;
        $new->last_name     = $student->last_name;
        $new->password      = $student->password;
        $new->user_type     = 3;
        $new->gender        = $student->gender;
        $new->date_of_birth = $student->date_of_birth;
        $new->mobile_number = $student->mobile_number;
        $new->blood_group   = $student->blood_group;
        $new->religion      = $student->religion;
        $new->height        = $student->height;
        $new->weight        = $student->weight;
        $new->profile_pic   = $student->profile_pic;
        $new->status        = $student->status;
        $new->parent_id     = $student->parent_id;
        $new->email         = $student->email;
        $new->admission_number = $this->generateAdmissionNumber($toSessionId);
        $new->session_id    = $toSessionId;
        $new->class_id      = $toClassId;
        $new->section_id    = $toSectionId ?: null;
        $new->promotion_status = 'pending';
        $new->roll_number   = null;
        $new->admission_date = now()->format('Y-m-d');
        $new->is_delete     = 0;
        $new->saveQuietly();
        return $new;
    }

    private function generateAdmissionNumber(int $toSessionId): string
{
    $session = AcademicSessionModel::find($toSessionId);
    $year    = $session ? $session->name : 'NEW';

    $last = User::where('admission_number', 'like', "ADM-{$year}-%")
        ->orderByRaw('CAST(SUBSTRING_INDEX(admission_number, "-", -1) AS UNSIGNED) DESC')
        ->value('admission_number');

    if ($last) {
        $parts  = explode('-', $last);   // ✅ store in variable
        $nextId = (int) end($parts) + 1; // ✅ now safe
    } else {
        $nextId = 1;
    }

    return 'ADM-' . $year . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
}
}