<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeGroup extends Model
{
    protected $table = 'fee_groups';

    protected $fillable = [
        'name', 'description', 'status', 'created_by',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function items()
    {
        return $this->hasMany(FeeGroupItem::class, 'fee_group_id');
    }

    public function itemsWithType()
    {
        return $this->hasMany(FeeGroupItem::class, 'fee_group_id')->with('feeType');
    }

    // ── Scopes / Static helpers ────────────────────────────────────────────

    public static function getRecord()
    {
        return self::with('itemsWithType')
            ->where('is_delete', 0)
            ->orderBy('name')
            ->get();
    }

    public static function getActive()
    {
        return self::where('is_delete', 0)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
    }

    public static function getSingle($id)
    {
        return self::with('itemsWithType')->find($id);
    }
}