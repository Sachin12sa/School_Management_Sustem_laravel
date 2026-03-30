<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClassSectionModel extends Model
{
    protected $table = 'class_sections';

    protected $fillable = [
        'class_id',
        'name',
        'status',
        'is_delete',
        'created_by',
    ];

    /*──────────────────────────────────────────────────────────────────────────
     | RELATIONSHIPS
     ──────────────────────────────────────────────────────────────────────────*/

    /** Section → Class */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /** Section → Students */
    public function students()
    {
        return $this->hasMany(User::class, 'section_id')->where('user_type', 3)->where('is_delete', 0);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC QUERY METHODS
     ──────────────────────────────────────────────────────────────────────────*/

    /**
     * Paginated list with optional filters — for the admin list view.
     */
    static public function getRecord()
    {
        $return = self::select(
                'class_sections.*',
                'classes.name as class_name',
                'users.name as created_by_name'
            )
            ->withCount('students')
            ->join('classes', 'classes.id', '=', 'class_sections.class_id')
            ->join('users',   'users.id',   '=', 'class_sections.created_by')
            ->where('class_sections.is_delete', 0);

        if (request('class_name')) {
            $return->where('classes.name', 'like', '%' . request('class_name') . '%');
        }
        if (request('section_name')) {
            $return->where('class_sections.name', 'like', '%' . request('section_name') . '%');
        }
        if (request('date')) {
            $return->whereDate('class_sections.created_at', request('date'));
        }

        return $return->orderBy('class_sections.id', 'desc')->paginate(10);
    }

    /**
     * All active sections for a specific class — used in dropdowns / student form.
     */
    static public function getSectionsByClass($class_id)
    {
        return self::where('class_id', $class_id)
            ->where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * All active sections across all classes — for general dropdowns.
     */
    static public function getAllActiveSections()
    {
        return self::select('class_sections.*', 'classes.name as class_name')
            ->join('classes', 'classes.id', '=', 'class_sections.class_id')
            ->where('class_sections.status', 0)
            ->where('class_sections.is_delete', 0)
            ->orderBy('classes.name', 'asc')
            ->orderBy('class_sections.name', 'asc')
            ->get();
    }

    static public function getSingle($id)
    {
        return self::find($id);
    }

    /**
     * Count total active sections — for dashboard.
     */
    static public function getTotalSections()
    {
        return self::where('is_delete', 0)->where('status', 0)->count();
    }

    /**
     * AJAX — return sections for a given class_id as JSON.
     */
    static public function getSectionsForAjax($class_id)
    {
        return self::where('class_id', $class_id)
            ->where('status', 0)
            ->where('is_delete', 0)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);
    }
}