<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name', 'status', 'is_delete', 'created_by'];

    /*──────────────────────────────────────────────────────────────────────────
     | RELATIONSHIPS
     ──────────────────────────────────────────────────────────────────────────*/

    /** Class → Sections */
    public function sections()
    {
        return $this->hasMany(ClassSectionModel::class, 'class_id')
                    ->where('is_delete', 0)
                    ->orderBy('name', 'asc');
    }

    /** Class → Active Sections only */
    public function activeSections()
    {
        return $this->hasMany(ClassSectionModel::class, 'class_id')
                    ->where('status', 0)
                    ->where('is_delete', 0)
                    ->orderBy('name', 'asc');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC QUERY METHODS
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getNameSingle($name)
    {
        return ClassModel::where('name', '=', $name)->first();
    }

    static public function getSingle($id)
    {
        return ClassModel::find($id);
    }

    /**
     * Paginated list with filters — for the admin list view.
     */
    static public function getRecord()
    {
        $return = ClassModel::select('classes.*', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'classes.created_by')
            ->where('classes.is_delete', 0);

        if (request('name')) {
            $return->where('classes.name', 'like', '%' . request('name') . '%');
        }
        if (request('date')) {
            $return->whereDate('classes.created_at', request('date'));
        }

        return $return->orderBy('classes.id', 'desc')->paginate(10);
    }

    /**
     * All active classes — for dropdowns.
     * Eager-loads activeSections so forms can show a nested section picker
     * without extra queries.
     */
    static public function getClass()
    {
        return ClassModel::select('classes.*')
            ->join('users', 'users.id', '=', 'classes.created_by')
            ->where('classes.is_delete', 0)
            ->where('classes.status', 0)
            ->orderBy('classes.id', 'asc')
            ->with('activeSections')   // eager-load sections
            ->get();
    }

    /**
     * Total active classes count — for dashboard.
     */
    static public function getTotalClass()
    {
        return ClassModel::where('is_delete', 0)->where('status', 0)->count();
    }
}