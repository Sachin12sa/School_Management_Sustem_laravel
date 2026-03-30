<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionRuleModel extends Model
{
    protected $table = 'promotion_rules';

    protected $fillable = [
        'from_session_id',
        'to_session_id',
        'from_class_id',
        'to_class_id',
        'is_final_class',
        'created_by',
    ];

    /*──────────────────────────────────────────────────────────────────────────
     | RELATIONSHIPS
     ──────────────────────────────────────────────────────────────────────────*/

    public function fromClass()
    {
        return $this->belongsTo(ClassModel::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(ClassModel::class, 'to_class_id');
    }

    public function fromSession()
    {
        return $this->belongsTo(AcademicSessionModel::class, 'from_session_id');
    }

    public function toSession()
    {
        return $this->belongsTo(AcademicSessionModel::class, 'to_session_id');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC QUERY METHODS
     ──────────────────────────────────────────────────────────────────────────*/

    /** All rules for a specific promotion batch. */
    static public function getRulesForBatch($from_session_id, $to_session_id)
    {
        return self::with(['fromClass', 'toClass'])
            ->where('from_session_id', $from_session_id)
            ->where('to_session_id',   $to_session_id)
            ->orderBy('from_class_id', 'asc')
            ->get()
            ->keyBy('from_class_id');   // keyed so lookup is O(1)
    }

    /** Find the rule for a specific class in a promotion batch. */
    static public function getRuleForClass($from_session_id, $to_session_id, $class_id)
    {
        return self::where('from_session_id', $from_session_id)
            ->where('to_session_id',   $to_session_id)
            ->where('from_class_id',   $class_id)
            ->first();
    }
}