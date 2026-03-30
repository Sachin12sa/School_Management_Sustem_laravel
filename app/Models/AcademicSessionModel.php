<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSessionModel extends Model
{
    protected $table = 'academic_sessions';

    protected $fillable = [
        'name',
        'label',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'is_delete',
        'created_by',
    ];

    /*──────────────────────────────────────────────────────────────────────────
     | RELATIONSHIPS
     ──────────────────────────────────────────────────────────────────────────*/

    public function students()
    {
        return $this->hasMany(User::class, 'session_id')
                    ->where('user_type', 3)
                    ->where('is_delete', 0);
    }

    public function promotionRules()
    {
        return $this->hasMany(PromotionRuleModel::class, 'from_session_id');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC QUERY METHODS
     ──────────────────────────────────────────────────────────────────────────*/

    /** Paginated list with filters — admin list view. */
    static public function getRecord()
    {
        $return = self::select('academic_sessions.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'academic_sessions.created_by')
            ->where('academic_sessions.is_delete', 0);

        if (request('name')) {
            $return->where('academic_sessions.name', 'like', '%' . request('name') . '%');
        }

        return $return->orderBy('academic_sessions.id', 'desc')->paginate(10);
    }

    /** All non-deleted sessions for dropdowns. */
    static public function getAll()
    {
        return self::where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    /** The one session marked as current. */
    static public function getCurrent()
    {
        return self::where('is_current', 1)->where('is_delete', 0)->first();
    }

    static public function getSingle($id)
    {
        return self::find($id);
    }

    /** Student count per session — for display. */
    static public function getStudentCount($session_id)
    {
        return User::where('session_id', $session_id)
            ->where('user_type', 3)
            ->where('is_delete', 0)
            ->count();
    }

    /** Switch the active session — only one can be current at a time. */
    static public function setAsCurrent($id)
    {
        // Deactivate all
        self::where('is_current', 1)->update(['is_current' => 0]);
        // Activate the chosen one
        self::where('id', $id)->update(['is_current' => 1]);
    }
}